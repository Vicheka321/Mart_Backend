<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\Category;
use App\Models\OrderModel;
use Carbon\Carbon;
use DB;
use App\Models\user;
use App\Models\PaymentModel;

class DashboardController extends Controller
{
    // ==========================
    // ADMIN DASHBOARD
    // ==========================
    // public function admin()
    // {
    //     $totalProducts = ProductsModel::count();
    //     $totalCategories = Category::count();

    //     // $todaySales = OrderModel::whereDate('created_at', Carbon::today())
    //     //                 ->sum('total_amount');
    //     $todaySales = '500'; // Placeholder value, replace with actual calculation

    //     $lowStock = ProductsModel::where('quantity','<=',5)->count();

    //     return view('Admin.dashboard', compact(
    //         'totalProducts',
    //         'totalCategories',
    //         'todaySales',
    //         'lowStock'
    //     ));
    // }
    public function admin()
    {
        // TOP CARDS
        $totalProducts = ProductsModel::count();

        $totalCategories = Category::count();

        $totalOrders = OrderModel::count();

        $totalCustomers = User::where('role', 'customer')->count();

        $totalRevenue = PaymentModel::where('payment_status', 'paid')
            ->sum('amount');

        $todaySales = OrderModel::whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $lowStock = ProductsModel::where('quantity', '<=', 5)->count();

        // RECENT ORDERS
        $recentOrders = OrderModel::with([
            'user:id,name',
            'payment:id,order_id,amount'
        ])
            ->latest()
            ->take(5)
            ->get();

        // BEST SELLING PRODUCTS
        $topProducts = ProductsModel::with('image')
            ->withSum('orderItems as sold_qty', 'qty')
            ->orderByDesc('sold_qty')
            ->take(5)
            ->get();
        $weeklySales = OrderModel::selectRaw("
        DATE(created_at) as date,
        SUM(total_amount) as total
    ")
            ->whereDate(
                'created_at',
                '>=',
                Carbon::now()->subDays(6)
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalSales = OrderModel::count();

        return view('Admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'todaySales',
            'lowStock',
            'recentOrders',
            'topProducts',
            'weeklySales',
            'totalSales'
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
