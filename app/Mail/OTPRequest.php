<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;

class OTPRequest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your One-Time Password (OTP)'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.otp_request',
            with: [
                'user' => $this->user,
                'otp'  => $this->otp,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
