<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\User;
use DaaluPay\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Withdrawal $withdrawal
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Withdrawal Has Been Approved'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction.approved',
            with: [
                'user' => $this->user,
                'withdrawal' => $this->withdrawal,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
