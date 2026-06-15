<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UnimtxService
{
    // public function sms(string $phone, string $message)
    // {
    //     return Http::post(
    //         config('services.unimtx.base_url')
    //         .'/?action=otp.send&accessKeyId='
    //         .config('services.unimtx.access_key'),
    //         [
    //             'to' => $phone,
    //             'message' => $message,
    //             'length' => 6,
    //             'ttl' => 300,
    //         ]
    //     );
    // }
}