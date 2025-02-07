<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;

class WalletCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Wallet $wallet
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Wallet Added to Your Account'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet.wallet_created',
            with: [
                'user'   => $this->user,
                'wallet' => $this->wallet,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
