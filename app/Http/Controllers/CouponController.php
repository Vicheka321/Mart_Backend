<?php

namespace App\Http\Controllers;

use App\Models\CouponModel;
use App\Models\CouponUsageModel;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        // Get paginated coupons
        $coupons = CouponModel::latest()->paginate(10);

        // Total coupons
        $totalCoupons = CouponModel::count();

        // Active coupons:
        // - status = true (1)
        // - not expired OR no end_date
        $activeCoupons = CouponModel::where('status', true)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->startOfDay());
            })
            ->count();

        // Expired coupons:
        // - has end_date
        // - end_date < today
        $expiredCoupons = CouponModel::whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->count();

        // Inactive coupons:
        // - status = false (0)
        $inactiveCoupons = CouponModel::where('status', false)->count();

        return view('Admin.coupons', compact(
            'coupons',
            'totalCoupons',
            'activeCoupons',
            'expiredCoupons',
            'inactiveCoupons'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'code' => preg_replace('/\s+/', ' ', trim($request->code))
        ]);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {

                    $normalized = strtolower(
                        preg_replace('/\s+/', '', trim($value))
                    );

                    $exists = CouponModel::get()->contains(function ($coupon) use ($normalized) {

                        return strtolower(
                            preg_replace('/\s+/', '', trim($coupon->code))
                        ) === $normalized;
                    });

                    if ($exists) {
                        $fail('Coupon code already exists.');
                    }
                }
            ],
            'name'                 => 'nullable|string|max:255',
            'description'          => 'nullable|string|max:255',
            'discount_type'        => 'required|in:percent,fixed',
            'discount_value'       => 'required|numeric|min:0',
            'min_order_amount'     => 'nullable|numeric|min:0',
            'max_discount'         => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'status'               => 'required|in:0,1',
        ]);

        $validated['used_count'] = 0;
        $validated['status'] = (bool) $request->status;

        CouponModel::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully!',
            ]);
        }
        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, CouponModel $coupon)
    {
        $request->merge([
            'code' => preg_replace('/\s+/', ' ', trim($request->code))
        ]);
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($coupon) {

                    $normalized = strtolower(
                        preg_replace('/\s+/', '', trim($value))
                    );

                    $exists = CouponModel::where('id', '!=', $coupon->id)
                        ->get()
                        ->contains(function ($item) use ($normalized) {

                            return strtolower(
                                preg_replace('/\s+/', '', trim($item->code))
                            ) === $normalized;
                        });

                    if ($exists) {
                        $fail('Coupon code already exists.');
                    }
                }
            ],
            'name'                 => 'nullable|string|max:255',
            'description'          => 'nullable|string|max:255',
            'discount_type'        => 'required|in:percent,fixed',
            'discount_value'       => 'required|numeric|min:0',
            'min_order_amount'     => 'nullable|numeric|min:0',
            'max_discount'         => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'status'               => 'required|in:0,1',
        ]);

        $validated['status'] = (bool) $request->status;
        $coupon->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully.',
            ]);
        }

        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(CouponModel $coupon)
    {
        $coupon->delete();

        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
