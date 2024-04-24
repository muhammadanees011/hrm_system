<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageCaseDiscussion extends Mailable
{
    use Queueable, SerializesModels;

    public $case;

    /**
     * Create a new message instance.
     */
    public function __construct($case)
    {
        $this->case = $case;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Message Case Discussion')
            ->view('email.new_message_case_discussion');
    }
}
