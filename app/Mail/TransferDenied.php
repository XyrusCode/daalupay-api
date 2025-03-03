<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\Transfer;
use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferDenied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Transfer $transfer,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payment Has Been Denied'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transfer.denied',
            with: [
                'user' => $this->user,
                'transfer' => $this->transfer,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
