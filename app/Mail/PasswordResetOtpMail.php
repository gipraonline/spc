<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PasswordResetOtpMail extends Mailable
{
    public function __construct(
        public string $name,
        public string $code,
        public int $minutes = 10,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your SPC password reset code: '.$this->code);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.password-reset-otp');
    }
}
