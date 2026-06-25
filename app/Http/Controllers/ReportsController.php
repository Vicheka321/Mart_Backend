<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\User;
use App\Models\OrderModel;
use App\Models\Order_itemModel;
use App\Models\PaymentModel;
use App\Models\ProductsModel;
use App\Models\Category;
use App\Models\BrandModel;
use App\Models\CouponModel;
use App\Models\PromotionsModel;

class ReportsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function resolveDateRange(Request $request): array
    {
        $range = $request->input('range', '30days');

        [$startDate, $endDate] = match ($range) {
            'today'      => [now()->startOfDay(), now()->endOfDay()],
            '7days'      => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            '30days'     => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->copy()->subMonth()->startOfMonth(), now()->copy()->subMonth()->endOfMonth()],
            'this_year'  => [now()->startOfYear(), now()->endOfYear()],
            default      => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
        };

        if ($range === 'custom' && $request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
                $endDate   = Carbon::parse(trim($dates[1]))->endOfDay();
            }
        }

        return [$range, $startDate, $endDate];
    }

    private function applyCommonOrderFilters($query, Request $request, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        if ($request->filled('status')) {
            $query->where('orders.status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('orders.payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('orders.id', 'like', "%{$keyword}%")
                    ->orWhere('orders.delivery_address', 'like', "%{$keyword}%")
                    ->orWhere('orders.coupon_code', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
            });
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ADDRESS PARSER FOR CAMBODIA DELIVERY ADDRESS (delivery_address only)
    |--------------------------------------------------------------------------
    | Goal:
    | - Province = Phnom Penh / Kandal / Svay Rieng / ...
    | - District = Doun Penh / Posenchey / Russey Keo / ...
    | - Sangkat  = Kakab / Tuek Thla / ...
    | - Street   = Street 2004 / Preah Trasak Paem St. (63)
    |
    | Supports:
    | 1) House 12, Street 2004, Sangkat Kakab, Khan Posenchey, Phnom Penh
    | 2) 41 Preah Trasak Paem St. (63), Khan Doun Penh, Phnom Penh
    | 3) HWM8+7J5, Khan Doun Penh, Phnom Penh, Cambodia
    |--------------------------------------------------------------------------
    */

    private function normalizeAddress(?string $address): string
    {
        $address = trim((string) $address);

        // remove duplicate spaces
        $address = preg_replace('/\s+/', ' ', $address);

        // unify commas
        $address = str_replace([' ,', ', '], [',', ', '], $address);

        return trim($address);
    }

    private function cleanAddressToken(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $value = trim($value, ", \t\n\r\0\x0B");
        return $value !== '' ? $value : null;
    }

    private function removeLeadingLabel(string $value, array $labels): string
    {
        foreach ($labels as $label) {
            $pattern = '/^' . preg_quote($label, '/') . '\s*/iu';
            $value = preg_replace($pattern, '', $value);
        }
        return trim($value);
    }

    private function isCountryToken(?string $value): bool
    {
        $v = mb_strtolower(trim((string) $value));
        return in_array($v, [
            'cambodia',
            'kingdom of cambodia',
            'ប្រទេសកម្ពុជា',
        ]);
    }

    private function isLikelyStreet(?string $value): bool
    {
        $value = trim((string) $value);
        if ($value === '') return false;

        $v = mb_strtolower($value);

        return str_contains($v, 'street')
            || str_contains($v, 'st.')
            || str_contains($v, 'st ')
            || str_contains($v, 'road')
            || str_contains($v, 'rd')
            || str_contains($v, 'boulevard')
            || str_contains($v, 'blvd')
            || str_contains($v, 'ave')
            || str_contains($v, 'avenue')
            || preg_match('/\(\d+\)/', $value)
            || preg_match('/\b\d{1,5}\b/', $value);
    }

    private function isPlusCodeOnly(?string $value): bool
    {
        $value = trim((string) $value);
        if ($value === '') return false;

        // e.g. HWM8+7J5 / HW9F+6PM / HWV7+WFX
        return (bool) preg_match('/^[A-Z0-9]{3,8}\+[A-Z0-9]{2,8}$/i', $value);
    }

    private function parseAddress(?string $address): array
    {
        $address = $this->normalizeAddress($address);

        if ($address === '') {
            return [
                'street'   => null,
                'sangkat'  => null,
                'district' => null,
                'province' => null,
                'full'     => null,
            ];
        }

        $parts = array_values(array_filter(array_map(function ($part) {
            return $this->cleanAddressToken($part);
        }, explode(',', $address))));

        // remove Cambodia token
        $parts = array_values(array_filter($parts, function ($part) {
            return !$this->isCountryToken($part);
        }));

        $province = null;
        $district = null;
        $sangkat  = null;
        $street   = null;

        /*
        |--------------------------------------------------------------------------
        | Province = last token after removing Cambodia
        |--------------------------------------------------------------------------
        */
        if (!empty($parts)) {
            $province = end($parts);
            $province = $this->cleanAddressToken($province);

            if ($province) {
                $lower = mb_strtolower($province);

                if (
                    str_contains($lower, 'khan ') ||
                    str_contains($lower, 'district ') ||
                    str_contains($lower, 'sangkat ') ||
                    str_contains($lower, 'commune ') ||
                    $this->isLikelyStreet($province) ||
                    $this->isPlusCodeOnly($province)
                ) {
                    $province = null;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Find district / khan
        |--------------------------------------------------------------------------
        */
        foreach ($parts as $part) {
            $lower = mb_strtolower($part);

            if (str_contains($lower, 'khan ')) {
                $district = $this->removeLeadingLabel($part, ['Khan', 'khan']);
                break;
            }

            if (str_contains($lower, 'district ')) {
                $district = $this->removeLeadingLabel($part, ['District', 'district']);
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Find sangkat / commune
        |--------------------------------------------------------------------------
        */
        foreach ($parts as $part) {
            $lower = mb_strtolower($part);

            if (str_contains($lower, 'sangkat ')) {
                $sangkat = $this->removeLeadingLabel($part, ['Sangkat', 'sangkat']);
                break;
            }

            if (str_contains($lower, 'commune ')) {
                $sangkat = $this->removeLeadingLabel($part, ['Commune', 'commune']);
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Find street
        |--------------------------------------------------------------------------
        */
        foreach ($parts as $part) {
            $lower = mb_strtolower($part);

            if (
                str_contains($lower, 'khan ') ||
                str_contains($lower, 'district ') ||
                str_contains($lower, 'sangkat ') ||
                str_contains($lower, 'commune ')
            ) {
                continue;
            }

            if ($province && trim($part) === trim($province)) {
                continue;
            }

            if ($this->isPlusCodeOnly($part)) {
                continue;
            }

            if ($this->isLikelyStreet($part)) {
                $street = $part;
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback: maybe second token is street
        |--------------------------------------------------------------------------
        */
        if (!$street && count($parts) >= 2) {
            $candidate = $parts[1] ?? null;
            if (
                $candidate &&
                !$this->isCountryToken($candidate) &&
                !str_contains(mb_strtolower($candidate), 'khan ') &&
                !str_contains(mb_strtolower($candidate), 'district ') &&
                !str_contains(mb_strtolower($candidate), 'sangkat ') &&
                !str_contains(mb_strtolower($candidate), 'commune ')
            ) {
                if (!$this->isPlusCodeOnly($candidate)) {
                    $street = $candidate;
                }
            }
        }

        $province = $this->cleanAddressToken($province);
        $district = $this->cleanAddressToken($district);
        $sangkat  = $this->cleanAddressToken($sangkat);
        $street   = $this->cleanAddressToken($street);

        if ($this->isCountryToken($province)) {
            $province = null;
        }

        return [
            'street'   => $street,
            'sangkat'  => $sangkat,
            'district' => $district,
            'province' => $province,
            'full'     => $address,
        ];
    }

    private function getAddressRowsFromOrders($orders): array
    {
        return collect($orders)->map(function ($order) {
            $parsed = $this->parseAddress($order->delivery_address);

            return [
                'order_id'   => $order->id ?? null,
                'street'     => $parsed['street'],
                'sangkat'    => $parsed['sangkat'],
                'district'   => $parsed['district'],
                'province'   => $parsed['province'],
                'address'    => $order->delivery_address ?? null,
                'amount'     => (float) ($order->total_amount ?? 0),
                'status'     => $order->status ?? null,
                'created_at' => $order->created_at ?? null,
            ];
        })->toArray();
    }

    private function filterAddressRows(array $rows, Request $request): array
    {
        return array_values(array_filter($rows, function ($row) use ($request) {
            if ($request->filled('province') && ($row['province'] ?? null) !== $request->province) {
                return false;
            }

            if ($request->filled('district') && ($row['district'] ?? null) !== $request->district) {
                return false;
            }

            if ($request->filled('sangkat') && ($row['sangkat'] ?? null) !== $request->sangkat) {
                return false;
            }

            if ($request->filled('street') && ($row['street'] ?? null) !== $request->street) {
                return false;
            }

            return true;
        }));
    }

    private function buildAddressOptions(array $rows, Request $request): array
    {
        // Province = all parsed province
        $provinceOptions = collect($rows)
            ->pluck('province')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // District depends on selected province
        $districtRows = $rows;
        if ($request->filled('province')) {
            $districtRows = array_values(array_filter($districtRows, fn($r) => ($r['province'] ?? null) === $request->province));
        }

        $districtOptions = collect($districtRows)
            ->pluck('district')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Sangkat depends on province + district
        $sangkatRows = $rows;
        if ($request->filled('province')) {
            $sangkatRows = array_values(array_filter($sangkatRows, fn($r) => ($r['province'] ?? null) === $request->province));
        }
        if ($request->filled('district')) {
            $sangkatRows = array_values(array_filter($sangkatRows, fn($r) => ($r['district'] ?? null) === $request->district));
        }

        $sangkatOptions = collect($sangkatRows)
            ->pluck('sangkat')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Street depends on province + district + sangkat
        $streetRows = $rows;
        if ($request->filled('province')) {
            $streetRows = array_values(array_filter($streetRows, fn($r) => ($r['province'] ?? null) === $request->province));
        }
        if ($request->filled('district')) {
            $streetRows = array_values(array_filter($streetRows, fn($r) => ($r['district'] ?? null) === $request->district));
        }
        if ($request->filled('sangkat')) {
            $streetRows = array_values(array_filter($streetRows, fn($r) => ($r['sangkat'] ?? null) === $request->sangkat));
        }

        $streetOptions = collect($streetRows)
            ->pluck('street')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return [
            'provinceOptions' => $provinceOptions,
            'districtOptions' => $districtOptions,
            'sangkatOptions'  => $sangkatOptions,
            'streetOptions'   => $streetOptions,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Build matched order IDs from selected address filters
    |--------------------------------------------------------------------------
    */
    private function getMatchedOrderIdsByAddressFilter(Request $request, $startDate, $endDate): array
    {
        $orders = OrderModel::query()
            ->select('id', 'delivery_address', 'total_amount', 'status', 'created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $rows = $this->getAddressRowsFromOrders($orders);
        $rows = $this->filterAddressRows($rows, $request);

        return collect($rows)->pluck('order_id')->filter()->unique()->values()->all();
    }

    private function salesBaseQuery(Request $request, $startDate, $endDate)
    {
        $query = OrderModel::query()
            ->with(['user', 'payment'])
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->select(
                'orders.id',
                'orders.user_id',
                'orders.delivery_address',
                'orders.total_amount',
                'orders.status',
                'orders.payment_method',
                'orders.coupon_code',
                'orders.coupon_discount',
                'orders.promotion_discount',
                'orders.created_at',
                DB::raw("COALESCE(payments.payment_status, 'unpaid') as payment_status"),
                DB::raw("COALESCE(payments.amount, 0) as paid_amount")
            );

        $this->applyCommonOrderFilters($query, $request, $startDate, $endDate);

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | REPORTS DASHBOARD
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $totalRevenue = PaymentModel::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalOrders = OrderModel::whereBetween('created_at', [$startDate, $endDate])->count();

        $completedOrders = OrderModel::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $salesByDay = OrderModel::selectRaw('DATE(created_at) as report_date, COUNT(*) as total_orders, SUM(total_amount) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        $topProducts = Order_itemModel::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.qty) as sold_qty'),
                DB::raw('SUM(order_items.price * order_items.qty) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        return view('admin.reports.dashboard', compact(
            'range',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'newCustomers',
            'salesByDay',
            'topProducts'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SALES REPORT
    |--------------------------------------------------------------------------
    */
    public function sales(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        // raw orders for building address dropdowns
        $rawOrders = OrderModel::query()
            ->select('id', 'delivery_address', 'total_amount', 'status', 'created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $addressRows = $this->getAddressRowsFromOrders($rawOrders);

        [
            'provinceOptions' => $provinceOptions,
            'districtOptions' => $districtOptions,
            'sangkatOptions'  => $sangkatOptions,
            'streetOptions'   => $streetOptions,
        ] = $this->buildAddressOptions($addressRows, $request);

        // get matched order IDs from parsed delivery_address
        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate);

        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        $ordersQuery = $this->salesBaseQuery($request, $startDate, $endDate);

        if ($hasAddressFilter) {
            if (empty($matchedOrderIds)) {
                $ordersQuery->whereRaw('1 = 0');
            } else {
                $ordersQuery->whereIn('orders.id', $matchedOrderIds);
            }
        }

        // grouped daily rows
        $salesRows = $this->salesDailyGroupedQuery($request, $startDate, $endDate, $matchedOrderIds, $hasAddressFilter)
            ->orderByDesc('sale_date')
            ->paginate(15)
            ->withQueryString();

        // KPI summary
        $summaryRows = (clone $ordersQuery)->get();

        $totalOrders = $summaryRows->count();
        $grossSales = (float) $summaryRows->sum('total_amount');
        $paidRevenue = (float) $summaryRows->where('payment_status', 'paid')->sum('paid_amount');
        $totalDiscount = (float) $summaryRows->sum(function ($row) {
            return (float) ($row->coupon_discount ?? 0) + (float) ($row->promotion_discount ?? 0);
        });
        $averageOrderValue = $totalOrders > 0 ? round($grossSales / $totalOrders, 2) : 0;

        // address analytics based on filtered rows
        $filteredAddressRows = $this->filterAddressRows($addressRows, $request);

        $topProvinces = collect($filteredAddressRows)
            ->groupBy('province')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        $topDistricts = collect($filteredAddressRows)
            ->groupBy('district')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        $topSangkats = collect($filteredAddressRows)
            ->groupBy('sangkat')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        $topStreets = collect($filteredAddressRows)
            ->groupBy('street')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        return view('admin.reports.sales', compact(
            'range',
            'startDate',
            'endDate',
            'salesRows',
            'totalOrders',
            'grossSales',
            'paidRevenue',
            'totalDiscount',
            'averageOrderValue',
            'provinceOptions',
            'districtOptions',
            'sangkatOptions',
            'streetOptions',
            'topProvinces',
            'topDistricts',
            'topSangkats',
            'topStreets'
        ));
    }

    private function salesDailyGroupedQuery(Request $request, $startDate, $endDate, array $matchedOrderIds = [], bool $hasAddressFilter = false)
    {
        $query = OrderModel::query()
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id');

        if ($startDate && $endDate) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        if ($request->filled('status')) {
            $query->where('orders.status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('orders.payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->where('payments.payment_status', $request->payment_status);
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('orders.id', 'like', "%{$keyword}%")
                    ->orWhere('orders.delivery_address', 'like', "%{$keyword}%")
                    ->orWhere('orders.coupon_code', 'like', "%{$keyword}%")
                    ->orWhereExists(function ($sub) use ($keyword) {
                        $sub->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.id', 'orders.user_id')
                            ->where(function ($uq) use ($keyword) {
                                $uq->where('users.full_name', 'like', "%{$keyword}%")
                                    ->orWhere('users.email', 'like', "%{$keyword}%")
                                    ->orWhere('users.phone', 'like', "%{$keyword}%");
                            });
                    });
            });
        }

        if ($hasAddressFilter) {
            if (empty($matchedOrderIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('orders.id', $matchedOrderIds);
            }
        }

        return $query->selectRaw("
            DATE(orders.created_at) as sale_date,
            COUNT(DISTINCT orders.id) as total_orders,
            SUM(orders.total_amount) as gross_sales,
            SUM(COALESCE(orders.coupon_discount,0) + COALESCE(orders.promotion_discount,0)) as total_discount,
            SUM(
                CASE
                    WHEN payments.payment_status = 'paid'
                    THEN COALESCE(payments.amount, orders.total_amount)
                    ELSE 0
                END
            ) as paid_revenue
        ")
            ->groupBy(DB::raw('DATE(orders.created_at)'));
    }

    /*
    |--------------------------------------------------------------------------
    | SALES EXPORT CSV
    |--------------------------------------------------------------------------
    */
    public function exportSalesCsv(Request $request): StreamedResponse
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate);
        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        $rows = $this->salesDailyGroupedQuery($request, $startDate, $endDate, $matchedOrderIds, $hasAddressFilter)
            ->orderByDesc('sale_date')
            ->get();

        $filename = 'sales_report_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Orders', 'Gross Sales', 'Discount', 'Paid Revenue']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->sale_date,
                    $row->total_orders,
                    number_format($row->gross_sales, 2, '.', ''),
                    number_format($row->total_discount, 2, '.', ''),
                    number_format($row->paid_revenue, 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | SALES EXPORT PDF
    |--------------------------------------------------------------------------
    */
    // public function exportSalesPdf(Request $request)
    // {
    //     [$range, $startDate, $endDate] = $this->resolveDateRange($request);

    //     $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate);
    //     $hasAddressFilter = $request->filled('province')
    //         || $request->filled('district')
    //         || $request->filled('sangkat')
    //         || $request->filled('street');

    //     $rows = $this->salesDailyGroupedQuery($request, $startDate, $endDate, $matchedOrderIds, $hasAddressFilter)
    //         ->orderByDesc('sale_date')
    //         ->get();

    //     $pdf = Pdf::loadView('admin.PDF.sales-pdf', [
    //         'rows'      => $rows,
    //         'startDate' => $startDate,
    //         'endDate'   => $endDate,
    //     ])->setPaper('a4', 'portrait');

    //     return $pdf->download('sales_report_' . now()->format('Ymd_His') . '.pdf');
    // }

    public function exportSalesPdf(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate);

        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        $rows = $this->salesDailyGroupedQuery(
            $request,
            $startDate,
            $endDate,
            $matchedOrderIds,
            $hasAddressFilter
        )
            ->orderByDesc('sale_date')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Build summary from grouped rows
    |--------------------------------------------------------------------------
    */
        $summary = [
            'total_orders' => (int) $rows->sum('total_orders'),
            'gross_sales' => (float) $rows->sum('gross_sales'),
            'paid_revenue' => (float) $rows->sum('paid_revenue'),
            'total_discount' => (float) $rows->sum('total_discount'),
            'average_order_value' => $rows->sum('total_orders') > 0
                ? round($rows->sum('gross_sales') / $rows->sum('total_orders'), 2)
                : 0,
        ];

        /*
    |--------------------------------------------------------------------------
    | Filters for PDF header
    |--------------------------------------------------------------------------
    */
        $filters = [
            'range'          => $request->input('range'),
            'status'         => $request->input('status'),
            'payment_method' => $request->input('payment_method'),
            'payment_status' => $request->input('payment_status'),
            'province'       => $request->input('province'),
            'district'       => $request->input('district'),
            'sangkat'        => $request->input('sangkat'),
            'street'         => $request->input('street'),
            'keyword'        => $request->input('keyword'),
        ];

        $pdf = Pdf::loadView('admin.PDF.sales-pdf', [
            'rows'      => $rows,
            'summary'   => $summary,
            'filters'   => $filters,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('sales_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | SALES DETAILS (AJAX modal content)
    |--------------------------------------------------------------------------
    */
    public function salesDetails(Request $request, $date)
    {
        $targetDate = Carbon::parse($date)->toDateString();

        $query = OrderModel::with(['user', 'payment', 'orderItems.product'])
            ->whereDate('created_at', $targetDate);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', fn($q) => $q->where('payment_status', $request->payment_status));
        }

        // address filter via parsed delivery_address
        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter(
            $request,
            Carbon::parse($targetDate)->startOfDay(),
            Carbon::parse($targetDate)->endOfDay()
        );

        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        if ($hasAddressFilter) {
            if (empty($matchedOrderIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $matchedOrderIds);
            }
        }

        $orders = $query->latest()->get();

        $summary = [
            'date'         => $targetDate,
            'total_orders' => $orders->count(),
            'gross_sales'  => $orders->sum('total_amount'),
            'discount'     => $orders->sum(fn($o) => ($o->coupon_discount ?? 0) + ($o->promotion_discount ?? 0)),
            'paid_revenue' => $orders->sum(fn($o) => optional($o->payment)->payment_status === 'paid'
                ? (optional($o->payment)->amount ?? $o->total_amount)
                : 0),
        ];

        return view('admin.reports.partials.sales-details-modal-body', compact('orders', 'summary', 'targetDate'));
    }

    /*
    |--------------------------------------------------------------------------
    | SALES DETAILS EXPORT CSV
    |--------------------------------------------------------------------------
    */
    public function exportSalesDetailsCsv(Request $request, $date): StreamedResponse
    {
        $targetDate = Carbon::parse($date)->toDateString();

        $query = OrderModel::with(['user', 'payment'])
            ->whereDate('created_at', $targetDate);

        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter(
            $request,
            Carbon::parse($targetDate)->startOfDay(),
            Carbon::parse($targetDate)->endOfDay()
        );

        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        if ($hasAddressFilter) {
            if (empty($matchedOrderIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $matchedOrderIds);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', fn($q) => $q->where('payment_status', $request->payment_status));
        }

        $orders = $query->get();
        $filename = 'sales_details_' . $targetDate . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order ID',
                'Customer',
                'Phone',
                'Status',
                'Payment Method',
                'Payment Status',
                'Total',
                'Address',
                'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    optional($order->user)->full_name,
                    optional($order->user)->phone,
                    $order->status,
                    $order->payment_method,
                    optional($order->payment)->payment_status,
                    $order->total_amount,
                    $order->delivery_address,
                    optional($order->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename);
    }

    /*
    |--------------------------------------------------------------------------
    | SALES DETAILS EXPORT PDF
    |--------------------------------------------------------------------------
    */
    public function exportSalesDetailsPdf(Request $request, $date)
    {
        $targetDate = Carbon::parse($date)->toDateString();

        $query = OrderModel::with(['user', 'payment'])
            ->whereDate('created_at', $targetDate);

        $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter(
            $request,
            Carbon::parse($targetDate)->startOfDay(),
            Carbon::parse($targetDate)->endOfDay()
        );

        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        if ($hasAddressFilter) {
            if (empty($matchedOrderIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $matchedOrderIds);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', fn($q) => $q->where('payment_status', $request->payment_status));
        }

        $orders = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf.sales-details', [
            'orders'     => $orders,
            'targetDate' => $targetDate,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('sales_details_' . $targetDate . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS REPORT
    |--------------------------------------------------------------------------
    */
    public function orders(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $orders = OrderModel::with(['user', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_method'), fn($q) => $q->where('payment_method', $request->payment_method))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalOrders = $orders->total();

        return view('admin.reports.orders', compact(
            'orders',
            'range',
            'startDate',
            'endDate',
            'totalOrders'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS REPORT
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $rows = Order_itemModel::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.qty) as sold_qty'),
                DB::raw('SUM(order_items.price * order_items.qty) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.products', compact('rows', 'range', 'startDate', 'endDate'));
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTORY REPORT
    |--------------------------------------------------------------------------
    */
    public function inventory(Request $request)
    {
        $rows = ProductsModel::query()
            ->with('category')
            ->orderBy('quantity')
            ->paginate(15);

        return view('admin.reports.inventory', compact('rows'));
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS REPORT
    |--------------------------------------------------------------------------
    */
    public function customers(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $rows = User::query()
            ->withCount('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $rows->getCollection()->transform(function ($customer) {
            $customer->total_spent = PaymentModel::where('payment_status', 'paid')
                ->whereHas('order', fn($q) => $q->where('user_id', $customer->id))
                ->sum('amount');

            return $customer;
        });

        return view('admin.reports.customers', compact('rows', 'range', 'startDate', 'endDate'));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS REPORT
    |--------------------------------------------------------------------------
    */
    public function payments(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $rows = PaymentModel::with(['order.user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.payments', compact('rows', 'range', 'startDate', 'endDate'));
    }

    /*
    |--------------------------------------------------------------------------
    | PROMOTIONS REPORT
    |--------------------------------------------------------------------------
    */
    public function promotions(Request $request)
    {
        $rows = OrderModel::query()
            ->where(function ($q) {
                $q->whereNotNull('coupon_code')
                    ->orWhere('coupon_discount', '>', 0)
                    ->orWhere('promotion_discount', '>', 0);
            })
            ->latest()
            ->paginate(15);

        return view('admin.reports.promotions', compact('rows'));
    }
}
