<?php

namespace App\Services\Mail\Worker;

interface MailerWorkerInterface
{
    public function send($to, $subject, $message);
}
