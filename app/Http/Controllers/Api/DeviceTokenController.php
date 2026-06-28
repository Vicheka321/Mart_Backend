<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_id' => 'nullable|string',
            'platform'  => 'required|string',
        ]);

        DeviceToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                // 'user_id'      => optional(auth()->user())->id, // Guest = null
                'device_id'    => $request->device_id,
                'platform'     => $request->platform,
                'is_active'    => true,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device token saved successfully.'
        ]);
    }
}