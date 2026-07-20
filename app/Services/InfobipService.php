<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InfobipService
{
    public function sendSms(string $phone, string $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'App ' . config('services.infobip.api_key'),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post(
            config('services.infobip.base_url') . '/sms/3/messages',
            [
                'messages' => [
                    [
                        'from' => config('services.infobip.sender'),

                        'destinations' => [
                            [
                                'to' => $phone,
                            ]
                        ],

                        'content' => [
                            'text' => $message,
                        ],
                    ],
                ],
            ]
        );

        return [
            'status' => $response->status(),
            'body'   => $response->json(),
        ];
    }
}