<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InfobipService
{
    public function sendSms(
        string $phone,
        string $message
    ) {

        $response = Http::withHeaders([
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post(
            env('INFOBIP_BASE_URL') . '/sms/3/messages',
            [
                'messages' => [
                    [
                        'destinations' => [
                            [
                                'to' => $phone,
                            ]
                        ],

                        'sender' => env('INFOBIP_SENDER'),

                        'content' => [
                            'text' => $message,
                        ],
                    ]
                ]
            ]
        );

        if (!$response->successful()) {

            throw new \Exception(
                $response->body()
            );
        }

        return $response->json();
    }
}