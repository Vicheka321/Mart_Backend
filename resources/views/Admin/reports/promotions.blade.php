@extends('layouts.app')

@section('title', 'Promotion Report')

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

        .kpi-card:nth-child(7) {
            animation-delay: .35s;
        }

        .kpi-card:nth-child(8) {
            animation-delay: .40s;
        }

        .kpi-card:nth-child(9) {
            animation-delay: .45s;
        }

        .insight-card {
            animation: fadeSlideUp .5s ease both;
        }

        .insight-card:nth-child(1) {
            animation-delay: .32s;
        }

        .insight-card:nth-child(2) {
            animation-delay: .37s;
        }

        .insight-card:nth-child(3) {
            animation-delay: .42s;
        }

        .insight-card:nth-child(4) {
            animation-delay: .47s;
        }

        .insight-card:nth-child(5) {
            animation-delay: .52s;
        }

        .insight-card:nth-child(6) {
            animation-delay: .57s;
        }

        .insight-card:nth-child(7) {
            animation-delay: .62s;
        }

        .insight-card:nth-child(8) {
            animation-delay: .67s;
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
            animation: fadeSlideUp .5s .40s ease both;
        }

        #promotionsTableBody tr {
            animation: rowSlideIn .35s ease both;
        }

        #promotionsTableBody tr:nth-child(1) {
            animation-delay: .42s;
        }

        #promotionsTableBody tr:nth-child(2) {
            animation-delay: .47s;
        }

        #promotionsTableBody tr:nth-child(3) {
            animation-delay: .52s;
        }

        #promotionsTableBody tr:nth-child(4) {
            animation-delay: .57s;
        }

        #promotionsTableBody tr:nth-child(5) {
            animation-delay: .62s;
        }

        #promotionsTableBody tr:nth-child(6) {
            animation-delay: .67s;
        }

        #promotionsTableBody tr:nth-child(7) {
            animation-delay: .72s;
        }

        #promotionsTableBody tr:nth-child(8) {
            animation-delay: .77s;
        }

        #promotionsTableBody tr:nth-child(9) {
            animation-delay: .82s;
        }

        #promotionsTableBody tr:nth-child(10) {
            animation-delay: .87s;
        }

        .progress-bar {
            animation: progressFill .9s .65s cubic-bezier(.4, 0, .2, 1) both;
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
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Promotion Report</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Analyze promotion performance, discount usage and campaign effectiveness.
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

        {{-- ==================== PROMOTION KPI CARDS ==================== --}}
        @php
            $kpis = [
                [
                    'label' => 'Total Promotions',
                    'value' => number_format($totalPromotions),
                    'sub' => 'All campaigns',
                    'from' => 'from-indigo-500',
                    'to' => 'to-violet-600',
                    'bg' => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
                    'pct' => 100,
                ],
                // [
                //     'label' => 'Active',
                //     'value' => number_format($activePromotions),
                //     'sub' => 'Running now',
                //     'from' => 'from-emerald-500',
                //     'to' => 'to-green-600',
                //     'bg' => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                //     'pct' => $totalPromotions > 0 ? round(($activePromotions / $totalPromotions) * 100) : 0,
                // ],
                // [
                //     'label' => 'Scheduled',
                //     'value' => number_format($scheduledPromotions),
                //     'sub' => 'Upcoming campaigns',
                //     'from' => 'from-blue-500',
                //     'to' => 'to-indigo-600',
                //     'bg' => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                //     'pct' => $totalPromotions > 0 ? round(($scheduledPromotions / $totalPromotions) * 100) : 0,
                // ],
                // [
                //     'label' => 'Expired',
                //     'value' => number_format($expiredPromotions),
                //     'sub' => 'Finished campaigns',
                //     'from' => 'from-rose-500',
                //     'to' => 'to-red-600',
                //     'bg' => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                //     'pct' => $totalPromotions > 0 ? round(($expiredPromotions / $totalPromotions) * 100) : 0,
                // ],
                [
                    'label' => 'Products Included',
                    'value' => number_format($totalProducts),
                    'sub' => 'Under promotions',
                    'from' => 'from-cyan-500',
                    'to' => 'to-blue-600',
                    'bg' => 'from-cyan-50 to-blue-100 dark:from-cyan-900/20 dark:to-blue-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                    'pct' => 100,
                ],
                [
                    'label' => 'Avg Discount',
                    'value' => number_format($averageDiscount, 1) . '%',
                    'sub' => 'Across all promos',
                    'from' => 'from-orange-500',
                    'to' => 'to-amber-600',
                    'bg' => 'from-orange-50 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    'pct' => min((int) $averageDiscount, 100),
                ],
                [
                    'label' => 'Highest Discount',
                    'value' => number_format($highestDiscount, 0) . '%',
                    'sub' => 'Maximum value',
                    'from' => 'from-pink-500',
                    'to' => 'to-rose-600',
                    'bg' => 'from-pink-50 to-rose-100 dark:from-pink-900/20 dark:to-rose-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>',
                    'pct' => min((int) $highestDiscount, 100),
                ],
                [
                    'label' => 'Best Promotion',
                    'value' => Str::limit($bestPromotion->title ?? '—', 14),
                    'sub' => ($bestPromotion->discount_value ?? 0) . '% discount',
                    'from' => 'from-purple-500',
                    'to' => 'to-violet-600',
                    'bg' => 'from-purple-50 to-violet-100 dark:from-purple-900/20 dark:to-violet-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
                    'pct' => 100,
                ],
                [
                    'label' => 'Most Products',
                    'value' => Str::limit($mostProductsPromotion->title ?? '—', 14),
                    'sub' => ($mostProductsPromotion->products_count ?? 0) . ' products',
                    'from' => 'from-teal-500',
                    'to' => 'to-cyan-600',
                    'bg' => 'from-teal-50 to-cyan-100 dark:from-teal-900/20 dark:to-cyan-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                    'pct' => 100,
                ],
            ];
        @endphp

        <div>
            <div class="flex items-center gap-2 mb-2">
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion KPIs</h2>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-6 gap-3">
                @foreach($kpis as $kpi)
                    <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                                            border border-gray-100 dark:border-gray-700
                                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                        <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                                        flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        {!! $kpi['icon'] !!}
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight truncate">
                                        {{ $kpi['label'] }}</h4>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight truncate">
                                        {{ $kpi['sub'] }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full flex-shrink-0 ml-1
                                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                                     text-white text-[10px] font-semibold opacity-80">
                                {{ $kpi['pct'] }}%
                            </span>
                        </div>
                        <div class="relative mt-2 pl-2">
                            <h2
                                class="text-xl font-bold tracking-tight
                                                   bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none truncate">
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
        </div>

        {{-- ==================== COUPON KPI CARDS ==================== --}}
        @php
            $couponKpis = [
                [
                    'label' => 'Total Coupons',
                    'value' => number_format($totalCoupons),
                    'sub' => 'All coupon codes',
                    'from' => 'from-fuchsia-500',
                    'to' => 'to-pink-600',
                    'bg' => 'from-fuchsia-50 to-pink-100 dark:from-fuchsia-900/20 dark:to-pink-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v3a2 2 0 01-2 2 2 2 0 012 2v3a2 2 0 002 2h2m6-14h2a2 2 0 012 2v3a2 2 0 002 2 2 2 0 00-2 2v3a2 2 0 01-2 2h-2"/>',
                    'pct' => 100,
                ],
                // [
                //     'label' => 'Active',
                //     'value' => number_format($activeCoupons),
                //     'sub' => 'Redeemable now',
                //     'from' => 'from-emerald-500',
                //     'to' => 'to-green-600',
                //     'bg' => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                //     'pct' => $totalCoupons > 0 ? round(($activeCoupons / $totalCoupons) * 100) : 0,
                // ],
                // [
                //     'label' => 'Scheduled',
                //     'value' => number_format($scheduledCoupons),
                //     'sub' => 'Upcoming codes',
                //     'from' => 'from-blue-500',
                //     'to' => 'to-indigo-600',
                //     'bg' => 'from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                //     'pct' => $totalCoupons > 0 ? round(($scheduledCoupons / $totalCoupons) * 100) : 0,
                // ],
                // [
                //     'label' => 'Expired',
                //     'value' => number_format($expiredCoupons),
                //     'sub' => 'No longer valid',
                //     'from' => 'from-rose-500',
                //     'to' => 'to-red-600',
                //     'bg' => 'from-rose-50 to-red-100 dark:from-rose-900/20 dark:to-red-900/20',
                //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                //     'pct' => $totalCoupons > 0 ? round(($expiredCoupons / $totalCoupons) * 100) : 0,
                // ],
                [
                    'label' => 'Avg Discount',
                    'value' => number_format($averageCouponDiscount, 1) . '%',
                    'sub' => 'Across all coupons',
                    'from' => 'from-orange-500',
                    'to' => 'to-amber-600',
                    'bg' => 'from-orange-50 to-amber-100 dark:from-orange-900/20 dark:to-amber-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                    'pct' => min((int) $averageCouponDiscount, 100),
                ],
                [
                    'label' => 'Highest Discount',
                    'value' => number_format($highestCouponDiscount, 0) . '%',
                    'sub' => 'Maximum value',
                    'from' => 'from-pink-500',
                    'to' => 'to-rose-600',
                    'bg' => 'from-pink-50 to-rose-100 dark:from-pink-900/20 dark:to-rose-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>',
                    'pct' => min((int) $highestCouponDiscount, 100),
                ],
                [
                    'label' => 'Total Usage',
                    'value' => number_format($totalCouponUsage),
                    'sub' => 'Times redeemed',
                    'from' => 'from-cyan-500',
                    'to' => 'to-blue-600',
                    'bg' => 'from-cyan-50 to-blue-100 dark:from-cyan-900/20 dark:to-blue-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'pct' => 100,
                ],
                [
                    'label' => 'Discount Given',
                    'value' => '$' . number_format($totalCouponDiscountGiven, 2),
                    'sub' => 'Total redeemed value',
                    'from' => 'from-violet-500',
                    'to' => 'to-purple-600',
                    'bg' => 'from-violet-50 to-purple-100 dark:from-violet-900/20 dark:to-purple-900/20',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/>',
                    'pct' => 100,
                ],
            ];
        @endphp

        <div style="animation: fadeSlideUp .4s .06s ease both;">
            <div class="flex items-center gap-2 mb-2">
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-fuchsia-500 to-pink-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v3a2 2 0 01-2 2 2 2 0 012 2v3a2 2 0 002 2h2m6-14h2a2 2 0 012 2v3a2 2 0 002 2 2 2 0 00-2 2v3a2 2 0 01-2 2h-2" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Coupon KPIs</h2>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">
                @foreach($couponKpis as $kpi)
                    <div class="kpi-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                                            border border-gray-100 dark:border-gray-700
                                            shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                        <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $kpi['bg'] }}"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }}
                                                        flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        {!! $kpi['icon'] !!}
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight truncate">
                                        {{ $kpi['label'] }}</h4>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight truncate">
                                        {{ $kpi['sub'] }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full flex-shrink-0 ml-1
                                                     bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }}
                                                     text-white text-[10px] font-semibold opacity-80">
                                {{ $kpi['pct'] }}%
                            </span>
                        </div>
                        <div class="relative mt-2 pl-2">
                            <h2
                                class="text-xl font-bold tracking-tight
                                                   bg-gradient-to-r {{ $kpi['from'] }} {{ $kpi['to'] }} bg-clip-text text-transparent leading-none truncate">
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
                <a href="{{ route('reports.promotions') }}"
                    class="text-xs text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-colors font-medium">
                    Reset all
                </a>
            </div>

            <form method="GET" action="{{ route('reports.promotions') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

                    {{-- Promo Type --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Promo
                            Type</label>
                        <select name="type" class="filter-select">
                            <option value="">All types</option>
                            <option value="percent" @selected(request('type') == 'percent')>Percentage</option>
                            <option value="fixed" @selected(request('type') == 'fixed')>Fixed Amount</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="filter-select">
                            <option value="">All statuses</option>
                            <option value="active" @selected(request('status') == 'active')>Active</option>
                            <option value="scheduled" @selected(request('status') == 'scheduled')>Scheduled</option>
                            <option value="expired" @selected(request('status') == 'expired')>Expired</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Sort
                            By</label>
                        <select name="sort" class="filter-select">
                            <option value="">Latest first</option>
                            <option value="highest_discount" @selected(request('sort') == 'highest_discount')>Highest Discount
                            </option>
                        </select>
                    </div>

                    {{-- Date Range --}}
                    <div>
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Date
                            Range</label>
                        <div class="flex gap-1.5">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-select"
                                placeholder="From">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-select"
                                placeholder="To">
                        </div>
                    </div>

                    {{-- Keyword --}}
                    <div class="sm:col-span-2 xl:col-span-4">
                        <label
                            class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Keyword</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                                </svg>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    placeholder="Promotion title, description…" class="filter-select pl-8">
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
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Campaign Insights</h2>
            </div>
            <div class="p-4 sm:p-5">
                @php
                    $insights = [
                        [
                            'label' => 'Best Promotion',
                            'value' => Str::limit($bestPromotion->title ?? '—', 18),
                            'sub' => ($bestPromotion->discount_value ?? 0) . '% discount',
                            'from' => 'from-indigo-500',
                            'to' => 'to-violet-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
                        ],
                        [
                            'label' => 'Highest Discount',
                            'value' => number_format($highestDiscount, 0) . '%',
                            'sub' => 'Maximum value',
                            'from' => 'from-pink-500',
                            'to' => 'to-rose-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>',
                        ],
                        [
                            'label' => 'Most Products',
                            'value' => Str::limit($bestPromotion->title ?? '—', 14),
                            'sub' => ($bestPromotion->products_count ?? 0) . ' products',
                            'from' => 'from-cyan-500',
                            'to' => 'to-blue-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                        ],
                        [
                            'label' => 'Ending Soon',
                            'value' => Str::limit($endingSoon->title ?? '—', 14),
                            'sub' => optional($endingSoon)->end_date?->format('d M Y') ?? '—',
                            'from' => 'from-rose-500',
                            'to' => 'to-red-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                        [
                            'label' => 'Latest Promo',
                            'value' => Str::limit($latestPromotion->title ?? '—', 14),
                            'sub' => optional($latestPromotion)->created_at?->format('d M Y') ?? '—',
                            'from' => 'from-blue-500',
                            'to' => 'to-indigo-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>',
                        ],
                        [
                            'label' => 'Avg Discount',
                            'value' => number_format($averageDiscount, 1) . '%',
                            'sub' => 'All campaigns',
                            'from' => 'from-orange-500',
                            'to' => 'to-amber-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                        ],
                        [
                            'label' => 'Active Now',
                            'value' => number_format($activePromotions),
                            'sub' => 'Running campaigns',
                            'from' => 'from-emerald-500',
                            'to' => 'to-green-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                        ],
                        [
                            'label' => 'Expired',
                            'value' => number_format($expiredPromotions),
                            'sub' => 'Finished campaigns',
                            'from' => 'from-gray-500',
                            'to' => 'to-gray-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
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
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    {!! $ins['icon'] !!}
                                </svg>
                            </div>
                            <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ $ins['label'] }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5 truncate"
                                title="{{ $ins['value'] }}">{{ $ins['value'] }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $ins['sub'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== CHARTS ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">

            {{-- Promotion Trend --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion Trend</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Campaigns created over time</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="promotionTrendChart"></canvas></div>
                </div>
            </div>

            {{-- Status Doughnut --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion Status</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Active · Scheduled · Expired</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="promotionStatusChart"></canvas></div>
                </div>
            </div>

            {{-- Types Horizontal Bar --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion Types</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Percentage vs Fixed discount</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="promotionTypeChart"></canvas></div>
                </div>
            </div>

            {{-- Top Discounts Bar --}}
            <div
                class="chart-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Top Discounts</h2>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Highest promotion values</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="h-64"><canvas id="promotionDiscountChart"></canvas></div>
                </div>
            </div>

        </div>

        {{-- ==================== PROMOTIONS TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                            border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion Campaigns</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ number_format($promotions->total()) }} promotions found
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr
                            class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Promotion</th>
                            <th class="px-6 py-3 text-center">Type</th>
                            <th class="px-6 py-3 text-center">Discount</th>
                            <th class="px-6 py-3 text-center">Products</th>
                            <th class="px-6 py-3 text-center">Start Date</th>
                            <th class="px-6 py-3 text-center">End Date</th>
                            <th class="px-6 py-3 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody id="promotionsTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($promotions as $promotion)
                            @php
                                if (now()->lt($promotion->start_date)) {
                                    $statusLabel = 'Scheduled';
                                    $statusDot = 'bg-blue-500';
                                    $statusBg = 'bg-blue-50 dark:bg-blue-500/10';
                                    $statusText = 'text-blue-600 dark:text-blue-400';
                                } elseif (now()->gt($promotion->end_date)) {
                                    $statusLabel = 'Expired';
                                    $statusDot = 'bg-rose-500';
                                    $statusBg = 'bg-rose-50 dark:bg-rose-500/10';
                                    $statusText = 'text-rose-600 dark:text-rose-400';
                                } else {
                                    $statusLabel = 'Active';
                                    $statusDot = 'bg-emerald-500';
                                    $statusBg = 'bg-emerald-50 dark:bg-emerald-500/10';
                                    $statusText = 'text-emerald-600 dark:text-emerald-400';
                                }

                                $isPercent = $promotion->discount_type === 'percent';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Promotion --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                                                flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $promotion->title }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">
                                                {{ Str::limit($promotion->description, 48) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-6 py-4 text-center">
                                    @if($isPercent)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                                     bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                            Percentage
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                                     bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                            Fixed
                                        </span>
                                    @endif
                                </td>

                                {{-- Discount --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        @if($isPercent)
                                            {{ $promotion->discount_value }}%
                                        @else
                                            ${{ number_format($promotion->discount_value, 2) }}
                                        @endif
                                    </span>
                                </td>

                                {{-- Products --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($promotion->products_count) }}
                                    </span>
                                </td>

                                {{-- Start Date --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded-md bg-indigo-50 dark:bg-indigo-500/10
                                                                flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            {{ optional($promotion->start_date)->format('d M Y') ?? '—' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- End Date --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded-md bg-rose-50 dark:bg-rose-500/10
                                                                flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-rose-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            {{ optional($promotion->end_date)->format('d M Y') ?? '—' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $statusBg }} {{ $statusText }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No promotions found. Try adjusting
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
                    @if($promotions->total())
                        Showing
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ $promotions->firstItem() }}–{{ $promotions->lastItem() }}</span>
                        of
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($promotions->total()) }}</span>
                        results
                    @else
                        No records found
                    @endif
                </p>

                @if($promotions->hasPages())
                    <nav class="flex items-center gap-1">
                        @if($promotions->onFirstPage())
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $promotions->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        @foreach($promotions->getUrlRange(max(1, $promotions->currentPage() - 2), min($promotions->lastPage(), $promotions->currentPage() + 2)) as $page => $url)
                            @if($page == $promotions->currentPage())
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

                        @if($promotions->hasMorePages())
                            <a href="{{ $promotions->nextPageUrl() }}"
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
                <a href="{{ route('reports.promotions.export.csv') }}" class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                                  hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
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
                <a href="{{ route('reports.promotions.export.pdf') }}" class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
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

                // Trend — filled area line
                new Chart(document.getElementById('promotionTrendChart'), {
                    type: 'line',
                    data: {
                        labels: @json($promotionTrend->pluck('day')),
                        datasets: [{
                            data: @json($promotionTrend->pluck('total')),
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

                // Status — doughnut
                new Chart(document.getElementById('promotionStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($statusChart)),
                        datasets: [{
                            data: @json(array_values($statusChart)),
                            backgroundColor: ['#10B981', '#6366F1', '#EF4444'],
                            borderWidth: 0,
                        }]
                    },
                    options: baseOpts(),
                });

                // Types — horizontal bar
                new Chart(document.getElementById('promotionTypeChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($typeChart->pluck('discount_type')),
                        datasets: [{
                            label: 'Promotions',
                            data: @json($typeChart->pluck('total')),
                            backgroundColor: '#8B5CF6',
                            borderRadius: 6,
                        }]
                    },
                    options: { ...baseOpts(false, true), indexAxis: 'y' },
                });

                // Top Discounts — vertical bar
                new Chart(document.getElementById('promotionDiscountChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($topDiscountChart->pluck('name')),
                        datasets: [{
                            label: 'Discount %',
                            data: @json($topDiscountChart->pluck('discount_value')),
                            backgroundColor: '#F97316',
                            borderRadius: 6,
                        }]
                    },
                    options: { ...baseOpts(false, true) },
                });
            });
        </script>
    @endpush

@endsection