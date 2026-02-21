<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductsModel;
class FavoriteModel extends Model
{
    use HasFactory;
    protected $table = "favorites";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
}
