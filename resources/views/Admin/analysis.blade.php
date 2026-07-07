@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.85); opacity: 0; }
            70%  { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

        .kpi-card { animation: fadeSlideUp .5s ease both; }
        .kpi-card:nth-child(1)  { animation-delay: .04s; }
        .kpi-card:nth-child(2)  { animation-delay: .08s; }
        .kpi-card:nth-child(3)  { animation-delay: .12s; }
        .kpi-card:nth-child(4)  { animation-delay: .16s; }
        .kpi-card:nth-child(5)  { animation-delay: .20s; }
        .kpi-card:nth-child(6)  { animation-delay: .24s; }
        .kpi-card:nth-child(7)  { animation-delay: .28s; }
        .kpi-card:nth-child(8)  { animation-delay: .32s; }

        .kpi-card2 { animation: fadeSlideUp .5s ease both; }
        .kpi-card2:nth-child(1) { animation-delay: .36s; }
        .kpi-card2:nth-child(2) { animation-delay: .40s; }
        .kpi-card2:nth-child(3) { animation-delay: .44s; }
        .kpi-card2:nth-child(4) { animation-delay: .48s; }

        .section-card { animation: fadeSlideUp .55s ease both; }
        .section-card:nth-child(1) { animation-delay: .50s; }
        .section-card:nth-child(2) { animation-delay: .55s; }
        .section-card:nth-child(3) { animation-delay: .60s; }
        .section-card:nth-child(4) { animation-delay: .65s; }

        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }
        .count-done   { animation: numberPop .35s cubic-bezier(.34,1.56,.64,1) both; }

        .action-btn { transition: transform .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        .table-row-anim { animation: rowSlideIn .35s ease both; }
        .table-row-anim:nth-child(1) { animation-delay: .55s; }
        .table-row-anim:nth-child(2) { animation-delay: .60s; }
        .table-row-anim:nth-child(3) { animation-delay: .65s; }
        .table-row-anim:nth-child(4) { animation-delay: .70s; }
        .table-row-anim:nth-child(5) { animation-delay: .75s; }
    </style>

    <div class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3"
             style="animation: fadeSlideUp .4s ease both;">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Business Analysis</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Insights and performance overview ·
                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $rangeLabel }}</span>
                </p>
            </div>

            {{-- Date Range Filter --}}
            <form method="GET" action="{{ route('analysis.index') }}"
                  class="flex flex-wrap items-center gap-2">

            <div class="relative w-56">
                <select
                    name="range"
                    onchange="this.form.submit()"
                    class="w-full appearance-none rounded-xl border border-gray-200 dark:border-gray-700
                        bg-white dark:bg-gray-800
                        px-4 py-3 pr-11
                        text-sm font-medium text-gray-700 dark:text-gray-200
                        shadow-sm
                        transition-all duration-200
                        hover:border-indigo-400
                        focus:border-indigo-500
                        focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900/30
                        focus:outline-none">

                    <option value="today" {{ $rangeKey=='today' ? 'selected' : '' }}>
                        Today
                    </option>

                    <option value="7d" {{ $rangeKey=='7d' ? 'selected' : '' }}>
                    Last 7 Days
                    </option>

                    <option value="30d" {{ $rangeKey=='30d' ? 'selected' : '' }}>
                        Last 30 Days
                    </option>

                    <option value="this_month" {{ $rangeKey=='this_month' ? 'selected' : '' }}>
                        This Month
                    </option>

                    <option value="last_month" {{ $rangeKey=='last_month' ? 'selected' : '' }}>
                        Last Month
                    </option>

                    <option value="3m" {{ $rangeKey=='3m' ? 'selected' : '' }}>
                        Last 3 Months
                    </option>

                    <option value="6m" {{ $rangeKey=='6m' ? 'selected' : '' }}>
                        Last 6 Months
                    </option>

                    <option value="12m" {{ $rangeKey=='12m' ? 'selected' : '' }}>
                        Last 12 Months
                    </option>

                    <option value="custom" {{ $rangeKey=='custom' ? 'selected' : '' }}>
                        Custom Range
                    </option>

                </select>

                <!-- Chevron -->
                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
                    <svg class="h-5 w-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

                {{-- Custom date fields --}}
                <div id="customDateFields"
                     class="{{ $rangeKey === 'custom' ? 'flex' : 'hidden' }} items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="text-xs rounded-xl border border-gray-200 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200
                               px-3 py-2 shadow-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-gray-400 text-xs">→</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="text-xs rounded-xl border border-gray-200 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200
                               px-3 py-2 shadow-sm outline-none focus:ring-2 focus:ring-indigo-500">
                    <input type="hidden" name="range" value="custom">
                    <button type="submit"
                        class="action-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700
                               text-white text-xs font-semibold shadow-sm shadow-indigo-500/20 transition-colors">
                        Apply
                    </button>
                </div>
            </form>
        </div>

        {{-- ==================== KPI ROW 1 ==================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-8 gap-3">
            @php
                $kpis = [
                    ['label' => 'Revenue',      'value' => '$'.number_format($totalRevenue, 2),        'sub' => 'Paid orders',       'from' => 'from-indigo-500',  'to' => 'to-violet-600',  'ring' => 'ring-indigo-200 dark:ring-indigo-800', 'text' => 'text-indigo-600 dark:text-indigo-400', 'bg' => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20'],
                    ['label' => 'Profit',       'value' => '$'.number_format($profit, 2),              'sub' => 'After cost',        'from' => 'from-emerald-500', 'to' => 'to-green-600',   'ring' => 'ring-emerald-200 dark:ring-emerald-800', 'text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20'],
                    ['label' => 'Orders',       'value' => number_format($totalOrders),                'sub' => 'Paid only',         'from' => 'from-blue-500',    'to' => 'to-indigo-600',  'ring' => 'ring-blue-200 dark:ring-blue-800', 'text' => 'text-blue-600 dark:text-blue-400', 'bg' => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20'],
                    ['label' => 'Avg Order',    'value' => '$'.number_format($averageOrderValue, 2),   'sub' => 'Per order',         'from' => 'from-amber-500',   'to' => 'to-yellow-600',  'ring' => 'ring-amber-200 dark:ring-amber-800', 'text' => 'text-amber-600 dark:text-amber-400', 'bg' => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20'],
                    ['label' => 'Rev Growth',   'value' => number_format($revenueGrowth, 1).'%',       'sub' => 'vs prior period',   'from' => 'from-purple-500',  'to' => 'to-fuchsia-600', 'ring' => 'ring-purple-200 dark:ring-purple-800', 'text' => 'text-purple-600 dark:text-purple-400', 'bg' => 'from-purple-50 to-fuchsia-100 dark:from-purple-900/20 dark:to-fuchsia-900/20'],
                    ['label' => 'Ord Growth',   'value' => number_format($orderGrowth, 1).'%',         'sub' => 'vs prior period',   'from' => 'from-pink-500',    'to' => 'to-rose-600',    'ring' => 'ring-pink-200 dark:ring-pink-800', 'text' => 'text-pink-600 dark:text-pink-400', 'bg' => 'from-pink-50 to-rose-100 dark:from-pink-900/20 dark:to-rose-900/20'],
                    ['label' => 'New Customers','value' => number_format($newCustomers),               'sub' => 'Registered',        'from' => 'from-cyan-500',    'to' => 'to-sky-600',     'ring' => 'ring-cyan-200 dark:ring-cyan-800', 'text' => 'text-cyan-600 dark:text-cyan-400', 'bg' => 'from-cyan-50 to-sky-100 dark:from-cyan-900/20 dark:to-sky-900/20'],
                    ['label' => 'Repeat Rate',  'value' => number_format($repeatPurchaseRate, 1).'%',  'sub' => 'Return buyers',     'from' => 'from-rose-500',    'to' => 'to-red-600',     'ring' => 'ring-rose-200 dark:ring-rose-800', 'text' => 'text-rose-600 dark:text-rose-400', 'bg' => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20'],
                ];
            @endphp

            @foreach($kpis as $kpi)
                <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                    <div class="absolute -top-8 -right-8 w-20 h-20 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                    <div class="relative">
                        <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $kpi['label'] }}</p>
                        <p class="text-lg font-bold tracking-tight mt-1 bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none">
                            {{ $kpi['value'] }}
                        </p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $kpi['sub'] }}</p>
                        <div class="mt-2 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ==================== KPI ROW 2 ==================== --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
            @php
                $kpis2 = [
                    ['label' => 'Returning Customers', 'value' => number_format($returningCustomers),         'sub' => 'Multiple orders',  'from' => 'from-violet-500',  'to' => 'to-purple-600',  'bg' => 'from-violet-50 to-purple-100 dark:from-violet-900/20 dark:to-purple-900/20'],
                    ['label' => 'Cancellation Rate',   'value' => number_format($cancellationRate, 1).'%',    'sub' => 'Of all orders',    'from' => 'from-red-500',     'to' => 'to-rose-600',    'bg' => 'from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20'],
                    ['label' => 'Completion Rate',     'value' => number_format($completionRate, 1).'%',      'sub' => 'Delivered orders', 'from' => 'from-emerald-500', 'to' => 'to-teal-600',    'bg' => 'from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20'],
                    ['label' => 'Out of Stock',        'value' => number_format($outOfStockCount),            'sub' => 'Products at 0',    'from' => 'from-slate-500',   'to' => 'to-gray-600',    'bg' => 'from-slate-50 to-gray-100 dark:from-slate-900/20 dark:to-gray-900/20'],
                ];
            @endphp

            @foreach($kpis2 as $kpi)
                <div class="kpi-card2 relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                    <div class="relative">
                        <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $kpi['label'] }}</p>
                        <p class="text-2xl font-bold tracking-tight mt-1 bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none">
                            {{ $kpi['value'] }}
                        </p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $kpi['sub'] }}</p>
                        <div class="mt-2 h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ==================== CHART + ORDER STATUS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3">

            {{-- Revenue / Profit Chart --}}
            <div class="section-card xl:col-span-2 bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue & Profit Trend</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Dynamic chart · {{ $rangeLabel }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Revenue
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Profit
                        </span>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-[300px]">
                        <canvas id="revenueProfitChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Orders by Status --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Orders by Status</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Distribution across all statuses</p>
                </div>
                <div class="p-4 sm:p-5 space-y-3">
                    @php
                        $statusGradients = [
                            'pending'    => ['from-amber-500',   'to-yellow-600',  'text-amber-600 dark:text-amber-400'],
                            'processing' => ['from-blue-500',    'to-indigo-600',  'text-blue-600 dark:text-blue-400'],
                            'completed'  => ['from-emerald-500', 'to-green-600',   'text-emerald-600 dark:text-emerald-400'],
                            'cancelled'  => ['from-red-500',     'to-rose-600',    'text-red-600 dark:text-red-400'],
                        ];
                    @endphp
                    @forelse($ordersByStatus as $row)
                        @php
                            $total   = $ordersByStatus->sum('count');
                            $pct     = $total > 0 ? ($row->count / $total) * 100 : 0;
                            $colors  = $statusGradients[$row->status] ?? ['from-gray-400', 'to-gray-500', 'text-gray-500'];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium capitalize text-gray-700 dark:text-gray-300">{{ $row->status }}</span>
                                <span class="text-xs font-semibold {{ $colors[2] }}">{{ $row->count }}</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }}"
                                     style="width: {{ min($pct, 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 dark:text-gray-500">No order data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==================== CATEGORIES + PAYMENT REVENUE ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Top Categories --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Categories</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">By units sold</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/40">
                            <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <th class="px-5 py-3">#</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3 text-right">Units Sold</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @php $catGradients = ['from-indigo-500 to-violet-600','from-emerald-500 to-green-600','from-amber-500 to-yellow-600','from-blue-500 to-indigo-600','from-pink-500 to-rose-600']; @endphp
                            @forelse($salesByCategory as $i => $cat)
                                <tr class="table-row-anim hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                                    <td class="px-5 py-3">
                                        <span class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $catGradients[$i % 5] }}
                                                     flex items-center justify-center text-[10px] font-bold text-white">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $cat->name }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                            {{ number_format($cat->total_sold) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-xs text-gray-400 dark:text-gray-500">No category data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Revenue by Payment Method --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue by Payment Method</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Share of total revenue</p>
                </div>
                <div class="p-4 sm:p-5 space-y-3">
                    @php
                        $pmColors = [
                            'khqr' => ['from-purple-500','to-fuchsia-600','text-purple-600 dark:text-purple-400'],
                            'aba'  => ['from-blue-500','to-indigo-600','text-blue-600 dark:text-blue-400'],
                            'wing' => ['from-green-500','to-emerald-600','text-green-600 dark:text-green-400'],
                            'cash' => ['from-amber-500','to-yellow-600','text-amber-600 dark:text-amber-400'],
                        ];
                    @endphp
                    @forelse($revenueByPaymentMethod as $pm)
                        @php
                            $pct    = $totalRevenue > 0 ? ($pm->revenue / $totalRevenue) * 100 : 0;
                            $colors = $pmColors[strtolower($pm->payment_method)] ?? ['from-gray-400','to-gray-500','text-gray-500'];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                                    {{ $pm->payment_method }}
                                </span>
                                <span class="text-xs font-semibold {{ $colors[2] }}">${{ number_format($pm->revenue, 2) }}</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }}"
                                     style="width: {{ min($pct, 100) }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 text-right">{{ number_format($pct, 1) }}% of total</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 dark:text-gray-500">No data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==================== TOP PRODUCTS + TOP CUSTOMERS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Top Products --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Selling Products</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">By quantity sold · paid orders only</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topProducts as $i => $product)
                        <div class="table-row-anim flex items-center gap-3 px-4 sm:px-5 py-3
                                    hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                            <span class="text-xs font-bold text-gray-300 dark:text-gray-600 w-4 flex-shrink-0">{{ $i + 1 }}</span>
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                                @if($product->firstImage?->image_url)
                                    <img src="{{ $product->firstImage->image_url }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold
                                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($product->sold_qty ?? 0) }} sold
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-xs text-gray-400 dark:text-gray-500">No products data.</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Customers --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Customers</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">By total spend · paid orders only</p>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topCustomers as $i => $customer)
                        @php $initials = strtoupper(substr($customer->full_name ?? 'C', 0, 1)); @endphp
                        <div class="table-row-anim flex items-center gap-3 px-4 sm:px-5 py-3
                                    hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                            <span class="text-xs font-bold text-gray-300 dark:text-gray-600 w-4 flex-shrink-0">{{ $i + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600
                                        flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $customer->full_name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ $customer->email ?? '—' }}</p>
                            </div>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                                ${{ number_format($customer->total_spent, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-xs text-gray-400 dark:text-gray-500">No customer data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==================== COUPON + KHQR ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Coupon & Discount --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Discount & Coupon Insights</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Coupon usage and discount performance</p>
                </div>
                <div class="p-4 sm:p-5 space-y-4">

                    {{-- 4 mini stats --}}
                    <div class="grid grid-cols-2 gap-3">
                        @php
                            $couponStats = [
                                ['label' => 'Orders w/ Coupon',   'value' => number_format($ordersWithCoupon),        'from' => 'from-indigo-500',  'to' => 'to-violet-600'],
                                ['label' => 'Usage Rate',         'value' => number_format($couponUsageRate, 1).'%',  'from' => 'from-amber-500',   'to' => 'to-yellow-600'],
                                ['label' => 'Coupon Discount',    'value' => '$'.number_format($totalCouponDiscount, 2), 'from' => 'from-rose-500', 'to' => 'to-pink-600'],
                                ['label' => 'Total Discount',     'value' => '$'.number_format($totalDiscountGiven, 2), 'from' => 'from-red-500',  'to' => 'to-rose-600'],
                            ];
                        @endphp
                        @foreach($couponStats as $cs)
                            <div class="relative overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-700/40
                                        border border-gray-100 dark:border-gray-700 p-3">
                                <div class="absolute -top-5 -right-5 w-14 h-14 rounded-full bg-gradient-to-br {{ $cs['from'] }} {{ $cs['to'] }} opacity-10"></div>
                                <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $cs['label'] }}</p>
                                <p class="text-lg font-bold mt-0.5 bg-gradient-to-r {{ $cs['from'] }} {{ $cs['to'] }} bg-clip-text text-transparent">
                                    {{ $cs['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Top Coupons table --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Top Coupons</p>
                        <div class="space-y-2">
                            @forelse($coupons as $coupon)
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl
                                            bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700
                                            hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-colors">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white">
                                            {{ $coupon->code ?? $coupon->name ?? 'Coupon' }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                            Used {{ $coupon->used_count ?? 0 }} times
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                        {{ $coupon->used_count ?? 0 }}×
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 dark:text-gray-500">No coupon data.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- KHQR --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">KHQR Performance</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">QR payment success, pending, expired & failed</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold
                                 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                                 ring-1 ring-emerald-200 dark:ring-emerald-800">
                        {{ number_format($khqrSuccessRate, 1) }}% success
                    </span>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @php
                            $khqrCards = [
                                ['label' => 'Total KHQR',    'value' => $khqrTotal,   'from' => 'from-indigo-500',  'to' => 'to-violet-600'],
                                ['label' => 'Success Rate',  'value' => number_format($khqrSuccessRate, 1).'%', 'from' => 'from-emerald-500', 'to' => 'to-green-600'],
                                ['label' => 'Successful',    'value' => $khqrSuccess, 'from' => 'from-emerald-500', 'to' => 'to-teal-600'],
                                ['label' => 'Pending',       'value' => $khqrPending, 'from' => 'from-amber-500',   'to' => 'to-yellow-600'],
                                ['label' => 'Expired',       'value' => $khqrExpired, 'from' => 'from-red-500',     'to' => 'to-rose-600'],
                                ['label' => 'Failed',        'value' => $khqrFailed,  'from' => 'from-rose-600',    'to' => 'to-pink-700'],
                            ];
                        @endphp
                        @foreach($khqrCards as $kc)
                            <div class="relative overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-700/40
                                        border border-gray-100 dark:border-gray-700 p-3">
                                <div class="absolute -top-5 -right-5 w-14 h-14 rounded-full bg-gradient-to-br {{ $kc['from'] }} {{ $kc['to'] }} opacity-10"></div>
                                <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $kc['label'] }}</p>
                                <p class="text-xl font-bold mt-0.5 bg-gradient-to-r {{ $kc['from'] }} {{ $kc['to'] }} bg-clip-text text-transparent">
                                    {{ $kc['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- KHQR progress bars --}}
                    @if($khqrTotal > 0)
                        <div class="space-y-2.5">
                            @foreach([
                                ['label' => 'Success', 'count' => $khqrSuccess, 'from' => 'from-emerald-500', 'to' => 'to-green-600'],
                                ['label' => 'Pending', 'count' => $khqrPending, 'from' => 'from-amber-500', 'to' => 'to-yellow-600'],
                                ['label' => 'Expired', 'count' => $khqrExpired, 'from' => 'from-red-500', 'to' => 'to-rose-600'],
                                ['label' => 'Failed',  'count' => $khqrFailed,  'from' => 'from-rose-600', 'to' => 'to-pink-700'],
                            ] as $kb)
                                @php $kpct = $khqrTotal > 0 ? ($kb['count'] / $khqrTotal) * 100 : 0; @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-0.5">
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $kb['label'] }}</span>
                                        <span class="text-[10px] font-semibold text-gray-700 dark:text-gray-300">{{ number_format($kpct, 1) }}%</span>
                                    </div>
                                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $kb['from'] }} {{ $kb['to'] }}"
                                             style="width: {{ min($kpct, 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================== PAYMENT USAGE + LOW STOCK ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Payment Method Usage Count --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Method Usage</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Transaction count by method</p>
                </div>
                <div class="p-4 sm:p-5 space-y-3">
                    @forelse($paymentMethods as $method)
                        @php
                            $total2 = $paymentMethods->sum('count');
                            $pct2   = $total2 > 0 ? ($method->count / $total2) * 100 : 0;
                            $mc     = $pmColors[strtolower($method->method)] ?? ['from-sky-500','to-blue-600','text-sky-600 dark:text-sky-400'];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="inline-flex items-center gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                                        {{ $method->method }}
                                    </span>
                                </span>
                                <span class="text-xs font-semibold {{ $mc[2] }}">{{ $method->count }} txn</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $mc[0] }} {{ $mc[1] }}"
                                     style="width: {{ min($pct2, 100) }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 text-right">{{ number_format($pct2, 1) }}%</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 dark:text-gray-500">No data.</p>
                    @endforelse
                </div>
            </div>

            {{-- Low Stock Products --}}
            <div class="section-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Low Stock Products</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Products at or below 10 units</p>
                    </div>
                    @if($outOfStockCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold
                                     bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                     ring-1 ring-red-200 dark:ring-red-800">
                            {{ $outOfStockCount }} out of stock
                        </span>
                    @endif
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($lowStockProducts as $product)
                        <div class="table-row-anim flex items-center justify-between px-4 sm:px-5 py-3
                                    hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500">ID: {{ $product->id }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                                         {{ $product->quantity <= 0
                                             ? 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400'
                                             : 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400' }}">
                                {{ $product->quantity }} left
                            </span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-xs text-gray-400 dark:text-gray-500">No low stock products.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>{{-- /space-y-4 --}}

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Custom date toggle
        document.querySelectorAll('[name="range"]').forEach(btn => {
            if (btn.tagName === 'BUTTON') {
                btn.addEventListener('click', function () {
                    const box = document.getElementById('customDateFields');
                    if (this.value === 'custom') {
                        box.classList.remove('hidden');
                        box.classList.add('flex');
                    } else {
                        box.classList.add('hidden');
                        box.classList.remove('flex');
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const isDark   = document.documentElement.classList.contains('dark');
            const tickClr  = isDark ? '#9ca3af' : '#6b7280';
            const gridClr  = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const legendClr = isDark ? '#e5e7eb' : '#111827';

            const ctx = document.getElementById('revenueProfitChart');
            if (!ctx) return;

            const labels      = @json($chartLabels);
            const revenueData = @json($monthlyRevenue);
            const profitData  = @json($monthlyProfit);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: revenueData,
                            borderColor: '#4f46e5',
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const { ctx: c, chartArea } = chart;
                                if (!chartArea) return 'transparent';
                                const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                                gradient.addColorStop(0, 'rgba(79,70,229,0.18)');
                                gradient.addColorStop(1, 'rgba(79,70,229,0)');
                                return gradient;
                            },
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#4f46e5',
                            borderWidth: 2.5,
                        },
                        {
                            label: 'Profit',
                            data: profitData,
                            borderColor: '#10b981',
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const { ctx: c, chartArea } = chart;
                                if (!chartArea) return 'transparent';
                                const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                                gradient.addColorStop(0, 'rgba(16,185,129,0.14)');
                                gradient.addColorStop(1, 'rgba(16,185,129,0)');
                                return gradient;
                            },
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            borderWidth: 2.5,
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#fff',
                            titleColor: isDark ? '#f9fafb' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#6b7280',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: ctx => ' $' + ctx.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: tickClr, font: { size: 10 } },
                            grid:  { color: gridClr }
                        },
                        y: {
                            ticks: {
                                color: tickClr, font: { size: 10 },
                                callback: v => '$' + v.toLocaleString()
                            },
                            grid: { color: gridClr }
                        }
                    }
                }
            });
        });
    </script>

@endsection