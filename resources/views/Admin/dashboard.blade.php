@extends('layouts.app')

@section('content')

    <style>
        /* ── Entry animations ── */
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

        @keyframes progressFill {
            from {
                width: 0 !important;
            }
        }

        @keyframes numberPop {
            0% {
                transform: scale(0.82);
                opacity: 0;
            }

            70% {
                transform: scale(1.06);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes barRise {
            from {
                transform: scaleY(0);
                opacity: 0;
            }

            to {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        @keyframes barSlideUp {
            from {
                height: 0 !important;
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes lineDrawIn {
            from {
                stroke-dashoffset: 2000;
            }

            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes areaFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes dotPop {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            70% {
                transform: scale(1.5);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes rowFadeIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes dropdownIn {
            from {
                opacity: 0;
                transform: translateY(-6px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes chartCardReveal {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes donutSpin {
            from {
                transform: rotate(-90deg);
                opacity: 0;
            }

            to {
                transform: rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes legendSlideIn {
            from {
                opacity: 0;
                transform: translateX(12px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes lowerCardReveal {
            from {
                opacity: 0;
                transform: translateY(28px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes productRowIn {
            from {
                opacity: 0;
                transform: translateX(-12px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes glowPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(99, 102, 241, 0.08);
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

        @keyframes spinPulse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

        @keyframes newRowPop {
            0% {
                opacity: 0;
                transform: scaleY(0.5);
                background-color: rgb(209 250 229);
            }

            60% {
                transform: scaleY(1.02);
            }

            100% {
                opacity: 1;
                transform: scaleY(1);
                background-color: transparent;
            }
        }

        @keyframes rowMoveOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(-16px);
            }
        }

        /* Header */
        .dash-header {
            animation: fadeSlideUp .4s ease both;
            position: relative;
            z-index: 30;
        }

        /* Stat cards staggered */
        .stat-card {
            animation: fadeSlideUp .5s ease both;
        }

        .stat-card:nth-child(1) {
            animation-delay: .06s;
        }

        .stat-card:nth-child(2) {
            animation-delay: .14s;
        }

        .stat-card:nth-child(3) {
            animation-delay: .22s;
        }

        .stat-card:nth-child(4) {
            animation-delay: .30s;
        }

        /* ── MAIN CHART CARDS ── */
        .chart-card-main {
            animation: chartCardReveal .6s cubic-bezier(.22, 1, .36, 1) both;
        }

        .chart-card-main:nth-child(1) {
            animation-delay: .38s;
        }

        .chart-card-main:nth-child(2) {
            animation-delay: .50s;
        }

        .bar-chart-area {
            animation: fadeSlideUp .5s .55s ease both;
        }

        .chart-bar-col {
            animation: barSlideUp .55s cubic-bezier(.22, 1, .36, 1) both;
            transform-origin: bottom;
        }

        .rev-line {
            stroke-dasharray: 2000;
            stroke-dashoffset: 2000;
            animation: lineDrawIn 1.6s 1.1s cubic-bezier(.4, 0, .2, 1) forwards;
        }

        .rev-area {
            animation: areaFadeIn .8s 1.8s ease forwards;
            opacity: 0;
        }

        .rev-dot {
            animation: dotPop .45s 2.6s cubic-bezier(.34, 1.56, .64, 1) both;
            transform-origin: center;
        }

        .rev-label {
            animation: fadeSlideUp .4s ease both;
        }

        .progress-bar {
            animation: progressFill .9s .8s cubic-bezier(.4, 0, .2, 1) both;
        }

        /* ── LOWER SECTION ── */
        .lower-card-anim {
            animation: lowerCardReveal .65s cubic-bezier(.22, 1, .36, 1) both;
        }

        .lower-card-anim:nth-child(1) {
            animation-delay: .55s;
        }

        .lower-card-anim:nth-child(2) {
            animation-delay: .68s;
        }

        .product-row-anim {
            animation: productRowIn .35s ease both;
        }

        .product-row-anim:nth-child(1) {
            animation-delay: .62s;
        }

        .product-row-anim:nth-child(2) {
            animation-delay: .68s;
        }

        .product-row-anim:nth-child(3) {
            animation-delay: .74s;
        }

        .product-row-anim:nth-child(4) {
            animation-delay: .80s;
        }

        .product-row-anim:nth-child(5) {
            animation-delay: .86s;
        }

        .product-row-anim:nth-child(6) {
            animation-delay: .92s;
        }

        .donut-inner {
            animation: donutSpin .8s .75s cubic-bezier(.34, 1.1, .64, 1) both;
            transform-origin: center;
        }

        .donut-center {
            animation: fadeSlideUp .4s 1.4s ease both;
        }

        .legend-row {
            animation: legendSlideIn .35s ease both;
        }

        .legend-row:nth-child(1) {
            animation-delay: .78s;
        }

        .legend-row:nth-child(2) {
            animation-delay: .84s;
        }

        .legend-row:nth-child(3) {
            animation-delay: .90s;
        }

        .legend-row:nth-child(4) {
            animation-delay: .96s;
        }

        .legend-row:nth-child(5) {
            animation-delay: 1.02s;
        }

        .count-done {
            animation: numberPop .32s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        #rangeDropdown:not(.hidden) {
            animation: dropdownIn .18s cubic-bezier(.34, 1.3, .64, 1) both;
        }

        .stat-card {
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .donut-wrap:hover .donut-inner {
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, .12));
        }

        .chart-bar-col:hover .bar-inner {
            filter: brightness(1.12) saturate(1.2);
            transform: scaleY(1.03);
            transform-origin: bottom;
        }

        .bar-inner {
            transition: filter .18s ease, transform .18s ease;
        }

        .btn-sm {
            transition: transform .14s ease, box-shadow .14s ease;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
        }

        .btn-sm:active {
            transform: translateY(0);
        }

        /* ── Recent Orders card ── */
        .orders-card-anim {
            animation: chartCardReveal .6s .42s cubic-bezier(.22, 1, .36, 1) both;
        }

        .order-row-anim {
            animation: productRowIn .35s ease both;
        }

        .order-status-badge {
            transition: all .2s ease;
        }

        .action-btn {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .action-btn:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        .action-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .accept-btn:disabled,
        .reject-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinPulse .65s linear infinite;
            vertical-align: middle;
        }

        .toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .75rem 1rem;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            font-size: .8125rem;
            font-weight: 500;
            animation: toastSlide .3s ease;
            min-width: 240px;
        }

        .dark .toast {
            background: #1f2937;
            color: #f3f4f6;
        }

        .toast.leaving {
            animation: toastOut .3s ease forwards;
        }

        .toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .row-moving-out {
            animation: rowMoveOut .25s ease both;
        }

        .new-order-row {
            animation: newRowPop .6s cubic-bezier(.34, 1.2, .64, 1) both;
            transform-origin: top;
        }

        #orderModal.flex {
            animation: overlayIn .2s ease;
        }

        .modal-inner {
            animation: modalIn .25s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .top-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .main-grid {
                grid-template-columns: 1fr;
            }

            .lower-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .top-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="toast-container" id="toastContainer"></div>

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
                    <div class="relative min-w-[130px] z-[200] z-[99999] overflow-visible">
                        <button type="button" id="rangeButton" class="btn-sm w-full flex items-center justify-between gap-3
                                       rounded-xl border border-gray-200 dark:border-gray-700
                                       bg-white dark:bg-gray-800 px-4 py-2.5
                                       text-sm font-medium text-gray-700 dark:text-gray-200
                                       shadow-sm hover:border-indigo-300 dark:hover:border-indigo-500
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <span id="rangeButtonText">
                                @if(request('range') === 'custom' && request('date_range'))
                                    {{ str_replace(' to ', ' → ', request('date_range')) }}
                                @elseif(request('range') === 'today') Today
                                @elseif(request('range') === '7days') Last 7 Days
                                @elseif(request('range') === 'this_month') This Month
                                @elseif(request('range') === 'last_month') Last Month
                                @elseif(request('range') === 'this_year') This Year
                                @else Last 30 Days
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="rangeDropdown" class="hidden absolute right-0 top-full mt-2 w-52 rounded-xl
    border border-gray-200 dark:border-gray-700
    bg-white dark:bg-gray-800 shadow-2xl
    z-[9999] overflow-hidden ">
                            @php
                                $ranges = [
                                    'today' => 'Today',
                                    '7days' => 'Last 7 Days',
                                    '30days' => 'Last 30 Days',
                                    'this_month' => 'This Month',
                                    'last_month' => 'Last Month',
                                    'this_year' => 'This Year',
                                    'custom' => 'Custom Range',
                                ];
                            @endphp
                            @foreach($ranges as $value => $label)
                                <button type="button" class="range-option w-full text-left px-4 py-2.5 text-sm
                                                   text-gray-700 dark:text-gray-200
                                                   hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                                   hover:text-indigo-600 dark:hover:text-indigo-400
                                                   transition-colors" data-value="{{ $value }}" data-label="{{ $label }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="range" id="rangeSelect" value="{{ request('range', '30days') }}">
                </form>
            </div>
        </div>

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="top-cards grid grid-cols-2 lg:grid-cols-4 gap-3 ">

            {{-- Revenue --}}
            <a href="{{ route('reports.sales') }}" class="stat-card group relative block overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5
                        hover:border-emerald-200 dark:hover:border-emerald-500/40
                        transition-all duration-300 p-3 cursor-pointer">

                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                                bg-gradient-to-br from-emerald-50 via-green-50 to-teal-100
                                dark:from-emerald-900/20 dark:via-green-900/20 dark:to-teal-900/20">
                </div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">

                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600
                                        flex items-center justify-center shadow-md shadow-emerald-500/25">

                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">
                                Revenue
                            </h4>

                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">
                                Selected period
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full
                                    bg-gradient-to-r from-emerald-50 to-green-50
                                    dark:from-emerald-900/20 dark:to-green-900/20
                                    text-emerald-600 dark:text-emerald-400
                                    ring-1 ring-emerald-200 dark:ring-emerald-800
                                    shadow-sm text-[10px] font-semibold">

                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>

                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                                bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600
                                bg-clip-text text-transparent" data-count="{{ (int) $totalRevenue }}"
                        data-cents="{{ substr(number_format($totalRevenue, 2), -2) }}">

                        $0<span class="rev-cents text-sm text-gray-400 dark:text-gray-500 font-normal">.00</span>

                    </h2>
                </div>

                <div class="relative mt-2">

                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full
                                        bg-gradient-to-r from-emerald-500 via-green-500 to-teal-600" style="width: 68%">
                        </div>
                    </div>

                    <div class="mt-1 flex items-center justify-between">

                        <span class="text-[10px] text-gray-400 dark:text-gray-500">
                            Sales Report
                        </span>

                        <span class="flex items-center gap-1 text-[10px] font-semibold
                                        text-emerald-600 dark:text-emerald-400
                                        opacity-0 group-hover:opacity-100
                                        translate-x-1 group-hover:translate-x-0
                                        transition-all duration-200">

                            View Report

                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                        </span>

                    </div>

                </div>

            </a>

            {{-- Total Sales --}}
            <a href="{{ route('reports.sales') }}" class="stat-card group relative block overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5
                        hover:border-blue-200 dark:hover:border-blue-500/40
                        transition-all duration-300 p-3 cursor-pointer">

                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                                bg-gradient-to-br from-blue-50 via-indigo-50 to-violet-100
                                dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-violet-900/20">
                </div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">

                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600
                                        flex items-center justify-center shadow-md shadow-blue-500/25">

                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                            </svg>

                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">
                                Total Sales
                            </h4>

                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">
                                Orders placed
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                    bg-blue-50 dark:bg-blue-900/20
                                    text-blue-600 dark:text-blue-400
                                    ring-1 ring-blue-200 dark:ring-blue-800
                                    text-[10px] font-semibold">
                        Orders
                    </span>
                </div>

                <div class="relative mt-2 pl-2">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                                bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600
                                bg-clip-text text-transparent" data-count="{{ $totalSales }}">
                        0
                    </h2>
                </div>

                <div class="relative mt-2">

                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full
                                        bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-600" style="width: 61%">
                        </div>
                    </div>

                    <div class="mt-1 flex items-center justify-between">

                        <span class="text-[10px] text-gray-400 dark:text-gray-500">
                            Sales Report
                        </span>

                        <span class="flex items-center gap-1
                                        text-[10px] font-semibold
                                        text-blue-600 dark:text-blue-400
                                        opacity-0 group-hover:opacity-100
                                        translate-x-1 group-hover:translate-x-0
                                        transition-all duration-200">

                            View Report

                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                        </span>

                    </div>

                </div>

            </a>

            {{-- New Customers --}}
            <a href="{{ route('reports.customers') }}" class="stat-card group relative block overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5
                        hover:border-violet-200 dark:hover:border-violet-500/40
                        transition-all duration-300 p-3 cursor-pointer">

                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                                bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-100
                                dark:from-violet-900/20 dark:via-purple-900/20 dark:to-fuchsia-900/20">
                </div>

                <div class="relative flex items-center justify-between">

                    <div class="flex items-center gap-2">

                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-600
                                        flex items-center justify-center shadow-md shadow-violet-500/25">

                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>

                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">
                                Customers
                            </h4>

                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">
                                Registered period
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                    bg-violet-50 dark:bg-violet-900/20
                                    text-violet-600 dark:text-violet-400
                                    ring-1 ring-violet-200 dark:ring-violet-800
                                    text-[10px] font-semibold">
                        Users
                    </span>

                </div>

                <div class="relative mt-2 pl-2">

                    <h2 class="text-2xl font-bold tracking-tight leading-none
                                bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600
                                bg-clip-text text-transparent" data-count="{{ $totalCustomers }}">
                        0
                    </h2>

                </div>

                <div class="relative mt-2">

                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">

                        <div class="progress-bar h-full w-full rounded-full
                                        bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-600">
                        </div>

                    </div>

                    <div class="mt-1 flex items-center justify-between">

                        <span class="text-[10px] text-gray-400 dark:text-gray-500">
                            All registered
                        </span>

                        <span class="flex items-center gap-1
                                        text-[10px] font-semibold
                                        text-violet-600 dark:text-violet-400
                                        opacity-0 group-hover:opacity-100
                                        translate-x-1 group-hover:translate-x-0
                                        transition-all duration-200">

                            View Report

                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                        </span>

                    </div>

                </div>

            </a>

            {{-- Profit --}}
            <a href="{{ route('reports.sales') }}" class="stat-card group relative block overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5
                        hover:border-pink-200 dark:hover:border-pink-500/40
                        transition-all duration-300 p-3 cursor-pointer">

                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                                bg-gradient-to-br from-pink-50 via-rose-50 to-red-100
                                dark:from-pink-900/20 dark:via-rose-900/20 dark:to-red-900/20">
                </div>

                <div class="relative flex items-center justify-between">

                    <div class="flex items-center gap-2">

                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-pink-500 via-rose-500 to-red-600
                                        flex items-center justify-center shadow-md shadow-pink-500/25">

                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10V6m0 12v-2m-6-6h12" />
                            </svg>

                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">
                                Profit
                            </h4>

                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">
                                Net, selected period
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full
                                    bg-gradient-to-r from-pink-50 to-rose-50
                                    dark:from-pink-900/20 dark:to-rose-900/20
                                    text-pink-600 dark:text-pink-400
                                    ring-1 ring-pink-200 dark:ring-pink-800
                                    shadow-sm text-[10px] font-semibold">
                        Net
                    </span>

                </div>

                <div class="relative mt-2 pl-2">

                    <h2 class="text-2xl font-bold tracking-tight leading-none
                                bg-gradient-to-r from-pink-600 via-rose-600 to-red-600
                                bg-clip-text text-transparent" data-count="{{ (int) $profit }}"
                        data-cents="{{ substr(number_format($profit, 2), -2) }}">

                        $0<span class="profit-cents text-sm text-gray-400 dark:text-gray-500 font-normal">.00</span>

                    </h2>

                </div>

                <div class="relative mt-2">

                    @php
                        $profitMarginPct = ($totalRevenue ?? 0) > 0
                            ? round(($profit / $totalRevenue) * 100)
                            : 0;
                    @endphp

                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">

                        <div class="progress-bar h-full rounded-full
                                        bg-gradient-to-r from-pink-500 via-rose-500 to-red-600"
                            style="width: {{ $profitMarginPct }}%">
                        </div>

                    </div>

                    <div class="mt-1 flex items-center justify-between">

                        <span class="text-[10px] text-gray-400 dark:text-gray-500">
                            {{ $profitMarginPct }}% margin
                        </span>

                        <span class="flex items-center gap-1
                                        text-[10px] font-semibold
                                        text-pink-600 dark:text-pink-400
                                        opacity-0 group-hover:opacity-100
                                        translate-x-1 group-hover:translate-x-0
                                        transition-all duration-200">

                            View Report

                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>

                        </span>

                    </div>

                </div>

            </a>
        </div>

        @php
            $currentRange = request('range', '30days');
            $labelFormat = match ($currentRange) {
                'today' => 'H:i',
                'this_year' => 'M',
                default => 'd',
            };
            $barCount = $chartData->count();
        @endphp

        {{-- ==================== RECENT ORDERS ==================== --}}
        <div class="orders-card-anim bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">

            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700
                            flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Latest activity, all statuses</p>
                </div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium
                              bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                              border border-indigo-100 dark:border-indigo-500/20
                              hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors flex-shrink-0">
                    View all
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr
                            class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-5 py-2.5">Client</th>
                            <th class="px-5 py-2.5">Total</th>
                            <th class="px-5 py-2.5 hidden lg:table-cell">Payment</th>
                            <th class="px-5 py-2.5">Status</th>
                            <th class="px-5 py-2.5 hidden xl:table-cell">Date</th>
                            <th class="px-5 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dashOrdersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentOrders as $i => $order)
                            @php
                                $fullName = $order['full_name'];
                                $initials = strtoupper(substr($fullName, 0, 1));

                                $badge = match ($order['status']) {
                                    'completed' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                    'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                    'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'cancelled' => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                };

                                $paymentStyles = match (strtolower($order['payment_method'] ?? '')) {
                                    'khqr' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                                    'aba' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                    'wing' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                    'cash' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
                                };

                                $canAccept = $order['status'] === 'pending' && $order['id'] == ($firstPendingId ?? null);
                            @endphp

                            <tr id="dash-order-row-{{ $order['id'] }}" data-order-id="{{ $order['id'] }}"
                                data-status="{{ $order['status'] }}"
                                class="order-row-anim hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors"
                                style="animation-delay: {{ 0.05 + $i * 0.06 }}s">

                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2.5">
                                        @if($order['avatar'])
                                            <img src="{{ $order['avatar'] }}" alt="{{ $fullName }}"
                                                class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700
                                                                    text-gray-600 dark:text-gray-300 flex items-center justify-center
                                                                    text-xs font-semibold flex-shrink-0">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <span
                                            class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $fullName }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    ${{ number_format($order['total'], 2) }}
                                </td>

                                <td class="px-5 py-3 hidden lg:table-cell">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold {{ $paymentStyles }}">
                                        {{ strtoupper($order['payment_method'] ?: 'N/A') }}
                                    </span>
                                </td>

                                <td class="px-5 py-3">
                                    <span id="dash-status-badge-{{ $order['id'] }}"
                                        class="order-status-badge inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>

                                <td
                                    class="px-5 py-3 hidden xl:table-cell text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $order['created_at'] }}
                                </td>

                                <td class="px-5 py-3">
                                    <div class="flex justify-end items-center gap-1.5 flex-wrap"
                                        id="dash-actions-{{ $order['id'] }}">

                                        <button type="button" onclick='openDashOrderModal(@json($order))'
                                            class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                           border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                           text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200">
                                            View
                                        </button>

                                        @if($order['status'] === 'pending')
                                            <button type="button" class="dash-accept-btn action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                               border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                               text-blue-600 dark:text-blue-400" data-order-id="{{ $order['id'] }}"
                                                title="{{ $canAccept ? 'Accept this order' : 'Process the older pending order first' }}"
                                                {{ !$canAccept ? 'disabled' : '' }}>
                                                Accept
                                            </button>
                                            <button type="button" class="dash-reject-btn action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                               border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                               text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400
                                                               hover:border-red-200 dark:hover:border-red-500/30"
                                                data-order-id="{{ $order['id'] }}"
                                                title="{{ $canAccept ? 'Reject this order' : 'Process the older pending order first' }}"
                                                {{ !$canAccept ? 'disabled' : '' }}>
                                                Reject
                                            </button>
                                        @endif

                                        @if($order['status'] === 'processing')
                                            <button type="button" onclick="dashConfirmChange({{ $order['id'] }}, 'completed', this)"
                                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                               border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                                                               text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all duration-200">
                                                Successful
                                            </button>
                                            <button type="button" onclick="dashConfirmChange({{ $order['id'] }}, 'cancelled', this)"
                                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                               border border-red-200 dark:border-red-500/30
                                                               bg-red-50 dark:bg-red-500/10
                                                               text-red-600 dark:text-red-400">
                                                Cancel
                                            </button>
                                        @endif

                                        @if(in_array($order['status'], ['processing', 'completed']))
                                            <button type="button" onclick="dashPrintInvoice({{ $order['id'] }})" class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                                               border border-purple-200 dark:border-purple-500/30
                                                               bg-purple-50 dark:bg-purple-500/10
                                                               text-purple-600 dark:text-purple-400">
                                                Print
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No orders in this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile list --}}
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentOrders as $i => $order)
                    @php
                        $fullNameM = $order['full_name'];
                        $badgeM = match ($order['status']) {
                            'completed' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                            'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                            'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                            'cancelled' => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                            default => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                        };
                        $canAcceptM = $order['status'] === 'pending' && $order['id'] == ($firstPendingId ?? null);
                    @endphp
                    <div id="dash-order-row-mobile-{{ $order['id'] }}" data-order-id="{{ $order['id'] }}"
                        data-status="{{ $order['status'] }}" class="order-row-anim p-4 flex flex-col gap-3"
                        style="animation-delay: {{ 0.05 + $i * 0.06 }}s">

                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                @if($order['avatar'])
                                    <img src="{{ $order['avatar'] }}" alt="{{ $fullNameM }}"
                                        class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                            flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($fullNameM, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $fullNameM }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ $order['created_at'] }}</p>
                                </div>
                            </div>
                            <span id="dash-status-badge-mobile-{{ $order['id'] }}"
                                class="order-status-badge flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $badgeM }}">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span
                                class="font-semibold text-gray-900 dark:text-white">${{ number_format($order['total'], 2) }}</span>
                        </div>

                        <div class="flex items-center gap-2 pt-1 flex-wrap" id="dash-actions-mobile-{{ $order['id'] }}">
                            <button type="button" onclick='openDashOrderModal(@json($order))' class="action-btn flex-1 inline-flex items-center justify-center h-8 rounded-lg text-[11px] font-medium
                                               border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                               text-gray-600 dark:text-gray-300">
                                View
                            </button>

                            @if($order['status'] === 'pending')
                                <button type="button" class="dash-accept-btn action-btn flex-1 inline-flex items-center justify-center h-8 rounded-lg text-[11px] font-medium
                                                   border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                   text-blue-600 dark:text-blue-400" data-order-id="{{ $order['id'] }}" {{ !$canAcceptM ? 'disabled' : '' }}>
                                    Accept
                                </button>
                                <button type="button"
                                    class="dash-reject-btn action-btn flex-1 inline-flex items-center justify-center h-8 rounded-lg text-[11px] font-medium
                                                   border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                                    data-order-id="{{ $order['id'] }}" {{ !$canAcceptM ? 'disabled' : '' }}>
                                    Reject
                                </button>
                            @endif

                            @if($order['status'] === 'processing')
                                <button type="button" onclick="dashConfirmChange({{ $order['id'] }}, 'completed', this)" class="action-btn flex-1 inline-flex items-center justify-center h-8 rounded-lg text-[11px] font-medium
                                                   border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                                                   text-emerald-600 dark:text-emerald-400">
                                    Successful
                                </button>
                            @endif

                            @if(in_array($order['status'], ['processing', 'completed']))
                                <button type="button" onclick="dashPrintInvoice({{ $order['id'] }})" class="action-btn w-8 h-8 flex-shrink-0 inline-flex items-center justify-center rounded-lg
                                                   border border-purple-200 dark:border-purple-500/30 bg-purple-50 dark:bg-purple-500/10
                                                   text-purple-600 dark:text-purple-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path d="M6 9V2h12v7" />
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                        <rect x="6" y="14" width="12" height="8" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-xs text-gray-400 dark:text-gray-500">No orders in this period.</div>
                @endforelse
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
                            $revenue = $product->revenue ?? 0;
                            $pct = round(($revenue / $maxProductRevenue) * 100);
                            $image = $product->image->first();
                            $imageUrl = $image?->image_url ? asset($image->image_url) : null;

                            [$barClass, $badgeClass, $badgeText] = match (true) {
                                $pct >= 80 => ['bg-emerald-100 dark:bg-emerald-900/40 border-r-2 border-emerald-500', 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400', 'Top'],
                                $pct >= 60 => ['bg-blue-100 dark:bg-blue-900/40 border-r-2 border-blue-500', 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400', 'High'],
                                $pct >= 40 => ['bg-amber-100 dark:bg-amber-900/40 border-r-2 border-amber-500', 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400', 'Mid'],
                                default => ['bg-gray-200 dark:bg-gray-600', 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400', 'Low'],
                            };
                        @endphp

                        <div class="product-row-anim flex items-center gap-3 px-4 py-2.5
                                            hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="w-9 h-9 rounded-xl object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                            @else
                                <div
                                    class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700
                                                        flex items-center justify-center text-xs font-medium text-gray-500 dark:text-gray-400 flex-shrink-0">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                        <div class="progress-bar h-full rounded-full {{ $barClass }}"
                                            style="width: {{ $pct }}%"></div>
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

            {{-- Sales by Category (donut) --}}
            {{-- <div class="lower-card-anim bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                            rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Sales by Category</h2>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Revenue distribution</p>
                </div>

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
            </div> --}}
            {{-- Total Revenue --}}
            <div class="lower-card-anim bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700
                        rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 h-full flex flex-col">

                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Total Revenue</h2>
                        <div class="mt-2 flex items-end gap-1">
                            <span class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white leading-none">
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

                <div class="mt-4 flex-1">
                    <div class="h-56">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== ORDER DETAIL MODAL (dashboard) ==================== --}}
    <div id="dashOrderModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-0 sm:p-4">
        <div
            class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        w-full h-full sm:h-auto sm:max-w-lg sm:rounded-2xl shadow-2xl flex flex-col sm:max-h-[90vh] overflow-hidden">

            <div class="bg-indigo-700 px-4 sm:px-6 pt-6 pb-12 flex-shrink-0">
                <div class="flex items-start justify-between">
                    <div>
                        <p id="dashModalOrderId"
                            class="text-[11px] font-medium tracking-widest text-indigo-300 uppercase mb-1">Order #—</p>
                        <p id="dashModalOrderTotal" class="text-2xl font-semibold text-white">—</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="dashModalStatusBadge" class="px-3 py-1 rounded-full text-[11px] font-semibold"></span>
                        <button onclick="closeDashOrderModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-center -mt-9 mb-1 relative z-10 flex-shrink-0">
                <div class="relative inline-block">
                    <img id="dashModalAvatar" src="" alt="Customer"
                        class="w-[72px] h-[72px] rounded-[18px] object-cover border-[3px] border-white dark:border-gray-800 shadow-lg">
                    <span id="dashModalStatusDot"
                        class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800"></span>
                </div>
            </div>

            <p id="dashModalCustomerName" class="text-center text-sm font-semibold text-gray-900 dark:text-white mt-2"></p>
            <p id="dashModalCustomerMeta" class="text-center text-xs text-gray-400 dark:text-gray-500 mb-4"></p>

            <div id="dashOrderContent"
                class="flex-1 overflow-y-auto px-4 sm:px-5 pb-5 space-y-3 text-sm text-gray-700 dark:text-gray-300"></div>
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
                    const target = parseInt(el.dataset.count, 10) || 0;
                    const cents = el.dataset.cents;
                    const isPrice = !!cents;
                    const duration = 1100;
                    const start = performance.now();

                    function ease(t) { return 1 - Math.pow(1 - t, 3); }

                    (function tick(now) {
                        const progress = Math.min((now - start) / duration, 1);
                        const current = Math.round(ease(progress) * target);

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
        //  BAR CHART — animated height via JS
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
                    barWrapper.style.height = finalH + 'px';
                }, delay);
            });
        });

        // ══════════════════════════════════════════════════════
        //  DATE RANGE FILTER
        // ══════════════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function () {
            const rangeButton = document.getElementById('rangeButton');
            const rangeText = document.getElementById('rangeButtonText');
            const rangeDropdown = document.getElementById('rangeDropdown');
            const rangeInput = document.getElementById('rangeSelect');
            const form = rangeInput.closest('form');

            const fpAnchor = document.createElement('input');
            fpAnchor.type = 'text';
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const canvas = document.getElementById("revenueChart");
            if (!canvas) return;
            const ctx = canvas.getContext("2d");

            const gradient = ctx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(16,185,129,.25)');
            gradient.addColorStop(1, 'rgba(16,185,129,0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($revenueChartData as $item)
                            "{{ \Carbon\Carbon::parse($item->date)->format('d M') }}",
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Revenue',
                        data: [
                            @foreach($revenueChartData as $item)
                                {{ $item->total }},
                            @endforeach
                        ],
                        borderColor: '#10B981',
                        backgroundColor: gradient,
                        fill: true,
                        borderWidth: 3,
                        tension: .45,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 3,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#10B981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 1500 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 14,
                            cornerRadius: 12,
                            displayColors: true,
                            callbacks: {
                                title: function (context) { return context[0].label; },
                                label: function (context) { return ' Revenue : $' + Number(context.parsed.y).toFixed(2); }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#9CA3AF' } },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F3F4F6' },
                            ticks: { color: '#9CA3AF', callback: function (value) { return '$' + value; } }
                        }
                    }
                }
            });
        });
    </script>

    <script defer>
        // ══════════════════════════════════════════════════════
        //  DASHBOARD RECENT ORDERS — ACTIONS
        //  Mirrors the FIFO + AJAX status-change logic from the
        //  main Orders page, scoped with a "dash" prefix so IDs
        //  never collide if both views are ever composed together.
        // ══════════════════════════════════════════════════════
        window.dashFirstPendingId = {{ $firstPendingId ?? 'null' }};

        function dashShowToast(message, type = 'success') {
            const colors = { success: '#10b981', error: '#ef4444', info: '#6366f1', warning: '#f59e0b' };
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `<span class="toast-dot" style="background:${colors[type] ?? colors.info}"></span><span>${message}</span>`;
            document.getElementById('toastContainer').appendChild(toast);
            setTimeout(() => {
                toast.classList.add('leaving');
                toast.addEventListener('animationend', () => toast.remove(), { once: true });
            }, 3500);
        }

        function dashShowModal() {
            const m = document.getElementById('dashOrderModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function dashHideModal() {
            const m = document.getElementById('dashOrderModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.getElementById('dashOrderModal').addEventListener('click', function (e) {
            if (e.target === this) dashHideModal();
        });
        function closeDashOrderModal() { dashHideModal(); }

        function openDashOrderModal(order) {
            const fullName = order.full_name ?? 'Customer';

            document.getElementById('dashModalOrderId').textContent = 'Order #' + order.id;
            document.getElementById('dashModalOrderTotal').textContent = '$' + parseFloat(order.total).toFixed(2);

            const statusBadge = document.getElementById('dashModalStatusBadge');
            const statusColors = {
                pending: 'bg-amber-400/20 text-amber-200',
                processing: 'bg-blue-400/20 text-blue-200',
                completed: 'bg-emerald-400/20 text-emerald-200',
                cancelled: 'bg-red-400/20 text-red-200',
            };
            statusBadge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold ' + (statusColors[order.status] || 'bg-white/10 text-white');
            statusBadge.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);

            const avatarEl = document.getElementById('dashModalAvatar');
            avatarEl.src = (order.avatar && order.avatar.trim())
                ? order.avatar
                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(fullName || 'Customer') + '&background=4338ca&color=fff&size=72&bold=true';

            const dotEl = document.getElementById('dashModalStatusDot');
            const dotClrs = { pending: 'bg-amber-400', processing: 'bg-blue-500', completed: 'bg-emerald-500', cancelled: 'bg-red-400' };
            dotEl.className = 'absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 ' + (dotClrs[order.status] || 'bg-gray-400');

            document.getElementById('dashModalCustomerName').textContent = fullName;
            document.getElementById('dashModalCustomerMeta').textContent = [order.phone, order.created_at].filter(Boolean).join(' · ');

            const isBlockedPending = order.status === 'pending' && order.id !== window.dashFirstPendingId;

            let actionBtns = '';
            if (isBlockedPending) {
                actionBtns = `
                        <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400 text-xs">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Please process the older pending order first.
                        </div>`;
            } else {
                actionBtns = [
                    order.status === 'pending' ? `
                            <button onclick="dashConfirmChange(${order.id}, 'processing', this)"
                                class="action-btn flex-1 py-2 text-xs font-medium rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">
                                Accept order
                            </button>` : '',
                    order.status === 'processing' ? `
                            <button onclick="dashConfirmChange(${order.id}, 'completed', this)"
                                class="action-btn flex-1 py-2 text-xs font-medium rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                Mark complete
                            </button>` : '',
                    ['pending', 'processing'].includes(order.status) ? `
                            <button onclick="dashConfirmChange(${order.id}, 'cancelled', this)"
                                class="action-btn flex-1 py-2 text-xs font-medium rounded-xl border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                Cancel order
                            </button>` : '',
                ].filter(Boolean).join('');
            }

            document.getElementById('dashOrderContent').innerHTML = `
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-4 space-y-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Payment</p>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Method</span>
                            <span class="font-medium text-gray-900 dark:text-white">${order.payment_method ? order.payment_method.toUpperCase() : '—'}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-gray-100 dark:border-gray-700 pt-2">
                            <span class="text-gray-500 dark:text-gray-400">Total</span>
                            <span class="font-semibold text-gray-900 dark:text-white">$${parseFloat(order.total).toFixed(2)}</span>
                        </div>
                    </div>
                    ${actionBtns ? `<div class="flex gap-2 pt-1">${actionBtns}</div>` : ''}
                `;

            dashShowModal();
        }

        // ══════════════════════════════════════════════════════
        //  FIFO BUTTON STATE
        // ══════════════════════════════════════════════════════
        function dashRefreshFifoButtons() {
            document.querySelectorAll('.dash-accept-btn, .dash-reject-btn').forEach(btn => {
                btn.disabled = true;
            });

            const tbody = document.getElementById('dashOrdersTableBody');
            if (tbody) {
                const firstPendingRow = tbody.querySelector('tr[data-status="pending"]');
                if (firstPendingRow) {
                    firstPendingRow.querySelectorAll('.dash-accept-btn, .dash-reject-btn').forEach(btn => btn.disabled = false);
                    window.dashFirstPendingId = parseInt(firstPendingRow.dataset.orderId, 10);
                } else {
                    window.dashFirstPendingId = null;
                }
            }

            if (window.dashFirstPendingId) {
                const mobileRow = document.getElementById('dash-order-row-mobile-' + window.dashFirstPendingId);
                if (mobileRow) {
                    mobileRow.querySelectorAll('.dash-accept-btn, .dash-reject-btn').forEach(btn => btn.disabled = false);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', dashRefreshFifoButtons);

        document.addEventListener('click', function (e) {
            const acceptBtn = e.target.closest('.dash-accept-btn');
            if (acceptBtn && !acceptBtn.disabled) {
                dashConfirmChange(parseInt(acceptBtn.dataset.orderId, 10), 'processing', acceptBtn);
                return;
            }
            const rejectBtn = e.target.closest('.dash-reject-btn');
            if (rejectBtn && !rejectBtn.disabled) {
                dashConfirmChange(parseInt(rejectBtn.dataset.orderId, 10), 'cancelled', rejectBtn);
            }
        });

        // ══════════════════════════════════════════════════════
        //  AJAX STATUS CHANGE
        // ══════════════════════════════════════════════════════
        const DASH_STATUS_CONFIG = {
            processing: { title: 'Accept this order?', confirmText: 'Accept', confirmColor: '#3b82f6', badge: 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', toast: 'Order accepted!', toastType: 'info' },
            completed: { title: 'Mark as completed?', confirmText: 'Complete', confirmColor: '#10b981', badge: 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', toast: 'Order marked as completed.', toastType: 'success' },
            cancelled: { title: 'Cancel this order?', confirmText: 'Yes, cancel', confirmColor: '#ef4444', badge: 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400', toast: 'Order cancelled.', toastType: 'warning' },
        };

        function dashConfirmChange(orderId, newStatus, triggerBtn) {
            const cfg = DASH_STATUS_CONFIG[newStatus] || { title: 'Confirm?', confirmText: 'Yes', confirmColor: '#6366f1', toast: 'Done.', toastType: 'info' };

            Swal.fire({
                title: 'Confirm', text: cfg.title, icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: cfg.confirmColor, cancelButtonColor: '#6b7280',
                confirmButtonText: cfg.confirmText,
            }).then(result => {
                if (!result.isConfirmed) return;

                document.querySelectorAll('.dash-accept-btn, .dash-reject-btn').forEach(btn => btn.disabled = true);

                let origHTML = null;
                if (triggerBtn) {
                    origHTML = triggerBtn.innerHTML;
                    triggerBtn.innerHTML = `<span class="btn-spinner"></span>`;
                }

                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ status: newStatus }),
                })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (res.status === 422) {
                            dashShowToast(data.error || 'Please process the oldest pending order first.', 'error');
                            dashRefreshFifoButtons();
                            return null;
                        }
                        if (!res.ok) throw new Error(res.status);
                        return data;
                    })
                    .then((data) => {
                        if (!data) return;

                        const badge = document.getElementById('dash-status-badge-' + orderId);
                        if (badge && cfg.badge) {
                            badge.className = 'order-status-badge inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium ' + cfg.badge;
                            badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        }
                        const badgeM = document.getElementById('dash-status-badge-mobile-' + orderId);
                        if (badgeM && cfg.badge) {
                            badgeM.className = 'order-status-badge flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium ' + cfg.badge;
                            badgeM.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        }

                        dashUpdateRowActions(orderId, newStatus);
                        closeDashOrderModal();
                        dashShowToast(cfg.toast, cfg.toastType);
                        dashRefreshFifoButtons();
                    })
                    .catch(err => {
                        console.error(err);
                        dashShowToast('Something went wrong. Please try again.', 'error');
                    })
                    .finally(() => {
                        if (triggerBtn && origHTML !== null && document.body.contains(triggerBtn)) {
                            triggerBtn.innerHTML = origHTML;
                        }
                    });
            });
        }

        function dashUpdateRowActions(orderId, newStatus) {
            const row = document.getElementById('dash-order-row-' + orderId);
            if (row) row.dataset.status = newStatus;
            const rowM = document.getElementById('dash-order-row-mobile-' + orderId);
            if (rowM) rowM.dataset.status = newStatus;

            const container = document.getElementById('dash-actions-' + orderId);
            if (container) {
                const viewBtn = container.querySelector('button:first-child');
                let html = '';

                if (newStatus === 'processing') {
                    html = `
                            <button type="button" onclick="dashConfirmChange(${orderId}, 'completed', this)"
                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                    border border-emerald-200 bg-emerald-50 text-emerald-600">
                                Successful
                            </button>
                            <button type="button" onclick="dashConfirmChange(${orderId}, 'cancelled', this)"
                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                    border border-red-200 bg-red-50 text-red-600">
                                Cancel
                            </button>
                            <button type="button" onclick="dashPrintInvoice(${orderId})"
                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                    border border-purple-200 bg-purple-50 text-purple-600">
                                Print
                            </button>
                        `;
                }

                if (newStatus === 'completed') {
                    html = `
                            <button type="button" onclick="dashPrintInvoice(${orderId})"
                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                                    border border-purple-200 bg-purple-50 text-purple-600">
                                Print
                            </button>
                        `;
                }

                container.innerHTML = '';
                if (viewBtn) container.appendChild(viewBtn);
                container.insertAdjacentHTML('beforeend', html);
            }

            const containerM = document.getElementById('dash-actions-mobile-' + orderId);
            if (containerM) {
                const viewBtnM = containerM.querySelector('button:first-child');
                let htmlM = '';

                if (newStatus === 'processing') {
                    htmlM = `
                            <button type="button" onclick="dashConfirmChange(${orderId}, 'completed', this)"
                                class="action-btn flex-1 inline-flex items-center justify-center h-8 rounded-lg text-[11px] font-medium
                                    border border-emerald-200 bg-emerald-50 text-emerald-600">
                                Successful
                            </button>
                            <button type="button" onclick="dashPrintInvoice(${orderId})"
                                class="action-btn w-8 h-8 flex-shrink-0 inline-flex items-center justify-center rounded-lg
                                    border border-purple-200 bg-purple-50 text-purple-600">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                                </svg>
                            </button>
                        `;
                }

                if (newStatus === 'completed') {
                    htmlM = `
                            <button type="button" onclick="dashPrintInvoice(${orderId})"
                                class="action-btn w-8 h-8 flex-shrink-0 inline-flex items-center justify-center rounded-lg
                                    border border-purple-200 bg-purple-50 text-purple-600">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                                </svg>
                            </button>
                        `;
                }

                containerM.innerHTML = '';
                if (viewBtnM) containerM.appendChild(viewBtnM);
                containerM.insertAdjacentHTML('beforeend', htmlM);
            }
        }

        function dashPrintInvoice(orderId) {
            let iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = `/admin/orders/${orderId}/invoice`;
            document.body.appendChild(iframe);
            iframe.onload = function () {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }
    </script>

@endsection