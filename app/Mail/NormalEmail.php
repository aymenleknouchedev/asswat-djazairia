<?php

namespace App\Mail;

use App\Models\Mail as MailModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NormalEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected MailModel $mail;

    /** Absolute paths of the files to attach. */
    protected array $files;

    public function __construct(MailModel $mail, array $files = [])
    {
        $this->mail = $mail;
        $this->files = $files;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'contact@asswatdjazairia.com'),
                config('mail.from.name')
            ),
            replyTo: [new Address(config('app.admin_email', config('mail.from.address')))],
            subject: $this->mail->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.normal',
            with: [
                'body' => $this->mail->body,
            ],
        );
    }

    /**
     * Real file attachments (not links).
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return collect($this->files)
            ->filter(fn ($path) => is_string($path) && is_file($path))
            ->map(fn ($path) => Attachment::fromPath($path))
            ->values()
            ->all();
    }
}
