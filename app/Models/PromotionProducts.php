<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionProducts extends Model
{
    protected $table = 'promotion_products';
    protected $fillable = ['promotion_id', 'product_id'];
}
