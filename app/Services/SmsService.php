<?php

namespace App\Services;


use Twilio\Rest\Client;
use Exception;
use log;
class SmsService
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
        $this->from = env('TWILIO_PHONE');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return true;
        } catch (Exception $e) {
            
            return false;
        }
    }
}

