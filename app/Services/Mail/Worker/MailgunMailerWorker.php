<?php

namespace App\Services\Mail\Worker;

use Mailgun\Mailgun;

class MailgunMailerWorker implements MailerWorkerInterface
{
    public function send($to, $subject, $message)
    {
        try {
            Mailgun::create(config('services.mailgun.secret'))
                ->messages()->send(config('services.mailgun.domain'), [
                    'from' => config('mail.from.address'),
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $message,
                    'text' => $message
                ]);
            return true;
        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }
}
