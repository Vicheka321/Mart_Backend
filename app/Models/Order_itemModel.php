<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Order_itemModel extends Model
{
    use HasFactory;
    protected $table = "order_items";
    protected $primaryKey = "id";
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
    ];

    public function order(){
        return $this->belongsTo(OrderModel::class, 'order_id', 'id');
    }

    public function product(){
        return $this->belongsTo(ProductsModel::class, 'product_id', 'id');
    }
}
