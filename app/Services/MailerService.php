<?php

namespace App\Services;

use App\Services\Mail\Builders\MailerBuilder;
use App\Services\Mail\Worker\MailgunMailerWorker;
use App\Services\Mail\Worker\SesMailerWorker;
use App\Services\Mail\Worker\SparkpostMailerWorker;

class MailerService implements MailerServiceInterface
{
    /**
     * @var MailerBuilder
     */
    private $builder;

    public function __construct()
    {
        $this->builder = new MailerBuilder();
    }

    public function send($to, $subject, $message)
    {
        // Priority of worker is from top to down of the array
        $mailer = $this->builder
            ->setWorkers([
                new SesMailerWorker,
                new MailgunMailerWorker,
                new SparkpostMailerWorker,
            ])
            ->setTo($to)
            ->setSubject($subject)
            ->setMessage($message)
            ->build();
        return $mailer->send();
    }
}
