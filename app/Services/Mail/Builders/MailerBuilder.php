<?php

namespace App\Services\Mail\Builders;

use App\Services\Mail\Mailer;

class MailerBuilder implements MailerBuilderInterface
{
    private $workers;
    private $to;
    private $subject;
    private $message;

    public function setWorkers($workers)
    {
        $this->workers = $workers;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function build(): Mailer
    {
        return new Mailer($this->workers, $this->to, $this->subject, $this->message);
    }
}
