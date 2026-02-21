<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class OrderModel extends Model
{
    use HasFactory;
    protected $table = "Orders";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'total_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
