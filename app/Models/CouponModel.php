<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CouponModel extends Model

{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'name',
        'description',     
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function usages()
    {
        return $this->hasMany(CouponUsageModel::class, 'coupon_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    // Check if coupon is currently valid
    public function isValid()
    {
        $today = Carbon::today();

        // Must be active
        if (!$this->status) {
            return false;
        }

        // Start date check
        if ($this->start_date && $this->start_date->gt($today)) {
            return false;
        }

        // End date check
        if ($this->end_date && $this->end_date->lt($today)) {
            return false;
        }

        // Global usage limit
        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    // Calculate discount based on cart subtotal
    public function calculateDiscount($subtotal)
    {
        $discount = 0;

        if ($this->discount_type === 'percent') {
            $discount = $subtotal * ($this->discount_value / 100);
        } else {
            $discount = $this->discount_value;
        }

        // Apply max discount limit if set
        if (!is_null($this->max_discount)) {
            $discount = min($discount, $this->max_discount);
        }

        // Discount cannot exceed subtotal
        return min($discount, $subtotal);
    }
}
