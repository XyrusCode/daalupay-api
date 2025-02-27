<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\Swap;
use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SwapCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Swap $swap
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Currency Swap is Complete'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.swap.swap_completed',
            with: [
                'user' => $this->user,
                'swap' => $this->swap,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
