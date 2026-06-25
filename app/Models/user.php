<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $table = 'users';

    protected $guard_name = 'web';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'facebook_id',
        'avatar',
        'password',
     
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function favorites()
    {
        return $this->belongsToMany(
            ProductsModel::class,
            'favorites',
            'user_id',
            'product_id'
        )->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(OrderModel::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(AddressModel::class, 'user_id');
    }

    public function promotionUsages()
    {
        return $this->hasMany(
            PromotionUsagesModel::class,
            'user_id'
        );
    }

    public function couponUsages()
    {
        return $this->hasMany(
            CouponUsageModel::class,
            'user_id'
        );
    }
}
