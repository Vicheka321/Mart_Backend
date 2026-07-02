@extends('layouts.app')

@section('title', 'Products Report')

@section('content')
    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-12px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes progressFill {
            from {
                width: 0 !important;
            }
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes overlayIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .kpi-card {
            animation: fadeSlideUp .5s ease both;
        }

        .kpi-card:nth-child(1) {
            animation-delay: .05s;
        }

        .kpi-card:nth-child(2) {
            animation-delay: .10s;
        }

        .kpi-card:nth-child(3) {
            animation-delay: .15s;
        }

        .kpi-card:nth-child(4) {
            animation-delay: .20s;
        }

        .kpi-card:nth-child(5) {
            animation-delay: .25s;
        }

        .kpi-card:nth-child(6) {
            animation-delay: .30s;
        }

        .filter-card {
            animation: fadeSlideUp .45s .08s ease both;
        }

        .chart-card {
            animation: fadeSlideUp .5s ease both;
        }

        .chart-card:nth-child(1) {
            animation-delay: .28s;
        }

        .chart-card:nth-child(2) {
            animation-delay: .33s;
        }

        .chart-card:nth-child(3) {
            animation-delay: .38s;
        }

        .chart-card:nth-child(4) {
            animation-delay: .43s;
        }

        .table-card {
            animation: fadeSlideUp .5s .35s ease both;
        }

        #productsTableBody tr {
            animation: rowSlideIn .35s ease both;
        }

        #productsTableBody tr:nth-child(1) {
            animation-delay: .38s;
        }

        #productsTableBody tr:nth-child(2) {
            animation-delay: .43s;
        }

        #productsTableBody tr:nth-child(3) {
            animation-delay: .48s;
        }

        #productsTableBody tr:nth-child(4) {
            animation-delay: .53s;
        }

        #productsTableBody tr:nth-child(5) {
            animation-delay: .58s;
        }

        #productsTableBody tr:nth-child(6) {
            animation-delay: .63s;
        }

        #productsTableBody tr:nth-child(7) {
            animation-delay: .68s;
        }

        #productsTableBody tr:nth-child(8) {
            animation-delay: .73s;
        }

        #productsTableBody tr:nth-child(9) {
            animation-delay: .78s;
        }

        #productsTableBody tr:nth-child(10) {
            animation-delay: .83s;
        }

        .progress-bar {
            animation: progressFill .9s .65s cubic-bezier(.4, 0, .2, 1) both;
        }

        #productModal.flex {
            animation: overlayIn .2s ease;
        }

        #exportModal.flex {
            animation: overlayIn .2s ease;
        }

        .modal-inner {
            animation: modalIn .25s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .action-btn {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .action-btn:active {
            transform: translateY(0);
        }

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
            box-shadow: 0 0 0 2px rgba(99, 102, 241, .35);
            border-color: #6366f1;
        }
    </style>

    <div class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3"
            style="animation: fadeSlideUp .4s ease both;">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Products Report</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Analyze and monitor your product inventory, pricing and performance.
                </p>
            </div>
            <button type="button" onclick="openExportModal()"
                class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                           border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                           text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12" />
                    <path d="m7 10 5 5 5-5" />
                    <path d="M4 21h16" />
                </svg>
                Export
            </button>
        </div>

        {{-- ==================== KPI CARDS ==================== --}}
        @php
            $kpis = [
                [
                    'label' => 'Total Products',
                    'value' => number_format($totalProducts),
                    'sub' => 'All catalogue',
                    'from' => 'from-indigo-500',
                    'to' => 'to-violet-600',
                    'bg' => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                    'pct' => 100,
                ],
                [
                    'label' => 'Active Products',
                    'value' => number_format($activeProducts),
                    'sub' => 'In stock',
                    'from' => 'from-emerald-500',
                    'to' => 'to-green-600',
                    'bg' => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                    'pct' => $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100) : 0,
                ],
                [
                    'label' => 'Out of Stock',
                    'value' => number_format($outStock),
                    'sub' => 'Needs restock',
                    'from' => 'from-rose-500',
                    'to' => 'to-red-600',
                    'bg' => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
                    'pct' => $totalProducts > 0 ? round(($outStock / $totalProducts) * 100) : 0,
                ],
                [
                    'label' => 'Low Stock',
                    'value' => number_format($lowStock),
                    'sub' => '1–20 units left',
                    'from' => 'from-amber-500',
                    'to' => 'to-yellow-600',
                    'bg' => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                    'pct' => $totalProducts > 0 ? round(($lowStock / $totalProducts) * 100) : 0,
                ],
                [
                    'label' => 'Stock Value',
                    'value' => '$' . number_format($stockValue, 2),
                    'sub' => 'Cost × quantity',
                    'from' => 'from-blue-500',
                    'to' => 'to-indigo-600',
                    'bg' => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct' => 100,
                ],
                [
                    'label' => 'Avg Sale Price',
                    'value' => '$' . number_format($averagePrice, 2),
                    'sub' => 'Across all products',
                    'from' => 'from-purple-500',
                    'to' => 'to-pink-600',
                    'bg' => 'from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    'pct' => 100,
                ],
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
                                                flex items-center justify-center shadow-md">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    {!! $kpi['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">
                                    {{ $kpi['label'] }}</h4>
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
                        <h2
                            class="text-2xl font-bold tracking-tight
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

            <div
                class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Filters</h2>
                </div>
                <a href="{{ route('reports.products') }}"
                    class="text-xs text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form method="GET" action="{{ route('reports.products') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

                    {{-- Category --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Category</label>
                        <select name="category" class="filter-select">
                            <option value="">All categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Brand</label>
                        <select name="brand" class="filter-select">
                            <option value="">All brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stock Status --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Stock
                            Status</label>
                        <select name="stock_status" class="filter-select">
                            <option value="">All stock levels</option>
                            <option value="instock" @selected(request('stock_status') == 'instock')>In Stock</option>
                            <option value="lowstock" @selected(request('stock_status') == 'lowstock')>Low Stock</option>
                            <option value="outstock" @selected(request('stock_status') == 'outstock')>Out of Stock</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Sort
                            By</label>
                        <select name="sort" class="filter-select">
                            <option value="">Latest</option>
                            <option value="price_high" @selected(request('sort') == 'price_high')>Price: High → Low</option>
                            <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low → High</option>
                            <option value="stock" @selected(request('sort') == 'stock')>Stock: High → Low</option>
                        </select>
                    </div>

                    {{-- Keyword --}}
                    <div class="sm:col-span-2 xl:col-span-4">
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Keyword</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                {{-- <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                                </svg> --}}
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    placeholder="Product name…" class="filter-select pl-8">
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

        {{-- ==================== CHARTS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Products by Category --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Products by Category</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Products by Brand --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top 5 Brands</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64">
                        <canvas id="brandChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Stock Status --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M4 7c0-2 1-3 3-3h10c2 0 3 1 3 3M4 7h16M10 11v4M14 11v4" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Stock Status Overview</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Price Distribution --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Price Distribution</h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== PRODUCTS TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Products</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($products->total()) }} products found — click "View" for full details.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr
                            class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Product</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Brand</th>
                            <th class="px-6 py-3 text-right">Cost</th>
                            <th class="px-6 py-3 text-right">Sale Price</th>
                            <th class="px-6 py-3 text-right">Margin</th>
                            <th class="px-6 py-3 text-center">Stock</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody id="productsTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($products as $product)
                            @php
                                $qty = $product->quantity;
                                $stockPct = min(($qty / max($qty, 100)) * 100, 100);

                                if ($qty == 0) {
                                    $stockFrom = 'from-rose-500';
                                    $stockTo = 'to-red-600';
                                    $stockBg = 'bg-rose-50 dark:bg-rose-500/10';
                                    $stockText = 'text-rose-600 dark:text-rose-400';
                                    $stockDot = 'bg-rose-500';
                                    $stockLabel = 'Out of Stock';
                                } elseif ($qty <= 20) {
                                    $stockFrom = 'from-amber-500';
                                    $stockTo = 'to-yellow-600';
                                    $stockBg = 'bg-amber-50 dark:bg-amber-500/10';
                                    $stockText = 'text-amber-600 dark:text-amber-400';
                                    $stockDot = 'bg-amber-500';
                                    $stockLabel = 'Low Stock';
                                } else {
                                    $stockFrom = 'from-emerald-500';
                                    $stockTo = 'to-green-600';
                                    $stockBg = 'bg-emerald-50 dark:bg-emerald-500/10';
                                    $stockText = 'text-emerald-600 dark:text-emerald-400';
                                    $stockDot = 'bg-emerald-500';
                                    $stockLabel = 'In Stock';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Product --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl border border-gray-100 dark:border-gray-700
                                                            bg-gray-50 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                                            <img src="{{ $product->firstImage->image_url ?? asset('images/no-image.png') }}"
                                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $product->name }}
                                                </p>
                                                @if(($product->sold_qty ?? 0) >= 50)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                                                             bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                                                             text-[9px] font-bold whitespace-nowrap">
                                                        🔥 Best Seller
                                                    </span>
                                                @endif
                                            </div>
                                            {{-- <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                                SKU: {{ $product->product_code }}
                                            </p> --}}
                                            {{-- <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">⭐ {{ $product->rating
                                                    ?? '—' }}</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">❤️ {{
                                                    number_format($product->favorites ?? 0) }}</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">👁 {{
                                                    number_format($product->views ?? 0) }}</span>
                                            </div> --}}
                                        </div>
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                        {{ optional($product->category)->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Brand --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                        {{ optional($product->brand)->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Cost --}}
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                        ${{ number_format($product->cost_price, 2) }}
                                    </span>
                                </td>

                                {{-- Sale Price --}}
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                </td>

                                {{-- Margin --}}
                                <td class="px-6 py-4 text-right">
                                    @php $margin = $product->margin ?? 0; @endphp
                                    <span
                                        class="text-sm font-semibold {{ $margin >= 30 ? 'text-emerald-600 dark:text-emerald-400' : ($margin >= 10 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                                        {{ $margin }}%
                                    </span>
                                </td>

                                {{-- Stock --}}
                                <td class="px-6 py-4">
                                    <div class="w-28 mx-auto">
                                        <div class="flex justify-between mb-1">
                                            <span
                                                class="text-xs font-semibold text-gray-900 dark:text-white">{{ number_format($qty) }}</span>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500">pcs</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                            <div class="progress-bar h-full rounded-full bg-gradient-to-r {{ $stockFrom }} {{ $stockTo }}"
                                                style="width: {{ $stockPct }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $stockBg }} {{ $stockText }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $stockDot }}"></span>
                                        {{ $stockLabel }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="openProductModal({{ $product->id }})"
                                        class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                       text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                                       hover:text-indigo-600 dark:hover:text-indigo-400
                                                       hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No products found. Try adjusting
                                            your filters.</p>
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
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                        of
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($products->total()) }}</span>
                        results
                    @else
                        No records found
                    @endif
                </p>

                @if($products->hasPages())
                    <nav class="flex items-center gap-1">
                        @if($products->onFirstPage())
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                                  hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            @if($page == $products->currentPage())
                                <span
                                    class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
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

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                                  hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>

    </div>{{-- /space-y-4 --}}


    {{-- ==================== PRODUCT DETAILS MODAL ==================== --}}
    <div id="productModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        w-full max-w-3xl rounded-2xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden">

            <div
                class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                    flex items-center justify-center shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Product Details</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Complete product information</p>
                    </div>
                </div>
                <button type="button" onclick="closeProductModal()" class="w-8 h-8 flex items-center justify-center rounded-full
                               bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                               text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="productModalBody" class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center justify-center py-20">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin">
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12" />
                            <path d="m7 10 5 5 5-5" />
                            <path d="M4 21h16" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Export Data</h3>
                </div>
                <button onclick="closeExportModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Choose your preferred export format:</p>
                <a href="{{ route('reports.products.export.csv') }}"
                    class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                              bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                              hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                        group-hover:border-emerald-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                            </svg>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">
                                CSV File</p>
                            <p class="text-[11px] text-gray-400">Spreadsheet compatible</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
                <a href="{{ route('reports.products.export.pdf') }}" 
                    class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                              bg-gray-50 dark:bg-gray-700/50 hover:bg-red-50 dark:hover:bg-red-500/10
                              hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                        group-hover:border-red-300 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                            </svg>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
                                PDF File</p>
                            <p class="text-[11px] text-gray-400">Print-ready document</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-red-500 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </div>
            <div class="px-6 pb-6">
                <button onclick="closeExportModal()" class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                               text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
        <script defer>
            // ── Chart colours ──────────────────────────────────────────────
            const PALETTE = ['#6366F1', '#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', '#EF4444', '#14B8A6', '#F97316'];

            const baseOptions = (axis = false) => ({
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 } } } },
                ...(axis ? { scales: { y: { beginAtZero: true, ticks: { font: { size: 11 } } }, x: { ticks: { font: { size: 11 } } } } } : {}),
            });

            window.addEventListener('load', () => {

                // Category Doughnut
                new Chart(document.getElementById('categoryChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($productCategoryChart->pluck('name')),
                        datasets: [{ data: @json($productCategoryChart->pluck('total')), backgroundColor: PALETTE, borderWidth: 0 }]
                    },
                    options: baseOptions(),
                });

                // Brand Horizontal Bar
                new Chart(document.getElementById('brandChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($productBrandChart->pluck('name')),
                        datasets: [{ label: 'Products', data: @json($productBrandChart->pluck('total')), backgroundColor: '#6366F1', borderRadius: 6 }]
                    },
                    options: { ...baseOptions(true), indexAxis: 'y', plugins: { legend: { display: false } } },
                });

                // Stock Doughnut
                new Chart(document.getElementById('stockChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($stockChart)),
                        datasets: [{ data: @json(array_values($stockChart)), backgroundColor: ['#10B981', '#F59E0B', '#EF4444'], borderWidth: 0 }]
                    },
                    options: baseOptions(),
                });

                // Price Bar
                new Chart(document.getElementById('priceChart'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_keys($priceChart)),
                        datasets: [{ label: 'Products', data: @json(array_values($priceChart)), backgroundColor: '#8B5CF6', borderRadius: 6 }]
                    },
                    options: { ...baseOptions(true), plugins: { legend: { display: false } } },
                });
            });

            // ── Product Modal ──────────────────────────────────────────────
            const productModal = document.getElementById('productModal');
            const productModalBody = document.getElementById('productModalBody');

            // async function openProductModal(id) {
            //     productModal.classList.remove('hidden');
            //     productModal.classList.add('flex');
            //     document.body.classList.add('overflow-hidden');

            //     productModalBody.innerHTML = `
            //         <div class="flex items-center justify-center py-20">
            //             <div class="flex flex-col items-center gap-3">
            //                 <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
            //                 <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
            //             </div>
            //         </div>`;

            //     try {
            //         const res  = await fetch(`/admin/reports/products/${id}`, {
            //             headers: { 'X-Requested-With': 'XMLHttpRequest' }
            //         });
            //         productModalBody.innerHTML = await res.text();
            //     } catch {
            //         productModalBody.innerHTML = `
            //             <div class="flex items-center justify-center py-20">
            //                 <div class="flex flex-col items-center gap-3">
            //                     <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
            //                         <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            //                             <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            //                         </svg>
            //                     </div>
            //                     <p class="text-sm text-red-500">Failed to load details. Please try again.</p>
            //                 </div>
            //             </div>`;
            //     }
            // }

            async function openProductModal(id) {

                // Open Modal
                productModal.classList.remove('hidden');
                productModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');

                // Loading
                productModalBody.innerHTML = `
                        <div class="flex items-center justify-center py-20">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-sm text-gray-500">
                                    Loading product details...
                                </p>
                            </div>
                        </div>
                    `;

                try {

                    const response = await fetch(`/admin/products/${id}`, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error("Cannot load product.");
                    }

                    const product = await response.json();

                    const image = product.image
                        ? product.image
                        : '/images/no-image.png';

                    const statusBadge = product.status == 1
                        ? `<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Active</span>`
                        : `<span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Inactive</span>`;

                    productModalBody.innerHTML = `

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div>

                        <img
                            src="${image}"
                            class="w-full h-80 rounded-xl border object-cover">

                    </div>

                    <div class="space-y-5">

                        <div>

                            <h2 class="text-2xl font-bold text-gray-900">
                                ${product.name}
                            </h2>

                            <div class="mt-2">
                                ${statusBadge}
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Category
                                </div>

                                <div class="font-semibold">
                                    ${product.category ?? '-'}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Brand
                                </div>

                                <div class="font-semibold">
                                    ${product.brand ?? '-'}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Cost Price
                                </div>

                                <div class="font-semibold">
                                    $${Number(product.cost_price).toFixed(2)}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Sale Price
                                </div>

                                <div class="font-semibold text-indigo-600">
                                    $${Number(product.sale_price).toFixed(2)}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Quantity
                                </div>

                                <div class="font-semibold">
                                    ${product.quantity}
                                </div>
                            </div>

                            <div>
                                <div class="text-gray-500 text-sm">
                                    Product Code
                                </div>

                                <div class="font-semibold">
                                    ${product.product_code ?? '-'}
                                </div>
                            </div>

                        </div>

                        <div>

                            <div class="text-gray-500 text-sm mb-2">
                                Description
                            </div>

                            <div class="rounded-xl border bg-gray-50 p-4 text-sm leading-6">
                                ${product.description ?? 'No description'}
                            </div>

                        </div>

                    </div>

                </div>

                        `;

                } catch (error) {

                    productModalBody.innerHTML = `
                            <div class="py-16 text-center">

                                <div class="text-red-500 text-lg font-semibold">
                                    Failed to load product.
                                </div>

                                <div class="mt-2 text-gray-500">
                                    ${error.message}
                                </div>

                            </div>
                        `;

                }

            }

            function closeProductModal() {
                productModal.classList.add('hidden');
                productModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            productModal.addEventListener('click', e => { if (e.target === productModal) closeProductModal(); });

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

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') { closeProductModal(); closeExportModal(); }
            });


        </script>
    @endpush

@endsection