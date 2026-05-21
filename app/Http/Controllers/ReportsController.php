<?php

namespace App\Http\Controllers;
use App\Models\OrderModel;
use App\Models\Order_itemModel;
use App\Models\PaymentModel;
use App\Models\Category;
use App\Models\ProductsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $now = now();

        // Core metrics
        $paidOrders = OrderModel::whereHas('payment', function ($q) {
            $q->where('payment_status', 'paid');
        });

        $totalOrders = (clone $paidOrders)->count();

        $totalRevenue = PaymentModel::where('payment_status', 'paid')
            ->sum('amount');

        $averageOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        // Profit = Revenue - Cost
        $profit = Order_itemModel::with('product')
            ->whereHas('order.payment', function ($q) {
                $q->where('payment_status', 'paid');
            })
            ->get()
            ->sum(function ($item) {
                $revenue = $item->price * $item->qty;
                $cost = ($item->product->cost_price ?? 0) * $item->qty;
                return $revenue - $cost;
            });

        // Conversion rate (demo metric)
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $conversionRate = $totalCustomers > 0
            ? ($totalOrders / $totalCustomers) * 100
            : 0;

        // Bounce rate (demo metric)
        $bounceRate = 28.4;

        // Monthly revenue for last 10 months
        $months = [];
        $monthlyRevenue = [];
        $monthlyProfit = [];

        for ($i = 9; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);

            $months[] = $date->format('M');

            $revenue = PaymentModel::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $profitMonth = Order_itemModel::with('product')
                ->whereHas('order.payment', function ($q) {
                    $q->where('payment_status', 'paid');
                })
                ->whereHas('order', function ($q) use ($date) {
                    $q->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                })
                ->get()
                ->sum(function ($item) {
                    $revenue = $item->price * $item->qty;
                    $cost = ($item->product->cost_price ?? 0) * $item->qty;
                    return $revenue - $cost;
                });

            $monthlyRevenue[] = (float) $revenue;
            $monthlyProfit[] = (float) $profitMonth;
        }

        // Sales by category
        $salesByCategory = Category::select('categories.name')
            ->join('products', 'products.categories_id', '=', 'categories.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('SUM(order_items.qty) as total_sold')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Top selling products
        $topProducts = ProductsModel::with('image')
            ->withSum('orderItems as sold_qty', 'qty')
            ->orderByDesc('sold_qty')
            ->take(5)
            ->get();

        // Demo traffic sources
        $trafficSources = [
            ['name' => 'Organic Search', 'percent' => 42],
            ['name' => 'Social Media', 'percent' => 28],
            ['name' => 'Direct', 'percent' => 18],
            ['name' => 'Email Marketing', 'percent' => 12],
        ];

        return view('Admin.reports', compact(
            'conversionRate',
            'averageOrderValue',
            'totalOrders',
            'bounceRate',
            'totalRevenue',
            'profit',
            'months',
            'monthlyRevenue',
            'monthlyProfit',
            'salesByCategory',
            'topProducts',
            'trafficSources'
        ));
    }
}
