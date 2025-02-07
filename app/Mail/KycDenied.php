<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;

class KycDenied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $reason
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KYC Submission Denied'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kyc.denied',
            with: [
                'user'   => $this->user,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
