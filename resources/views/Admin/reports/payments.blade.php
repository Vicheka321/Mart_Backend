@extends('layouts.app')

@section('title', 'Payment Report')

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
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
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
        .kpi-card:nth-child(6) { animation-delay: .30s; }
        .kpi-card:nth-child(7) { animation-delay: .35s; }
        .kpi-card:nth-child(8) { animation-delay: .40s; }

        .insight-card { animation: fadeSlideUp .5s ease both; }
        .insight-card:nth-child(1) { animation-delay: .30s; }
        .insight-card:nth-child(2) { animation-delay: .35s; }
        .insight-card:nth-child(3) { animation-delay: .40s; }
        .insight-card:nth-child(4) { animation-delay: .45s; }
        .insight-card:nth-child(5) { animation-delay: .50s; }
        .insight-card:nth-child(6) { animation-delay: .55s; }
        .insight-card:nth-child(7) { animation-delay: .60s; }
        .insight-card:nth-child(8) { animation-delay: .65s; }

        .filter-card { animation: fadeSlideUp .45s .08s ease both; }
        .chart-card  { animation: fadeSlideUp .5s ease both; }
        .chart-card:nth-child(1) { animation-delay: .28s; }
        .chart-card:nth-child(2) { animation-delay: .33s; }
        .chart-card:nth-child(3) { animation-delay: .38s; }
        .chart-card:nth-child(4) { animation-delay: .43s; }
        .table-card  { animation: fadeSlideUp .5s .38s ease both; }

        #paymentsTableBody tr { animation: rowSlideIn .35s ease both; }
        #paymentsTableBody tr:nth-child(1)  { animation-delay: .40s; }
        #paymentsTableBody tr:nth-child(2)  { animation-delay: .45s; }
        #paymentsTableBody tr:nth-child(3)  { animation-delay: .50s; }
        #paymentsTableBody tr:nth-child(4)  { animation-delay: .55s; }
        #paymentsTableBody tr:nth-child(5)  { animation-delay: .60s; }
        #paymentsTableBody tr:nth-child(6)  { animation-delay: .65s; }
        #paymentsTableBody tr:nth-child(7)  { animation-delay: .70s; }
        #paymentsTableBody tr:nth-child(8)  { animation-delay: .75s; }
        #paymentsTableBody tr:nth-child(9)  { animation-delay: .80s; }
        #paymentsTableBody tr:nth-child(10) { animation-delay: .85s; }

        .progress-bar { animation: progressFill .9s .65s cubic-bezier(.4,0,.2,1) both; }

        #exportModal.flex { animation: overlayIn .2s ease; }
        .modal-inner      { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

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
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Payment Report</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Analyze payment performance, gateways, transactions and success rates.
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

        {{-- ==================== KPI CARDS ==================== --}}
        @php
            $kpis = [
                [
                    'label' => 'Total Payments',
                    'value' => number_format($totalPayments),
                    'sub'   => 'All transactions',
                    'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                    'bg'    => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                    'pct'   => 100,
                ],
                [
                    'label' => 'Successful',
                    'value' => number_format($successfulPayments),
                    'sub'   => 'Paid & confirmed',
                    'from'  => 'from-emerald-500', 'to' => 'to-green-600',
                    'bg'    => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => $totalPayments > 0 ? round(($successfulPayments / $totalPayments) * 100) : 0,
                ],
                [
                    'label' => 'Pending',
                    'value' => number_format($pendingPayments),
                    'sub'   => 'Awaiting confirmation',
                    'from'  => 'from-amber-500', 'to' => 'to-yellow-600',
                    'bg'    => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => $totalPayments > 0 ? round(($pendingPayments / $totalPayments) * 100) : 0,
                ],
                [
                    'label' => 'Failed',
                    'value' => number_format($failedPayments),
                    'sub'   => 'Payment failed',
                    'from'  => 'from-rose-500', 'to' => 'to-red-600',
                    'bg'    => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => $totalPayments > 0 ? round(($failedPayments / $totalPayments) * 100) : 0,
                ],
                [
                    'label' => 'Total Revenue',
                    'value' => '$' . number_format($totalRevenue, 2),
                    'sub'   => 'From paid transactions',
                    'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                    'bg'    => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct'   => 100,
                ],
                // [
                //     'label' => 'ABA Revenue',
                //     'value' => '$' . number_format($abaRevenue, 2),
                //     'sub'   => 'ABA Pay gateway',
                //     'from'  => 'from-cyan-500', 'to' => 'to-blue-600',
                //     'bg'    => 'from-cyan-50 to-blue-100 dark:from-cyan-900/20 dark:to-blue-900/20',
                //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>',
                //     'pct'   => $totalRevenue > 0 ? round(($abaRevenue / $totalRevenue) * 100) : 0,
                // ],
                [
                    'label' => 'KHQR Revenue',
                    'value' => '$' . number_format($khqrRevenue, 2),
                    'sub'   => 'KHQR QR gateway',
                    'from'  => 'from-purple-500', 'to' => 'to-violet-600',
                    'bg'    => 'from-purple-50 to-violet-100 dark:from-purple-900/20 dark:to-violet-900/20',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                    'pct'   => $totalRevenue > 0 ? round(($khqrRevenue / $totalRevenue) * 100) : 0,
                ],
                // [
                //     'label' => 'Avg Payment',
                //     'value' => '$' . number_format($averagePayment, 2),
                //     'sub'   => 'Per paid transaction',
                //     'from'  => 'from-orange-500', 'to' => 'to-amber-600',
                //     'bg'    => 'from-orange-50 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20',
                //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                //     'pct'   => 100,
                // ],
            ];
        @endphp

        <div class="grid grid-cols-2 xl:grid-cols-6 gap-3">
            @foreach($kpis as $kpi)
                <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                        flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $kpi['icon'] !!}
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight truncate">{{ $kpi['label'] }}</h4>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight truncate">{{ $kpi['sub'] }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full flex-shrink-0 ml-1
                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                     text-white text-[10px] font-semibold opacity-80">
                            {{ $kpi['pct'] }}%
                        </span>
                    </div>
                    <div class="relative mt-2 pl-2">
                        <h2 class="text-xl font-bold tracking-tight
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
                <a href="{{ route('reports.payments') }}"
                   class="text-xs text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form method="GET" action="{{ route('reports.payments') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Method</label>
                        <select name="payment_method" class="filter-select">
                            <option value="">All methods</option>
                            <option value="aba"  @selected(request('payment_method') == 'aba')>ABA</option>
                            <option value="khqr" @selected(request('payment_method') == 'khqr')>KHQR</option>
                            <option value="cash" @selected(request('payment_method') == 'cash')>Cash</option>
                            <option value="wing" @selected(request('payment_method') == 'wing')>Wing</option>
                            <option value="card" @selected(request('payment_method') == 'card')>Card</option>
                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Status</label>
                        <select name="payment_status" class="filter-select">
                            <option value="">All statuses</option>
                            <option value="paid"    @selected(request('payment_status') == 'paid')>Paid</option>
                            <option value="pending" @selected(request('payment_status') == 'pending')>Pending</option>
                            <option value="failed"  @selected(request('payment_status') == 'failed')>Failed</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Sort By</label>
                        <select name="sort" class="filter-select">
                            <option value="">Latest first</option>
                            <option value="highest" @selected(request('sort') == 'highest')>Highest Amount</option>
                            <option value="lowest"  @selected(request('sort') == 'lowest')>Lowest Amount</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Date Range</label>
                        <div class="flex gap-1.5">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                   class="filter-select" placeholder="From">
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                   class="filter-select" placeholder="To">
                        </div>
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
                                       placeholder="Transaction ID, customer name, phone, email…"
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

        {{-- ==================== INSIGHTS STRIP ==================== --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden"
             style="animation: fadeSlideUp .5s .26s ease both;">
            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Insights</h2>
            </div>
            <div class="p-4 sm:p-5">
                @php
                    $successRate = $totalPayments > 0
                        ? round(($successfulPayments / $totalPayments) * 100, 1) : 0;

                    $insights = [
                        [
                            'label' => 'Highest Transaction',
                            'value' => '$' . number_format($highestTransaction->amount ?? 0, 2),
                            'sub'   => $highestTransaction->order->user->full_name ?? 'N/A',
                            'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                        ],
                        [
                            'label' => 'Largest Paid',
                            'value' => '$' . number_format($largestPaid->amount ?? 0, 2),
                            'sub'   => $largestPaid->order->user->full_name ?? 'N/A',
                            'from'  => 'from-emerald-500', 'to' => 'to-teal-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'label' => 'Most Used Method',
                            'value' => strtoupper($mostUsedMethod->payment_method ?? '—'),
                            'sub'   => number_format($mostUsedMethod->total ?? 0) . ' transactions',
                            'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                        ],
                        [
                            'label' => 'Best Revenue Day',
                            'value' => '$' . number_format($highestRevenueDay->revenue ?? 0, 2),
                            'sub'   => $highestRevenueDay->day ?? '—',
                            'from'  => 'from-purple-500', 'to' => 'to-pink-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                        ],
                        [
                            'label' => 'Success Rate',
                            'value' => $successRate . '%',
                            'sub'   => number_format($successfulPayments) . ' paid',
                            'from'  => 'from-emerald-500', 'to' => 'to-green-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                        ],
                        [
                            'label' => 'Pending',
                            'value' => number_format($pendingPayments),
                            'sub'   => 'Awaiting confirmation',
                            'from'  => 'from-amber-500', 'to' => 'to-yellow-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'label' => 'Failed',
                            'value' => number_format($failedPayments),
                            'sub'   => 'Payment failed',
                            'from'  => 'from-rose-500', 'to' => 'to-red-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
                        ],
                        [
                            'label' => 'Avg Payment',
                            'value' => '$' . number_format($averagePayment, 2),
                            'sub'   => 'Per paid transaction',
                            'from'  => 'from-orange-500', 'to' => 'to-amber-600',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                    ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-8 gap-3">
                    @foreach($insights as $ins)
                        <div class="insight-card relative overflow-hidden rounded-xl
                                    bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700
                                    p-3 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $ins['from'] }} {{ $ins['to'] }}
                                        flex items-center justify-center shadow-sm mb-2">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    {!! $ins['icon'] !!}
                                </svg>
                            </div>
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $ins['label'] }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5 truncate" title="{{ $ins['value'] }}">{{ $ins['value'] }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $ins['sub'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== CHARTS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Payment Trend --}}
            <div class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Trend</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Daily successful revenue</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="paymentTrendChart"></canvas></div>
                </div>
            </div>

            {{-- Payment Status --}}
            <div class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Status</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Paid · Pending · Failed</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="paymentStatusChart"></canvas></div>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Methods</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Transactions by gateway</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="paymentMethodChart"></canvas></div>
                </div>
            </div>

            {{-- Revenue by Method --}}
            <div class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue by Method</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Successful payment revenue</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="paymentRevenueChart"></canvas></div>
                </div>
            </div>

        </div>

        {{-- ==================== PAYMENTS TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment Transactions</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($payments->total()) }} transactions found
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Payment</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3 text-center">Method</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                            <th class="px-6 py-3 text-center">Currency</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3">Transaction ID</th>
                            <th class="px-6 py-3 text-center">Paid At</th>
                        </tr>
                    </thead>

                    <tbody id="paymentsTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            @php
                                $user = optional(optional($payment->order)->user);
                                $initials = strtoupper(substr($user->full_name ?? 'U', 0, 1));

                                $methodColors = [
                                    'aba'  => ['from' => 'from-blue-500',   'to' => 'to-indigo-600', 'bg' => 'bg-blue-50 dark:bg-blue-500/10',   'text' => 'text-blue-600 dark:text-blue-400'],
                                    'khqr' => ['from' => 'from-purple-500', 'to' => 'to-violet-600', 'bg' => 'bg-purple-50 dark:bg-purple-500/10','text' => 'text-purple-600 dark:text-purple-400'],
                                    'cash' => ['from' => 'from-emerald-500','to' => 'to-green-600',  'bg' => 'bg-emerald-50 dark:bg-emerald-500/10','text' => 'text-emerald-600 dark:text-emerald-400'],
                                    'wing' => ['from' => 'from-orange-500', 'to' => 'to-amber-600',  'bg' => 'bg-orange-50 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400'],
                                    'card' => ['from' => 'from-cyan-500',   'to' => 'to-blue-600',   'bg' => 'bg-cyan-50 dark:bg-cyan-500/10',    'text' => 'text-cyan-600 dark:text-cyan-400'],
                                ];
                                $mc = $methodColors[strtolower($payment->payment_method)] ?? ['from' => 'from-gray-400', 'to' => 'to-gray-500', 'bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-600 dark:text-gray-400'];

                                $statusConfig = [
                                    'paid'    => ['dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'label' => 'Paid'],
                                    'pending' => ['dot' => 'bg-amber-500',   'bg' => 'bg-amber-50 dark:bg-amber-500/10',     'text' => 'text-amber-600 dark:text-amber-400',     'label' => 'Pending'],
                                    'failed'  => ['dot' => 'bg-rose-500',    'bg' => 'bg-rose-50 dark:bg-rose-500/10',       'text' => 'text-rose-600 dark:text-rose-400',       'label' => 'Failed'],
                                ];
                                $sc = $statusConfig[$payment->payment_status] ?? $statusConfig['pending'];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Payment ID --}}
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#PAY-{{ $payment->id }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Order #{{ $payment->order_id }}</p>
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
                                                {{ $user->full_name ?? '—' }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                                {{ $user->phone ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Method --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $mc['bg'] }} {{ $mc['text'] }}">
                                        {{ strtoupper($payment->payment_method) }}
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>

                                {{-- Currency --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        {{ strtoupper($payment->currency ?? 'USD') }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $sc['bg'] }} {{ $sc['text'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                        {{ $sc['label'] }}
                                    </span>
                                </td>

                                {{-- Transaction ID --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs text-gray-600 dark:text-gray-400">
                                        {{ $payment->transaction_id ?? '—' }}
                                    </span>
                                </td>

                                {{-- Paid At --}}
                                <td class="px-6 py-4 text-center">
                                    @if($payment->paid_at)
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
                                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                                                </p>
                                                <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No payment transactions found.</p>
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
                    @if($payments->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $payments->firstItem() }}–{{ $payments->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($payments->total()) }}</span>
                        results
                    @else
                        No records found
                    @endif
                </p>

                @if($payments->hasPages())
                    <nav class="flex items-center gap-1">
                        @if($payments->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $payments->previousPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        @foreach($payments->getUrlRange(max(1, $payments->currentPage()-2), min($payments->lastPage(), $payments->currentPage()+2)) as $page => $url)
                            @if($page == $payments->currentPage())
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

                        @if($payments->hasMorePages())
                            <a href="{{ $payments->nextPageUrl() }}"
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
                <a href="{{ route('reports.payments.export.csv') }}"
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
                <a href="{{ route('reports.payments.export.pdf') }}"
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script defer>
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
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeExportModal(); });

        // ── Charts ─────────────────────────────────────────────────────
        const baseOpts = (legend = true, axis = false) => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: legend, position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } }
            },
            ...(axis ? {
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 11 } } },
                    x: { ticks: { font: { size: 11 } } }
                }
            } : {}),
        });

        window.addEventListener('load', () => {

            // Payment Trend — area line
            new Chart(document.getElementById('paymentTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($paymentTrend->pluck('day')),
                    datasets: [{
                        data: @json($paymentTrend->pluck('revenue')),
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99,102,241,.1)',
                        fill: true,
                        tension: .4,
                        pointRadius: 3,
                        pointBackgroundColor: '#6366F1',
                    }]
                },
                options: { ...baseOpts(false, true) },
            });

            // Payment Status — doughnut
            new Chart(document.getElementById('paymentStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($statusChart)),
                    datasets: [{
                        data: @json(array_values($statusChart)),
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0,
                    }]
                },
                options: baseOpts(),
            });

            // Payment Methods — horizontal bar
            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'bar',
                data: {
                    labels: @json($methodChart->pluck('payment_method')),
                    datasets: [{
                        label: 'Transactions',
                        data: @json($methodChart->pluck('total')),
                        backgroundColor: '#6366F1',
                        borderRadius: 6,
                    }]
                },
                options: { ...baseOpts(false, true), indexAxis: 'y' },
            });

            // Revenue by Method — vertical bar
            new Chart(document.getElementById('paymentRevenueChart'), {
                type: 'bar',
                data: {
                    labels: @json($revenueChart->pluck('payment_method')),
                    datasets: [{
                        label: 'Revenue ($)',
                        data: @json($revenueChart->pluck('revenue')),
                        backgroundColor: '#8B5CF6',
                        borderRadius: 6,
                    }]
                },
                options: { ...baseOpts(false, true) },
            });
        });
    </script>
    @endpush

@endsection