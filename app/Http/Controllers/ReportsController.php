<?php

namespace App\Http\Controllers;

use App\Models\AddressModel;
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
use App\Models\PromotionModel;
use App\Models\PromotionsModel;

class ReportsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Sales
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

        $hasAddressFilter =
            $request->filled('province') ||
            $request->filled('district') ||
            $request->filled('sangkat') ||
            $request->filled('street');

        if ($hasAddressFilter) {

            if (empty($matchedOrderIds)) {
                $query->whereRaw('1=0');
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

            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            });
        }

        $orders = $query->latest()->get();

        /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

        $summary = [

            'total_orders' => $orders->count(),

            'total_revenue' => $orders->sum('total_amount'),

            'coupon_discount' => $orders->sum('coupon_discount'),

            'promotion_discount' => $orders->sum('promotion_discount'),

            'total_discount' => $orders->sum(function ($order) {

                return ($order->coupon_discount ?? 0)
                    + ($order->promotion_discount ?? 0);
            }),

            'net_revenue' => $orders->sum(function ($order) {

                return $order->total_amount
                    - ($order->coupon_discount ?? 0)
                    - ($order->promotion_discount ?? 0);
            }),

        ];

        /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

        $filters = [

            'status' => $request->status,

            'payment_method' => $request->payment_method,

            'payment_status' => $request->payment_status,

            'province' => $request->province,

            'district' => $request->district,

            'sangkat' => $request->sangkat,

            'street' => $request->street,

            'search' => $request->keyword,

        ];

        $pdf = Pdf::loadView(
            'admin.PDF.sales-details-pdf',
            [

                'orders' => $orders,

                'summary' => $summary,

                'filters' => $filters,

                'targetDate' => $targetDate,

            ]
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'sales_details_' . $targetDate . '.pdf'
        );
    }
    // public function sales(Request $request)
    // {
    //     [$range, $startDate, $endDate] = $this->resolveDateRange($request);

    //     $sort = $request->input('sort', 'sale_date');
    //     $direction = $request->input('direction', 'desc');

    //     $allowedSorts = [
    //         'sale_date',
    //         'total_orders',
    //         'gross_sales',
    //         'total_discount',
    //         'paid_revenue',
    //     ];

    //     if (! in_array($sort, $allowedSorts)) {
    //         $sort = 'sale_date';
    //     }

    //     $direction = $direction === 'asc'
    //         ? 'asc'
    //         : 'desc';

    //     // raw orders for building address dropdowns
    //     $rawOrders = OrderModel::query()
    //         ->select('id', 'delivery_address', 'total_amount', 'status', 'created_at')
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->get();

    //     $addressRows = $this->getAddressRowsFromOrders($rawOrders);

    //     [
    //         'provinceOptions' => $provinceOptions,
    //         'districtOptions' => $districtOptions,
    //         'sangkatOptions'  => $sangkatOptions,
    //         'streetOptions'   => $streetOptions,
    //     ] = $this->buildAddressOptions($addressRows, $request);

    //     // get matched order IDs from parsed delivery_address
    //     $matchedOrderIds = $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate);

    //     $hasAddressFilter = $request->filled('province')
    //         || $request->filled('district')
    //         || $request->filled('sangkat')
    //         || $request->filled('street');

    //     $ordersQuery = $this->salesBaseQuery($request, $startDate, $endDate);

    //     if ($hasAddressFilter) {
    //         if (empty($matchedOrderIds)) {
    //             $ordersQuery->whereRaw('1 = 0');
    //         } else {
    //             $ordersQuery->whereIn('orders.id', $matchedOrderIds);
    //         }
    //     }

    //     // grouped daily rows
    //     $salesRows = $this->salesDailyGroupedQuery(
    //         $request,
    //         $startDate,
    //         $endDate,
    //         $matchedOrderIds,
    //         $hasAddressFilter
    //     )
    //         ->orderBy($sort, $direction)
    //         ->paginate(15)
    //         ->withQueryString();

    //     // KPI summary
    //     $summaryRows = (clone $ordersQuery)->get();
    //     $highestSellingDay = $this->salesDailyGroupedQuery(
    //         $request,
    //         $startDate,
    //         $endDate,
    //         $matchedOrderIds,
    //         $hasAddressFilter
    //     )
    //         ->orderByDesc('gross_sales')
    //         ->first();

    //     $totalOrders = $summaryRows->count();
    //     $grossSales = (float) $summaryRows->sum('total_amount');
    //     $paidRevenue = (float) $summaryRows->where('payment_status', 'paid')->sum('paid_amount');
    //     $totalDiscount = (float) $summaryRows->sum(function ($row) {
    //         return (float) ($row->coupon_discount ?? 0) + (float) ($row->promotion_discount ?? 0);
    //     });
    //     $averageOrderValue = $totalOrders > 0 ? round($grossSales / $totalOrders, 2) : 0;

    //     // address analytics based on filtered rows
    //     $filteredAddressRows = $this->filterAddressRows($addressRows, $request);

    //     $topProvinces = collect($filteredAddressRows)
    //         ->groupBy('province')
    //         ->map(fn($items, $key) => [
    //             'name'    => $key,
    //             'orders'  => count($items),
    //             'revenue' => collect($items)->sum('amount'),
    //         ])
    //         ->filter(fn($x) => !empty($x['name']))
    //         ->sortByDesc('revenue')
    //         ->take(8)
    //         ->values();

    //     $topDistricts = collect($filteredAddressRows)
    //         ->groupBy('district')
    //         ->map(fn($items, $key) => [
    //             'name'    => $key,
    //             'orders'  => count($items),
    //             'revenue' => collect($items)->sum('amount'),
    //         ])
    //         ->filter(fn($x) => !empty($x['name']))
    //         ->sortByDesc('revenue')
    //         ->take(8)
    //         ->values();

    //     $topSangkats = collect($filteredAddressRows)
    //         ->groupBy('sangkat')
    //         ->map(fn($items, $key) => [
    //             'name'    => $key,
    //             'orders'  => count($items),
    //             'revenue' => collect($items)->sum('amount'),
    //         ])
    //         ->filter(fn($x) => !empty($x['name']))
    //         ->sortByDesc('revenue')
    //         ->take(8)
    //         ->values();

    //     $topStreets = collect($filteredAddressRows)
    //         ->groupBy('street')
    //         ->map(fn($items, $key) => [
    //             'name'    => $key,
    //             'orders'  => count($items),
    //             'revenue' => collect($items)->sum('amount'),
    //         ])
    //         ->filter(fn($x) => !empty($x['name']))
    //         ->sortByDesc('revenue')
    //         ->take(8)
    //         ->values();

    //     return view('admin.reports.sales', compact(
    //         'range',
    //         'startDate',
    //         'endDate',
    //         'salesRows',
    //         'totalOrders',
    //         'grossSales',
    //         'paidRevenue',
    //         'totalDiscount',
    //         'averageOrderValue',
    //         'provinceOptions',
    //         'districtOptions',
    //         'sangkatOptions',
    //         'streetOptions',
    //         'topProvinces',
    //         'topDistricts',
    //         'topSangkats',
    //         'topStreets',
    //         'highestSellingDay',
    //         'sort',
    //         'direction'
    //     ));
    // }
































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
        /* ── 1. Date range ─────────────────────────────────────────── */
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        /* ── 2. Sort ───────────────────────────────────────────────── */
        $allowedSorts = [
            'sale_date',
            'total_orders',
            'gross_sales',
            'total_discount',
            'paid_revenue',
        ];

        $sort      = in_array($request->input('sort'), $allowedSorts)
            ? $request->input('sort')
            : 'sale_date';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        /* ── 3. Raw orders → address rows → dropdowns ──────────────── */
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

        /* ── 4. Address filter ─────────────────────────────────────── */
        $hasAddressFilter = $request->filled('province')
            || $request->filled('district')
            || $request->filled('sangkat')
            || $request->filled('street');

        $matchedOrderIds = $hasAddressFilter
            ? $this->getMatchedOrderIdsByAddressFilter($request, $startDate, $endDate)
            : [];

        /* ── 5. KPI summary (full order rows — not paginated) ───────── */
        //  Only recalculate KPIs on full-page loads so they never stale.
        //  On AJAX calls we skip this — the JS never touches KPI cards.
        $ordersQuery  = $this->salesBaseQuery($request, $startDate, $endDate);

        if ($hasAddressFilter) {
            empty($matchedOrderIds)
                ? $ordersQuery->whereRaw('1 = 0')
                : $ordersQuery->whereIn('orders.id', $matchedOrderIds);
        }

        $summaryRows       = $ordersQuery->get();
        $totalOrders       = $summaryRows->count();
        $grossSales        = (float) $summaryRows->sum('total_amount');
        $paidRevenue       = (float) $summaryRows->where('payment_status', 'paid')->sum('paid_amount');
        $totalDiscount     = (float) $summaryRows->sum(fn($r) =>
        (float)($r->coupon_discount    ?? 0)
            + (float)($r->promotion_discount ?? 0));
        $averageOrderValue = $totalOrders > 0 ? round($grossSales / $totalOrders, 2) : 0;

        /* ── 6. Grouped daily rows (paginated) ─────────────────────── */
        $salesRows = $this->salesDailyGroupedQuery(
            $request,
            $startDate,
            $endDate,
            $matchedOrderIds,
            $hasAddressFilter
        )
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        /* ── 7. Address insights ────────────────────────────────────── */
        $filteredAddressRows = $this->filterAddressRows($addressRows, $request);

        $topProvinces = collect($filteredAddressRows)
            ->groupBy('province')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')->take(8)->values();

        $topDistricts = collect($filteredAddressRows)
            ->groupBy('district')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')->take(8)->values();

        $topSangkats = collect($filteredAddressRows)
            ->groupBy('sangkat')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')->take(8)->values();

        $topStreets = collect($filteredAddressRows)
            ->groupBy('street')
            ->map(fn($items, $key) => [
                'name'    => $key,
                'orders'  => count($items),
                'revenue' => collect($items)->sum('amount'),
            ])
            ->filter(fn($x) => !empty($x['name']))
            ->sortByDesc('revenue')->take(8)->values();

        $highestSellingDay = $this->salesDailyGroupedQuery(
            $request,
            $startDate,
            $endDate,
            $matchedOrderIds,
            $hasAddressFilter
        )->orderByDesc('gross_sales')->first();

        /* ── 8. Shared view data ───────────────────────────────────── */
        $viewData = compact(
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
            'topStreets',
            'highestSellingDay',
            'sort',
            'direction'
        );

        /* ── 9. AJAX → return partial only ─────────────────────────── */
        if ($request->ajax()) {
            return view(
                'admin.reports.partials.sales-table',
                $viewData
            )->render();
        }

        /* ── 10. Full-page load ─────────────────────────────────────── */
        return view('admin.reports.sales', $viewData);
    }




    /*
    |--------------------------------------------------------------------------
    | ORDERS REPORT
    |--------------------------------------------------------------------------
    */
    public function orders(Request $request)
    {
        [$range, $startDate, $endDate] = $this->resolveDateRange($request);

        $query = OrderModel::query()
            ->with([
                'user',
                'payment',
                'orderItems.product'
            ])
            ->whereBetween('created_at', [$startDate, $endDate]);

        /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('payment_status')) {

            $query->whereHas('payment', function ($q) use ($request) {

                $q->where('payment_status', $request->payment_status);
            });
        }

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhere('delivery_address', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($user) use ($keyword) {

                        $user->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $orders = (clone $query)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $summary = (clone $query)->get();

        $totalOrders = $summary->count();

        $pendingOrders = $summary
            ->where('status', 'pending')
            ->count();

        $processingOrders = $summary
            ->where('status', 'processing')
            ->count();

        $completedOrders = $summary
            ->where('status', 'completed')
            ->count();

        $cancelledOrders = $summary
            ->where('status', 'cancelled')
            ->count();

        $paidOrders = $summary
            ->filter(fn($o) => optional($o->payment)->payment_status == 'paid')
            ->count();

        $unpaidOrders = $summary
            ->filter(fn($o) => optional($o->payment)->payment_status != 'paid')
            ->count();

        $grossRevenue = $summary->sum('total_amount');

        $averageOrderValue = $totalOrders
            ? round($grossRevenue / $totalOrders, 2)
            : 0;

        $totalItems = $summary->sum(function ($order) {

            return $order->orderItems->sum('qty');
        });

        $averageItems = $totalOrders
            ? round($totalItems / $totalOrders, 1)
            : 0;
        $highestOrder = (clone $query)
            ->orderByDesc('total_amount')
            ->first();

        $lowestOrder = (clone $query)
            ->orderBy('total_amount')
            ->first();

        $bestCustomer = OrderModel::query()
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('revenue')
            ->first();

        $topPayment = OrderModel::query()
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->first();

        $peakDay = OrderModel::query()
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('total')
            ->first();

        $peakHour = OrderModel::query()
            ->select(
                DB::raw("DATE_PART('hour', created_at) as hour"),
                DB::raw("COUNT(*) as total")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_PART('hour', created_at)"))
            ->orderByDesc('total')
            ->first();

        $topProvince = collect($this->getAddressRowsFromOrders(
            (clone $query)->get()
        ))
            ->groupBy('province')
            ->map(function ($rows, $province) {

                return [

                    'province' => $province,

                    'orders' => count($rows),

                    'revenue' => collect($rows)->sum('amount')

                ];
            })
            ->sortByDesc('orders')
            ->first();

        $topProduct = Order_itemModel::query()
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.qty) total_qty')
            )
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->first();

        $monthlyOrders = OrderModel::query()
            ->select(
                DB::raw("TO_CHAR(created_at,'Mon') as month"),
                DB::raw("COUNT(*) as total")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(
                DB::raw("EXTRACT(MONTH FROM created_at)"),
                DB::raw("TO_CHAR(created_at,'Mon')")
            )
            ->orderBy(DB::raw("EXTRACT(MONTH FROM created_at)"))
            ->get();

        $orderTrend = OrderModel::query()
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("COUNT(*) as total")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy(DB::raw("DATE(created_at)"))
            ->get();

        $paymentStats = OrderModel::query()
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();
        $statusStats = OrderModel::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $topCustomers = OrderModel::query()
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select(
                'users.full_name',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('users.full_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $topProducts = Order_itemModel::query()
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.qty) as sold'),
                DB::raw('SUM(order_items.qty * order_items.price) as revenue')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('sold')
            ->limit(10)
            ->get();

        return view('admin.reports.orders', compact(

            'orders',

            'range',

            'startDate',

            'endDate',

            'totalOrders',

            'pendingOrders',

            'processingOrders',

            'completedOrders',

            'cancelledOrders',

            'paidOrders',

            'unpaidOrders',

            'grossRevenue',

            'averageOrderValue',

            'totalItems',

            'averageItems',
            'highestOrder',

            'lowestOrder',

            'bestCustomer',

            'topPayment',

            'peakDay',

            'peakHour',

            'topProvince',

            'topProduct',

        ));
    }
    public function orderDetails(OrderModel $order)
    {
        $order->load([
            'user',
            'payment',
            'orderItems.product'
        ]);

        return view(
            'admin.reports.partials.order-details',
            compact('order')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS REPORT
    |--------------------------------------------------------------------------
    */
    public function products(Request $request)
    {
        $query = ProductsModel::query()
            ->with([
                'category',
                'brand',
                'orderItems',
                'firstImage'
            ]);
        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('name', 'ILIKE', "%{$keyword}%")
                    ->orWhere('product_code', 'ILIKE', "%{$keyword}%")
                    ->orWhere('barcode', 'ILIKE', "%{$keyword}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

        if ($request->filled('category')) {
            $query->where('categories_id', $request->category);
        }

        /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        /*
    |--------------------------------------------------------------------------
    | Stock Status
    |--------------------------------------------------------------------------
    */

        if ($request->stock_status == "instock") {
            $query->where('quantity', '>', 20);
        }

        if ($request->stock_status == "lowstock") {
            $query->whereBetween('quantity', [1, 20]);
        }

        if ($request->stock_status == "outstock") {
            $query->where('quantity', 0);
        }

        /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

        switch ($request->sort) {

            case 'price_high':

                $query->orderByDesc('sale_price');

                break;

            case 'price_low':

                $query->orderBy('sale_price');

                break;

            case 'stock':

                $query->orderByDesc('quantity');

                break;

            default:

                $query->latest();
        }

        $products = $query->paginate(10)->withQueryString();
        $query->withCount('orderItems');

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $totalProducts = ProductsModel::count();

        $activeProducts = ProductsModel::where('quantity', '>', 0)->count();

        $outStock = ProductsModel::where('quantity', 0)->count();

        $lowStock = ProductsModel::whereBetween('quantity', [1, 20])->count();

        $stockValue = ProductsModel::selectRaw('SUM(quantity * cost_price) as total')
            ->value('total');

        $averagePrice = ProductsModel::avg('sale_price');

        $productCategoryChart = ProductsModel::query()
            ->join('categories', 'products.categories_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('COUNT(products.id) as total')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $productBrandChart = ProductsModel::query()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                'brands.name',
                DB::raw('COUNT(products.id) as total')
            )
            ->groupBy('brands.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stockChart = [

            'In Stock' =>
            ProductsModel::where('quantity', '>', 20)->count(),

            'Low Stock' =>
            ProductsModel::whereBetween('quantity', [1, 20])->count(),

            'Out of Stock' =>
            ProductsModel::where('quantity', 0)->count(),

        ];

        $priceChart = [

            '$0-25' =>
            ProductsModel::whereBetween('sale_price', [0, 25])->count(),

            '$25-50' =>
            ProductsModel::whereBetween('sale_price', [25, 50])->count(),

            '$50-100' =>
            ProductsModel::whereBetween('sale_price', [50, 100])->count(),

            '$100-200' =>
            ProductsModel::whereBetween('sale_price', [100, 200])->count(),

            '$200+' =>
            ProductsModel::where('sale_price', '>', 200)->count(),

        ];

        $products->getCollection()->transform(function ($product) {

            /*
    |--------------------------------------------------------------------------
    | Sold Qty
    |--------------------------------------------------------------------------
    */

            $sold = $product->orderItems->sum('qty');

            /*
    |--------------------------------------------------------------------------
    | Revenue
    |--------------------------------------------------------------------------
    */

            $revenue = $product->orderItems->sum(function ($item) {

                return $item->qty * $item->price;
            });

            /*
    |--------------------------------------------------------------------------
    | Profit
    |--------------------------------------------------------------------------
    */

            $profit = ($product->sale_price - $product->cost_price) * $sold;

            /*
    |--------------------------------------------------------------------------
    | Margin
    |--------------------------------------------------------------------------
    */

            $margin = 0;

            if ($product->sale_price > 0) {

                $margin = (($product->sale_price - $product->cost_price)

                    / $product->sale_price) * 100;
            }

            /*
    |--------------------------------------------------------------------------
    | Fake Demo Data
    | Replace later with real tables
    |--------------------------------------------------------------------------
    */

            $product->sold_qty = $sold;

            $product->revenue = $revenue;

            $product->profit = $profit;

            $product->margin = round($margin);

            $product->views = rand(20, 600);

            $product->rating = rand(40, 50) / 10;

            $product->favorites = rand(5, 120);

            return $product;
        });

        /*
    |--------------------------------------------------------------------------
    | Dropdown
    |--------------------------------------------------------------------------
    */

        $categories = Category::orderBy('name')->get();

        $brands = BrandModel::orderBy('name')->get();

        return view('admin.reports.products', compact(

            'products',

            'categories',

            'brands',

            'totalProducts',

            'activeProducts',

            'outStock',

            'lowStock',

            'stockValue',

            'averagePrice',
            'productCategoryChart',
            'productBrandChart',
            'stockChart',
            'priceChart',

        ));
    }


    public function productDetails(ProductsModel $product)
    {
        $product->load([
            'category',
            'brand',
            'firstImage',
        ]);

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'description' => $product->description,
            'cost_price'  => $product->cost_price,
            'sale_price'  => $product->sale_price,
            'quantity'    => $product->quantity,
            'status'      => $product->status,
            'category'    => $product->category?->name,
            'brand'       => $product->brand?->name,
            'image'       => $product->firstImage?->image_url,
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | INVENTORY REPORT
    |--------------------------------------------------------------------------
    */
    public function inventory(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = ProductsModel::query()
            ->with([
                'category',
                'brand',
                'orderItems',
                'firstImage',
            ]);

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('name', 'ILIKE', "%{$keyword}%")
                    ->orWhere('product_code', 'ILIKE', "%{$keyword}%")
                    ->orWhere('description', 'ILIKE', "%{$keyword}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

        if ($request->filled('category')) {

            $query->where('categories_id', $request->category);
        }

        /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

        if ($request->filled('brand')) {

            $query->where('brand_id', $request->brand);
        }

        /*
    |--------------------------------------------------------------------------
    | Stock Status
    |--------------------------------------------------------------------------
    */

        switch ($request->stock_status) {

            case 'instock':

                $query->where('quantity', '>', 20);

                break;

            case 'lowstock':

                $query->whereBetween('quantity', [1, 20]);

                break;

            case 'outstock':

                $query->where('quantity', 0);

                break;
        }

        /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

        switch ($request->sort) {

            case 'stock_high':

                $query->orderByDesc('quantity');

                break;

            case 'stock_low':

                $query->orderBy('quantity');

                break;

            case 'price_high':

                $query->orderByDesc('sale_price');

                break;

            case 'price_low':

                $query->orderBy('sale_price');

                break;

            default:

                $query->latest();
        }

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $products = $query
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $totalProducts = ProductsModel::count();

        $activeProducts = ProductsModel::where('quantity', '>', 0)->count();

        $outStock = ProductsModel::where('quantity', 0)->count();

        $lowStock = ProductsModel::whereBetween('quantity', [1, 20])->count();

        $inventoryValue = ProductsModel::selectRaw('SUM(quantity * cost_price) as total')
            ->value('total') ?? 0;

        $averageStock = ProductsModel::avg('quantity');

        $deadStock = ProductsModel::where('quantity', '>', 0)
            ->doesntHave('orderItems')
            ->count();

        $restockProducts = ProductsModel::whereBetween('quantity', [1, 20])->count();

        /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */

        $categoryChart = ProductsModel::query()
            ->join('categories', 'products.categories_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(products.quantity) as stock')
            )
            ->groupBy('categories.name')
            ->orderByDesc('stock')
            ->get();

        $brandChart = ProductsModel::query()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select(
                'brands.name',
                DB::raw('SUM(products.quantity) as stock')
            )
            ->groupBy('brands.name')
            ->orderByDesc('stock')
            ->limit(8)
            ->get();

        $stockChart = [

            'Healthy' => ProductsModel::where('quantity', '>', 20)->count(),

            'Low Stock' => ProductsModel::whereBetween('quantity', [1, 20])->count(),

            'Out Stock' => ProductsModel::where('quantity', 0)->count(),

        ];

        $valueChart = ProductsModel::query()

            ->join('categories', 'products.categories_id', '=', 'categories.id')

            ->select(

                'categories.name',

                DB::raw('SUM(products.quantity * products.cost_price) as value')

            )

            ->groupBy('categories.name')

            ->get();

        /*
    |--------------------------------------------------------------------------
    | Dropdown
    |--------------------------------------------------------------------------
    */

        $categories = Category::orderBy('name')->get();

        $brands = BrandModel::orderBy('name')->get();

        $highestStock = ProductsModel::orderByDesc('quantity')->first();

        $lowestStock = ProductsModel::where('quantity', '>', 0)
            ->orderBy('quantity')
            ->first();

        $mostValuable = ProductsModel::select(
            '*',
            DB::raw('(quantity * cost_price) as inventory_value')
        )
            ->orderByDesc('inventory_value')
            ->first();

        $needRestock = ProductsModel::whereBetween('quantity', [1, 20])
            ->orderBy('quantity')
            ->first();

        $outStockProduct = ProductsModel::where('quantity', 0)
            ->latest()
            ->first();

        $fastMoving = ProductsModel::query()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.*',
                DB::raw('SUM(order_items.qty) as sold_qty')
            )
            ->groupBy('products.id')
            ->orderByDesc('sold_qty')
            ->first();

        $slowMoving = ProductsModel::query()
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.*',
                DB::raw('COALESCE(SUM(order_items.qty),0) as sold_qty')
            )
            ->groupBy('products.id')
            ->orderBy('sold_qty')
            ->first();

        $highestValue = ProductsModel::select(
            '*',
            DB::raw('(quantity * cost_price) as stock_value')
        )
            ->orderByDesc('stock_value')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

        return view('admin.reports.inventory', compact(

            'products',

            'categories',

            'brands',

            'totalProducts',

            'activeProducts',

            'outStock',

            'lowStock',

            'inventoryValue',

            'averageStock',

            'deadStock',

            'restockProducts',

            'categoryChart',

            'brandChart',

            'stockChart',

            'valueChart',
            'highestStock',
            'lowestStock',
            'mostValuable',
            'needRestock',
            'outStockProduct',
            'fastMoving',
            'slowMoving',
            'highestValue',

        ));
    }
    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS REPORT
    |--------------------------------------------------------------------------
    */
    public function customers(Request $request)
    {
        /*
    
        |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = User::query()
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('full_name', 'ILIKE', "%{$keyword}%")
                    ->orWhere('email', 'ILIKE', "%{$keyword}%")
                    ->orWhere('phone', 'ILIKE', "%{$keyword}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Province
    |--------------------------------------------------------------------------
    */

        if ($request->filled('province')) {

            $query->where('province', $request->province);
        }

        /*
    |--------------------------------------------------------------------------
    | Customer Status
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            $query->where('status', $request->status);
        }

        /*
    |--------------------------------------------------------------------------
    | Customer Type
    |--------------------------------------------------------------------------
    */

        if ($request->filled('customer_type')) {

            switch ($request->customer_type) {

                case 'vip':

                    $query->has('orders', '>=', 20);

                    break;

                case 'returning':

                    $query->has('orders', '>=', 2);

                    break;

                case 'new':

                    $query->whereDate(
                        'created_at',
                        '>=',
                        now()->subDays(30)
                    );

                    break;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Date Range
    |--------------------------------------------------------------------------
    */

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

        /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

        switch ($request->sort) {

            case 'highest_spent':

                $query->orderByDesc('orders_sum_total_amount');

                break;

            case 'most_orders':

                $query->orderByDesc('orders_count');

                break;

            case 'latest':

                $query->latest();

                break;

            default:

                $query->latest();
        }

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $customers = $query
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $totalCustomers = User::count();

        $activeCustomers = User::whereHas('orders', function ($q) {

            $q->whereDate(
                'created_at',
                '>=',
                now()->subDays(30)
            );
        })->count();

        $newCustomers = User::whereDate(
            'created_at',
            '>=',
            now()->subDays(30)
        )->count();

        $vipCustomers = User::has('orders', '>=', 20)->count();

        $returningCustomers = User::has('orders', '>=', 2)->count();

        $inactiveCustomers = User::whereDoesntHave('orders', function ($q) {

            $q->whereDate(
                'created_at',
                '>=',
                now()->subDays(90)
            );
        })->count();

        $loyalCustomers = User::has('orders', '>=', 10)->count();

        $retentionRate = $totalCustomers > 0
            ? round(($returningCustomers / $totalCustomers) * 100, 1)
            : 0;

        /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */

        $growthChart = User::selectRaw(
            'DATE(created_at) as day,
             COUNT(*) as total'
        )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $provinceChart = DB::table('address')
            ->selectRaw("
        trim(
            split_part(
                reverse(split_part(reverse(address), ',', 2)),
                ',',
                1
            )
        ) as province,
        COUNT(*) as total
    ")
            ->groupBy('province')
            ->orderByDesc('total')
            ->get();

        $newVsReturning = [

            'New' => $newCustomers,

            'Returning' => $returningCustomers,

        ];

        /*
    |--------------------------------------------------------------------------
    | Customer Insights
    |--------------------------------------------------------------------------
    */

        $highestSpent = User::withSum('orders', 'total_amount')
            ->orderByDesc('orders_sum_total_amount')
            ->first();

        $mostOrders = User::withCount('orders')
            ->orderByDesc('orders_count')
            ->first();

        $newestCustomer = User::latest()->first();

        $topProvince = AddressModel::all()
            ->map(function ($address) {

                $parts = array_map('trim', explode(',', $address->address));

                // Remove "Cambodia"
                if (strtolower(end($parts)) == 'cambodia') {
                    array_pop($parts);
                }

                return end($parts); // Province
            })
            ->countBy()
            ->map(function ($total, $province) {

                return (object)[
                    'province' => $province,
                    'total' => $total,
                ];
            })
            ->sortByDesc('total')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Dropdown
    |--------------------------------------------------------------------------
    */
        $provinces = AddressModel::all()
            ->map(function ($address) {

                $parts = array_map('trim', explode(',', $address->address));

                // Remove Cambodia
                if (strtolower(end($parts)) === 'cambodia') {
                    array_pop($parts);
                }

                // Province
                return end($parts);
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

        return view('admin.reports.customers', compact(

            'customers',

            'provinces',

            'totalCustomers',

            'activeCustomers',

            'newCustomers',

            'vipCustomers',

            'returningCustomers',

            'inactiveCustomers',

            'loyalCustomers',

            'retentionRate',

            'growthChart',

            'provinceChart',

            'newVsReturning',

            'highestSpent',

            'mostOrders',

            'newestCustomer',

            'topProvince'

        ));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS REPORT
    |--------------------------------------------------------------------------
    */
    public function payments(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = PaymentModel::query()
            ->with([
                'order.user'
            ]);

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('transaction_id', 'ILIKE', "%{$keyword}%")
                    ->orWhere('md5', 'ILIKE', "%{$keyword}%")
                    ->orWhereHas('order.user', function ($user) use ($keyword) {

                        $user->where('full_name', 'ILIKE', "%{$keyword}%")
                            ->orWhere('phone', 'ILIKE', "%{$keyword}%")
                            ->orWhere('email', 'ILIKE', "%{$keyword}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

        if ($request->filled('payment_method')) {

            $query->where('payment_method', $request->payment_method);
        }

        /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

        if ($request->filled('payment_status')) {

            $query->where('payment_status', $request->payment_status);
        }

        /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

        if ($request->filled('start_date')) {

            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {

            $query->whereDate('created_at', '<=', $request->end_date);
        }

        /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

        switch ($request->sort) {

            case 'highest':

                $query->orderByDesc('amount');

                break;

            case 'lowest':

                $query->orderBy('amount');

                break;

            case 'latest':

                $query->latest();

                break;

            default:

                $query->latest();
        }

        /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

        $payments = $query
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $totalPayments = PaymentModel::count();

        $successfulPayments = PaymentModel::where(
            'payment_status',
            'paid'
        )->count();

        $pendingPayments = PaymentModel::where(
            'payment_status',
            'pending'
        )->count();

        $failedPayments = PaymentModel::where(
            'payment_status',
            'failed'
        )->count();

        $totalRevenue = PaymentModel::where(
            'payment_status',
            'paid'
        )->sum('amount');

        $abaRevenue = PaymentModel::where(
            'payment_method',
            'aba'
        )
            ->where('payment_status', 'paid')
            ->sum('amount');

        $khqrRevenue = PaymentModel::where(
            'payment_method',
            'khqr'
        )
            ->where('payment_status', 'paid')
            ->sum('amount');

        $averagePayment = PaymentModel::where(
            'payment_status',
            'paid'
        )->avg('amount');

        /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */

        $paymentTrend = PaymentModel::selectRaw("
            DATE(created_at) as day,
            SUM(amount) as revenue
        ")
            ->where('payment_status', 'paid')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $statusChart = [

            'Paid' => $successfulPayments,

            'Pending' => $pendingPayments,

            'Failed' => $failedPayments,

        ];

        $methodChart = PaymentModel::select(
            'payment_method',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('payment_method')
            ->get();

        $revenueChart = PaymentModel::select(
            'payment_method',
            DB::raw('SUM(amount) as revenue')
        )
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Insights
    |--------------------------------------------------------------------------
    */

        $highestTransaction = PaymentModel::orderByDesc('amount')->first();

        $largestPaid = PaymentModel::where('payment_status', 'paid')
            ->orderByDesc('amount')
            ->first();

        $mostUsedMethod = PaymentModel::select(
            'payment_method',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->first();

        $highestRevenueDay = PaymentModel::selectRaw("
            DATE(created_at) as day,
            SUM(amount) as revenue
        ")
            ->where('payment_status', 'paid')
            ->groupBy('day')
            ->orderByDesc('revenue')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

        return view('admin.reports.payments', compact(

            'payments',

            'totalPayments',

            'successfulPayments',

            'pendingPayments',

            'failedPayments',

            'totalRevenue',

            'abaRevenue',

            'khqrRevenue',

            'averagePayment',

            'paymentTrend',

            'statusChart',

            'methodChart',

            'revenueChart',

            'highestTransaction',

            'largestPaid',

            'mostUsedMethod',

            'highestRevenueDay'

        ));
    }

    /*
    |--------------------------------------------------------------------------
    | PROMOTIONS REPORT
    |--------------------------------------------------------------------------
    */
    public function promotions(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

        $query = PromotionModel::query()
            ->withCount('products')
            ->with('products');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('title', 'ILIKE', "%{$keyword}%")
                    ->orWhere('description', 'ILIKE', "%{$keyword}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Promotion Type
    |--------------------------------------------------------------------------
    */

        if ($request->filled('type')) {

            $query->where('discount_type', $request->type);
        }

        /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            switch ($request->status) {

                case 'active':

                    $query->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now());

                    break;

                case 'scheduled':

                    $query->whereDate('start_date', '>', now());

                    break;

                case 'expired':

                    $query->whereDate('end_date', '<', now());

                    break;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'start_date',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {

            $query->whereDate(
                'end_date',
                '<=',
                $request->end_date
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

        switch ($request->sort) {

            case 'highest_discount':

                $query->orderByDesc('discount_value');

                break;

            case 'latest':

                $query->latest();

                break;

            default:

                $query->latest();
        }

        /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

        $promotions = $query
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

        $totalPromotions = PromotionModel::count();

        $activePromotions = PromotionModel::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->count();

        $scheduledPromotions = PromotionModel::whereDate(
            'start_date',
            '>',
            now()
        )->count();

        $expiredPromotions = PromotionModel::whereDate(
            'end_date',
            '<',
            now()
        )->count();

        /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

        $totalProducts = PromotionModel::withCount('products')
            ->get()
            ->sum('products_count');

        $averageDiscount = PromotionModel::avg('discount_value');

        $highestDiscount = PromotionModel::max('discount_value');

        $bestPromotion = PromotionModel::orderByDesc('discount_value')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */

        $promotionTrend = PromotionModel::selectRaw("
        DATE(start_date) as day,
        COUNT(*) as total
    ")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $statusChart = [

            'Active' => $activePromotions,

            'Scheduled' => $scheduledPromotions,

            'Expired' => $expiredPromotions,

        ];

        $typeChart = PromotionModel::select(
            'discount_type',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('discount_type')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Insights
    |--------------------------------------------------------------------------
    */

        $highestPromotion = PromotionModel::orderByDesc(
            'discount_value'
        )->first();

        $latestPromotion = PromotionModel::latest()->first();

        $endingSoon = PromotionModel::whereDate(
            'end_date',
            '>=',
            today()
        )
            ->orderBy('end_date')
            ->first();

        $topDiscountChart = PromotionModel::select(
            'name',
            'discount_value'
        )
            ->orderByDesc('discount_value')
            ->limit(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

        return view('admin.reports.promotions', compact(

            'promotions',

            'totalPromotions',

            'activePromotions',

            'scheduledPromotions',

            'expiredPromotions',

            'totalProducts',

            'averageDiscount',

            'highestDiscount',

            'bestPromotion',

            'promotionTrend',

            'statusChart',

            'typeChart',

            'highestPromotion',

            'latestPromotion',

            'endingSoon',
            'topDiscountChart',

        ));
    }
}
