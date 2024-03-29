<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEmployeeOnboarding extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeName;
    public $onboardingLink;

    /**
     * Create a new message instance.
     */
    public function __construct($employeeName, $onboardingLink)
    {
        $this->employeeName = $employeeName;
        $this->onboardingLink = $onboardingLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Our Company - Employee Onboarding')
            ->view('email.new_employee_onboarding');
    }
}
