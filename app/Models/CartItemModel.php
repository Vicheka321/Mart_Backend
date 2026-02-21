<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CartItemModel extends Model
{
    use HasFactory;
    protected $table = "cart_items";
    protected $primaryKey = "id";
    protected $fillable = [
        'cart_id',
        'product_id',
        'qty',
        'price'
    ];

    public function cart()
    {
        return $this->belongsTo(CartModel::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
    



}
