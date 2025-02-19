<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
use DaaluPay\Models\AlipayPayment;

class ReceiptDenied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public AlipayPayment $alipayPayment,
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
            view: 'emails.receipt.denied',
            with: [
                'user'    => $this->user,
                'alipayPayment' => $this->alipayPayment,
                'reason'  => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
