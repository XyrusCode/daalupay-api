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

    public $paymentRequest;

    /**
     * Create a new message instance.
     */
    public function __construct($admin, $paymentRequest)
    {
        $this->admin = $admin;
        $this->paymentRequest = $paymentRequest;
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
            view: 'email.payment.new_request',
            with: [
                'admin' => $this->admin,
                'paymentRequest' => $this->paymentRequest,
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
