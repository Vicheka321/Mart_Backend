<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'lat',
        'lng',
        'is_main',
        'status',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // Converts DB boolean <-> frontend 'active'/'inactive' string automatically
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? 'active' : 'inactive',
            set: fn($value) => in_array($value, ['active', 1, '1', true], true),
        );
    }
}
