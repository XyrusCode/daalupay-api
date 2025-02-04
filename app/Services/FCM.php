<?php

namespace DaaluPay\Services;

// use Kreait\Firebase\Factory;
// use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
class FCMService
{
    protected $messaging;

    public function __construct()
    {
        // $this->firebase = (new Factory)->withServiceAccount(config('firebase.credentials'))->create();
        $this->messaging = app('firebase.messaging');
    }

    public function sendNotification($token, $title, $body)
    {
        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ]);
        $messaging = $this->messaging->send($message);
        return $messaging;
    }

    public function sendNotificationToTopic($topic, $title, $body)
    {
        $tokens = $this->messaging->getTopicRegistrationTokens($topic);
        $messaging = $this->messaging->sendEachForMulticast($tokens, $title, $body);
        return $messaging;
    }

    public function sendNotificationToMultipleTokens($tokens, $title, $body)
    {
        $messaging = $this->messaging->sendEachForMulticast($tokens, $title, $body);
        return $messaging;
    }

    public function sendNotificationToAll($title, $body)
    {
        $tokens = $this->messaging->getTopicRegistrationTokens('all');
        $messaging = $this->messaging->sendEachForMulticast($tokens, $title, $body);
        return $messaging;
    }

    public function sendToDevice($token, $title, $body)
    {
        $message = CloudMessage::fromArray([
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ]);
        $messaging = $this->messaging->send($message);
        return $messaging;
    }
}
