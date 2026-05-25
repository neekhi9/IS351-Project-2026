<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The registration instance.
     *
     * @var \App\Models\Registration
     */
    public Registration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'New Registration Submitted (' . $this->registration->account_type . ') #' . $this->registration->id;

        return $this->subject($subject)
            ->view('emails.admin.registration_notification')
            ->with([
                'registration' => $this->registration,
            ]);
    }
}
