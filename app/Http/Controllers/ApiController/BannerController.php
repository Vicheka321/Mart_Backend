<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\banners;

class BannerController extends Controller
{
    public function index()
    {
        $banners = banners::where('status', true) 
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json($banners);
    }
}
