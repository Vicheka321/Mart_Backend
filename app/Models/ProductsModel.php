<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'categories_id',
        'brand_id',
        'product_code',
        'name',
        'description',
        'unit',
        'cost_price',
        'sale_price',
        'quantity',
        'status',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function brand()
    {
        return $this->belongsTo(BrandModel::class, 'brand_id');
    }

    public function image()
    {
        return $this->hasMany(ProductsImageModel::class, 'product_id');
    }


    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }
}
