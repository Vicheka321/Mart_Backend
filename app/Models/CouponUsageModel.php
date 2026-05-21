<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsageModel extends Model

{
    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function coupon()
    {
        return $this->belongsTo(CouponModel::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
