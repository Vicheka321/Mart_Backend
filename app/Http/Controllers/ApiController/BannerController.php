<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\banners;
class BannerController extends Controller
{
    public function index()
    {
        // $banners = banners::where('status', true)
        //     ->where(function ($query) {
        //              $query->whereNull('start_date')
        //             ->orWhere('start_date', '<=', now());
        //     })
        //     ->where(function ($query) {
        //         $query->whereNull('end_date')
        //             ->orWhere('end_date', '>=', now());
        //     })
        //     ->orderBy('sort_order', 'asc')
        //     ->get();
        $banners = banners::orderBy('sort_order')->get();

        return response()->json($banners);
    }
}
