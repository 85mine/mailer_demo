<?php

namespace App\Services\Mail\Worker;

use Aws\Ses\SesClient;

class SesMailerWorker implements MailerWorkerInterface
{
    public function send($to, $subject, $message)
    {
        try {
            $SesClient = new SesClient([
                'region' => 'ap-northeast-1',
                'version' => '2010-12-01',
                'credentials' => [
                    'key' => config('services.ses.key'),
                    'secret' => config('services.ses.secret'),
                ]
            ]);
            $SesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$to],
                ],
                'Source' => config('mail.from.address'),
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => $message,
                        ],
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => $message,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => $subject,
                    ],
                ],
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }
}
