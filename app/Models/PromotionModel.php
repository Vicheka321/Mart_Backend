<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionModel extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'name',
        'image_url',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(ProductsModel::class, 'promotion_products', 'promotion_id', 'product_id');
    }
}
