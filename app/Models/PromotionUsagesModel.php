<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUsagesModel extends Model
{
    protected $table = 'promotion_usages';

    protected $fillable = [
        'promotion_id',
        'user_id',
        'order_id',
        'discount_amount',
    ];

    public function promotion()
    {
        return $this->belongsTo(
            PromotionModel::class,
            'promotion_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function order()
    {
        return $this->belongsTo(
            OrderModel::class,
            'order_id'
        );
    }
}
