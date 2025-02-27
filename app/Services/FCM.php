<?php

namespace DaaluPay\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Messaging\Notification;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    public function sendNotification(string $token, string $title, string $body)
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body));

        return $this->messaging->send($message);
    }

    public function sendNotificationToTopic(string $topic, string $title, string $body)
    {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body));

        return $this->messaging->send($message);
    }

    public function sendNotificationToMultipleTokens(array $tokens, string $title, string $body): MulticastSendReport
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body));

        return $this->messaging->sendMulticast($message, $tokens);
    }
}
