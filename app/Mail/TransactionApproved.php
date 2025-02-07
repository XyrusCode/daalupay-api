<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
use DaaluPay\Models\Transaction;

class TransactionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Transaction $transaction
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Transaction Has Been Approved'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction.approved',
            with: [
                'user'        => $this->user,
                'transaction' => $this->transaction,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
