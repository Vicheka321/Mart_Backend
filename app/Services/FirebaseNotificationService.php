<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\MessagingException;

class FirebaseNotificationService
{
    protected $messaging;
    public function __construct()
    {
        $credentials = config('services.firebase.credentials');

        // Railway: JSON stored in environment variable
        if (is_string($credentials) && str_starts_with(trim($credentials), '{')) {

            $credentials = json_decode($credentials, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException(
                    'Invalid FIREBASE_CREDENTIALS_JSON'
                );
            }
        }

        $factory = (new Factory)
            ->withServiceAccount($credentials);

        $this->messaging = $factory->createMessaging();
    }

    // public function __construct()
    // {
    //     $factory = (new Factory)
    //         ->withServiceAccount(
    //             config('services.firebase.credentials')
    //         );

    //     $this->messaging = $factory->createMessaging();
    // }

    /**
     * Send notification to single device
     */
    // public function sendToToken(
    //     string $token,
    //     string $title,
    //     string $body,
    //     array $data = [],
    //     ?string $image = null,
    // ) {
    //     $notification = Notification::create(
    //         $title,
    //         $body,
    //         $image
    //     );

    //     $message = CloudMessage::withTarget(
    //         'token',
    //         $token
    //     )
    //         ->withNotification($notification)
    //         ->withData($data);

    //     return $this->messaging->send($message);
    // }

    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = [],
        ?string $image = null,
    ) {
        try {

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
        } catch (MessagingException $e) {

            Log::warning('FCM Error', [
                'token' => $token,
                'message' => $e->getMessage(),
            ]);

            if (
                str_contains($e->getMessage(), 'UNREGISTERED') ||
                str_contains($e->getMessage(), 'Requested entity was not found')
            ) {

                DeviceToken::where('fcm_token', $token)->delete();

                Log::info("Deleted invalid FCM token.");
            }

            return false;
        } catch (\Throwable $e) {

            Log::error($e->getMessage());

            return false;
        }
    }
    // public function sendToTokens(
    //     array $tokens,
    //     string $title,
    //     string $body,
    //     array $data = [],
    //     ?string $image = null,
    // ) {
    //     foreach ($tokens as $token) {

    //         $this->sendToToken(
    //             token: $token,
    //             title: $title,
    //             body: $body,
    //             data: $data,
    //             image: $image,
    //         );
    //     }
    // }
    public function sendToTokens(
        array $tokens,
        string $title,
        string $body,
        array $data = [],
        ?string $image = null,
    ) {
        foreach ($tokens as $token) {

            try {

                $this->sendToToken(
                    token: $token,
                    title: $title,
                    body: $body,
                    data: $data,
                    image: $image,
                );
            } catch (\Throwable $e) {

                Log::error($e->getMessage());

                continue;
            }
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
