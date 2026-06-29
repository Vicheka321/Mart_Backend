@extends('layouts.app')

@section('title', 'Orders Report')

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
            100% { transform: scale(1);    opacity: 1; }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to   { opacity: 1; transform: scale(1)    translateY(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .kpi-card { animation: fadeSlideUp .5s ease both; }
        .kpi-card:nth-child(1) { animation-delay: .05s; }
        .kpi-card:nth-child(2) { animation-delay: .10s; }
        .kpi-card:nth-child(3) { animation-delay: .15s; }
        .kpi-card:nth-child(4) { animation-delay: .20s; }
        .kpi-card:nth-child(5) { animation-delay: .25s; }

        .kpi-card-2 { animation: fadeSlideUp .5s ease both; }
        .kpi-card-2:nth-child(1) { animation-delay: .30s; }
        .kpi-card-2:nth-child(2) { animation-delay: .35s; }
        .kpi-card-2:nth-child(3) { animation-delay: .40s; }
        .kpi-card-2:nth-child(4) { animation-delay: .45s; }
        .kpi-card-2:nth-child(5) { animation-delay: .50s; }

        .filter-card  { animation: fadeSlideUp .45s .05s ease both; }
        .table-card   { animation: fadeSlideUp .5s .28s ease both; }
        .analytic-card { animation: fadeSlideUp .5s ease both; }
        .analytic-card:nth-child(1) { animation-delay: .30s; }
        .analytic-card:nth-child(2) { animation-delay: .35s; }
        .analytic-card:nth-child(3) { animation-delay: .40s; }
        .analytic-card:nth-child(4) { animation-delay: .45s; }
        .analytic-card:nth-child(5) { animation-delay: .50s; }
        .analytic-card:nth-child(6) { animation-delay: .55s; }
        .analytic-card:nth-child(7) { animation-delay: .60s; }
        .analytic-card:nth-child(8) { animation-delay: .65s; }

        .chart-card { animation: fadeSlideUp .5s ease both; }
        .chart-card:nth-child(1) { animation-delay: .28s; }
        .chart-card:nth-child(2) { animation-delay: .33s; }

        #ordersTableBody tr { animation: rowSlideIn .35s ease both; }
        #ordersTableBody tr:nth-child(1)  { animation-delay: .35s; }
        #ordersTableBody tr:nth-child(2)  { animation-delay: .40s; }
        #ordersTableBody tr:nth-child(3)  { animation-delay: .45s; }
        #ordersTableBody tr:nth-child(4)  { animation-delay: .50s; }
        #ordersTableBody tr:nth-child(5)  { animation-delay: .55s; }
        #ordersTableBody tr:nth-child(6)  { animation-delay: .60s; }
        #ordersTableBody tr:nth-child(7)  { animation-delay: .65s; }
        #ordersTableBody tr:nth-child(8)  { animation-delay: .70s; }
        #ordersTableBody tr:nth-child(9)  { animation-delay: .75s; }
        #ordersTableBody tr:nth-child(10) { animation-delay: .80s; }

        .progress-bar { animation: progressFill .9s .65s cubic-bezier(.4,0,.2,1) both; }

        #orderModal.flex   { animation: overlayIn .2s ease; }
        #exportModal.flex  { animation: overlayIn .2s ease; }
        .modal-inner       { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        .filter-select {
            width: 100%;
            border-radius: .75rem;
            border: 1px solid rgb(229 231 235);
            background: white;
            padding: .5rem .75rem;
            font-size: .8125rem;
            color: rgb(17 24 39);
            outline: none;
            transition: box-shadow .15s ease, border-color .15s ease;
        }
        .dark .filter-select {
            border-color: rgb(55 65 81);
            background: rgb(31 41 55);
            color: rgb(243 244 246);
        }
        .filter-select:focus {
            box-shadow: 0 0 0 2px rgba(99,102,241,.35);
            border-color: #6366f1;
        }
    </style>

    <div class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3"
             style="animation: fadeSlideUp .4s ease both;">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Orders Report</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Analyze orders, payments, customers and delivery performance.
                </p>
            </div>

            <button type="button" onclick="openExportModal()"
                class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                       border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                       text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                </svg>
                Export
            </button>
        </div>

        {{-- ==================== KPI ROW 1 — ORDER STATUS ==================== --}}
        {{-- @php
            $statusKpis = [
                [
                    'label' => 'Total Orders',
                    'value' => number_format($totalOrders),
                    'sub'   => 'All statuses',
                    'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                    'bg'    => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                    'pct'   => 100,
                ],
                [
                    'label' => 'Pending',
                    'value' => number_format($pendingOrders),
                    'sub'   => 'Awaiting action',
                    'from'  => 'from-amber-500', 'to' => 'to-yellow-600',
                    'bg'    => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100) : 0,
                ],
                [
                    'label' => 'Processing',
                    'value' => number_format($processingOrders),
                    'sub'   => 'In progress',
                    'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                    'bg'    => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                    'pct'   => $totalOrders > 0 ? round(($processingOrders / $totalOrders) * 100) : 0,
                ],
                [
                    'label' => 'Completed',
                    'value' => number_format($completedOrders),
                    'sub'   => 'Successfully fulfilled',
                    'from'  => 'from-emerald-500', 'to' => 'to-green-600',
                    'bg'    => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                    'pct'   => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0,
                ],
                [
                    'label' => 'Cancelled',
                    'value' => number_format($cancelledOrders),
                    'sub'   => 'Not fulfilled',
                    'from'  => 'from-rose-500', 'to' => 'to-red-600',
                    'bg'    => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
                    'pct'   => $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100) : 0,
                ],
            ];
        @endphp --}}

        {{-- <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">
            @foreach($statusKpis as $kpi)
                <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                        flex items-center justify-center shadow-md">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $kpi['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">{{ $kpi['label'] }}</h4>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">{{ $kpi['sub'] }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                     text-white text-[10px] font-semibold opacity-80">
                            {{ $kpi['pct'] }}%
                        </span>
                    </div>
                    <div class="relative mt-2 pl-2">
                        <h2 class="text-2xl font-bold tracking-tight
                                   bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none">
                            {{ $kpi['value'] }}
                        </h2>
                    </div>
                    <div class="relative mt-2">
                        <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}"
                                 style="width: {{ $kpi['pct'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}

        {{-- ==================== KPI ROW 2 — REVENUE ==================== --}}
        @php
            $revenueKpis = [
                [
                    'label' => 'Gross Revenue',
                    'value' => '$' . number_format($grossRevenue, 2),
                    'sub'   => 'Total billed',
                    'from'  => 'from-emerald-500', 'to' => 'to-teal-600',
                    'bg'    => 'from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => 100,
                ],
                [
                    'label' => 'Paid Orders',
                    'value' => number_format($paidOrders),
                    'sub'   => 'Payment confirmed',
                    'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                    'bg'    => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100) : 0,
                ],
                [
                    'label' => 'Unpaid Orders',
                    'value' => number_format($unpaidOrders),
                    'sub'   => 'Awaiting payment',
                    'from'  => 'from-rose-500', 'to' => 'to-red-600',
                    'bg'    => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                    'pct'   => $totalOrders > 0 ? round(($unpaidOrders / $totalOrders) * 100) : 0,
                ],
                [
                    'label' => 'Avg Order Value',
                    'value' => '$' . number_format($averageOrderValue, 2),
                    'sub'   => 'Per order average',
                    'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                    'bg'    => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    'pct'   => 100,
                ],
                [
                    'label' => 'Items Sold',
                    'value' => number_format($totalItems),
                    'sub'   => 'Avg ' . number_format($averageItems, 1) . ' / order',
                    'from'  => 'from-amber-500', 'to' => 'to-orange-600',
                    'bg'    => 'from-amber-50 to-orange-100 dark:from-amber-900/20 dark:to-orange-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                    'pct'   => 100,
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">
            @foreach($revenueKpis as $kpi)
                <div class="kpi-card-2 relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                        flex items-center justify-center shadow-md">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $kpi['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">{{ $kpi['label'] }}</h4>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">{{ $kpi['sub'] }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                     text-white text-[10px] font-semibold opacity-80">
                            {{ $kpi['pct'] }}%
                        </span>
                    </div>
                    <div class="relative mt-2 pl-2">
                        <h2 class="text-2xl font-bold tracking-tight
                                   bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none">
                            {{ $kpi['value'] }}
                        </h2>
                    </div>
                    <div class="relative mt-2">
                        <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}"
                                 style="width: {{ $kpi['pct'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ==================== FILTERS ==================== --}}
        <div class="filter-card bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Filters</h2>
                </div>
                <a href="{{ route('reports.orders') }}"
                   class="text-xs text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form method="GET" action="{{ route('reports.orders') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

                    {{-- Date Range --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Date Range</label>
                        <select name="range" class="filter-select">
                            <option value="today"      {{ request('range') == 'today'      ? 'selected' : '' }}>Today</option>
                            <option value="7days"      {{ request('range') == '7days'      ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30days"     {{ request('range', '30days') == '30days' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="this_month" {{ request('range') == 'this_month' ? 'selected' : '' }}>This month</option>
                            <option value="last_month" {{ request('range') == 'last_month' ? 'selected' : '' }}>Last month</option>
                            <option value="this_year"  {{ request('range') == 'this_year'  ? 'selected' : '' }}>This year</option>
                        </select>
                    </div>

                    {{-- Order Status --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Order Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All statuses</option>
                            <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed"  {{ request('status') == 'completed'  ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Method</label>
                        <select name="payment_method" class="filter-select">
                            <option value="">All methods</option>
                            <option value="cash"   {{ request('payment_method') == 'cash'   ? 'selected' : '' }}>Cash</option>
                            <option value="aba"    {{ request('payment_method') == 'aba'    ? 'selected' : '' }}>ABA</option>
                            <option value="khqr"   {{ request('payment_method') == 'khqr'   ? 'selected' : '' }}>KHQR</option>
                            <option value="wing"   {{ request('payment_method') == 'wing'   ? 'selected' : '' }}>Wing</option>
                            <option value="card"   {{ request('payment_method') == 'card'   ? 'selected' : '' }}>Card</option>
                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Status</label>
                        <select name="payment_status" class="filter-select">
                            <option value="">All payments</option>
                            <option value="paid"    {{ request('payment_status') == 'paid'    ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed"  {{ request('payment_status') == 'failed'  ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    {{-- Keyword --}}
                    <div class="sm:col-span-2 xl:col-span-4">
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Keyword</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                </svg>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                       placeholder="Order ID, customer name, phone, email, address…"
                                       class="filter-select pl-8">
                            </div>
                            <button type="submit"
                                class="action-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                       text-white text-xs font-semibold shadow-sm shadow-indigo-500/20 transition-colors whitespace-nowrap">
                                Apply
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- ==================== ANALYTICS INSIGHTS ==================== --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden"
             style="animation: fadeSlideUp .5s .28s ease both;">
            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order Analytics</h2>
            </div>
            <div class="p-4 sm:p-5">
                <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-6 gap-3">

                    @php
                        $analytics = [
                            [
                                'label' => 'Highest Order',
                                'value' => $highestOrder ? '$' . number_format($highestOrder->total_amount, 2) : '—',
                                'sub'   => $highestOrder ? '#' . $highestOrder->id : 'No data',
                                'from'  => 'from-emerald-500', 'to' => 'to-teal-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                            ],
                            [
                                'label' => 'Lowest Order',
                                'value' => $lowestOrder ? '$' . number_format($lowestOrder->total_amount, 2) : '—',
                                'sub'   => $lowestOrder ? '#' . $lowestOrder->id : 'No data',
                                'from'  => 'from-rose-500', 'to' => 'to-red-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>',
                            ],
                            [
                                'label' => 'Best Customer',
                                'value' => $bestCustomer ? ($bestCustomer->user->full_name ?? '—') : '—',
                                'sub'   => $bestCustomer ? number_format($bestCustomer->total_orders) . ' orders' : 'No data',
                                'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                            ],
                            [
                                'label' => 'Best Province',
                                'value' => $topProvince ? $topProvince['province'] : '—',
                                'sub'   => $topProvince ? number_format($topProvince['orders']) . ' orders' : 'No data',
                                'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                            ],
                            // [
                            //     'label' => 'Peak Hour',
                            //     'value' => $peakHour ? (($peakHour->hour % 12 ?: 12) . ($peakHour->hour >= 12 ? 'pm' : 'am')) : '—',
                            //     'sub'   => $peakHour ? number_format($peakHour->total) . ' orders' : 'No data',
                            //     'from'  => 'from-amber-500', 'to' => 'to-yellow-600',
                            //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            // ],
                            // [
                            //     'label' => 'Peak Day',
                            //     'value' => $peakDay ? \Carbon\Carbon::parse($peakDay->day)->format('d M') : '—',
                            //     'sub'   => $peakDay ? number_format($peakDay->total) . ' orders' : 'No data',
                            //     'from'  => 'from-orange-500', 'to' => 'to-amber-600',
                            //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                            // ],
                            [
                                'label' => 'Top Payment',
                                'value' => $topPayment ? strtoupper($topPayment->payment_method) : '—',
                                'sub'   => $topPayment ? number_format($topPayment->total) . ' orders' : 'No data',
                                'from'  => 'from-cyan-500', 'to' => 'to-blue-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                            ],
                            [
                                'label' => 'Top Product',
                                'value' => $topProduct ? $topProduct->name : '—',
                                'sub'   => $topProduct ? number_format($topProduct->total_qty) . ' units' : 'No data',
                                'from'  => 'from-purple-500', 'to' => 'to-pink-600',
                                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                            ],
                        ];
                    @endphp

                    @foreach($analytics as $a)
                        <div class="analytic-card relative overflow-hidden rounded-xl
                                    bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700
                                    p-3 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $a['from'] }} {{ $a['to'] }}
                                        flex items-center justify-center shadow-sm mb-2">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $a['icon'] !!}
                                </svg>
                            </div>
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $a['label'] }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5 truncate" title="{{ $a['value'] }}">{{ $a['value'] }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $a['sub'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== ORDERS TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Orders List</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($orders->total()) }} orders found — click "View" for full order details.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full
                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                             text-[11px] font-semibold">
                    {{ $range }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Address</th>
                            <th class="px-6 py-3 text-center">Items</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                            <th class="px-6 py-3 text-center">Payment</th>
                            <th class="px-6 py-3 text-center">Pay Status</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Date</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody id="ordersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                            @php
                                $payment   = optional($order->payment);
                                $itemCount = $order->orderItems->sum('qty');
                                $initials  = strtoupper(substr($order->user->full_name ?? 'U', 0, 1));
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- ID --}}
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#{{ $order->id }}</span>
                                </td>

                                {{-- Customer --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                                    flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-sm">
                                            {{ $initials }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $order->user->full_name ?? '—' }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                                {{ $order->user->phone ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Address --}}
                                <td class="px-6 py-4 max-w-[180px]">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $order->delivery_address }}
                                    </p>
                                </td>

                                {{-- Items --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $itemCount }}
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </span>
                                </td>

                                {{-- Payment Method --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                        {{ strtoupper($order->payment_method) }}
                                    </span>
                                </td>

                                {{-- Payment Status --}}
                                <td class="px-6 py-4 text-center">
                                    @if($payment->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                     bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                        </span>
                                    @elseif($payment->payment_status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                     bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                     bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Failed
                                        </span>
                                    @endif
                                </td>

                                {{-- Order Status --}}
                                <td class="px-6 py-4 text-center">
                                    @switch($order->status)
                                        @case('completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completed
                                            </span>
                                            @break
                                        @case('processing')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Processing
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Cancelled
                                            </span>
                                    @endswitch
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-500/10
                                                    flex items-center justify-center flex-shrink-0">
                                            <svg class="w-3 h-3 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-xs font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $order->created_at->format('d M Y') }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ $order->created_at->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="openOrderModal({{ $order->id }})"
                                        class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                               border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                               text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                               hover:text-indigo-600 dark:hover:text-indigo-400
                                               hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No orders found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($orders->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->firstItem() }}–{{ $orders->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($orders->total()) }}</span>
                        results
                    @else
                        No records found
                    @endif
                </p>

                @if($orders->hasPages())
                    <nav class="flex items-center gap-1">
                        @if($orders->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
                            @if($page == $orders->currentPage())
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                             bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                          text-sm font-medium text-gray-500 dark:text-gray-400
                                          hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>

    </div>{{-- /space-y-4 --}}


    {{-- ==================== ORDER DETAILS MODAL ==================== --}}
    <div id="orderModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                flex items-center justify-center shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Order Details</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Complete order information</p>
                    </div>
                </div>
                <button type="button" onclick="closeOrderModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full
                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                           text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="orderModalBody" class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center justify-center py-20">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Export Data</h3>
                </div>
                <button onclick="closeExportModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Choose your preferred export format:</p>

                <a href="#"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                          hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-emerald-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">CSV File</p>
                            <p class="text-[11px] text-gray-400">Spreadsheet compatible</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

                <a href="#"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-red-50 dark:hover:bg-red-500/10
                          hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-red-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">PDF File</p>
                            <p class="text-[11px] text-gray-400">Print-ready document</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
            </div>
            <div class="px-6 pb-6">
                <button onclick="closeExportModal()"
                    class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                           text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script defer>
        // ── Order Modal ────────────────────────────────────────────────
        const orderModal     = document.getElementById('orderModal');
        const orderModalBody = document.getElementById('orderModalBody');

        async function openOrderModal(id) {
            orderModal.classList.remove('hidden');
            orderModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            orderModalBody.innerHTML = `
                <div class="flex items-center justify-center py-20">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
                    </div>
                </div>`;

            try {
                const res  = await fetch(`/admin/reports/orders/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await res.text();
                orderModalBody.innerHTML = html;
            } catch {
                orderModalBody.innerHTML = `
                    <div class="flex items-center justify-center py-20">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <p class="text-sm text-red-500">Failed to load details. Please try again.</p>
                        </div>
                    </div>`;
            }
        }

        function closeOrderModal() {
            orderModal.classList.add('hidden');
            orderModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        orderModal.addEventListener('click', e => { if (e.target === orderModal) closeOrderModal(); });

        // ── Export Modal ───────────────────────────────────────────────
        const exportModal = document.getElementById('exportModal');

        function openExportModal() {
            exportModal.classList.remove('hidden');
            exportModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeExportModal() {
            exportModal.classList.add('hidden');
            exportModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        exportModal.addEventListener('click', e => { if (e.target === exportModal) closeExportModal(); });

        // ── ESC closes both ────────────────────────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeOrderModal(); closeExportModal(); }
        });
    </script>
    @endpush

@endsection