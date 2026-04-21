<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AddressModel extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'lat',
        'lng',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
