<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'province',
        'city',
        'district',
        'commune',
        'street',
        'address_detail',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
