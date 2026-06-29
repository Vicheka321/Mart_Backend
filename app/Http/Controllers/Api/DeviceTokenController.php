<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_id' => 'nullable|string',
            'platform'  => 'required|string',
        ]);
        $user_id = Auth::id();

        DeviceToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                'user_id'      => $user_id,
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

    public function storeGuest(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => ['required', 'string'],
            'device_id' => ['nullable', 'string'],
            'platform'  => ['required', 'in:android,ios'],
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            [
                'fcm_token' => $validated['fcm_token'],
            ],
            [
                'user_id'      => null,
                'device_id'    => $validated['device_id'] ?? null,
                'platform'     => $validated['platform'],
                'topic'        => 'guest',
                'is_active'    => true,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Guest device token saved successfully.',
            'data'    => $deviceToken,
        ]);
    }
}
