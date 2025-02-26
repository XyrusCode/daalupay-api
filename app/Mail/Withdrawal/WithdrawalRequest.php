<?php

namespace DaaluPay\Mail\Withdrawal;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\Admin;
use DaaluPay\Models\User;
use DaaluPay\Models\Withdrawal;

class WithdrawalRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Admin $admin,
        public User $user,
        public Withdrawal $withdrawal,
    )

    {
        $this->admin = $admin;
        $this->user = $user;
        $this->withdrawal = $withdrawal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Withdrawal Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawal.request',
            with: [
                'withdrawal' => $this->withdrawal,
                'user' => $this->user,
                'admin' => $this->admin,
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
