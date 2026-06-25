<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\Category;
use App\Models\OrderModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\user;
use App\Models\PaymentModel;
use App\Models\Order_itemModel;

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
        /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */
        $range = request('range', '30days');

        [$startDate, $endDate] = match ($range) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            '7days' => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            '30days' => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [
                now()->copy()->subMonth()->startOfMonth(),
                now()->copy()->subMonth()->endOfMonth()
            ],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            default => [null, null],
        };

        if ($range === 'custom' && request('date_range')) {
            $dates = explode(' to ', request('date_range'));
            if (count($dates) === 2) {
                $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
                $endDate   = Carbon::parse(trim($dates[1]))->endOfDay();
            }
        }


        $applyDateFilter = function ($query, $column = 'created_at') use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween($column, [$startDate, $endDate]);
            }
        };





        $totalRevenue = PaymentModel::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalSales = OrderModel::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalCustomers = User::role('Customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $profit = Order_itemModel::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('
        SUM(
            (order_items.price * order_items.qty)
            -
            (COALESCE(products.cost_price, 0) * order_items.qty)
        ) as total_profit
    ')
            ->value('total_profit') ?? 0;



        $chartData = OrderModel::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        $maxSales = max($chartData->max('total') ?? 1, 1);

        $revenueChartData = PaymentModel::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $svgW = 300;
        $svgH = 110;
        $svgPad = 12;
        $svgCount = $revenueChartData->count();
        $svgMaxRevenue = max($revenueChartData->max('total') ?? 1, 1);
        $points = [];
        foreach ($revenueChartData as $i => $item) {
            $x = $svgCount > 1
                ? ($i / ($svgCount - 1)) * ($svgW - $svgPad * 2) + $svgPad
                : $svgPad;

            $y = $svgH - (
                (($item->total / $svgMaxRevenue) * ($svgH - $svgPad * 2))
                + $svgPad
            );

            $points[] = round($x, 2) . ',' . round($y, 2);
        }

        $svgPStr = implode(' ', $points);

        $svgAStr = $svgCount > 0
            ? $svgPad . ',' . $svgH . ' ' . $svgPStr . ' ' .
            (($svgCount > 1 ? $svgW - $svgPad : $svgPad)) . ',' . $svgH
            : '';


        $revenueByProducts = ProductsModel::with('image')
            ->select('products.*')
            ->selectRaw('
        SUM(order_items.price * order_items.qty) as revenue,
        SUM(order_items.qty) as sold_qty
    ')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $maxProductRevenue = max($revenueByProducts->max('revenue') ?? 1, 1);
        $salesByCategory = Category::select('categories.id', 'categories.name')
            ->selectRaw('SUM(order_items.price * order_items.qty) as revenue')
            ->join('products', 'categories.id', '=', 'products.categories_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $donutColors = ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'];
        $totalCat = $salesByCategory->sum('revenue');

        $currentDeg = 0;
        $segments = [];

        foreach ($salesByCategory as $i => $cat) {
            $pct = $totalCat > 0 ? ($cat->revenue / $totalCat) * 100 : 0;
            $deg = ($pct / 100) * 360;
            $color = $donutColors[$i % count($donutColors)];

            $segments[] = "{$color} {$currentDeg}deg " . ($currentDeg + $deg) . "deg";
            $currentDeg += $deg;
        }

        $donutGradient = !empty($segments)
            ? implode(', ', $segments)
            : '#e5e7eb 0deg 360deg';



        /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */
        return view('Admin.dashboard', compact(
            'range',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalSales',
            'totalCustomers',
            'profit',
            'chartData',
            'maxSales',
            'revenueChartData',
            'svgPStr',
            'svgAStr',
            'svgCount',
            'svgW',
            'svgH',
            'svgPad',
            'svgMaxRevenue',
            'revenueByProducts',
            'maxProductRevenue',
            'salesByCategory',
            'donutColors',
            'donutGradient',
            'totalCat',



        ));
    }

    public function staff()
    {
        $totalProducts = ProductsModel::count();
        // $todaySales = OrderModel::whereDate('created_at', Carbon::today())
        //                 ->sum('total_amount');
        $todaySales = '500'; // Placeholder value, replace with actual calculation

        return view('Staff.dashboard');
    }
}
