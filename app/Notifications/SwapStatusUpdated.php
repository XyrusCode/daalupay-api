<?php

namespace DaaluPay\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SwapStatusUpdated extends Notification
{
    use Queueable;

    protected $status;

    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $status, ?string $reason = null)
    {
        $this->status = $status;
        $this->reason = $reason;
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
        $message = (new MailMessage)
            ->subject("Swap Request {$this->status}")
            ->greeting("Hello {$notifiable->name},");

        if ($this->status === 'approved') {
            $message->line('Your swap request has been approved!')
                ->line('You can now proceed with the swap transaction.')
                ->action('View Swap Details', config('app.url').'/swaps');
        } else {
            $message->line('Your swap request has been denied.')
                ->line("Reason: {$this->reason}")
                ->action('Contact Support', config('app.url').'/support');
        }

        return $message->line('Thank you for using our service.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }
}
