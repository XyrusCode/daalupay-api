<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\Kyc;
use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Kyc $kyc
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KYC Submission Received'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kyc.submitted',
            with: [
                'user' => $this->user,
                'kyc' => $this->kyc,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
