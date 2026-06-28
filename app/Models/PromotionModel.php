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

    protected $casts = [

        'start_date' => 'datetime',

        'end_date' => 'datetime',

    ];

    public function products()
    {
        return $this->belongsToMany(ProductsModel::class, 'promotion_products', 'promotion_id', 'product_id');
    }
    public function usages()
    {
        return $this->hasMany(
            PromotionUsagesModel::class,
            'promotion_id'
        );
    }
}
