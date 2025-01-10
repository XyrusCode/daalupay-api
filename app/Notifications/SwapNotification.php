<?php

namespace DaaluPay\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SwapApprovalNotification extends Notification
{
    use Queueable;

    private $swapData;

    public function __construct($swapData)
    {
        $this->swapData = $swapData;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Swap Approval Required')
            ->line('A new swap transaction requires your approval.')
            ->line("From Currency: {$this->swapData->from_currency}")
            ->line("To Currency: {$this->swapData->to_currency}")
            ->line("Amount: {$this->swapData->amount}")
            ->action('Review Swap', url('/admin/swaps'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'New swap approval required',
            'swap_id' => $this->swapData->id,
            'from_currency' => $this->swapData->from_currency,
            'to_currency' => $this->swapData->to_currency,
            'amount' => $this->swapData->amount,
        ];
    }
}
