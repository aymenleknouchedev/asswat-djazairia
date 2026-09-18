<?php

namespace App\Console\Commands;

use App\Mail\NormalEmail;
use App\Models\Mail as MailModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {email : The recipient address} {--mailer= : Force a specific mailer, e.g. microsoft}';

    protected $description = 'Send a test email to verify the mail configuration';

    public function handle(): int
    {
        $mailer = $this->option('mailer') ?: config('mail.default');
        $to = $this->argument('email');

        $this->line("Mailer:  <info>{$mailer}</info>");
        $this->line('From:    <info>' . config('mail.from.address') . '</info>');
        $this->line("To:      <info>{$to}</info>");

        // Not persisted: this is only a carrier for the mailable.
        $message = new MailModel([
            'email' => $to,
            'subject' => 'اختبار إرسال البريد — ' . config('app.name'),
            'body' => '<p>هذه رسالة اختبار من لوحة التحكم.</p><p>إذا وصلتك هذه الرسالة فإن إعدادات البريد تعمل بشكل صحيح.</p>',
        ]);

        try {
            Mail::mailer($mailer)->to($to)->send(new NormalEmail($message));
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('Sending failed: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Sent. Check the inbox (and the spam folder).');

        return self::SUCCESS;
    }
}
