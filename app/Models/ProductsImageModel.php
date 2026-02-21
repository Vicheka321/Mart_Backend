<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsImageModel extends Model
{
    use HasFactory;
    protected $table = 'product_images';
    protected $primaryKey = 'id';


    public function product()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }

}
