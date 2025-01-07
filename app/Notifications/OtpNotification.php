<?php

namespace DaaluPay\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    protected $otp;
    protected $validityInMinutes;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp, int $validityInMinutes = 5)
    {
        $this->otp = $otp;
        $this->validityInMinutes = $validityInMinutes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your OTP Verification Code')
            ->greeting('Hello!')
            ->line('Your OTP verification code is:')
            ->line($this->otp)
            ->line("This code will expire in {$this->validityInMinutes} minutes.")
            ->line('If you did not request this code, please ignore this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'validity_minutes' => $this->validityInMinutes,
            'expires_at' => now()->addMinutes($this->validityInMinutes),
        ];
    }
}
