@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.82); opacity: 0; }
            70%  { transform: scale(1.06); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.93) translateY(16px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* ── Staggered stat cards ── */
        .stat-card { animation: fadeSlideUp .5s ease both; transition: box-shadow .2s ease, transform .2s ease; }
        .stat-card:nth-child(1) { animation-delay: .06s; }
        .stat-card:nth-child(2) { animation-delay: .14s; }
        .stat-card:nth-child(3) { animation-delay: .22s; }
        .stat-card:nth-child(4) { animation-delay: .30s; }

        .chart-card { animation: fadeSlideUp .55s ease both; }
        .chart-card:nth-child(1) { animation-delay: .36s; }
        .chart-card:nth-child(2) { animation-delay: .46s; }

        .table-card { animation: fadeSlideUp .55s .52s ease both; }

        .progress-bar { animation: progressFill .9s .75s cubic-bezier(.4,0,.2,1) both; }
        .count-done   { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        .data-row { animation: rowSlideIn .35s ease both; }
        .data-row:nth-child(1)  { animation-delay: .56s; }
        .data-row:nth-child(2)  { animation-delay: .62s; }
        .data-row:nth-child(3)  { animation-delay: .68s; }
        .data-row:nth-child(4)  { animation-delay: .74s; }
        .data-row:nth-child(5)  { animation-delay: .80s; }

        #exportModal.flex { animation: overlayIn .2s ease; }
        .modal-inner      { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

        .btn-sm { transition: transform .14s ease, box-shadow .14s ease; }
        .btn-sm:hover  { transform: translateY(-1px); }
        .btn-sm:active { transform: translateY(0); }
    </style>

    <div class="space-y-6">

        {{-- ==================== HEADER ==================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
             style="animation: fadeSlideUp .4s ease both;">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Reports</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Revenue, orders, customers & product analytics.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <input type="date" id="dateFrom"
                    class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700
                           bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                <span class="text-xs text-gray-400">→</span>

                <input type="date" id="dateTo"
                    class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700
                           bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                <button onclick="applyDateFilter()"
                    class="btn-sm px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium shadow-sm transition">
                    Apply
                </button>

                <button onclick="openExportModal()"
                    class="btn-sm inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                           border border-gray-200 dark:border-gray-600
                           bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200
                           hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- ==================== KPI STAT CARDS ==================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            {{-- Revenue --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-indigo-50 via-violet-50 to-purple-100
                            dark:from-indigo-900/20 dark:via-violet-900/20 dark:to-purple-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600
                                    flex items-center justify-center shadow-md shadow-indigo-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Revenue</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Total earned</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400
                                 ring-1 ring-indigo-200 dark:ring-indigo-800 text-[10px] font-semibold">USD</span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent"
                        data-count="{{ (int) $totalRevenue }}" data-prefix="$">$0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-600"
                             style="width: 100%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All time</span>
                        <span class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400">${{ number_format($totalRevenue, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-blue-50 via-sky-50 to-cyan-100
                            dark:from-blue-900/20 dark:via-sky-900/20 dark:to-cyan-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 via-sky-500 to-cyan-600
                                    flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Orders</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">All orders</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">Orders</span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-600 bg-clip-text text-transparent"
                        data-count="{{ $totalOrders }}">0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 via-sky-500 to-cyan-600"
                             style="width: 100%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">Placed</span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalOrders) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Customers --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-emerald-50 via-green-50 to-teal-100
                            dark:from-emerald-900/20 dark:via-green-900/20 dark:to-teal-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Customers</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Registered</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">Users</span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 bg-clip-text text-transparent"
                        data-count="{{ $totalCustomers }}">0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-600"
                             style="width: 100%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All time</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($totalCustomers) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Products --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-100
                            dark:from-orange-900/20 dark:via-amber-900/20 dark:to-yellow-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-600
                                    flex items-center justify-center shadow-md shadow-orange-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Products</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">In catalog</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400
                                 ring-1 ring-orange-200 dark:ring-orange-800 text-[10px] font-semibold">SKUs</span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 bg-clip-text text-transparent"
                        data-count="{{ $totalProducts }}">0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-orange-500 via-amber-500 to-yellow-600"
                             style="width: 100%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">Active</span>
                        <span class="text-[10px] font-semibold text-orange-600 dark:text-orange-400">{{ number_format($totalProducts) }} total</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== CHARTS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">

            {{-- Revenue Trend --}}
            <div class="chart-card xl:col-span-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue Trend</h2>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Daily revenue over selected period</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg
                                    bg-indigo-50 dark:bg-indigo-500/10
                                    text-indigo-600 dark:text-indigo-400
                                    text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20">
                            Area
                        </span>
                    </div>
                    <div id="revenueChart"></div>
                </div>
            </div>

            {{-- Order Status Donut --}}
            <div class="chart-card xl:col-span-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order Status</h2>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Distribution by status</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg
                                    bg-emerald-50 dark:bg-emerald-500/10
                                    text-emerald-600 dark:text-emerald-400
                                    text-xs font-semibold border border-emerald-100 dark:border-emerald-500/20">
                            Donut
                        </span>
                    </div>
                    <div id="statusChart"></div>

                    {{-- Legend --}}
                    <div class="mt-3 space-y-2">
                        @php
                            $statuses = [
                                ['label' => 'Pending',    'color' => '#f59e0b', 'value' => $pendingOrders],
                                ['label' => 'Processing', 'color' => '#3b82f6', 'value' => $processingOrders],
                                ['label' => 'Completed',  'color' => '#10b981', 'value' => $completedOrders],
                                ['label' => 'Cancelled',  'color' => '#ef4444', 'value' => $cancelledOrders],
                            ];
                            $total = array_sum(array_column($statuses, 'value')) ?: 1;
                        @endphp
                        @foreach($statuses as $s)
                            @php $pct = round(($s['value'] / $total) * 100); @endphp
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background: {{ $s['color'] }}"></span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $s['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <div class="w-12 h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="progress-bar h-full rounded-full"
                                             style="width: {{ $pct }}%; background: {{ $s['color'] }}"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-900 dark:text-white min-w-[28px] text-right">
                                        {{ number_format($s['value']) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TABLES ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

            {{-- Top Products --}}
            <div class="table-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Products</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">By revenue generated</p>
                    </div>
                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-full
                                 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                 text-gray-500 dark:text-gray-400">
                        {{ count($topProducts) }} items
                    </span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topProducts as $i => $item)
                        @php
                            $rev = $item->revenue ?? 0;
                            $maxRev = $topProducts->max('revenue') ?: 1;
                            $pct = round(($rev / $maxRev) * 100);

                            [$barGrad, $rankColor] = match(true) {
                                $i === 0 => ['from-amber-500 to-yellow-500', 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'],
                                $i === 1 => ['from-gray-400 to-gray-500',   'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'],
                                $i === 2 => ['from-orange-500 to-amber-600', 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400'],
                                default  => ['from-indigo-400 to-violet-500', 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400'],
                            };
                        @endphp

                        <div class="data-row flex items-center gap-3 px-4 py-2.5
                                    hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold flex-shrink-0 {{ $rankColor }}">
                                {{ $i + 1 }}
                            </span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->product->name ?? '—' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $barGrad }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-[9px] text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                        {{ number_format($item->total_sold ?? 0) }} sold
                                    </span>
                                </div>
                            </div>

                            <span class="text-xs font-semibold text-gray-900 dark:text-white flex-shrink-0">
                                ${{ number_format($rev, 2) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-400 dark:text-gray-500">No product data available.</div>
                    @endforelse
                </div>
            </div>

            {{-- Latest Orders --}}
            <div class="table-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Latest Orders</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Most recent transactions</p>
                    </div>
                    <a href="{{ route('orders.index') }}"
                       class="text-[10px] font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        View all →
                    </a>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($latestOrders as $order)
                        @php
                            $badge = match($order->status) {
                                'completed'  => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                'pending'    => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                'cancelled'  => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
                            };
                            $fullName = $order->user->full_name ?? 'Customer';
                            $initials = strtoupper(substr($fullName, 0, 1));
                        @endphp

                        <div class="data-row flex items-center gap-3 px-4 py-2.5
                                    hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30
                                        text-indigo-600 dark:text-indigo-400
                                        flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                {{ $initials }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $fullName }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    #{{ $order->id }} · {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                <span class="text-xs font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium {{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-400 dark:text-gray-500">No orders found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Export Report</h3>
                </div>
                <button onclick="closeExportModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Choose your preferred export format:</p>

                {{-- <a href="{{ route('reports.export.excel') ?? '#' }}" --}}
                   class="group flex items-center justify-between px-4 py-3 rounded-xl
                          border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50
                          hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                          hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-emerald-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200
                                      group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">Excel / CSV</p>
                            <p class="text-[11px] text-gray-400">Spreadsheet compatible</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

                {{-- <a href="{{ route('reports.export.pdf') ?? '#' }}" --}}
                   class="group flex items-center justify-between px-4 py-3 rounded-xl
                          border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50
                          hover:bg-red-50 dark:hover:bg-red-500/10
                          hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-red-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200
                                      group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">PDF File</p>
                            <p class="text-[11px] text-gray-400">Print-ready document</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-red-500 transition-colors"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
            </div>

            <div class="px-5 pb-5">
                <button onclick="closeExportModal()"
                    class="w-full py-2 text-sm font-medium rounded-xl
                           border border-gray-200 dark:border-gray-600
                           text-gray-500 dark:text-gray-400
                           hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    <script>
    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER (matches dashboard.blade.php)
    // ══════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(el => {
                const target   = parseInt(el.dataset.count, 10) || 0;
                const prefix   = el.dataset.prefix || '';
                const duration = 1100;
                const start    = performance.now();

                function ease(t) { return 1 - Math.pow(1 - t, 3); }

                (function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const current  = Math.round(ease(progress) * target);
                    el.textContent = prefix + current.toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = prefix + target.toLocaleString();
                        el.classList.add('count-done');
                    }
                })(performance.now());
            });
        }, 320);
    });

    // ══════════════════════════════════════════════════════
    //  EXPORT MODAL
    // ══════════════════════════════════════════════════════
    function openExportModal()  {
        const m = document.getElementById('exportModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeExportModal() {
        const m = document.getElementById('exportModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) closeExportModal();
    });

    // ══════════════════════════════════════════════════════
    //  DATE FILTER
    // ══════════════════════════════════════════════════════
    function applyDateFilter() {
        const from = document.getElementById('dateFrom').value;
        const to   = document.getElementById('dateTo').value;
        if (!from || !to) return;
        const url = new URL(window.location.href);
        url.searchParams.set('from', from);
        url.searchParams.set('to', to);
        window.location.href = url.toString();
    }

    // ══════════════════════════════════════════════════════
    //  APEXCHARTS
    // ══════════════════════════════════════════════════════
    const isDark = document.documentElement.classList.contains('dark');
    const textColor   = isDark ? '#9ca3af' : '#6b7280';
    const borderColor = isDark ? '#374151' : '#e5e7eb';
    const bgColor     = isDark ? '#1f2937' : '#ffffff';

    // Revenue Trend
    const revenueDates  = @json($revenueChart->pluck('date'));
    const revenueValues = @json($revenueChart->pluck('revenue'));

    new ApexCharts(document.querySelector('#revenueChart'), {
        chart: {
            type: 'area',
            height: 220,
            toolbar: { show: false },
            background: 'transparent',
            sparkline: { enabled: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 900,
            }
        },
        series: [{ name: 'Revenue', data: revenueValues }],
        xaxis: {
            categories: revenueDates,
            labels: {
                style: { colors: textColor, fontSize: '10px' },
                rotate: 0,
                hideOverlappingLabels: true,
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: textColor, fontSize: '10px' },
                formatter: val => '$' + (val >= 1000 ? (val / 1000).toFixed(1) + 'K' : val),
            }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.22,
                opacityTo: 0,
                stops: [0, 100],
            }
        },
        colors: ['#6366f1'],
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            xaxis: { lines: { show: false } },
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: val => '$' + Number(val).toLocaleString() }
        },
        markers: { size: 0, hover: { size: 4 } },
        dataLabels: { enabled: false },
    }).render();

    // Order Status Donut
    new ApexCharts(document.querySelector('#statusChart'), {
        chart: {
            type: 'donut',
            height: 180,
            background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
        series: [
            {{ $pendingOrders }},
            {{ $processingOrders }},
            {{ $completedOrders }},
            {{ $cancelledOrders }},
        ],
        colors: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '11px',
                            color: textColor,
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString()
                        }
                    }
                }
            }
        },
        tooltip: { theme: isDark ? 'dark' : 'light' },
        stroke: { width: 0 },
    }).render();
    </script>

@endsection