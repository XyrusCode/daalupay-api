<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\Transfer;
use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Transfer $transfer
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payment Has Been Approved'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transfer.approved',
            with: [
                'user' => $this->user,
                'transfer' => $this->transfer,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
