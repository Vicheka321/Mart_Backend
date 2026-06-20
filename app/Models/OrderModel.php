<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\AddressModel;
use App\Models\Order_itemModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderModel extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $primaryKey = "id";
    protected $fillable = [

        'user_id',

        'delivery_address',
        'lat',
        'lng',

        'total_amount',

        'promotion_discount',

        'coupon_code',
        'coupon_type',
        'coupon_value',
        'coupon_discount',

        'payment_method',
        'status',

        'telegram_message_id',
        'telegram_chat_id',

        'is_sent',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(AddressModel::class, 'address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(Order_itemModel::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(PaymentModel::class, 'order_id');
    }

    public function promotionUsages()
    {
        return $this->hasMany(
            PromotionUsagesModel::class,
            'order_id'
        );
    }

    public function couponUsage()
    {
        return $this->hasOne(
            CouponUsageModel::class,
            'order_id'
        );
    }
}
