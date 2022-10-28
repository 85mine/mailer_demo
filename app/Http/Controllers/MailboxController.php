<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mailbox\SendRequest;
use App\Services\MailerService;
use Inertia\Inertia;

class MailboxController extends Controller
{
    public function index()
    {
        return Inertia::render('Mailbox');
    }

    public function send(SendRequest $request)
    {
        $mailService = new MailerService();
        $success = $mailService->send($request->to, $request->subject, $request->message);
        if (!$success) {
            return redirect()->route('mailbox.index')->withErrors([
                'flash_message' => 'We got an error in sending process, please try again later'
            ]);
        } else {
            return redirect()->route('mailbox.index')->with('message', 'We had sent, please check your inbox');
        }
    }
}
