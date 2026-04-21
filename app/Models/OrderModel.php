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
        'address_id',
        'total_amount',
        'status',
        'telegram_message_id',
        'telegram_chat_id'

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
}
