<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = "otps";
    protected $primaryKey = "id";
    protected $fillable = [
        'email',
        'phone',
        'otp',
        'payload',
        'reset_token',
        'expires_at',
        'type'
    ];

    protected $dates = [
        'expires_at'
    ];
}
