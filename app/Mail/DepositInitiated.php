<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
use DaaluPay\Models\Deposit;

class DepositInitiated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Deposit $deposit
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Initiated'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit.deposit_initiated',
            with: [
                'user'    => $this->user,
                'deposit' => $this->deposit,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
