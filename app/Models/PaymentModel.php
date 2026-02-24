<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'transaction_id',
        'amount'
    ];

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
