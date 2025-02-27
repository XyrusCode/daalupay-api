<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\AlipayPayment;
use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceiptApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public AlipayPayment $alipayPayment
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
            view: 'emails.receipt.approved',
            with: [
                'user' => $this->user,
                'alipayPayment' => $this->alipayPayment,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
