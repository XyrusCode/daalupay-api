<?php

namespace DaaluPay\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use DaaluPay\Models\User;
class OtpNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public User $user;
    protected $otp;
    protected $validityInMinutes;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $otp, int $validityInMinutes = 5)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->validityInMinutes = $validityInMinutes;
    }

      /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DaluuPay - OTP Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration.request-otp',
            with: [
                'user' => $this->user,
                'otp' => $this->otp,
                'validityInMinutes' => $this->validityInMinutes,
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
