<?php

namespace Tests\Feature\Services;

use App\Services\Mail\Builders\MailerBuilder;
use App\Services\Mail\Worker\MailgunMailerWorker;
use App\Services\Mail\Worker\SesMailerWorker;
use App\Services\Mail\Worker\SparkpostMailerWorker;
use Tests\TestCase;

class MailerServiceTest extends TestCase
{

    private $builder;
    private $to;
    private $subject;
    private $message;
    private $mailgunMailerWorker;
    private $sesMailerWorker;
    private $sparkpostMailerWorker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new MailerBuilder();
        $this->to = 'admin@example.com';
        $this->subject = 'Subject';
        $this->message = 'Message';

        $this->mailgunMailerWorker = $this->createMock(MailgunMailerWorker::class);
        $this->sesMailerWorker = $this->createMock(SesMailerWorker::class);
        $this->sparkpostMailerWorker = $this->createMock(SparkpostMailerWorker::class);

    }

    public function test_send_by_mailgun_is_called()
    {
        $this->mailgunMailerWorker->method('send')->willReturn(true);
        $this->sesMailerWorker->method('send')->willReturn(false);
        $this->sparkpostMailerWorker->method('send')->willReturn(false);

        $mailer = $this->builder
            ->setWorkers([
                $this->sesMailerWorker,
                $this->mailgunMailerWorker,
                $this->sparkpostMailerWorker,
            ])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setMessage($this->message)
            ->build();
        $this->assertEquals($this->mailgunMailerWorker, $mailer->send());
    }

    public function test_send_by_ses_is_called()
    {
        $this->mailgunMailerWorker->method('send')->willReturn(false);
        $this->sesMailerWorker->method('send')->willReturn(true);
        $this->sparkpostMailerWorker->method('send')->willReturn(false);

        $mailer = $this->builder
            ->setWorkers([
                $this->sesMailerWorker,
                $this->mailgunMailerWorker,
                $this->sparkpostMailerWorker,
            ])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setMessage($this->message)
            ->build();
        $this->assertEquals($this->sesMailerWorker, $mailer->send());
    }

    public function test_send_by_sparkpost_is_called()
    {
        $this->mailgunMailerWorker->method('send')->willReturn(false);
        $this->sesMailerWorker->method('send')->willReturn(false);
        $this->sparkpostMailerWorker->method('send')->willReturn(true);

        $mailer = $this->builder
            ->setWorkers([
                $this->sesMailerWorker,
                $this->mailgunMailerWorker,
                $this->sparkpostMailerWorker,
            ])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setMessage($this->message)
            ->build();
        $this->assertEquals($this->sparkpostMailerWorker, $mailer->send());
    }

    public function test_send_priority()
    {
        $this->mailgunMailerWorker->method('send')->willReturn(true);
        $this->sesMailerWorker->method('send')->willReturn(true);
        $this->sparkpostMailerWorker->method('send')->willReturn(true);

        $mailer = $this->builder
            ->setWorkers([
                $this->sesMailerWorker,
                $this->mailgunMailerWorker,
                $this->sparkpostMailerWorker,
            ])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setMessage($this->message)
            ->build();
        $this->assertEquals($this->sesMailerWorker, $mailer->send());
    }

    public function test_send_is_failed()
    {
        $this->mailgunMailerWorker->method('send')->willReturn(false);
        $this->sesMailerWorker->method('send')->willReturn(false);
        $this->sparkpostMailerWorker->method('send')->willReturn(false);

        $mailer = $this->builder
            ->setWorkers([
                $this->sesMailerWorker,
                $this->mailgunMailerWorker,
                $this->sparkpostMailerWorker,
            ])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setMessage($this->message)
            ->build();
        $this->assertFalse($mailer->send());
    }

}
