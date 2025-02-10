<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\Swap;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;

class TransactionDenied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Swap $swap,
        public string $reason
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Transaction Has Been Denied'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction.denied',
            with: [
                'user'        => $this->user,
                'swap' => $this->swap,
                'reason'      => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
