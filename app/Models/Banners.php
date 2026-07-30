<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    protected $table = "banners";
    protected $fillable = [
        'title',
        'image_url',
        'sort_order',
        'status',
        'start_date',
        'end_date'
    ];
}
