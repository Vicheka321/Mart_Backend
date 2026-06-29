<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(
                config('services.firebase.credentials')
            );

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to single device
     */
    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = [],
        ?string $image = null,
    ) {
        $notification = Notification::create(
            $title,
            $body,
            $image
        );

        $message = CloudMessage::withTarget(
            'token',
            $token
        )
            ->withNotification($notification)
            ->withData($data);

        return $this->messaging->send($message);
    }

    public function sendToTokens(
        array $tokens,
        string $title,
        string $body,
        array $data = [],
        ?string $image = null,
    ) {
        foreach ($tokens as $token) {

            $this->sendToToken(
                token: $token,
                title: $title,
                body: $body,
                data: $data,
                image: $image,
            );
        }
    }
    /**
     * Send notification to Topic
     */
    public function sendToTopic(
        string $topic,
        string $title,
        string $body,
        array $data = [],
        ?string $image = null,
    ) {
        $notification = Notification::create(
            $title,
            $body,
            $image
        );

        $message = CloudMessage::withTarget(
            'topic',
            $topic
        )
            ->withNotification($notification)
            ->withData($data);

        return $this->messaging->send($message);
    }
}
