<?php

namespace App\Services;

interface MailerServiceInterface
{
    public function send($to, $subject, $message);
}
