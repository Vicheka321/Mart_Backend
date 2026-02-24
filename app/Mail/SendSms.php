<?php

use Twilio\Rest\Client;

function sendSms($phone, $message)
{
    $client = new Client(
        env('TWILIO_SID'),
        env('TWILIO_TOKEN')
    );

    $client->messages->create($phone, [
        'from' => env('TWILIO_FROM'),
        'body' => $message
    ]);
}