<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnauthorizedSuperAdminAccess extends Notification
{
    use Queueable;

    private $attemptedRoute;

    private $adminUser;

    public function __construct($adminUser, $attemptedRoute)
    {
        $this->adminUser = $adminUser;
        $this->attemptedRoute = $attemptedRoute;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Unauthorized Super Admin Access Attempt')
            ->line('An admin has attempted to access a super admin route.')
            ->line("Admin: {$this->adminUser->email}")
            ->line("Attempted Route: {$this->attemptedRoute}")
            ->line('Time: '.now()->format('Y-m-d H:i:s'));
    }

    public function toArray($notifiable): array
    {
        return [
            'admin_id' => $this->adminUser->id,
            'admin_email' => $this->adminUser->email,
            'attempted_route' => $this->attemptedRoute,
            'timestamp' => now(),
        ];
    }
}
