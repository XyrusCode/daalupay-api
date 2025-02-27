<?php

namespace DaaluPay\Mail;

use DaaluPay\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $recipient,
        public string $senderName,
        public string $messageExcerpt
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You Have a New Message'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.messages.new_message',
            with: [
                'recipient' => $this->recipient,
                'senderName' => $this->senderName,
                'messageExcerpt' => $this->messageExcerpt,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
