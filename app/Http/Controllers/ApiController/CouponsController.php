<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\CouponModel;
use App\Models\CouponUsageModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'code' => ['required', 'string'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $total   = (float) $request->total;

        $couponCode = trim($request->code);

        $coupon = CouponModel::where('code', 'ILIKE', $couponCode)
            ->where('status', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.',
            ], 422);
        }

        if (
            ($coupon->start_date && now()->lt($coupon->start_date)) ||
            ($coupon->end_date && now()->gt($coupon->end_date->endOfDay()))
        ) {
            return response()->json([
                'success' => false,
                'message' => $coupon->start_date && now()->lt($coupon->start_date)
                    ? 'This coupon is not active yet.'
                    : 'This coupon has expired.',
            ], 422);
        }

        if ($total < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is $' .
                    number_format($coupon->min_order_amount, 2),
            ], 422);
        }

        if (
            !is_null($coupon->usage_limit) &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit.',
            ], 422);
        }

        $userUsageCount = CouponUsageModel::where('coupon_id', $coupon->id)
            ->where('user_id', $user_id)
            ->whereNotNull('order_id')
            ->count();

        if ($userUsageCount >= $coupon->usage_limit_per_user) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used this coupon.',
            ], 422);
        }

        if ($coupon->discount_type === 'percent') {
            $couponDiscount = ($total * $coupon->discount_value) / 100;

            if (!is_null($coupon->max_discount)) {
                $couponDiscount = min(
                    $couponDiscount,
                    $coupon->max_discount
                );
            }
        } else {
            $couponDiscount = $coupon->discount_value;
        }
        $couponDiscount = min($couponDiscount, $total);
        $finalTotal = $total - $couponDiscount;

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully.',

            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
            ],

            'summary' => [
                'subtotal'        => round($total, 2),
                'coupon_discount' => round($couponDiscount, 2),
                'final_total'     => round($finalTotal, 2),
            ],
        ]);
    }
}
