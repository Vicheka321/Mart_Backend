<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartModel extends Model
{
    use HasFactory;
    protected $table = "carts";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItemModel::class);
    }

    public function get_items()
    {
        return $this->hasMany(CartItemModel::class, 'cart_id',);
    }
}
