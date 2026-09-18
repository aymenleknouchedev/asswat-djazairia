<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

/**
 * Sends mail through the Microsoft Graph API (Office 365 / Exchange Online)
 * using an Entra ID app registration and the OAuth2 client credentials flow.
 *
 * This replaces SMTP AUTH, which Microsoft has disabled on the tenant and is
 * retiring for Exchange Online altogether.
 */
class MicrosoftGraphTransport extends AbstractTransport
{
    /** Graph rejects a sendMail request larger than 4 MB (MIME, base64 encoded). */
    private const MAX_REQUEST_BYTES = 4 * 1024 * 1024;

    public function __construct(
        private readonly string $tenantId,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $fromMailbox,
        private readonly int $timeout = 30,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $this->assertConfigured();

        $mime = $message->toString();
        $payload = base64_encode($mime);

        if (strlen($payload) > self::MAX_REQUEST_BYTES) {
            throw new TransportException(
                'The message is too large for Microsoft Graph sendMail (limit is 4 MB including attachments).'
            );
        }

        // The mailbox we send as: the envelope sender, or the configured default.
        $mailbox = $message->getEnvelope()->getSender()->getAddress() ?: $this->fromMailbox;

        $response = Http::withToken($this->accessToken())
            ->withBody($payload, 'text/plain')
            ->timeout($this->timeout)
            ->post(sprintf(
                'https://graph.microsoft.com/v1.0/users/%s/sendMail',
                rawurlencode($mailbox)
            ));

        if ($response->failed()) {
            // Never reuse a token Graph has just rejected.
            if ($response->status() === 401) {
                Cache::forget($this->tokenCacheKey());
            }

            throw new TransportException(sprintf(
                'Microsoft Graph refused the message (HTTP %d): %s',
                $response->status(),
                $response->json('error.message') ?? $response->body()
            ));
        }
    }

    /**
     * Fail with a useful message instead of a confusing HTTP error when the
     * Entra ID credentials have not been filled in yet.
     */
    private function assertConfigured(): void
    {
        $missing = [];

        foreach ([
            'MS_GRAPH_TENANT_ID' => $this->tenantId,
            'MS_GRAPH_CLIENT_ID' => $this->clientId,
            'MS_GRAPH_CLIENT_SECRET' => $this->clientSecret,
            'MS_GRAPH_FROM' => $this->fromMailbox,
        ] as $key => $value) {
            if (trim($value) === '') {
                $missing[] = $key;
            }
        }

        if ($missing !== []) {
            throw new TransportException(
                'Microsoft Graph mail is not configured; missing ' . implode(', ', $missing)
                . '. See docs/MICROSOFT_MAIL_SETUP.md.'
            );
        }
    }

    /**
     * Fetch an application token, cached until shortly before it expires.
     */
    private function accessToken(): string
    {
        return Cache::remember($this->tokenCacheKey(), now()->addMinutes(50), function () {
            $response = Http::asForm()
                ->timeout($this->timeout)
                ->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => 'https://graph.microsoft.com/.default',
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                throw new TransportException(sprintf(
                    'Could not get a Microsoft Graph token (HTTP %d): %s',
                    $response->status(),
                    $response->json('error_description') ?? $response->body()
                ));
            }

            $token = $response->json('access_token');

            if (! is_string($token) || $token === '') {
                throw new TransportException('Microsoft Graph returned an empty access token.');
            }

            return $token;
        });
    }

    private function tokenCacheKey(): string
    {
        return 'microsoft-graph-mail-token:' . sha1($this->tenantId . '|' . $this->clientId);
    }

    public function __toString(): string
    {
        return 'microsoft-graph://' . $this->fromMailbox;
    }
}
