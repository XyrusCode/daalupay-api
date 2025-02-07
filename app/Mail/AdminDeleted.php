<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\Admin;

class AdminDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Admin $admin
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Account Deleted'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.deleted',
            with: [
                'admin' => $this->admin,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
