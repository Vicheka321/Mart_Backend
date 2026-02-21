<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandModel extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'image'];



    // public function products()
    // {
    //     return $this->hasMany(ProductsModel::class,'brand_id');
    // }
}
