<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DeliveryFee;
use Illuminate\Http\Request;
use App\Services\GoogleRouteService;

class DeliveryController extends Controller
{
    // public function quote(Request $request, GoogleRouteService $google)
    // {
    //     $request->validate([
    //         'lat' => ['required', 'numeric'],
    //         'lng' => ['required', 'numeric'],
    //     ]);

    //     $customerLat = $request->lat;
    //     $customerLng = $request->lng;

    //     /*
    // |--------------------------------------------------------------------------
    // | STEP 1 : Find Nearest Branch (Haversine)
    // |--------------------------------------------------------------------------
    // */

    //     $branch = Branch::selectRaw("
    //         *,
    //         (
    //             6371 * acos(
    //                 cos(radians(?))
    //                 * cos(radians(lat))
    //                 * cos(radians(lng) - radians(?))
    //                 + sin(radians(?))
    //                 * sin(radians(lat))
    //             )
    //         ) AS air_distance
    //     ", [
    //         $customerLat,
    //         $customerLng,
    //         $customerLat
    //     ])
    //         ->where('status', true)
    //         ->orderBy('air_distance')
    //         ->first();



    //     // if (!$branch) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'No active branch found.'
    //     //     ], 404);
    //     // }

    //     if (!$branch) {
    //         return response()->json([
    //             'success' => false,
    //             'code' => 'NO_BRANCH',
    //             'message' => 'No active branch is available for delivery.'
    //         ], 422);
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | STEP 2 : Google Routes API
    // |--------------------------------------------------------------------------
    // */

    //     $distanceKm = $google->getDrivingDistance(
    //         $branch->lat,
    //         $branch->lng,
    //         $customerLat,
    //         $customerLng
    //     );
    //     /*
    // |--------------------------------------------------------------------------
    // | STEP 3 : Calculate Delivery Fee
    // |--------------------------------------------------------------------------
    // */

    //     $delivery = DeliveryFee::where('status', true)
    //         ->where('min_km', '<=', $distanceKm)
    //         ->where('max_km', '>=', $distanceKm)
    //         ->first();

    //     if (!$delivery) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delivery area is not supported.'
    //         ], 422);
    //     }
    //     /*
    // |--------------------------------------------------------------------------
    // | STEP 4 : Return Response
    // |--------------------------------------------------------------------------
    // */

    //     return response()->json([
    //         'success' => true,

    //         'branch' => [
    //             'id' => $branch->id,
    //             'name' => $branch->name,
    //             'address' => $branch->address,
    //             'phone' => $branch->phone,
    //         ],

    //         'distance_km' => round($distanceKm, 2),

    //         'delivery_fee' => $delivery->fee,
    //     ]);
    // }

    public function quote(Request $request, GoogleRouteService $google)
    {
        $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        $customerLat = $request->lat;
        $customerLng = $request->lng;

        /*
    |--------------------------------------------------------------------------
    | STEP 1 : Find Nearest Active Branch
    |--------------------------------------------------------------------------
    */

        $branch = Branch::selectRaw("
        *,
        (
            6371 * acos(
                cos(radians(?))
                * cos(radians(lat))
                * cos(radians(lng) - radians(?))
                + sin(radians(?))
                * sin(radians(lat))
            )
        ) AS air_distance
    ", [
            $customerLat,
            $customerLng,
            $customerLat
        ])
            ->where('status', true)
            ->orderBy('air_distance')
            ->first();


        /*
    |--------------------------------------------------------------------------
    | NO ACTIVE BRANCH
    |--------------------------------------------------------------------------
    */

        if (!$branch) {
            return response()->json([
                'success' => false,
                'code' => 'NO_BRANCH',
                'message' => 'No active branch is available for delivery.'
            ], 422);
        }


        /*
    |--------------------------------------------------------------------------
    | STEP 2 : Get Driving Distance
    |--------------------------------------------------------------------------
    */

        $distanceKm = $google->getDrivingDistance(
            $branch->lat,
            $branch->lng,
            $customerLat,
            $customerLng
        );


        /*
    |--------------------------------------------------------------------------
    | STEP 3 : Find Delivery Fee
    |--------------------------------------------------------------------------
    */

        $delivery = DeliveryFee::where('status', true)
            ->where('min_km', '<=', $distanceKm)
            ->where(function ($q) use ($distanceKm) {
                $q->where('max_km', '>=', $distanceKm)
                    ->orWhereNull('max_km');
            })
            ->first();


        /*
    |--------------------------------------------------------------------------
    | NO DELIVERY FEE / AREA NOT SUPPORTED
    |--------------------------------------------------------------------------
    */

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'code' => 'DELIVERY_NOT_SUPPORTED',
                'message' => 'Sorry, we currently do not deliver to this location.'
            ], 422);
        }


        /*
    |--------------------------------------------------------------------------
    | STEP 4 : Return Quote
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,

            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'phone' => $branch->phone,
            ],

            'distance_km' => round($distanceKm, 2),

            'delivery_fee' => round($delivery->fee, 2),
        ]);
    }
}
