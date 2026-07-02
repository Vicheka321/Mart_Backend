@extends('layouts.app')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ── Entry animations ─────────────────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.85); opacity: 0; }
            70%  { transform: scale(1.05); }
            100% { transform: scale(1);    opacity: 1; }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes chartReveal {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── KPI cards ─────────────────────────────────────────────── */
        .kpi-card { animation: fadeSlideUp .5s ease both; transition: box-shadow .2s ease, transform .2s ease; }
        .kpi-card:nth-child(1) { animation-delay: .04s; }
        .kpi-card:nth-child(2) { animation-delay: .09s; }
        .kpi-card:nth-child(3) { animation-delay: .14s; }
        .kpi-card:nth-child(4) { animation-delay: .19s; }
        .kpi-card:nth-child(5) { animation-delay: .24s; }
        .kpi-card:nth-child(6) { animation-delay: .29s; }
        .kpi-card:nth-child(7) { animation-delay: .34s; }
        .kpi-card:nth-child(8) { animation-delay: .39s; }

        .filter-card { animation: fadeSlideUp .45s .22s ease both; }

        /* ── Chart cards ───────────────────────────────────────────── */
        .chart-card { animation: chartReveal .6s cubic-bezier(.22,1,.36,1) both; }
        .chart-card:nth-child(1) { animation-delay: .36s; }
        .chart-card:nth-child(2) { animation-delay: .46s; }
        .chart-card:nth-child(3) { animation-delay: .56s; }
        .chart-card:nth-child(4) { animation-delay: .66s; }

        /* ── Insight spotlight cards ───────────────────────────────── */
        .spot-card { animation: fadeSlideUp .5s ease both; transition: box-shadow .2s ease, transform .2s ease; }
        .spot-card:nth-child(1) { animation-delay: .42s; }
        .spot-card:nth-child(2) { animation-delay: .47s; }
        .spot-card:nth-child(3) { animation-delay: .52s; }
        .spot-card:nth-child(4) { animation-delay: .57s; }
        .spot-card:nth-child(5) { animation-delay: .62s; }
        .spot-card:nth-child(6) { animation-delay: .67s; }
        .spot-card:nth-child(7) { animation-delay: .72s; }
        .spot-card:nth-child(8) { animation-delay: .77s; }

        .table-card { animation: fadeSlideUp .5s .56s ease both; }

        /* ── Table rows ────────────────────────────────────────────── */
        #customersTableBody tr { animation: rowSlideIn .35s ease both; }
        #customersTableBody tr:nth-child(1)  { animation-delay: .60s; }
        #customersTableBody tr:nth-child(2)  { animation-delay: .65s; }
        #customersTableBody tr:nth-child(3)  { animation-delay: .70s; }
        #customersTableBody tr:nth-child(4)  { animation-delay: .75s; }
        #customersTableBody tr:nth-child(5)  { animation-delay: .80s; }
        #customersTableBody tr:nth-child(6)  { animation-delay: .85s; }
        #customersTableBody tr:nth-child(7)  { animation-delay: .90s; }
        #customersTableBody tr:nth-child(8)  { animation-delay: .95s; }
        #customersTableBody tr:nth-child(9)  { animation-delay: 1.00s; }
        #customersTableBody tr:nth-child(10) { animation-delay: 1.05s; }

        .progress-bar { animation: progressFill .9s .65s cubic-bezier(.4,0,.2,1) both; }
        .count-done   { animation: numberPop .35s cubic-bezier(.34,1.56,.64,1) both; }

        /* ── Buttons ───────────────────────────────────────────────── */
        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        /* ── Filter selects ────────────────────────────────────────── */
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
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Customer Report</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                    Growth, loyalty, engagement and purchasing behavior analytics.
                </p>
            </div>

            <div class="flex gap-2">
                {{-- <a href="{{ route('reports.customers.export.csv') }}"
                   class="action-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                          border border-emerald-200 dark:border-emerald-500/30
                          bg-emerald-50 dark:bg-emerald-500/10
                          text-emerald-600 dark:text-emerald-400
                          hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('reports.customers.export.pdf') }}"
                   class="action-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                          border border-red-200 dark:border-red-500/30
                          bg-red-50 dark:bg-red-500/10
                          text-red-600 dark:text-red-400
                          hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    Export PDF
                </a> --}}

                <button type="button" onclick="openExportModal()"
                    class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium
                            rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                            text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
            </div>
        </div>

        {{-- ==================== KPI CARDS ==================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            @php
                $kpis = [
                    [
                        'label' => 'Total',
                        'value' => number_format($totalCustomers),
                        'sub'   => 'All customers',
                        'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                        'bg'    => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                        'pct'   => 100,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    ],
                    [
                        'label' => 'Active',
                        'value' => number_format($activeCustomers),
                        'sub'   => 'Last 30 days',
                        'from'  => 'from-emerald-500', 'to' => 'to-green-600',
                        'bg'    => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                        'pct'   => $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                    ],
                    [
                        'label' => 'New',
                        'value' => number_format($newCustomers),
                        'sub'   => 'Last 30 days',
                        'from'  => 'from-blue-500', 'to' => 'to-sky-600',
                        'bg'    => 'from-blue-50 to-sky-100 dark:from-blue-900/20 dark:to-sky-900/20',
                        'pct'   => $totalCustomers > 0 ? round(($newCustomers / $totalCustomers) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
                    ],
                    // [
                    //     'label' => 'VIP',
                    //     'value' => number_format($vipCustomers),
                    //     'sub'   => '20+ orders',
                    //     'from'  => 'from-violet-500', 'to' => 'to-purple-600',
                    //     'bg'    => 'from-violet-50 to-purple-100 dark:from-violet-900/20 dark:to-purple-900/20',
                    //     'pct'   => $totalCustomers > 0 ? round(($vipCustomers / $totalCustomers) * 100) : 0,
                    //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                    // ],
                    // [
                    //     'label' => 'Loyal',
                    //     'value' => number_format($loyalCustomers),
                    //     'sub'   => '10+ orders',
                    //     'from'  => 'from-pink-500', 'to' => 'to-rose-600',
                    //     'bg'    => 'from-pink-50 to-rose-100 dark:from-pink-900/20 dark:to-rose-900/20',
                    //     'pct'   => $totalCustomers > 0 ? round(($loyalCustomers / $totalCustomers) * 100) : 0,
                    //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                    // ],
                    [
                        'label' => 'Returning',
                        'value' => number_format($returningCustomers),
                        'sub'   => '2+ orders',
                        'from'  => 'from-cyan-500', 'to' => 'to-teal-600',
                        'bg'    => 'from-cyan-50 to-teal-100 dark:from-cyan-900/20 dark:to-teal-900/20',
                        'pct'   => $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                    ],
                    [
                        'label' => 'Inactive',
                        'value' => number_format($inactiveCustomers),
                        'sub'   => 'No orders 90d',
                        'from'  => 'from-red-500', 'to' => 'to-rose-600',
                        'bg'    => 'from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20',
                        'pct'   => $totalCustomers > 0 ? round(($inactiveCustomers / $totalCustomers) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>',
                    ],
                    [
                        'label' => 'Retention',
                        'value' => $retentionRate . '%',
                        'sub'   => 'Returning / Total',
                        'from'  => 'from-orange-500', 'to' => 'to-amber-600',
                        'bg'    => 'from-orange-50 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20',
                        'pct'   => (int) $retentionRate,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    ],
                ];
            @endphp

            @foreach($kpis as $kpi)
                <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                    <div class="absolute -top-8 -right-8 w-20 h-20 rounded-full
                                bg-gradient-to-br {{ $kpi['bg'] }}"></div>

                    <div class="relative flex items-start justify-between mb-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                    flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                {!! $kpi['icon'] !!}
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                     text-white text-[9px] font-semibold opacity-80">
                            {{ $kpi['pct'] }}%
                        </span>
                    </div>

                    <div class="relative">
                        <h2 class="text-xl font-bold tracking-tight
                                   bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                   bg-clip-text text-transparent leading-none">
                            {{ $kpi['value'] }}
                        </h2>
                        <p class="text-[10px] font-semibold text-gray-900 dark:text-white mt-0.5 leading-tight">
                            {{ $kpi['label'] }}
                        </p>
                        <p class="text-[9px] text-gray-400 dark:text-gray-500">{{ $kpi['sub'] }}</p>
                    </div>

                    <div class="relative mt-2">
                        <div class="h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="progress-bar h-full rounded-full
                                        bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}"
                                 style="width: {{ $kpi['pct'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ==================== FILTERS ==================== --}}
        <div class="filter-card bg-white dark:bg-gray-800
                    border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700
                        flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600
                                flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707
                                     L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21
                                     v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Filters</h2>
                </div>
                <a href="{{ route('reports.customers') }}"
                   class="text-xs text-gray-400 dark:text-gray-500
                          hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form action="{{ route('reports.customers') }}" method="GET" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3">

                    {{-- Search --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            {{-- <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                            </svg> --}}
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                   placeholder="Name, email, phone…"
                                   class="filter-select pl-8">
                        </div>
                    </div>

                    {{-- Province --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Province</label>
                        <select name="province" class="filter-select">
                            <option value="">All provinces</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}"
                                        {{ request('province') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Customer Type --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Customer Type</label>
                        <select name="customer_type" class="filter-select">
                            <option value="">All types</option>
                            <option value="new"       {{ request('customer_type') == 'new'       ? 'selected' : '' }}>New</option>
                            <option value="returning" {{ request('customer_type') == 'returning' ? 'selected' : '' }}>Returning</option>
                            {{-- <option value="vip"       {{ request('customer_type') == 'vip'       ? 'selected' : '' }}>VIP</option> --}}
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All statuses</option>
                            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Sort By</label>
                        <select name="sort" class="filter-select">
                            <option value="">Latest</option>
                            <option value="highest_spent" {{ request('sort') == 'highest_spent' ? 'selected' : '' }}>Highest Spending</option>
                            <option value="most_orders"   {{ request('sort') == 'most_orders'   ? 'selected' : '' }}>Most Orders</option>
                            <option value="latest"        {{ request('sort') == 'latest'        ? 'selected' : '' }}>Recently Joined</option>
                        </select>
                    </div>

                </div>

                {{-- Date range row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Join From</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="filter-select">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Join To</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="filter-select">
                    </div>
                </div>

                <div class="flex justify-end mt-3">
                    <button type="submit"
                            class="action-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                   text-white text-xs font-semibold shadow-sm shadow-indigo-500/20 transition-colors">
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- ==================== CHARTS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

            {{-- Customer Growth --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer Growth</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">New registrations over time</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-indigo-50 dark:bg-indigo-500/10
                                 text-indigo-600 dark:text-indigo-400
                                 text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20">
                        Line
                    </span>
                </div>
                <div class="h-52"><canvas id="growthChart"></canvas></div>
            </div>

            {{-- New vs Returning Donut --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">New vs Returning</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Customer segmentation</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-emerald-50 dark:bg-emerald-500/10
                                 text-emerald-600 dark:text-emerald-400
                                 text-xs font-semibold border border-emerald-100 dark:border-emerald-500/20">
                        Donut
                    </span>
                </div>
                <div class="h-52"><canvas id="returnChart"></canvas></div>
            </div>

            {{-- Province Horizontal Bar --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customers by Province</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Top 10 provinces</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-violet-50 dark:bg-violet-500/10
                                 text-violet-600 dark:text-violet-400
                                 text-xs font-semibold border border-violet-100 dark:border-violet-500/20">
                        Horizontal
                    </span>
                </div>
                <div class="h-52"><canvas id="provinceChart"></canvas></div>
            </div>

            {{-- Join Trend --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Join Trend</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Registration trend over time</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-pink-50 dark:bg-pink-500/10
                                 text-pink-600 dark:text-pink-400
                                 text-xs font-semibold border border-pink-100 dark:border-pink-500/20">
                        Area
                    </span>
                </div>
                <div class="h-52"><canvas id="joinTrendChart"></canvas></div>
            </div>
        </div>

        {{-- ==================== CUSTOMER INSIGHTS ==================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

            {{-- Highest Spending --}}
            <div class="spot-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-4">
                <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full opacity-10
                            bg-gradient-to-br from-emerald-500 to-green-600"></div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600
                                flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                        Highest Spending
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @php $avatar = $highestSpent->avatar ?? null; @endphp
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $highestSpent->full_name }}"
                             class="w-10 h-10 rounded-full object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30
                                    flex items-center justify-center text-xs font-bold
                                    text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                            {{ strtoupper(substr($highestSpent->full_name ?? 'C', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">
                            {{ $highestSpent->full_name ?? '—' }}
                        </p>
                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($highestSpent->orders_sum_total_amount ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Most Orders --}}
            <div class="spot-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-4">
                <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full opacity-10
                            bg-gradient-to-br from-indigo-500 to-violet-600"></div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600
                                flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                        Most Orders
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @php $avatar2 = $mostOrders->avatar ?? null; @endphp
                    @if($avatar2)
                        <img src="{{ $avatar2 }}" alt="{{ $mostOrders->full_name }}"
                             class="w-10 h-10 rounded-full object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30
                                    flex items-center justify-center text-xs font-bold
                                    text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                            {{ strtoupper(substr($mostOrders->full_name ?? 'C', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">
                            {{ $mostOrders->full_name ?? '—' }}
                        </p>
                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($mostOrders->orders_count ?? 0) }} orders
                        </p>
                    </div>
                </div>
            </div>

            {{-- Newest Customer --}}
            <div class="spot-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-4">
                <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full opacity-10
                            bg-gradient-to-br from-blue-500 to-sky-600"></div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600
                                flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                        Newest Customer
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @php $avatar3 = $newestCustomer->avatar ?? null; @endphp
                    @if($avatar3)
                        <img src="{{ $avatar3 }}" alt="{{ $newestCustomer->full_name }}"
                             class="w-10 h-10 rounded-full object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30
                                    flex items-center justify-center text-xs font-bold
                                    text-blue-600 dark:text-blue-400 flex-shrink-0">
                            {{ strtoupper(substr($newestCustomer->full_name ?? 'C', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">
                            {{ $newestCustomer->full_name ?? '—' }}
                        </p>
                        <p class="text-xs font-bold text-blue-600 dark:text-blue-400">
                            {{ optional($newestCustomer->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Top Province --}}
            <div class="spot-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-4">
                <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full opacity-10
                            bg-gradient-to-br from-violet-500 to-purple-600"></div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600
                                flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                        Top Province
                    </p>
                </div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ $topProvince->province ?? '—' }}
                </p>
                <p class="text-lg font-bold text-violet-600 dark:text-violet-400 mt-0.5">
                    {{ number_format($topProvince->total ?? 0) }}
                    <span class="text-xs font-normal text-gray-400">customers</span>
                </p>
            </div>
        </div>

        {{-- ==================== CUSTOMER TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                    border border-gray-100 dark:border-gray-700
                    rounded-2xl overflow-hidden shadow-sm">

            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700
                        flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer List</h2>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($customers->total()) }} customers
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider
                                   text-gray-400 dark:text-gray-500">
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Phone</th>
                            <th class="px-5 py-3">Province</th>
                            <th class="px-5 py-3 text-center">Joined</th>
                            <th class="px-5 py-3 text-center">Orders</th>
                            <th class="px-5 py-3 text-right">Lifetime Value</th>
                            <th class="px-5 py-3 text-right">Avg Order</th>
                            <th class="px-5 py-3 text-center">Type</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody id="customersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($customers as $customer)
                            @php
                                $orderCount = $customer->orders_count ?? 0;
                                $spent      = $customer->orders_sum_total_amount ?? 0;
                                $avgOrder   = $orderCount > 0 ? $spent / $orderCount : 0;

                                [$typeClass, $typeLabel] = match(true) {
                                    $orderCount >= 20 => ['bg-violet-100 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400', 'VIP'],
                                    $orderCount >= 10 => ['bg-pink-100 dark:bg-pink-500/10 text-pink-700 dark:text-pink-400',     'Loyal'],
                                    $orderCount >= 2  => ['bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',     'Returning'],
                                    default           => ['bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',        'New'],
                                };

                                $statusClass = $customer->status == 'active'
                                    ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                                    : 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400';
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Customer --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($customer->avatar)
                                            <img src="{{ $customer->avatar }}" alt="{{ $customer->full_name }}"
                                                 class="w-9 h-9 rounded-full object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30
                                                        flex items-center justify-center text-xs font-bold
                                                        text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                                {{ strtoupper(substr($customer->full_name ?? 'C', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[140px]">
                                                {{ $customer->full_name }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate max-w-[140px]">
                                                {{ $customer->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Phone --}}
                                <td class="px-5 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $customer->phone ?? '—' }}
                                </td>

                                {{-- Province --}}
                                <td class="px-5 py-3.5">
                                    @if($customer->province)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold
                                                     bg-indigo-50 dark:bg-indigo-500/10
                                                     text-indigo-600 dark:text-indigo-400">
                                            {{ $customer->province }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>

                                {{-- Joined --}}
                                <td class="px-5 py-3.5 text-center text-[10px] text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ optional($customer->created_at)->format('d M Y') }}
                                </td>

                                {{-- Orders --}}
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10
                                                 text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($orderCount) }}
                                    </span>
                                </td>

                                {{-- Lifetime Value --}}
                                <td class="px-5 py-3.5 text-right">
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        ${{ number_format($spent, 2) }}
                                    </span>
                                </td>

                                {{-- Avg Order --}}
                                <td class="px-5 py-3.5 text-right text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    ${{ number_format($avgOrder, 2) }}
                                </td>

                                {{-- Type --}}
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                 text-[10px] font-semibold {{ $typeClass }}">
                                        {{ $typeLabel }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                 text-[10px] font-semibold {{ $statusClass }}">
                                        {{ ucfirst($customer->status ?? 'active') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700
                                                    flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No customers found.</p>
                                        <p class="text-xs text-gray-300 dark:text-gray-600">Try adjusting your filters.</p>
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
                    @if($customers->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $customers->firstItem() }}–{{ $customers->lastItem() }}
                        </span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ number_format($customers->total()) }}
                        </span>
                        customers
                    @else
                        No customers found
                    @endif
                </p>

                @if($customers->hasPages())
                    <nav class="flex items-center gap-1">

                        @if($customers->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg
                                         text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700
                                      hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        @foreach(
                            $customers->getUrlRange(
                                max(1, $customers->currentPage() - 2),
                                min($customers->lastPage(), $customers->currentPage() + 2)
                            ) as $page => $url
                        )
                            @if($page == $customers->currentPage())
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center
                                             rounded-lg bg-indigo-600 text-white text-sm font-semibold
                                             shadow-md shadow-indigo-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center
                                          rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400
                                          hover:bg-gray-100 dark:hover:bg-gray-700
                                          hover:text-gray-900 dark:hover:text-white transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700
                                      hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg
                                         text-gray-300 dark:text-gray-600 cursor-not-allowed">
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

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const isDark    = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        const defaults = {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeInOutQuart' },
        };

        const growthLabels   = @json($growthChart->pluck('day'));
        const growthData     = @json($growthChart->pluck('total'));
        const provinceLabels = @json($provinceChart->pluck('province'));
        const provinceData   = @json($provinceChart->pluck('total'));
        const returnLabels   = @json(array_keys($newVsReturning));
        const returnData     = @json(array_values($newVsReturning));

        /* ── Customer Growth Line ─────────────────────────────── */
        new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: growthLabels,
                datasets: [{
                    label: 'Customers',
                    data: growthData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,.10)',
                    fill: true,
                    tension: .45,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#6366f1',
                    borderWidth: 2.5,
                }]
            },
            options: {
                ...defaults,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10 } } }
                }
            }
        });

        /* ── New vs Returning Donut ───────────────────────────── */
        new Chart(document.getElementById('returnChart'), {
            type: 'doughnut',
            data: {
                labels: returnLabels,
                datasets: [{
                    data: returnData,
                    backgroundColor: ['#3b82f6','#10b981'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                ...defaults,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 16,
                            font: { size: 11 },
                            usePointStyle: true,
                            pointStyleWidth: 8,
                        }
                    }
                }
            }
        });

        /* ── Province Horizontal Bar ──────────────────────────── */
        new Chart(document.getElementById('provinceChart'), {
            type: 'bar',
            data: {
                labels: provinceLabels,
                datasets: [{
                    data: provinceData,
                    backgroundColor: 'rgba(139,92,246,.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: '#8b5cf6',
                }]
            },
            options: {
                ...defaults,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10 } } },
                    y: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } }
                }
            }
        });

        /* ── Join Trend Area ──────────────────────────────────── */
        new Chart(document.getElementById('joinTrendChart'), {
            type: 'line',
            data: {
                labels: growthLabels,
                datasets: [{
                    data: growthData,
                    borderColor: '#ec4899',
                    backgroundColor: 'rgba(236,72,153,.10)',
                    fill: true,
                    tension: .45,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#ec4899',
                    borderWidth: 2.5,
                }]
            },
            options: {
                ...defaults,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10 } } }
                }
            }
        });
    });
    </script>
    @endpush

@endsection