<?php

namespace App\Services\Mail\Builders;

interface MailerBuilderInterface
{
    public function setWorkers($workers);

    public function setTo($to);

    public function setSubject($subject);

    public function setMessage($message);

    public function build();
}
