<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\Admin;

class AdminSuspended extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Admin $admin,
        public string $reason
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Account Suspended'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.suspended',
            with: [
                'admin'  => $this->admin,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
