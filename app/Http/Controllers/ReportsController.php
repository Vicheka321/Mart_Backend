<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order_itemModel as OrderItem;
use App\Models\OrderModel as Order;
use App\Models\PaymentModel as Payment;
use App\Models\ProductsModel as Product;
use App\Models\Coupon;
use App\Models\CouponModel;
use App\Models\khqr_payments;
use App\Models\KhqrPayment;
use App\Models\Order_itemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductsModel;
use App\Models\PromotionModel;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = OrderModel::where('status', 'completed')
            ->sum('total_amount');

        $todayRevenue = OrderModel::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthRevenue = OrderModel::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $totalOrders = OrderModel::count();

        $pendingOrders = OrderModel::where(
            'status',
            'pending'
        )->count();

        $processingOrders = OrderModel::where(
            'status',
            'processing'
        )->count();

        $completedOrders = OrderModel::where(
            'status',
            'completed'
        )->count();

        $cancelledOrders = OrderModel::where(
            'status',
            'cancelled'
        )->count();

        $totalCustomers = User::where(
            'role',
            'customer'
        )->count();

        $totalProducts = ProductsModel::count();

        $outOfStock = ProductsModel::where(
            'quantity',
            0
        )->count();

        $lowStock = ProductsModel::where(
            'quantity',
            '<=',
            5
        )->count();

        $topProducts = Order_itemModel::select(
            'product_id',
            DB::raw('SUM(qty) as total_sold'),
            DB::raw('SUM(qty * price) as revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $latestOrders = OrderModel::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $revenueChart = OrderModel::selectRaw(
            'DATE(created_at) as date,
         SUM(total_amount) as revenue'
        )
            ->where('status', 'completed')
            ->whereDate(
                'created_at',
                '>=',
                now()->subDays(30)
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view(
            'admin.reports.dashboard',
            compact(
                'totalRevenue',
                'todayRevenue',
                'monthRevenue',
                'totalOrders',
                'pendingOrders',
                'processingOrders',
                'completedOrders',
                'cancelledOrders',
                'totalCustomers',
                'totalProducts',
                'outOfStock',
                'lowStock',
                'topProducts',
                'latestOrders',
                'revenueChart'
            )
        );
    }


    public function sales(Request $request)
    {
        $query = OrderModel::query();

        if ($request->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")
                    ->orWhere(
                        'coupon_code',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        $sales = $query
            ->selectRaw("
            DATE(created_at) as sale_date,
            COUNT(*) as total_orders,
            SUM(total_amount) as revenue,
            SUM(coupon_discount + promotion_discount) as discount
        ")
            ->groupBy('sale_date')
            ->orderByDesc('sale_date')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.sales',
            compact('sales')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS REPORT
    |--------------------------------------------------------------------------
    */
    public function orders(Request $request)
    {
        $query = OrderModel::with('user');

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {

                        $u->where(
                            'full_name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        $orders = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.orders',
            compact('orders')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS REPORT
    |--------------------------------------------------------------------------
    */
    public function products()
    {
        $products = Order_itemModel::select(
            'product_id',
            DB::raw('SUM(qty) as total_sold'),
            DB::raw('SUM(qty * price) as revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->paginate(20);

        return view(
            'admin.reports.products',
            compact('products')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTORY REPORT
    |--------------------------------------------------------------------------
    */
    public function inventory()
    {
        $products = ProductsModel::with([
            'brand',
            'category'
        ])
            ->latest()
            ->paginate(20);

        $totalStockValue = ProductsModel::selectRaw(
            'SUM(quantity * cost_price) as total'
        )->value('total');

        $outOfStock = ProductsModel::where(
            'quantity',
            0
        )->count();

        $lowStock = ProductsModel::where(
            'quantity',
            '<=',
            5
        )->count();

        return view(
            'admin.reports.inventory',
            compact(
                'products',
                'totalStockValue',
                'outOfStock',
                'lowStock'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER REPORT
    |--------------------------------------------------------------------------
    */
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->paginate(20);

        return view(
            'admin.reports.customers',
            compact('customers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT REPORT
    |--------------------------------------------------------------------------
    */
    public function payments()
    {
        $payments = PaymentModel::latest()
            ->paginate(20);

        $paymentSummary = PaymentModel::select(
            'payment_method',
            DB::raw('COUNT(*) as total_transactions'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->groupBy('payment_method')
            ->get();

        return view(
            'admin.reports.payments',
            compact(
                'payments',
                'paymentSummary'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROMOTION REPORT
    |--------------------------------------------------------------------------
    */
    public function promotions()
    {
        $promotions = PromotionModel::withCount(
            'products'
        )
            ->latest()
            ->paginate(20);

        $totalDiscountGiven = OrderModel::sum(
            'promotion_discount'
        );

        return view(
            'admin.reports.promotions',
            compact(
                'promotions',
                'totalDiscountGiven'
            )
        );
    }
}
