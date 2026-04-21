<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'qr_string',
        'md5_hash',
        'status',
        'expires_at'

    ];

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
