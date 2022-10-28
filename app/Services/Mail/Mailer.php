<?php

namespace App\Services\Mail;

use App\Services\Mail\Worker\MailerWorkerInterface;

class Mailer implements MailerInterface
{
    private $to;
    private $subject;
    private $message;

    /*
     * For one mailer you have to create a `worker` class and implement MailerWorkerInterface to handle the send method
     * The position of worker in the array determines its priority from high to low
     * The class name following format <Worker>MailerWorker
     * Ex: MailgunMailerWorker, SesMailerWorker, SparkpostMailerWorker
     */
    private $workers;

    public function __construct($workers, $to, $subject, $message)
    {
        if (is_array($workers)) {
            foreach ($workers as $worker) {
                if (!$worker instanceof MailerWorkerInterface) {
                    throw new \Exception('The worker ' . get_class($worker) . ' is not instance of MailerWorkerInterface');
                }
                $this->workers[] = $worker;
            }
        }
        if (empty($this->workers)) {
            throw new \Exception('You have to at least 1 worker');
        }
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function send()
    {
        // Get current mailer in array
        $mailer = current($this->workers);
        if ($mailer) {
            $mailerClass = get_class($mailer);
            // For testing only, in the real system please remove it
            $footer = "\n<br/>From <b>$mailerClass</b> with love";
            $message = $this->message . $footer;
            \Log::info("We are sending email to client $this->to from $mailerClass with content...");
            \Log::info($this->message);
            // Check status of sending service
            $success = $mailer->send($this->to, $this->subject, $message);
            if (!$success) {
                // If the current sending service is down or got some exception we will continue with next service
                next($this->workers);
                return $this->send();
            } else {
                \Log::info("The email was sent to the client $this->to by $mailerClass");
                return $mailer;
            }
        }
        return false;
    }
}
