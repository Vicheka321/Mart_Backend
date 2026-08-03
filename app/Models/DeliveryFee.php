<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryFee extends Model
{
    protected $fillable = [
        'min_km',
        'max_km',
        'fee',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'min_km' => 'float',
        'max_km' => 'float',
        'fee' => 'float',
    ];
}