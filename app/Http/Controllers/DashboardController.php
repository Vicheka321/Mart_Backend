<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;
use App\Models\OrderModel;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    // ==========================
    // ADMIN DASHBOARD
    // ==========================
    public function admin()
    {
        $totalProducts = ProductsModel::count();
        $totalCategories = CategoriesModel::count();

        // $todaySales = OrderModel::whereDate('created_at', Carbon::today())
        //                 ->sum('total_amount');
        $todaySales = '500'; // Placeholder value, replace with actual calculation

        $lowStock = ProductsModel::where('quantity','<=',5)->count();

        return view('Admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'todaySales',
            'lowStock'
        ));
    }

    // ==========================
    // STAFF DASHBOARD
    // ==========================
    public function staff()
    {
        $totalProducts = ProductsModel::count();
        // $todaySales = OrderModel::whereDate('created_at', Carbon::today())
        //                 ->sum('total_amount');
        $todaySales = '500'; // Placeholder value, replace with actual calculation

        return view('Staff.dashboard');
    }
}
