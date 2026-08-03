<?php

namespace App\Http\Controllers;

use App\Models\DeliveryFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryFeeController extends Controller
{


    /**
     * Display delivery fee list
     */
    public function index()
    {
        $deliveryFees = DeliveryFee::orderBy('min_km')->paginate(10);

        return view('Admin.deliveryfees', compact('deliveryFees'));
    }

    /**
     * Store new delivery fee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_km' => ['required', 'numeric', 'min:0'],
            'max_km' => ['required', 'numeric', 'gt:min_km'],
            'fee'    => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $exists = DeliveryFee::where(function ($query) use ($validated) {
            $query->whereBetween('min_km', [$validated['min_km'], $validated['max_km']])
                ->orWhereBetween('max_km', [$validated['min_km'], $validated['max_km']])
                ->orWhere(function ($q) use ($validated) {
                    $q->where('min_km', '<=', $validated['min_km'])
                        ->where('max_km', '>=', $validated['max_km']);
                });
        })->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'min_km' => [
                        'This delivery distance range overlaps with an existing range.'
                    ]
                ]
            ], 422);
        }

        $deliveryFee = DeliveryFee::create([
            'min_km' => $validated['min_km'],
            'max_km' => $validated['max_km'],
            'fee'    => $validated['fee'],
            'status' => $request->boolean('status'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery fee created successfully.',
            'data'    => $deliveryFee,
        ]);
    }

    /**
     * Update delivery fee
     */
    public function update(Request $request, DeliveryFee $deliveryFee)
    {
        $validated = $request->validate([
            'min_km' => ['required', 'numeric', 'min:0'],
            'max_km' => ['required', 'numeric', 'gt:min_km'],
            'fee'    => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $exists = DeliveryFee::where('id', '!=', $deliveryFee->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('min_km', [$validated['min_km'], $validated['max_km']])
                    ->orWhereBetween('max_km', [$validated['min_km'], $validated['max_km']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('min_km', '<=', $validated['min_km'])
                            ->where('max_km', '>=', $validated['max_km']);
                    });
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'min_km' => [
                        'This delivery distance range overlaps with an existing range.'
                    ]
                ]
            ], 422);
        }

        $deliveryFee->update([
            'min_km' => $validated['min_km'],
            'max_km' => $validated['max_km'],
            'fee'    => $validated['fee'],
            'status' => $request->boolean('status'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery fee updated successfully.',
            'data'    => $deliveryFee,
        ]);
    }

    /**
     * Delete delivery fee
     */
    public function destroy(DeliveryFee $deliveryFee)
    {
        $deliveryFee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delivery fee deleted successfully.',
        ]);
    }
}
