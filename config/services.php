<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'bakong' => [
        'api_url' => env('BAKONG_API_URL'),
        'token' => env('BAKONG_TOKEN'),
        'merchant' => [
            'bakong_id' => env('MERCHANT_BAKONG_ID'),
            'name' => env('MERCHANT_NAME'),
            'city' => env('MERCHANT_CITY', 'PHNOM PENH'),
            'acquiring_bank' => env('ACQUIRING_BANK'),
        ],
    ],

    'pusher' => [
        'key'     => env('PUSHER_APP_KEY'),
        'secret'  => env('PUSHER_APP_SECRET'),
        'app_id'  => env('PUSHER_APP_ID'),
        'cluster' => env('PUSHER_APP_CLUSTER'),
    ],

    'unimtx' => [
    'access_key' => env('UNIMTX_ACCESS_KEY'),
    'base_url'   => env('UNIMTX_BASE_URL'),
],
];
