<?php

namespace App\Services\Mail\Worker;

use GuzzleHttp\Client;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use SparkPost\SparkPost;

class SparkpostMailerWorker implements MailerWorkerInterface
{
    public function send($to, $subject, $message)
    {
        try {
            $sparky = new SparkPost(new GuzzleAdapter(new Client()), ['key' => config('services.sparkpost.secret'), 'async' => false]);
            $sparky->transmissions->post([
                'content' => [
                    'from' => [
                        'name' => config('mail.from.name'),
                        'email' => config('mail.from.address'),
                    ],
                    'subject' => $subject,
                    'html' => $message,
                    'text' => $message,
                ],
                'recipients' => [
                    [
                        'address' => [
                            'email' => $to,
                        ],
                    ],
                ]
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }
}
