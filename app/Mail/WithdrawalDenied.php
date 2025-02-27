<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalDenied extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $withdrawal;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $withdrawal
     */
    public function __construct($user, $withdrawal, $reason)
    {
        $this->user = $user;
        $this->withdrawal = $withdrawal;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Withdrawal Denied',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'denied',
            with: [
                'user' => $this->user,
                'withdrawal' => $this->withdrawal,
                'reason' => $this->reason
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
