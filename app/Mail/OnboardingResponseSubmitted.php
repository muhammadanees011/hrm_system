<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OnboardingResponseSubmitted extends Mailable
{
    use Queueable, SerializesModels;
    public $responseLink;
    public $asd;


    /**
     * Create a new message instance.
     */

     public function __construct($responseLink)
     {
         $this->responseLink = $responseLink;
     }

     /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Onboarding Response Submitted')
                    ->view('email.onboarding_response_submitted');
    }
}
