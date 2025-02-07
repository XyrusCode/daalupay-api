<?php

namespace DaaluPay\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\Admin;

class AdminReactivated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Admin $admin
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Account Reactivated'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.reactivated',
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
