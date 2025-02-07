<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
use DaaluPay\Models\Receipt;

class ReceiptDenied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Receipt $receipt,
        public string $reason
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Receipt Has Been Denied'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.receipt.denied',
            with: [
                'user'    => $this->user,
                'receipt' => $this->receipt,
                'reason'  => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
