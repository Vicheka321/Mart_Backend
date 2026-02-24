<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class user extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];


    public function favorites()
    {
        return $this->belongsToMany(ProductsModel::class, 'favorites', 'user_id', 'product_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'user_id');
    }

     public function addresses()
    {
        return $this->hasMany(AddressesModel::class, 'user_id');
    }
}
