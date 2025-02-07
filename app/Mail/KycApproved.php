<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;

class KycApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KYC Approved'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kyc.approved',
            with: [
                'user' => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
