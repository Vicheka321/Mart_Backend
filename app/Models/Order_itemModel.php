<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order_itemModel extends Model
{
    use HasFactory;
    protected $table = "Orders_Item";
    protected $primaryKey = "id";
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
}
