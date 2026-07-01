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

        /* ── KPI cards staggered ──────────────────────────────────── */
        .kpi-card { animation: fadeSlideUp .5s ease both; transition: box-shadow .2s ease, transform .2s ease; }
        .kpi-card:nth-child(1) { animation-delay: .04s; }
        .kpi-card:nth-child(2) { animation-delay: .09s; }
        .kpi-card:nth-child(3) { animation-delay: .14s; }
        .kpi-card:nth-child(4) { animation-delay: .19s; }
        .kpi-card:nth-child(5) { animation-delay: .24s; }
        .kpi-card:nth-child(6) { animation-delay: .29s; }
        .kpi-card:nth-child(7) { animation-delay: .34s; }
        .kpi-card:nth-child(8) { animation-delay: .39s; }

        .filter-card  { animation: fadeSlideUp .45s .22s ease both; }

        /* ── Chart cards ──────────────────────────────────────────── */
        .chart-card { animation: chartReveal .6s cubic-bezier(.22,1,.36,1) both; }
        .chart-card:nth-child(1) { animation-delay: .36s; }
        .chart-card:nth-child(2) { animation-delay: .46s; }
        .chart-card:nth-child(3) { animation-delay: .56s; }
        .chart-card:nth-child(4) { animation-delay: .66s; }

        /* ── Analytics spotlight cards ────────────────────────────── */
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

        /* ── Table rows ───────────────────────────────────────────── */
        #inventoryTableBody tr { animation: rowSlideIn .35s ease both; }
        #inventoryTableBody tr:nth-child(1)  { animation-delay: .60s; }
        #inventoryTableBody tr:nth-child(2)  { animation-delay: .65s; }
        #inventoryTableBody tr:nth-child(3)  { animation-delay: .70s; }
        #inventoryTableBody tr:nth-child(4)  { animation-delay: .75s; }
        #inventoryTableBody tr:nth-child(5)  { animation-delay: .80s; }
        #inventoryTableBody tr:nth-child(6)  { animation-delay: .85s; }
        #inventoryTableBody tr:nth-child(7)  { animation-delay: .90s; }
        #inventoryTableBody tr:nth-child(8)  { animation-delay: .95s; }
        #inventoryTableBody tr:nth-child(9)  { animation-delay: 1.00s; }
        #inventoryTableBody tr:nth-child(10) { animation-delay: 1.05s; }

        .progress-bar { animation: progressFill .9s .65s cubic-bezier(.4,0,.2,1) both; }
        .count-done   { animation: numberPop .35s cubic-bezier(.34,1.56,.64,1) both; }

        /* ── Buttons ──────────────────────────────────────────────── */
        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        /* ── Filter select ────────────────────────────────────────── */
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
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Inventory Report</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                    Stock levels, inventory value, warehouse health and restock recommendations.
                </p>
            </div>

            <div class="flex gap-2">
                {{-- <a href="#"
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
                <a href="#"
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
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3">
            @php
                $kpis = [
                    [
                        'label' => 'Total Products',
                        'value' => number_format($totalProducts),
                        'sub'   => 'In catalog',
                        'from'  => 'from-indigo-500', 'to' => 'to-violet-600',
                        'bg'    => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                        'pct'   => 100,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>',
                    ],
                    [
                        'label' => 'Active',
                        'value' => number_format($activeProducts),
                        'sub'   => 'In stock',
                        'from'  => 'from-emerald-500', 'to' => 'to-green-600',
                        'bg'    => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                        'pct'   => $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                    ],
                    [
                        'label' => 'Out of Stock',
                        'value' => number_format($outStock),
                        'sub'   => 'Zero quantity',
                        'from'  => 'from-red-500', 'to' => 'to-rose-600',
                        'bg'    => 'from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20',
                        'pct'   => $totalProducts > 0 ? round(($outStock / $totalProducts) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                    ],
                    [
                        'label' => 'Low Stock',
                        'value' => number_format($lowStock),
                        'sub'   => '1–20 units',
                        'from'  => 'from-amber-500', 'to' => 'to-yellow-600',
                        'bg'    => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20',
                        'pct'   => $totalProducts > 0 ? round(($lowStock / $totalProducts) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                    ],
                    [
                        'label' => 'Inventory Value',
                        'value' => '$' . (number_format($inventoryValue) ),
                        'sub'   => 'Cost × qty',
                        'from'  => 'from-blue-500', 'to' => 'to-indigo-600',
                        'bg'    => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                        'pct'   => 100,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    ],
                    // [
                    //     'label' => 'Avg Stock',
                    //     'value' => number_format($averageStock),
                    //     'sub'   => 'Units per SKU',
                    //     'from'  => 'from-cyan-500', 'to' => 'to-sky-600',
                    //     'bg'    => 'from-cyan-50 to-sky-100 dark:from-cyan-900/20 dark:to-sky-900/20',
                    //     'pct'   => 100,
                    //     'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    // ],
                    [
                        'label' => 'Dead Stock',
                        'value' => number_format($deadStock),
                        'sub'   => 'No sales ever',
                        'from'  => 'from-gray-500', 'to' => 'to-slate-600',
                        'bg'    => 'from-gray-50 to-slate-100 dark:from-gray-800/40 dark:to-slate-800/40',
                        'pct'   => $totalProducts > 0 ? round(($deadStock / $totalProducts) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>',
                    ],
                    [
                        'label' => 'Need Restock',
                        'value' => number_format($restockProducts),
                        'sub'   => 'Action required',
                        'from'  => 'from-orange-500', 'to' => 'to-amber-600',
                        'bg'    => 'from-orange-50 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20',
                        'pct'   => $totalProducts > 0 ? round(($restockProducts / $totalProducts) * 100) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
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
                <a href="{{ route('reports.inventory') }}"
                   class="text-xs text-gray-400 dark:text-gray-500
                          hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form action="{{ route('reports.inventory') }}" method="GET" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3">

                    {{-- Search --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                            </svg>
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                   placeholder="Product name, SKU…"
                                   class="filter-select pl-8">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Category</label>
                        <select name="category" class="filter-select">
                            <option value="">All categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Brand</label>
                        <select name="brand" class="filter-select">
                            <option value="">All brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                        {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stock Status --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Stock Status</label>
                        <select name="stock_status" class="filter-select">
                            <option value="">All statuses</option>
                            <option value="instock"  {{ request('stock_status') == 'instock'  ? 'selected' : '' }}>Healthy</option>
                            <option value="lowstock" {{ request('stock_status') == 'lowstock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="outstock" {{ request('stock_status') == 'outstock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500
                                      uppercase tracking-wider mb-1.5">Sort By</label>
                        <select name="sort" class="filter-select">
                            <option value="">Latest</option>
                            <option value="stock_high" {{ request('sort') == 'stock_high' ? 'selected' : '' }}>Highest Stock</option>
                            <option value="stock_low"  {{ request('sort') == 'stock_low'  ? 'selected' : '' }}>Lowest Stock</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                            <option value="price_low"  {{ request('sort') == 'price_low'  ? 'selected' : '' }}>Lowest Price</option>
                        </select>
                    </div>

                </div>

                <div class="flex justify-end gap-2 mt-3">
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

            {{-- Stock Status Donut --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Stock Status</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Healthy vs Low vs Out of stock</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-emerald-50 dark:bg-emerald-500/10
                                 text-emerald-600 dark:text-emerald-400
                                 text-xs font-semibold border border-emerald-100 dark:border-emerald-500/20">
                        Donut
                    </span>
                </div>
                <div class="h-56">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>

            {{-- Category Bar --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Stock by Category</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Current inventory per category</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-indigo-50 dark:bg-indigo-500/10
                                 text-indigo-600 dark:text-indigo-400
                                 text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20">
                        Bar
                    </span>
                </div>
                <div class="h-56">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            {{-- Brand Horizontal Bar --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Brands by Stock</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Top 8 brands with highest inventory</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-blue-50 dark:bg-blue-500/10
                                 text-blue-600 dark:text-blue-400
                                 text-xs font-semibold border border-blue-100 dark:border-blue-500/20">
                        Horizontal
                    </span>
                </div>
                <div class="h-56">
                    <canvas id="brandChart"></canvas>
                </div>
            </div>

            {{-- Inventory Value Line --}}
            <div class="chart-card bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Inventory Value</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Value by category (cost × qty)</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 bg-violet-50 dark:bg-violet-500/10
                                 text-violet-600 dark:text-violet-400
                                 text-xs font-semibold border border-violet-100 dark:border-violet-500/20">
                        Line
                    </span>
                </div>
                <div class="h-56">
                    <canvas id="valueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ==================== ANALYTICS SPOTLIGHT ==================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-3">
            @php
                $spots = [
                    [
                        'label'    => 'Highest Stock',
                        'name'     => $highestStock->name ?? '—',
                        'meta'     => number_format($highestStock->quantity ?? 0) . ' pcs',
                        'from'     => 'from-indigo-500', 'to' => 'to-violet-600',
                        'metaColor'=> 'text-indigo-600 dark:text-indigo-400',
                        'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>',
                    ],
                    [
                        'label'    => 'Lowest Stock',
                        'name'     => $lowestStock->name ?? '—',
                        'meta'     => number_format($lowestStock->quantity ?? 0) . ' pcs',
                        'from'     => 'from-red-500', 'to' => 'to-rose-600',
                        'metaColor'=> 'text-red-600 dark:text-red-400',
                        'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>',
                    ],
                        // [
                        //     'label'    => 'Most Valuable',
                        //     'name'     => $mostValuable->name ?? '—',
                        //     'meta'     => '$' . number_format($mostValuable->inventory_value ?? 0, 2),
                        //     'from'     => 'from-emerald-500', 'to' => 'to-green-600',
                        //     'metaColor'=> 'text-emerald-600 dark:text-emerald-400',
                        //     'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/>',
                        // ],
                    [
                        'label'    => 'Need Restock',
                        'name'     => $needRestock->name ?? '—',
                        'meta'     => ($needRestock->quantity ?? 0) . ' pcs left',
                        'from'     => 'from-orange-500', 'to' => 'to-amber-600',
                        'metaColor'=> 'text-orange-600 dark:text-orange-400',
                        'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                    ],
                    [
                        'label'    => 'Out of Stock',
                        'name'     => $outStockProduct->name ?? '—',
                        'meta'     => '0 pcs',
                        'from'     => 'from-red-500', 'to' => 'to-pink-600',
                        'metaColor'=> 'text-red-600 dark:text-red-400',
                        'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>',
                    ],
                        // [
                        //     'label'    => 'Fast Moving',
                        //     'name'     => $fastMoving->name ?? '—',
                        //     'meta'     => number_format($fastMoving->sold_qty ?? 0) . ' sold',
                        //     'from'     => 'from-blue-500', 'to' => 'to-cyan-600',
                        //     'metaColor'=> 'text-blue-600 dark:text-blue-400',
                        //     'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                        // ],
                    // [
                    //     'label'    => 'Slow Moving',
                    //     'name'     => $slowMoving->name ?? '—',
                    //     'meta'     => number_format($slowMoving->sold_qty ?? 0) . ' sold',
                    //     'from'     => 'from-gray-400', 'to' => 'to-slate-500',
                    //     'metaColor'=> 'text-gray-500 dark:text-gray-400',
                    //     'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    // ],
                    // [
                    //     'label'    => 'Highest Value',
                    //     'name'     => $highestValue->name ?? '—',
                    //     'meta'     => '$' . number_format($highestValue->stock_value ?? 0, 2),
                    //     'from'     => 'from-violet-500', 'to' => 'to-purple-600',
                    //     'metaColor'=> 'text-violet-600 dark:text-violet-400',
                    //     'icon'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
                    // ],
                ];
            @endphp

            @foreach($spots as $spot)
                <div class="spot-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700
                            shadow-sm hover:shadow-md hover:-translate-y-0.5 p-3">
                    <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full opacity-10
                                bg-gradient-to-br {{ $spot['from'] }} {{ $spot['to'] }}"></div>

                    <div class="relative flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $spot['from'] }} {{ $spot['to'] }}
                                    flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                {!! $spot['icon'] !!}
                            </svg>
                        </div>
                        <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide truncate">
                            {{ $spot['label'] }}
                        </p>
                    </div>

                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate leading-tight">
                        {{ $spot['name'] }}
                    </p>
                    <p class="text-xs font-bold {{ $spot['metaColor'] }} mt-0.5">
                        {{ $spot['meta'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- ==================== INVENTORY TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                    border border-gray-100 dark:border-gray-700
                    rounded-2xl overflow-hidden shadow-sm">

            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700
                        flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Inventory List</h2>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($products->total()) }} products
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider
                                   text-gray-400 dark:text-gray-500">
                            <th class="px-5 py-3">Product</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Brand</th>
                            <th class="px-5 py-3 text-right">Cost</th>
                            <th class="px-5 py-3 text-right">Sale</th>
                            <th class="px-5 py-3 text-center">Stock</th>
                            <th class="px-5 py-3 text-right">Value</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Updated</th>
                        </tr>
                    </thead>

                    <tbody id="inventoryTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($products as $product)
                            @php
                                $value = $product->quantity * ($product->cost_price ?? 0);

                                [$statusClass, $statusLabel] = match(true) {
                                    $product->quantity === 0    => ['bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400',    'Out of Stock'],
                                    $product->quantity <= 20    => ['bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400', 'Low Stock'],
                                    default                     => ['bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'Healthy'],
                                };

                                // Stock bar width (cap at 100)
                                $barPct = min(100, max(0, $product->quantity / 2));
                                $barColor = $product->quantity === 0
                                    ? 'bg-red-500'
                                    : ($product->quantity <= 20 ? 'bg-amber-500' : 'bg-emerald-500');
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Product --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">

                                    @if($product->firstImage && $product->firstImage->image_url)

                                        <img
                                            src="{{ asset($product->firstImage->image_url) }}"
                                            alt="{{ $product->name }}"
                                            class="w-10 h-10 rounded-xl object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">

                                    @else

                                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700
                                                    flex items-center justify-center text-xs font-semibold
                                                    text-gray-500 dark:text-gray-400 flex-shrink-0">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>

                                    @endif

                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[160px]">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                            {{ $product->product_code ?? '—' }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                                {{-- Category --}}
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10
                                                 text-indigo-600 dark:text-indigo-400">
                                        {{ optional($product->category)->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Brand --}}
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold
                                                 bg-blue-50 dark:bg-blue-500/10
                                                 text-blue-600 dark:text-blue-400">
                                        {{ optional($product->brand)->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Cost --}}
                                <td class="px-5 py-3.5 text-right text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    ${{ number_format($product->cost_price ?? 0, 2) }}
                                </td>

                                {{-- Sale --}}
                                <td class="px-5 py-3.5 text-right text-xs font-semibold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                    ${{ number_format($product->sale_price ?? 0, 2) }}
                                </td>

                                {{-- Stock with mini bar --}}
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ number_format($product->quantity) }}
                                        </span>
                                        <div class="w-16 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                            <div class="{{ $barColor }} h-full rounded-full"
                                                 style="width: {{ $barPct }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Value --}}
                                <td class="px-5 py-3.5 text-right text-xs font-bold
                                           text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                    ${{ number_format($value, 2) }}
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                                 text-[10px] font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Updated --}}
                                <td class="px-5 py-3.5 text-center text-[10px] text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $product->updated_at->format('d M Y') }}
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
                                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No products found.</p>
                                        <p class="text-xs text-gray-300 dark:text-gray-600">
                                            Try adjusting your filters.
                                        </p>
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
                    @if($products->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $products->firstItem() }}–{{ $products->lastItem() }}
                        </span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ number_format($products->total()) }}
                        </span>
                        products
                    @else
                        No products found
                    @endif
                </p>

                @if($products->hasPages())
                    <nav class="flex items-center gap-1">

                        @if($products->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg
                                         text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}"
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
                            $products->getUrlRange(
                                max(1, $products->currentPage() - 2),
                                min($products->lastPage(), $products->currentPage() + 2)
                            ) as $page => $url
                        )
                            @if($page == $products->currentPage())
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

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}"
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
    /* ════════════════════════════════════════════════════════════════
       INVENTORY CHARTS — Chart.js styled to match admin vibe
    ════════════════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', () => {

        const isDark    = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        const defaults = {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeInOutQuart' },
        };

        /* ── Stock Status Donut ───────────────────────────────── */
        new Chart(document.getElementById('stockChart'), {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($stockChart)),
                datasets: [{
                    data: @json(array_values($stockChart)),
                    backgroundColor: ['#10b981','#f59e0b','#ef4444'],
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

        /* ── Stock by Category Bar ────────────────────────────── */
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: @json($categoryChart->pluck('name')),
                datasets: [{
                    data: @json($categoryChart->pluck('stock')),
                    backgroundColor: 'rgba(99,102,241,.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: '#6366f1',
                }]
            },
            options: {
                ...defaults,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 10 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 10 } }
                    }
                }
            }
        });

        /* ── Top Brands Horizontal Bar ────────────────────────── */
        new Chart(document.getElementById('brandChart'), {
            type: 'bar',
            data: {
                labels: @json($brandChart->pluck('name')),
                datasets: [{
                    data: @json($brandChart->pluck('stock')),
                    backgroundColor: 'rgba(14,165,233,.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: '#0ea5e9',
                }]
            },
            options: {
                ...defaults,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 10 } }
                    }
                }
            }
        });

        /* ── Inventory Value Line ─────────────────────────────── */
        new Chart(document.getElementById('valueChart'), {
            type: 'line',
            data: {
                labels: @json($valueChart->pluck('name')),
                datasets: [{
                    data: @json($valueChart->pluck('value')),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139,92,246,.12)',
                    fill: true,
                    tension: .45,
                    pointBackgroundColor: '#8b5cf6',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }]
            },
            options: {
                ...defaults,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 10 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            font: { size: 10 },
                            callback: v => '$' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush

@endsection