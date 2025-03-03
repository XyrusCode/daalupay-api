<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;

    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct($admin, $payment)
    {
        $this->admin = $admin;
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Request Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.payment.request',
            with: [
                'admin' => $this->admin,
                'payment' => $this->payment,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
