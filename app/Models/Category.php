<?php

namespace App\Models;


use App\Models\ProductsModel as ModelsProductsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use hasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'image'];


    public function products()
    {
        return $this->hasMany(ModelsProductsModel::class,'categories_id');
    }
}


