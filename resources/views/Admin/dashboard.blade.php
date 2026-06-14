@extends('layouts.app')

@section('content')

    <style>
        /* ── Entry animations ── */
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
        @keyframes barRise {
            from { transform: scaleY(0); opacity: 0; }
            to   { transform: scaleY(1); opacity: 1; }
        }
        @keyframes barSlideUp {
            from { height: 0 !important; opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes lineDrawIn {
            from { stroke-dashoffset: 2000; }
            to   { stroke-dashoffset: 0; }
        }
        @keyframes areaFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes dotPop {
            0%   { transform: scale(0); opacity: 0; }
            70%  { transform: scale(1.5); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes rowFadeIn {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes dropdownIn {
            from { opacity: 0; transform: translateY(-6px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes chartCardReveal {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes donutSpin {
            from { transform: rotate(-90deg); opacity: 0; }
            to   { transform: rotate(0deg); opacity: 1; }
        }
        @keyframes legendSlideIn {
            from { opacity: 0; transform: translateX(12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes lowerCardReveal {
            from { opacity: 0; transform: translateY(28px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes productRowIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
            50%       { box-shadow: 0 0 0 6px rgba(99,102,241,0.08); }
        }

        /* Header */
        .dash-header {
            animation: fadeSlideUp .4s ease both;
            position: relative;   /* required for z-index to apply */
            z-index: 30;          /* explicit context, higher than sibling cards */
        }

        /* Stat cards staggered */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .06s; }
        .stat-card:nth-child(2) { animation-delay: .14s; }
        .stat-card:nth-child(3) { animation-delay: .22s; }
        .stat-card:nth-child(4) { animation-delay: .30s; }

        /* ── MAIN CHART CARDS ── */
        .chart-card-main {
            animation: chartCardReveal .6s cubic-bezier(.22,1,.36,1) both;
        }
        .chart-card-main:nth-child(1) { animation-delay: .38s; }
        .chart-card-main:nth-child(2) { animation-delay: .50s; }

        /* Bar chart wrapper fade */
        .bar-chart-area {
            animation: fadeSlideUp .5s .55s ease both;
        }

        /* Individual bars — each bar gets inline animation-delay via JS */
        .chart-bar-col {
            animation: barSlideUp .55s cubic-bezier(.22,1,.36,1) both;
            transform-origin: bottom;
        }

        /* SVG line draw */
        .rev-line {
            stroke-dasharray: 2000;
            stroke-dashoffset: 2000;
            animation: lineDrawIn 1.6s 1.1s cubic-bezier(.4,0,.2,1) forwards;
        }

        /* SVG area fade */
        .rev-area {
            animation: areaFadeIn .8s 1.8s ease forwards;
            opacity: 0;
        }

        /* Last dot pop */
        .rev-dot {
            animation: dotPop .45s 2.6s cubic-bezier(.34,1.56,.64,1) both;
            transform-origin: center;
        }

        /* X-axis labels */
        .rev-label {
            animation: fadeSlideUp .4s ease both;
        }

        /* Progress bars */
        .progress-bar { animation: progressFill .9s .8s cubic-bezier(.4,0,.2,1) both; }

        /* ── LOWER SECTION ── */
        .lower-card-anim {
            animation: lowerCardReveal .65s cubic-bezier(.22,1,.36,1) both;
        }
        .lower-card-anim:nth-child(1) { animation-delay: .55s; }
        .lower-card-anim:nth-child(2) { animation-delay: .68s; }

        /* Product rows staggered */
        .product-row-anim { animation: productRowIn .35s ease both; }
        .product-row-anim:nth-child(1)  { animation-delay: .62s; }
        .product-row-anim:nth-child(2)  { animation-delay: .68s; }
        .product-row-anim:nth-child(3)  { animation-delay: .74s; }
        .product-row-anim:nth-child(4)  { animation-delay: .80s; }
        .product-row-anim:nth-child(5)  { animation-delay: .86s; }
        .product-row-anim:nth-child(6)  { animation-delay: .92s; }

        /* Donut */
        .donut-inner {
            animation: donutSpin .8s .75s cubic-bezier(.34,1.1,.64,1) both;
            transform-origin: center;
        }
        .donut-center {
            animation: fadeSlideUp .4s 1.4s ease both;
        }

        /* Legend rows */
        .legend-row { animation: legendSlideIn .35s ease both; }
        .legend-row:nth-child(1) { animation-delay: .78s; }
        .legend-row:nth-child(2) { animation-delay: .84s; }
        .legend-row:nth-child(3) { animation-delay: .90s; }
        .legend-row:nth-child(4) { animation-delay: .96s; }
        .legend-row:nth-child(5) { animation-delay: 1.02s; }

        /* Counter pop */
        .count-done { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        /* Dropdown */
        #rangeDropdown:not(.hidden) { animation: dropdownIn .18s cubic-bezier(.34,1.3,.64,1) both; }

        /* Hover lift */
        .stat-card { transition: box-shadow .2s ease, transform .2s ease; }

        /* Donut hover */
        .donut-wrap:hover .donut-inner { filter: drop-shadow(0 6px 18px rgba(0,0,0,.12)); }

        /* Bar hover */
        .chart-bar-col:hover .bar-inner {
            filter: brightness(1.12) saturate(1.2);
            transform: scaleY(1.03);
            transform-origin: bottom;
        }
        .bar-inner { transition: filter .18s ease, transform .18s ease; }

        /* Button */
        .btn-sm { transition: transform .14s ease, box-shadow .14s ease; }
        .btn-sm:hover  { transform: translateY(-1px); }
        .btn-sm:active { transform: translateY(0); }

        /* Responsive */
        @media (max-width: 1024px) {
            .top-cards  { grid-template-columns: repeat(2, 1fr); }
            .main-grid  { grid-template-columns: 1fr; }
            .lower-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .top-cards { grid-template-columns: 1fr; }
        }
    </style>

    <div class="space-y-6 overflow-visible">

        {{-- ==================== HEADER ==================== --}}
        <div class="dash-header flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Overview</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Welcome back — here's what's happening.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Range Filter --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" class="relative flex items-center gap-3">
                    <div class="relative min-w-[210px] z-[200] z-[99999] overflow-visible">
                        <button type="button" id="rangeButton"
                            class="btn-sm w-full flex items-center justify-between gap-3
                                   rounded-xl border border-gray-200 dark:border-gray-700
                                   bg-white dark:bg-gray-800 px-4 py-2.5
                                   text-sm font-medium text-gray-700 dark:text-gray-200
                                   shadow-sm hover:border-indigo-300 dark:hover:border-indigo-500
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <span id="rangeButtonText">
                                @if(request('range') === 'custom' && request('date_range'))
                                    {{ str_replace(' to ', ' → ', request('date_range')) }}
                                @elseif(request('range') === 'today')       Today
                                @elseif(request('range') === '7days')      Last 7 Days
                                @elseif(request('range') === 'this_month')  This Month
                                @elseif(request('range') === 'last_month')  Last Month
                                @elseif(request('range') === 'this_year')   This Year
                                @else                                         Last 30 Days
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="rangeDropdown" class="hidden absolute right-0 top-full mt-2 w-52 rounded-xl
border border-gray-200 dark:border-gray-700
bg-white dark:bg-gray-800 shadow-2xl
z-[9999] overflow-hidden ">
                            @php
                                $ranges = [
                                    'today'      => 'Today',
                                    '7days'      => 'Last 7 Days',
                                    '30days'     => 'Last 30 Days',
                                    'this_month' => 'This Month',
                                    'last_month' => 'Last Month',
                                    'this_year'  => 'This Year',
                                    'custom'     => 'Custom Range',
                                ];
                            @endphp
                            @foreach($ranges as $value => $label)
                                <button type="button"
                                    class="range-option w-full text-left px-4 py-2.5 text-sm
                                           text-gray-700 dark:text-gray-200
                                           hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                           hover:text-indigo-600 dark:hover:text-indigo-400
                                           transition-colors"
                                    data-value="{{ $value }}" data-label="{{ $label }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="range" id="rangeSelect" value="{{ request('range', '30days') }}">
                </form>

                {{-- Export --}}
                <button type="button"
                    class="btn-sm inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium
                           rounded-xl border border-gray-200 dark:border-gray-600
                           bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200
                           hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="top-cards grid grid-cols-2 lg:grid-cols-4 gap-3 ">

            {{-- Revenue --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-emerald-50 via-green-50 to-teal-100
                            dark:from-emerald-900/20 dark:via-green-900/20 dark:to-teal-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Revenue</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Selected period</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full
                                 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20
                                 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800
                                 shadow-sm text-[10px] font-semibold">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                        </svg>
                        
                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 bg-clip-text text-transparent"
                        data-count="{{ (int) $totalRevenue }}"
                        data-cents="{{ substr(number_format($totalRevenue, 2), -2) }}">
                        $0<span class="rev-cents text-sm text-gray-400 dark:text-gray-500 font-normal">.00</span>
                    </h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-600"
                             style="width: 68%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500"></span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">${{ number_format($totalRevenue, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- Total Sales --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-blue-50 via-indigo-50 to-violet-100
                            dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-violet-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600
                                    flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Sales</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Orders placed</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">
                        Orders
                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent"
                        data-count="{{ $totalSales }}">0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-600"
                             style="width: 61%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500"></span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalSales) }} total</span>
                    </div>
                </div>
            </div>

            {{-- New Customers --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-100
                            dark:from-violet-900/20 dark:via-purple-900/20 dark:to-fuchsia-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-600
                                    flex items-center justify-center shadow-md shadow-violet-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Customers</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Registered period</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400
                                 ring-1 ring-violet-200 dark:ring-violet-800 text-[10px] font-semibold">
                        Users
                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 bg-clip-text text-transparent"
                        data-count="{{ $totalCustomers }}">0</h2>
                </div>

                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All registered</span>
                        <span class="text-[10px] font-semibold text-violet-600 dark:text-violet-400">{{ number_format($totalCustomers) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Profit --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-pink-50 via-rose-50 to-red-100
                            dark:from-pink-900/20 dark:via-rose-900/20 dark:to-red-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-pink-500 via-rose-500 to-red-600
                                    flex items-center justify-center shadow-md shadow-pink-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m-6-6h12"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Profit</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Net, selected period</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full
                                 bg-gradient-to-r from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20
                                 text-pink-600 dark:text-pink-400 ring-1 ring-pink-200 dark:ring-pink-800
                                 shadow-sm text-[10px] font-semibold">
                        Net
                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-pink-600 via-rose-600 to-red-600 bg-clip-text text-transparent"
                        data-count="{{ (int) $profit }}"
                        data-cents="{{ substr(number_format($profit, 2), -2) }}">
                        $0<span class="profit-cents text-sm text-gray-400 dark:text-gray-500 font-normal">.00</span>
                    </h2>
                </div>

                <div class="relative mt-2">
                    @php $profitMarginPct = ($totalRevenue ?? 0) > 0 ? round(($profit / $totalRevenue) * 100) : 0; @endphp
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-pink-500 via-rose-500 to-red-600"
                             style="width: {{ $profitMarginPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $profitMarginPct }}% margin</span>
                        <span class="text-[10px] font-semibold text-pink-600 dark:text-pink-400">${{ number_format($profit, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== MAIN CHARTS ==================== --}}
        @php
            $currentRange = request('range', '30days');

            // Determine label format based on range
            $labelFormat = match($currentRange) {
                'today'      => 'H:i',
                'this_year'  => 'M',
                default      => 'd',
            };

            // Determine how many bars to show (use actual chartData count)
            $barCount = $chartData->count();
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 ">

            {{-- ===================== TOTAL SALES BAR CHART ===================== --}}
            <div class="chart-card-main xl:col-span-7">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-gray-700
                            shadow-sm p-5 h-full">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Total Sales</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Sales performance · {{ $barCount }} {{ $barCount === 1 ? 'point' : 'days' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg
                                    bg-indigo-50 dark:bg-indigo-500/10
                                    text-indigo-600 dark:text-indigo-400
                                    text-xs font-semibold border border-indigo-100 dark:border-indigo-500/20">
                            @if($currentRange === 'today')       Today
                            @elseif($currentRange === '7days')   Last 7 Days
                            @elseif($currentRange === '30days')  Last 30 Days
                            @elseif($currentRange === 'this_month') This Month
                            @elseif($currentRange === 'last_month') Last Month
                            @elseif($currentRange === 'this_year')  This Year
                            @elseif($currentRange === 'custom')  Custom
                            @else Last 30 Days
                            @endif
                        </span>
                    </div>

                    {{-- Bar Chart — only bars, no lines --}}
                    @php
                        $maxSales = max($chartData->max('total') ?? 1, 1);
                        $chartHeight = 130; // px, visual max bar height

                        // For many bars (30+), use thinner styling
                        $isManyBars = $barCount >= 20;
                    @endphp

                    <div class="bar-chart-area h-44 flex items-end justify-between gap-1"
                         id="barChartContainer">
                        @foreach($chartData as $idx => $item)
                            @php
                                $barH   = max(6, round(($item->total / $maxSales) * $chartHeight));
                                // stagger delay: spread across 600ms total
                                $delay  = round(($idx / max($barCount - 1, 1)) * 600 + 400);
                            @endphp

                            <div class="chart-bar-col group flex flex-col items-center flex-1"
                                 style="animation-delay: {{ $delay }}ms">

                                {{-- Tooltip --}}
                                <div class="relative flex flex-col items-center w-full">
                                    {{-- Tooltip bubble --}}
                                    <div class="absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2
                                                opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none z-10">
                                        <div class="bg-gray-900 dark:bg-gray-700 text-white text-[9px] font-medium
                                                    px-1.5 py-0.5 rounded-md whitespace-nowrap shadow-lg">
                                            {{ number_format($item->total) }}
                                        </div>
                                    </div>

                                    {{-- Bar wrapper (sets height, clips overflow) --}}
                                    <div class="w-full overflow-hidden rounded-t-md
                                                bg-indigo-100 dark:bg-indigo-900/30"
                                         style="height: {{ $barH }}px">
                                        {{-- Actual colored bar --}}
                                        <div class="bar-inner w-full h-full
                                                    bg-gradient-to-t from-indigo-600 via-indigo-500 to-indigo-400
                                                    rounded-t-md">
                                        </div>
                                    </div>
                                </div>

                                {{-- Date label — show every N labels to avoid clutter --}}
                                @php
                                    $showLabel = $isManyBars
                                        ? ($idx % max(1, (int) ceil($barCount / 7)) === 0 || $loop->last)
                                        : true;
                                @endphp
                                @if($showLabel)
                                    <span class="mt-1.5 text-[8px] font-medium text-gray-400 dark:text-gray-500 leading-none">
                                        {{ \Carbon\Carbon::parse($item->date)->format($labelFormat) }}
                                    </span>
                                @else
                                    <span class="mt-1.5 text-[8px] leading-none opacity-0">·</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===================== TOTAL REVENUE LINE CHART ===================== --}}
            <div class="chart-card-main xl:col-span-5">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-gray-700
                            shadow-sm p-5 h-full">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Total Revenue</h2>

                            <div class="mt-2 flex items-end gap-1">
                                <span class="text-3xl font-bold tracking-tight
                                            text-gray-900 dark:text-white leading-none">
                                    ${{ number_format($totalRevenue, 0) }}
                                </span>
                                <span class="text-sm text-gray-400 dark:text-gray-500 mb-0.5">
                                    .{{ substr(number_format($totalRevenue, 2), -2) }}
                                </span>
                            </div>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-lg
                                    bg-emerald-50 dark:bg-emerald-500/10
                                    text-emerald-600 dark:text-emerald-400
                                    text-xs font-semibold border border-emerald-100 dark:border-emerald-500/20">
                            @if($currentRange === 'today')       Today
                            @elseif($currentRange === '7days')   Last 7 Days
                            @elseif($currentRange === '30days')  Last 30 Days
                            @elseif($currentRange === 'this_month') This Month
                            @elseif($currentRange === 'last_month') Last Month
                            @elseif($currentRange === 'this_year')  This Year
                            @elseif($currentRange === 'custom')  Custom
                            @else Last 30 Days
                            @endif
                        </span>
                    </div>

                    {{-- Line Chart --}}
                    <div class="mt-4">
                        <svg viewBox="0 0 300 150"
                            class="w-full h-44"
                            overflow="visible">

                            <defs>
                                <linearGradient id="revenueGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#10b981" stop-opacity="0.22" />
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
                                </linearGradient>
                            </defs>

                            {{-- Area fill — fades in after line draws --}}
                            <polygon class="rev-area"
                                     points="{{ $svgAStr }}"
                                     fill="url(#revenueGradient)" />

                            {{-- Animated line draw --}}
                            <polyline class="rev-line"
                                      points="{{ $svgPStr }}"
                                      fill="none"
                                      stroke="#10b981"
                                      stroke-width="2.5"
                                      stroke-linecap="round"
                                      stroke-linejoin="round" />

                            {{-- Last point dot --}}
                            @foreach($revenueChartData as $i => $item)
                                @if($loop->last)
                                    @php
                                        $x = ($svgCount > 1)
                                            ? ($i / ($svgCount - 1)) * ($svgW - $svgPad * 2) + $svgPad
                                            : $svgPad;

                                        $y = $svgH - (
                                            (($item->total / max($svgMaxRevenue, 1))
                                            * ($svgH - $svgPad * 2))
                                            + $svgPad
                                        );
                                    @endphp

                                    {{-- Outer glow ring --}}
                                    <circle class="rev-dot"
                                            cx="{{ $x }}" cy="{{ $y }}"
                                            r="7"
                                            fill="#10b981"
                                            fill-opacity="0.18" />

                                    {{-- Main dot --}}
                                    <circle class="rev-dot"
                                            cx="{{ $x }}" cy="{{ $y }}"
                                            r="4"
                                            fill="#10b981"
                                            stroke="white"
                                            stroke-width="2.5" />
                                @endif
                            @endforeach

                            {{-- X Labels --}}
                            @foreach($revenueChartData as $i => $item)
                                @if($i % max(1, ceil($svgCount / 4)) === 0 || $loop->last)
                                    @php
                                        $x = ($svgCount > 1)
                                            ? ($i / ($svgCount - 1)) * ($svgW - $svgPad * 2) + $svgPad
                                            : $svgPad;
                                    @endphp

                                    <text class="rev-label"
                                          x="{{ $x }}" y="148"
                                          text-anchor="middle"
                                          font-size="8"
                                          fill="#9ca3af"
                                          style="animation-delay: {{ 1.0 + $loop->index * 0.1 }}s">
                                        {{ \Carbon\Carbon::parse($item->date)->format('d M') }}
                                    </text>
                                @endif
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== LOWER SECTION ==================== --}}
        <div class="lower-grid grid grid-cols-1 lg:grid-cols-2 gap-4 ">

            {{-- Revenue by Products --}}
            <div class="lower-card-anim bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="px-4 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue by Products</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Top performing items</p>
                    </div>
                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-full
                                 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                 text-gray-500 dark:text-gray-400">
                        {{ $revenueByProducts->count() }} products
                    </span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($revenueByProducts as $product)
                        @php
                            $revenue  = $product->revenue ?? 0;
                            $pct      = round(($revenue / $maxProductRevenue) * 100);
                            $image    = $product->image->first();
                            $imageUrl = $image?->image_url ? asset($image->image_url) : null;

                            [$barClass, $badgeClass, $badgeText] = match(true) {
                                $pct >= 80 => ['bg-emerald-100 dark:bg-emerald-900/40 border-r-2 border-emerald-500', 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400', 'Top'],
                                $pct >= 60 => ['bg-blue-100 dark:bg-blue-900/40 border-r-2 border-blue-500',         'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400',         'High'],
                                $pct >= 40 => ['bg-amber-100 dark:bg-amber-900/40 border-r-2 border-amber-500',      'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400',      'Mid'],
                                default    => ['bg-gray-200 dark:bg-gray-600',                                        'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400',             'Low'],
                            };
                        @endphp

                        <div class="product-row-anim flex items-center gap-3 px-4 py-2.5
                                    hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                     class="w-9 h-9 rounded-xl object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700
                                            flex items-center justify-center text-xs font-medium text-gray-500 dark:text-gray-400 flex-shrink-0">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="progress-bar h-full rounded-full {{ $barClass }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-[9px] text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                        {{ number_format($product->sold_qty ?? 0) }} sold
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                <span class="text-xs font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($revenue, 2) }}
                                </span>
                                <span class="text-[9px] font-medium px-1.5 py-0.5 rounded-full border {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-400 dark:text-gray-500">No revenue data available.</div>
                    @endforelse
                </div>
            </div>

            {{-- Sales by Category --}}
            <div class="lower-card-anim bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Sales by Category</h2>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Revenue distribution</p>
                </div>

                {{-- Donut --}}
                <div class="flex justify-center">
                    <div class="donut-wrap relative w-36 h-36 transition-all duration-300">
                        <div class="donut-inner w-full h-full rounded-full"
                             style="background: conic-gradient({{ $donutGradient }})"></div>
                        <div class="donut-center absolute inset-9 bg-white dark:bg-gray-800 rounded-full
                                    flex flex-col items-center justify-center shadow-inner">
                            <span class="text-base font-bold text-gray-900 dark:text-white leading-none">
                                {{ $totalCat >= 1000 ? number_format($totalCat / 1000, 1) . 'K' : number_format($totalCat) }}
                            </span>
                            <span class="text-[9px] text-gray-400 dark:text-gray-500 mt-0.5">total</span>
                        </div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="space-y-2.5">
                    @foreach($salesByCategory as $i => $cat)
                        @php
                            $c = $donutColors[$i % count($donutColors)];
                            $p = $totalCat > 0 ? round(($cat->revenue / $totalCat) * 100) : 0;
                        @endphp
                        <div class="legend-row flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:{{ $c }}"></span>
                                <span class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $cat->name }}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="w-16 h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="progress-bar h-full rounded-full opacity-80"
                                         style="width:{{ $p }}%; background:{{ $c }}"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-900 dark:text-white min-w-[40px] text-right">
                                    {{ $cat->revenue >= 1000 ? number_format($cat->revenue / 1000, 1) . 'K' : number_format($cat->revenue) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER
    // ══════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(el => {
                const target   = parseInt(el.dataset.count, 10) || 0;
                const cents    = el.dataset.cents;
                const isPrice  = !!cents;
                const duration = 1100;
                const start    = performance.now();

                function ease(t) { return 1 - Math.pow(1 - t, 3); }

                (function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const current  = Math.round(ease(progress) * target);

                    const innerSpan = el.querySelector('span');
                    if (innerSpan) {
                        el.firstChild.textContent = (isPrice ? '$' : '') + current.toLocaleString();
                    } else {
                        el.textContent = (isPrice ? '$' : '') + current.toLocaleString();
                    }

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        if (innerSpan) {
                            el.firstChild.textContent = (isPrice ? '$' : '') + target.toLocaleString();
                        } else {
                            el.textContent = (isPrice ? '$' : '') + target.toLocaleString();
                        }
                        el.classList.add('count-done');
                    }
                })(performance.now());
            });
        }, 320);
    });

    // ══════════════════════════════════════════════════════
    //  BAR CHART — animated height via JS (CSS alone can't
    //  animate height:0 → height:Npx per-element reliably)
    // ══════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', () => {
        const bars = document.querySelectorAll('.chart-bar-col');
        bars.forEach((col, idx) => {
            const barWrapper = col.querySelector('.overflow-hidden');
            if (!barWrapper) return;

            const finalH = parseInt(barWrapper.style.height, 10) || 0;
            barWrapper.style.height = '0px';
            barWrapper.style.transition = 'none';

            const delay = 400 + (idx / Math.max(bars.length - 1, 1)) * 600;

            setTimeout(() => {
                barWrapper.style.transition = `height 0.55s cubic-bezier(0.22, 1, 0.36, 1)`;
                barWrapper.style.height     = finalH + 'px';
            }, delay);
        });
    });

    // ══════════════════════════════════════════════════════
    //  DATE RANGE FILTER
    // ══════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        const rangeButton   = document.getElementById('rangeButton');
        const rangeText     = document.getElementById('rangeButtonText');
        const rangeDropdown = document.getElementById('rangeDropdown');
        const rangeInput    = document.getElementById('rangeSelect');
        const form          = rangeInput.closest('form');

        const fpAnchor = document.createElement('input');
        fpAnchor.type  = 'text';
        fpAnchor.style.cssText = 'position:absolute;width:0;height:0;opacity:0;pointer-events:none;';
        document.body.appendChild(fpAnchor);

        const fp = flatpickr(fpAnchor, {
            mode: 'range', dateFormat: 'Y-m-d', allowInput: false,
            clickOpens: false, maxDate: 'today', positionElement: rangeButton,
            onClose(selectedDates, dateStr) {
                if (selectedDates.length === 2 && dateStr) {
                    rangeText.textContent = dateStr.replace(' to ', ' → ');
                    const url = new URL(form.action);
                    url.searchParams.set('range', 'custom');
                    url.searchParams.set('date_range', dateStr);
                    window.location.href = url.toString();
                }
            }
        });

        rangeButton.addEventListener('click', e => {
            e.stopPropagation();
            rangeDropdown.classList.toggle('hidden');
        });

        document.querySelectorAll('.range-option').forEach(option => {
            option.addEventListener('click', function () {
                const value = this.dataset.value;
                const label = this.dataset.label;
                rangeDropdown.classList.add('hidden');
                if (value === 'custom') { fp.open(); return; }
                rangeInput.value = value;
                rangeText.textContent = label;
                const url = new URL(form.action);
                url.searchParams.set('range', value);
                url.searchParams.delete('date_range');
                window.location.href = url.toString();
            });
        });

        document.addEventListener('click', e => {
            if (!rangeButton.contains(e.target) && !rangeDropdown.contains(e.target)) {
                rangeDropdown.classList.add('hidden');
            }
        });
    });
    </script>

@endsection