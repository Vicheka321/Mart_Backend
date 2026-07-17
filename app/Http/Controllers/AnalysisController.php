<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;
use App\Models\CouponModel;
use App\Models\khqr_payments;
use App\Models\Order_itemModel as OrderItem;
use App\Models\OrderModel as Order;
use App\Models\PaymentModel as Payment;
use App\Models\ProductsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate, $rangeLabel, $rangeKey] = $this->resolveDateRange($request);

        /*
        |--------------------------------------------------------------------------
        | Base Queries
        |--------------------------------------------------------------------------
        */
        $paidOrdersQuery = Order::query()
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->whereBetween('created_at', [$startDate, $endDate]);

        $paidPaymentsQuery = Payment::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate]);

        /*
        |--------------------------------------------------------------------------
        | Core KPIs
        |--------------------------------------------------------------------------
        */
        $totalOrders = (clone $paidOrdersQuery)->count();

        $totalRevenue = (clone $paidPaymentsQuery)->sum('amount');

        $averageOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        $profit = OrderItem::with('product')
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->whereHas('payment', fn($p) => $p->where('payment_status', 'paid'));
            })
            ->get()
            ->sum(function ($item) {
                $cost = $item->product->cost_price ?? 0;
                return ($item->price - $cost) * $item->qty;
            });

        /*
        |--------------------------------------------------------------------------
        | Previous Period KPIs for Growth
        |--------------------------------------------------------------------------
        */
        [$previousStart, $previousEnd] = $this->getPreviousPeriod($startDate, $endDate);

        $previousRevenue = Payment::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('amount');

        $previousOrders = Order::whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        $revenueGrowth = $previousRevenue > 0
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
            : 0;

        $orderGrowth = $previousOrders > 0
            ? (($totalOrders - $previousOrders) / $previousOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Customer Analytics
        |--------------------------------------------------------------------------
        */
        $newCustomers = User::role('Customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $returningCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $customersWithOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        $repeatPurchaseRate = $customersWithOrders > 0
            ? ($returningCustomers / $customersWithOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Order Rates
        |--------------------------------------------------------------------------
        */
        $totalAllOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $cancelledOrders = Order::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $completedOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $cancellationRate = $totalAllOrders > 0
            ? ($cancelledOrders / $totalAllOrders) * 100
            : 0;

        $completionRate = $totalAllOrders > 0
            ? ($completedOrders / $totalAllOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Chart Data (Revenue + Profit)
        |--------------------------------------------------------------------------
        */
        [$chartLabels, $monthlyRevenue, $monthlyProfit] = $this->buildRevenueProfitChart(
            $startDate,
            $endDate
        );

        /*
        |--------------------------------------------------------------------------
        | Sales by Category
        |--------------------------------------------------------------------------
        */
        $salesByCategory = ModelsCategory::select('categories.id', 'categories.name')
            ->join('products', 'products.categories_id', '=', 'categories.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('SUM(order_items.qty) as total_sold')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Selling Products
        |--------------------------------------------------------------------------
        */
        $topProducts = ProductsModel::select('products.*')
            ->with('firstImage')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id')
            ->selectRaw('SUM(order_items.qty) as sold_qty')
            ->orderByDesc('sold_qty')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Payment Methods Breakdown
        |--------------------------------------------------------------------------
        */
        $paymentMethods = Payment::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method as method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Revenue by Payment Method
        |--------------------------------------------------------------------------
        */
        $revenueByPaymentMethod = Payment::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Order Status Breakdown
        |--------------------------------------------------------------------------
        */
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Coupon Performance
        |--------------------------------------------------------------------------
        | If your coupons table has used_count, keep it.
        | If not, you can replace this logic later with order-based coupon usage.
        */
        $coupons = CouponModel::orderByDesc('used_count')->take(10)->get();

        $ordersWithCoupon = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('coupon_code')
            ->count();

        $totalCouponDiscount = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('coupon_discount');

        $totalPromotionDiscount = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('promotion_discount');

        $totalDiscountGiven = $totalCouponDiscount + $totalPromotionDiscount;

        $couponUsageRate = $totalAllOrders > 0
            ? ($ordersWithCoupon / $totalAllOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | KHQR Stats
        |--------------------------------------------------------------------------
        */
        $khqrStats = khqr_payments::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $khqrTotal = $khqrStats->sum('count');
        $khqrSuccess = $khqrStats->get('SUCCESS')?->count ?? 0;
        $khqrPending = $khqrStats->get('PENDING')?->count ?? 0;
        $khqrExpired = $khqrStats->get('EXPIRED')?->count ?? 0;
        $khqrFailed = $khqrStats->get('FAILED')?->count ?? 0;

        $khqrSuccessRate = $khqrTotal > 0
            ? ($khqrSuccess / $khqrTotal) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Extra Useful Widgets
        |--------------------------------------------------------------------------
        */
        $topCustomers = User::role('Customer')
            ->select('users.id', 'users.full_name', 'users.email')
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('users.id', 'users.full_name', 'users.email')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        $lowStockProducts = ProductsModel::where('quantity', '<=', 10)
            ->orderBy('quantity')
            ->take(10)
            ->get();

        $outOfStockCount = ProductsModel::where('quantity', '<=', 0)->count();

        return view('Admin.analysis', compact(
            'startDate',
            'endDate',
            'rangeLabel',
            'rangeKey',

            'totalRevenue',
            'profit',
            'totalOrders',
            'averageOrderValue',

            'revenueGrowth',
            'orderGrowth',
            'newCustomers',
            'repeatPurchaseRate',

            'returningCustomers',
            'cancellationRate',
            'completionRate',

            'chartLabels',
            'monthlyRevenue',
            'monthlyProfit',

            'salesByCategory',
            'topProducts',
            'paymentMethods',
            'revenueByPaymentMethod',
            'ordersByStatus',

            'coupons',
            'ordersWithCoupon',
            'totalCouponDiscount',
            'totalPromotionDiscount',
            'totalDiscountGiven',
            'couponUsageRate',

            'khqrTotal',
            'khqrSuccess',
            'khqrPending',
            'khqrExpired',
            'khqrFailed',
            'khqrSuccessRate',

            'topCustomers',
            'lowStockProducts',
            'outOfStockCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Date Range
    |--------------------------------------------------------------------------
    */
    protected function resolveDateRange(Request $request): array
    {
        $range = $request->get('range', '30d');

        $startDate = null;
        $endDate = now()->endOfDay();
        $label = 'Last 30 Days';

        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $label = 'Today';
                break;

            case '7d':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                $label = 'Last 7 Days';
                break;

            case '30d':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                $label = 'Last 30 Days';
                break;

            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $label = 'This Month';
                break;

            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                $label = 'Last Month';
                break;

            case '3m':
                $startDate = now()->subMonths(2)->startOfMonth();
                $endDate = now()->endOfMonth();
                $label = 'Last 3 Months';
                break;

            case '6m':
                $startDate = now()->subMonths(5)->startOfMonth();
                $endDate = now()->endOfMonth();
                $label = 'Last 6 Months';
                break;

            case '12m':
                $startDate = now()->subMonths(11)->startOfMonth();
                $endDate = now()->endOfMonth();
                $label = 'Last 12 Months';
                break;

            case 'custom':

                $request->validate([
                    'start_date' => 'required|date',
                    'end_date'   => 'required|date|after_or_equal:start_date',
                ]);

                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate   = Carbon::parse($request->end_date)->endOfDay();
                $label = 'Custom Range';

                break;

            default:
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                $label = 'Last 30 Days';
                $range = '30d';
                break;
        }

        return [$startDate, $endDate, $label, $range];
    }

    /*
    |--------------------------------------------------------------------------
    | Previous Period Helper
    |--------------------------------------------------------------------------
    */
    protected function getPreviousPeriod(Carbon $startDate, Carbon $endDate): array
    {
        $days = $startDate->diffInDays($endDate) + 1;

        $previousEnd = $startDate->copy()->subDay()->endOfDay();
        $previousStart = $previousEnd->copy()->subDays($days - 1)->startOfDay();

        return [$previousStart, $previousEnd];
    }

    /*
    |--------------------------------------------------------------------------
    | Build Revenue / Profit Chart
    |--------------------------------------------------------------------------
    | <= 45 days => daily
    | > 45 days  => monthly
    |--------------------------------------------------------------------------
    */
    protected function buildRevenueProfitChart(Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $revenues = [];
        $profits = [];

        $daysDiff = $startDate->diffInDays($endDate);

        if ($daysDiff <= 45) {
            $cursor = $startDate->copy();

            while ($cursor <= $endDate) {
                $labels[] = $cursor->format('d M');

                $dayRevenue = Payment::where('payment_status', 'paid')
                    ->whereDate('created_at', $cursor->toDateString())
                    ->sum('amount');

                $dayProfit = OrderItem::with('product')
                    ->whereHas('order', function ($q) use ($cursor) {
                        $q->whereDate('created_at', $cursor->toDateString())
                            ->whereHas('payment', fn($p) => $p->where('payment_status', 'paid'));
                    })
                    ->get()
                    ->sum(function ($item) {
                        $cost = $item->product->cost_price ?? 0;
                        return ($item->price - $cost) * $item->qty;
                    });

                $revenues[] = (float) $dayRevenue;
                $profits[] = (float) $dayProfit;

                $cursor->addDay();
            }
        } else {
            $cursor = $startDate->copy()->startOfMonth();

            while ($cursor <= $endDate) {
                $labels[] = $cursor->format('M Y');

                $monthRevenue = Payment::where('payment_status', 'paid')
                    ->whereYear('created_at', $cursor->year)
                    ->whereMonth('created_at', $cursor->month)
                    ->sum('amount');

                $monthProfit = OrderItem::with('product')
                    ->whereHas('order', function ($q) use ($cursor) {
                        $q->whereYear('created_at', $cursor->year)
                            ->whereMonth('created_at', $cursor->month)
                            ->whereHas('payment', fn($p) => $p->where('payment_status', 'paid'));
                    })
                    ->get()
                    ->sum(function ($item) {
                        $cost = $item->product->cost_price ?? 0;
                        return ($item->price - $cost) * $item->qty;
                    });

                $revenues[] = (float) $monthRevenue;
                $profits[] = (float) $monthProfit;

                $cursor->addMonth();
            }
        }

        return [$labels, $revenues, $profits];
    }
}
