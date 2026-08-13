@extends('layouts.app')

@section('content')
    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(.94);
            }

            to {
                opacity: 1;
                transform: scale(1);
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

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes barGrow {
            from {
                width: 0 !important;
            }
        }

        .sr-page {
            animation: fadeSlideUp .4s ease both;
        }

        .sr-kpi {
            animation: fadeSlideUp .5s ease both;
        }

        .sr-kpi:nth-child(1) {
            animation-delay: .04s
        }

        .sr-kpi:nth-child(2) {
            animation-delay: .09s
        }

        .sr-kpi:nth-child(3) {
            animation-delay: .14s
        }

        .sr-kpi:nth-child(4) {
            animation-delay: .19s
        }

        .sr-panel {
            animation: fadeSlideUp .5s .18s ease both;
        }

        .sr-side {
            animation: fadeSlideUp .5s .24s ease both;
        }

        #salesTableBody tr {
            animation: rowSlideIn .32s ease both;
        }

        #salesTableBody tr:nth-child(1) {
            animation-delay: .28s
        }

        #salesTableBody tr:nth-child(2) {
            animation-delay: .32s
        }

        #salesTableBody tr:nth-child(3) {
            animation-delay: .36s
        }

        #salesTableBody tr:nth-child(4) {
            animation-delay: .40s
        }

        #salesTableBody tr:nth-child(5) {
            animation-delay: .44s
        }

        #salesTableBody tr:nth-child(6) {
            animation-delay: .48s
        }

        #salesTableBody tr:nth-child(7) {
            animation-delay: .52s
        }

        #salesTableBody tr:nth-child(8) {
            animation-delay: .56s
        }

        #salesTableBody tr:nth-child(9) {
            animation-delay: .60s
        }

        #salesTableBody tr:nth-child(10) {
            animation-delay: .64s
        }

        .sr-bar {
            animation: barGrow .8s .4s cubic-bezier(.4, 0, .2, 1) both;
        }

        .sr-dropdown.open,
        #salesDetailsModal.flex,
        #filterOverlay.flex {
            animation: overlayIn .18s ease;
        }

        .sr-drawer {
            animation: slideInRight .28s cubic-bezier(.32, .72, 0, 1) both;
        }

        .sr-modal-inner {
            animation: popIn .22s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .sr-input {
                width: 100%; border-radius: .75rem; border: 1px solid rgb(229 231 235);
                background: #fff; padding: .55rem .75rem; font-size: .8125rem;
                color: rgb(17 24 39); outline: none; transition: box-shadow .15s ease, border-color .15s ease;
            }
            .dark .sr-input { border-color: rgb(55 65 81); background: rgb(31 41 55); color: rgb(243 244 246); }
            .sr-input:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.25); border-color:#6366f1; }

            .sr-btn { transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease; }
            .sr-btn:active { transform: translateY(1px); }

            .sr-scrollbar::-webkit-scrollbar { width: 6px; height:6px; }
            .sr-scrollbar::-webkit-scrollbar-thumb { background: rgba(120,120,140,.25); border-radius: 999px; }

            /* ---- Custom calendar ---- */
            .cal-day { transition: background-color .12s ease, color .12s ease; }
            .cal-day[data-state="in-range"] { background: rgba(99,102,241,.12); }
            .dark .cal-day[data-state="in-range"] { background: rgba(129,140,248,.16); }
            .cal-day[data-state="edge"] { background: #6366f1; color: #fff; font-weight: 600; }
            .cal-day[data-state="edge"]:hover { background: #4f46e5; }
            .cal-day[data-disabled="true"] { opacity: .3; pointer-events: none; }
        </style>

        <div class="sr-page space-y-4">

            {{-- ==================== HEADER ==================== --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Sales Analysis</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Track performance, spot trends and drill into any day's orders.</p>
                </div>

                <div class="flex items-center gap-2 relative">

                    {{-- ---- Date range pill + custom calendar dropdown ---- --}}
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('dateDropdown')"
                            class="sr-btn inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700
                                   bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-200">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} – {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div id="dateDropdown" class="sr-dropdown hidden absolute right-0 mt-2 w-[540px] max-w-[92vw] rounded-2xl border border-gray-200 dark:border-gray-700
                                    bg-white dark:bg-gray-800 shadow-2xl shadow-black/10 dark:shadow-black/40 z-40 overflow-hidden">
                            <form method="GET" action="{{ route('reports.sales') }}" id="dateRangeForm" class="flex flex-col sm:flex-row">
                                @foreach(request()->except(['range', 'date_range', 'page']) as $k => $v)
                                    @if(is_array($v))
                                        @foreach($v as $vv)<input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">@endforeach
                                    @else
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endif
                                @endforeach
                                <input type="hidden" name="range" id="rangeField" value="{{ $range }}">
                                <input type="hidden" name="date_range" id="dateRangeField" value="{{ request('date_range') }}">

                                {{-- presets --}}
                                @php
                                    $ranges = [
                                        'today' => 'Today',
                                        '7days' => 'Last 7 days',
                                        '30days' => 'Last 30 days',
                                        'this_month' => 'This month',
                                        'last_month' => 'Last month',
                                        'this_year' => 'This year',
                                    ];
                                @endphp
                                <div class="sm:w-36 flex-shrink-0 border-b sm:border-b-0 sm:border-r border-gray-100 dark:border-gray-700 p-2 space-y-0.5">
                                    @foreach($ranges as $val => $label)
                                        <button type="submit" name="range" value="{{ $val }}"
                                            class="w-full text-left px-3 py-2 rounded-xl text-xs font-medium transition-colors
                                                   {{ $range == $val ? 'bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                    <div class="pt-1 mt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="block px-3 py-2 rounded-xl text-xs font-medium {{ $range == 'custom' ? 'bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}">
                                            Custom
                                        </span>
                                    </div>
                                </div>

                                {{-- calendar --}}
                                <div class="p-3 flex-1">
                                    <div class="flex items-center justify-between mb-2 px-1">
                                        <button type="button" id="calPrev" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 19-7-7 7-7"/></svg>
                                        </button>
                                        <span id="calLabel" class="text-xs font-semibold text-gray-700 dark:text-gray-200"></span>
                                        <button type="button" id="calNext" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 5 7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-7 gap-y-1 text-center text-[10px] font-semibold text-gray-400 dark:text-gray-500 mb-1">
                                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                    </div>
                                    <div id="calGrid" class="grid grid-cols-7 gap-y-1 text-center text-xs"></div>

                                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <span id="calSelectedLabel" class="text-[11px] text-gray-400 dark:text-gray-500">Pick a start and end date</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" onclick="toggleDropdown('dateDropdown')" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                                            <button type="button" id="calApply" class="sr-btn px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold disabled:opacity-40 disabled:pointer-events-none" disabled>Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ---- Filter button (opens slide-over) ---- --}}
                    <button type="button" onclick="openFilterDrawer()"
                        class="sr-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500
                               text-white text-xs font-semibold shadow-lg shadow-indigo-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                        </svg>
                        Filter
                        @php $activeCount = count(array_filter(request()->only(['status', 'payment_method', 'payment_status', 'province', 'district', 'sangkat', 'street', 'keyword']))); @endphp
                        @if($activeCount)
                            <span class="ml-0.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-white text-indigo-600 text-[10px] font-bold">{{ $activeCount }}</span>
                        @endif
                    </button>

                    {{-- ---- Export dropdown ---- --}}
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('exportDropdown')"
                            class="sr-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700
                                   bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                            </svg>
                            Export
                        </button>
                        <div id="exportDropdown" class="sr-dropdown hidden absolute right-0 mt-2 w-48 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl shadow-black/10 dark:shadow-black/40 z-40 overflow-hidden p-1.5">
                            <a href="{{ route('reports.sales.export.csv') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                Export as CSV
                            </a>
                            <a href="{{ route('reports.sales.export.pdf') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== ACTIVE FILTER CHIPS ==================== --}}
            @if($activeCount)
                <div class="flex flex-wrap items-center gap-2">
                    @foreach(request()->only(['status', 'payment_method', 'payment_status', 'province', 'district', 'sangkat', 'street', 'keyword']) as $k => $v)
                        @if($v)
                            <span class="inline-flex items-center gap-1.5 pl-3 pr-2 py-1 rounded-full bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-[11px] text-gray-600 dark:text-gray-300">
                                {{ ucfirst(str_replace('_', ' ', $k)) }}: <span class="font-semibold text-gray-900 dark:text-white">{{ $v }}</span>
                                <a href="{{ route('reports.sales', request()->except($k, 'page')) }}" class="text-gray-400 hover:text-rose-500">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </a>
                            </span>
                        @endif
                    @endforeach
                    <a href="{{ route('reports.sales') }}" class="text-[11px] text-gray-400 hover:text-rose-500 font-medium">Clear all</a>
                </div>
            @endif

            {{-- ==================== KPI CARDS ==================== --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
                @php
                    $kpis = [
                        [
                            'label' => 'Total Orders',
                            'value' => number_format($totalOrders),
                            'sub' => 'All statuses',
                            'accent' => '#6366f1',
                            'from' => 'from-indigo-500',
                            'to' => 'to-violet-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'
                        ],
                        [
                            'label' => 'Gross Sales',
                            'value' => '$' . number_format($grossSales, 2),
                            'sub' => 'Before discount',
                            'accent' => '#f59e0b',
                            'from' => 'from-amber-500',
                            'to' => 'to-yellow-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        ],
                        [
                            'label' => 'Paid Revenue',
                            'value' => '$' . number_format($paidRevenue, 2),
                            'sub' => 'Confirmed payments',
                            'accent' => '#10b981',
                            'from' => 'from-emerald-500',
                            'to' => 'to-teal-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'
                        ],
                        [
                            'label' => 'Avg Order Value',
                            'value' => '$' . number_format($averageOrderValue, 2),
                            'sub' => 'Discount: $' . number_format($totalDiscount, 2),
                            'accent' => '#3b82f6',
                            'from' => 'from-blue-500',
                            'to' => 'to-indigo-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
                        ],
                    ];
                @endphp

                @foreach($kpis as $kpi)
                    <div class="sr-kpi relative overflow-hidden rounded-2xl p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</span>
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $kpi['from'] }} {{ $kpi['to'] }} flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $kpi['icon'] !!}</svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">{{ $kpi['value'] }}</h2>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1.5">{{ $kpi['sub'] }}</p>

                        <svg class="w-full h-8 mt-3 opacity-80" viewBox="0 0 100 24" preserveAspectRatio="none">
                            <polyline points="0,18 12,14 24,16 36,10 48,12 60,6 72,9 84,4 100,7"
                                fill="none" stroke="{{ $kpi['accent'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                @endforeach
            </div>

            {{-- ==================== MAIN GRID ==================== --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

                {{-- ---------- LEFT: Sales Overview table ---------- --}}
                <div class="xl:col-span-2 sr-panel bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V9m4 8V5m4 12v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Sales Overview</h2>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">Click "View" to see individual orders for that day.</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto sr-scrollbar">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/40">
                                <tr class="text-left text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    <th class="px-5 py-3">Date</th>
                                    <th class="px-5 py-3">Orders</th>
                                    <th class="px-5 py-3">Gross Sales</th>
                                    <th class="px-5 py-3">Discount</th>
                                    <th class="px-5 py-3">Paid Revenue</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($salesRows as $row)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($row->sale_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-300">
                                                {{ number_format($row->total_orders) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-white whitespace-nowrap">${{ number_format($row->gross_sales, 2) }}</td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="text-rose-600 dark:text-rose-400 font-medium">-${{ number_format($row->total_discount, 2) }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($row->paid_revenue, 2) }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <button type="button" onclick="openSalesDetailsModal('{{ $row->sale_date }}')"
                                                class="sr-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                       hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-500/30">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-400 dark:text-gray-500">No sales records found for this filter.</p>
                                                <a href="{{ route('reports.sales') }}" class="text-xs font-medium text-indigo-500 hover:text-indigo-600">Reset filters</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50 dark:bg-gray-800/30">
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            @if($salesRows->total())
                                Showing <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $salesRows->firstItem() }}–{{ $salesRows->lastItem() }}</span>
                                of <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($salesRows->total()) }}</span> results
                            @else
                                No records found
                            @endif
                        </p>

                        @if($salesRows->hasPages())
                            @php
                                $currentPage = $salesRows->currentPage();
                                $lastPage = $salesRows->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp
                            <nav class="flex items-center gap-1">
                                @if($salesRows->onFirstPage())
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 19-7-7 7-7"/></svg>
                                    </span>
                                @else
                                    <a href="{{ $salesRows->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 19-7-7 7-7"/></svg>
                                    </a>
                                @endif

                                @if($start > 1)
                                    <a href="{{ $salesRows->url(1) }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">1</a>
                                    @if($start > 2)<span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>@endif
                                @endif

                                @foreach($salesRows->getUrlRange($start, $end) as $page => $url)
                                    @if($page == $currentPage)
                                        <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if($end < $lastPage)
                                    @if($end < $lastPage - 1)<span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>@endif
                                    <a href="{{ $salesRows->url($lastPage) }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">{{ $lastPage }}</a>
                                @endif

                                @if($salesRows->hasMorePages())
                                    <a href="{{ $salesRows->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 5 7 7-7 7"/></svg>
                                    </a>
                                @else
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 5 7 7-7 7"/></svg>
                                    </span>
                                @endif
                            </nav>
                        @endif
                    </div>
                </div>

                {{-- ---------- RIGHT: sidebar ---------- --}}
                <div class="space-y-4">

                    @if($highestSellingDay)
                        <div class="sr-side rounded-2xl p-4 bg-gradient-to-br from-indigo-600 to-violet-700 text-white shadow-lg shadow-indigo-900/20">
                            <p class="text-[11px] font-semibold text-indigo-100 uppercase tracking-wider">Best day in range</p>
                            <h3 class="text-lg font-bold mt-1">{{ \Carbon\Carbon::parse($highestSellingDay->sale_date)->format('d M Y') }}</h3>
                            <div class="flex items-center gap-4 mt-2 text-xs text-indigo-100">
                                <span>{{ number_format($highestSellingDay->total_orders) }} orders</span>
                                <span>${{ number_format($highestSellingDay->gross_sales, 2) }} gross</span>
                            </div>
                        </div>
                    @endif

                    <div class="sr-side bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <div class="px-4 pt-4 pb-2 flex items-center gap-2 flex-wrap">
                            @php
                                $tabs = [
                                    'province' => ['label' => 'Provinces', 'data' => $topProvinces, 'from' => 'from-indigo-500', 'to' => 'to-violet-600'],
                                    'district' => ['label' => 'Districts', 'data' => $topDistricts, 'from' => 'from-blue-500', 'to' => 'to-indigo-600'],
                                    'sangkat' => ['label' => 'Sangkats', 'data' => $topSangkats, 'from' => 'from-emerald-500', 'to' => 'to-teal-600'],
                                    'street' => ['label' => 'Streets', 'data' => $topStreets, 'from' => 'from-amber-500', 'to' => 'to-yellow-600'],
                                ];
                            @endphp
                            @foreach($tabs as $key => $t)
                                <button type="button" onclick="showInsightTab('{{ $key }}')" id="insightTabBtn-{{ $key }}"
                                    class="insight-tab-btn px-3 py-1.5 rounded-lg text-[11px] font-semibold transition-colors
                                           {{ $loop->first ? 'bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    {{ $t['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="px-4 pb-4 pt-1 space-y-3">
                            @foreach($tabs as $key => $t)
                                @php $max = collect($t['data'])->max('revenue') ?: 1; @endphp
                                <div id="insightTab-{{ $key }}" class="insight-tab-panel {{ $loop->first ? '' : 'hidden' }} space-y-3">
                                    @forelse($t['data'] as $i => $row)
                                        @php $pct = $max > 0 ? min(100, ($row['revenue'] / $max) * 100) : 0; @endphp
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-5 h-5 rounded-lg bg-gradient-to-br {{ $t['from'] }} {{ $t['to'] }} flex items-center justify-center text-[9px] font-bold text-white flex-shrink-0">{{ $i + 1 }}</span>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $row['name'] }}</p>
                                                        <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ $row['orders'] }} orders</p>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-bold text-gray-900 dark:text-white flex-shrink-0 ml-3">${{ number_format($row['revenue'], 2) }}</span>
                                            </div>
                                            <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                                <div class="sr-bar h-full rounded-full bg-gradient-to-r {{ $t['from'] }} {{ $t['to'] }}" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 dark:text-gray-500">No data available.</p>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== FILTER DRAWER ==================== --}}
        <div id="filterOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50" onclick="closeFilterDrawer()"></div>
        <div id="filterDrawer" class="sr-drawer fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 z-50 hidden flex-col shadow-2xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Filters</h2>
                </div>
                <button type="button" onclick="closeFilterDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="GET" action="{{ route('reports.sales') }}" class="flex-1 overflow-y-auto sr-scrollbar p-5 space-y-4">
                <input type="hidden" name="range" value="{{ $range }}">
                <input type="hidden" name="date_range" value="{{ request('date_range') }}">

                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Order Status</label>
                    <select name="status" class="sr-input">
                        <option value="">All statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Method</label>
                    <select name="payment_method" class="sr-input">
                        <option value="">All methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="khqr" {{ request('payment_method') == 'khqr' ? 'selected' : '' }}>KHQR</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Payment Status</label>
                    <select name="payment_status" class="sr-input">
                        <option value="">All payments</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Delivery address</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] text-gray-400 dark:text-gray-500 mb-1.5">Province</label>
                            <select name="province" class="sr-input">
                                <option value="">All provinces</option>
                                @foreach($provinceOptions as $province)
                                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 dark:text-gray-500 mb-1.5">District / Khan</label>
                            <select name="district" class="sr-input">
                                <option value="">All districts</option>
                                @foreach($districtOptions as $district)
                                    <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 dark:text-gray-500 mb-1.5">Sangkat / Commune</label>
                            <select name="sangkat" class="sr-input">
                                <option value="">All sangkats</option>
                                @foreach($sangkatOptions as $sangkat)
                                    <option value="{{ $sangkat }}" {{ request('sangkat') == $sangkat ? 'selected' : '' }}>{{ $sangkat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 dark:text-gray-500 mb-1.5">Street</label>
                            <select name="street" class="sr-input">
                                <option value="">All streets</option>
                                @foreach($streetOptions as $street)
                                    <option value="{{ $street }}" {{ request('street') == $street ? 'selected' : '' }}>{{ $street }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                    <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Keyword</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                           placeholder="Order ID, customer, phone, email, coupon…" class="sr-input">
                </div>

                <div class="sticky bottom-0 pt-4 pb-1 bg-white dark:bg-gray-800 flex items-center gap-2">
                    <a href="{{ route('reports.sales') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-700">Reset all</a>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-500/20">Apply filters</button>
                </div>
            </form>
        </div>

        {{-- ==================== SALES DETAILS MODAL ==================== --}}
        <div id="salesDetailsModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="sr-modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-6xl rounded-2xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Sales Details</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Orders for the selected date</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeSalesDetailsModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <div id="salesDetailsModalContent" class="flex-1 overflow-y-auto sr-scrollbar p-6">
                    <div class="flex items-center justify-center py-20">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script defer>
                // ── Generic dropdown toggling (date range / export) ─────────────────
                function toggleDropdown(id) {
                    document.querySelectorAll('.sr-dropdown').forEach(el => { if (el.id !== id) el.classList.add('hidden'); });
                    document.getElementById(id).classList.toggle('hidden');
                }
                document.addEventListener('click', function (e) {
                    document.querySelectorAll('.sr-dropdown').forEach(el => {
                        if (!el.contains(e.target) && !e.target.closest('button[onclick*="toggleDropdown"]')) {
                            el.classList.add('hidden');
                        }
                    });
                });

                // ── Filter drawer ────────────────────────────────────────────────
                const filterDrawer = document.getElementById('filterDrawer');
                const filterOverlay = document.getElementById('filterOverlay');
                function openFilterDrawer() {
                    filterOverlay.classList.remove('hidden');
                    filterDrawer.classList.remove('hidden');
                    filterDrawer.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }
                function closeFilterDrawer() {
                    filterOverlay.classList.add('hidden');
                    filterDrawer.classList.add('hidden');
                    filterDrawer.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }

                // ── Insight tabs ─────────────────────────────────────────────────
                function showInsightTab(key) {
                    document.querySelectorAll('.insight-tab-panel').forEach(el => el.classList.add('hidden'));
                    document.getElementById('insightTab-' + key).classList.remove('hidden');
                    document.querySelectorAll('.insight-tab-btn').forEach(btn => {
                        btn.classList.remove('bg-indigo-50', 'dark:bg-indigo-500/15', 'text-indigo-600', 'dark:text-indigo-300');
                        btn.classList.add('text-gray-400', 'dark:text-gray-500');
                    });
                    const activeBtn = document.getElementById('insightTabBtn-' + key);
                    activeBtn.classList.add('bg-indigo-50', 'dark:bg-indigo-500/15', 'text-indigo-600', 'dark:text-indigo-300');
                    activeBtn.classList.remove('text-gray-400', 'dark:text-gray-500');
                }

                // ── Sales details modal ──────────────────────────────────────────
                const modal = document.getElementById('salesDetailsModal');
                const content = document.getElementById('salesDetailsModalContent');

                modal.addEventListener('click', function (e) { if (e.target === this) closeSalesDetailsModal(); });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') { closeSalesDetailsModal(); closeFilterDrawer(); }
                });

                async function openSalesDetailsModal(date) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    content.innerHTML = `
                        <div class="flex items-center justify-center py-20">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-8 h-8 rounded-full border-2 border-indigo-500 border-t-transparent animate-spin"></div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Loading details…</p>
                            </div>
                        </div>`;

                    const qs = new URLSearchParams(window.location.search).toString();
                    const url = `{{ url('/admin/reports/sales') }}/${date}/details${qs ? '?' + qs : ''}`;

                    try {
                        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        content.innerHTML = await res.text();
                    } catch {
                        content.innerHTML = `
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
                function closeSalesDetailsModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                // ── Custom calendar (no external dependency) ─────────────────────
                (function () {
                    const grid = document.getElementById('calGrid');
                    const label = document.getElementById('calLabel');
                    const selectedLabel = document.getElementById('calSelectedLabel');
                    const applyBtn = document.getElementById('calApply');
                    const prevBtn = document.getElementById('calPrev');
                    const nextBtn = document.getElementById('calNext');
                    const rangeField = document.getElementById('rangeField');
                    const dateRangeField = document.getElementById('dateRangeField');
                    const dateRangeForm = document.getElementById('dateRangeForm');

                    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                    const pad = n => String(n).padStart(2, '0');
                    const fmt = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

                    // Seed from existing date_range value if present, else current month
                    let seed = dateRangeField.value && dateRangeField.value.includes(' to ')
                        ? dateRangeField.value.split(' to ')
                        : (dateRangeField.value ? [dateRangeField.value, null] : [null, null]);

                    let selStart = seed[0] ? new Date(seed[0] + 'T00:00:00') : null;
                    let selEnd = seed[1] ? new Date(seed[1] + 'T00:00:00') : null;
                    let viewDate = selStart ? new Date(selStart.getFullYear(), selStart.getMonth(), 1) : new Date();
                    viewDate.setDate(1);

                    function sameDay(a, b) { return a && b && a.toDateString() === b.toDateString(); }

                    function render() {
                        label.textContent = `${months[viewDate.getMonth()]} ${viewDate.getFullYear()}`;
                        grid.innerHTML = '';

                        const firstOfMonth = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
                        const startOffset = firstOfMonth.getDay();
                        const daysInMonth = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 0).getDate();
                        const today = new Date(); today.setHours(0,0,0,0);

                        for (let i = 0; i < startOffset; i++) {
                            grid.appendChild(document.createElement('span'));
                        }

                        for (let day = 1; day <= daysInMonth; day++) {
                            const d = new Date(viewDate.getFullYear(), viewDate.getMonth(), day);
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.textContent = day;
                            btn.className = 'cal-day w-8 h-8 mx-auto flex items-center justify-center rounded-full text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700';

                            if (sameDay(d, today)) btn.classList.add('ring-1', 'ring-indigo-400');

                            const isEdge = sameDay(d, selStart) || sameDay(d, selEnd);
                            const inRange = selStart && selEnd && d > selStart && d < selEnd;

                            if (isEdge) btn.setAttribute('data-state', 'edge');
                            else if (inRange) btn.setAttribute('data-state', 'in-range');

                            btn.addEventListener('click', () => {
                                if (!selStart || (selStart && selEnd)) {
                                    selStart = d; selEnd = null;
                                } else if (d < selStart) {
                                    selEnd = selStart; selStart = d;
                                } else {
                                    selEnd = d;
                                }
                                updateSelectedLabel();
                                render();
                            });

                            grid.appendChild(btn);
                        }
                    }

                    function updateSelectedLabel() {
                        if (selStart && selEnd) {
                            selectedLabel.textContent = `${fmt(selStart)} → ${fmt(selEnd)}`;
                            applyBtn.disabled = false;
                        } else if (selStart) {
                            selectedLabel.textContent = `${fmt(selStart)} → pick an end date`;
                            applyBtn.disabled = true;
                        } else {
                            selectedLabel.textContent = 'Pick a start and end date';
                            applyBtn.disabled = true;
                        }
                    }

                    prevBtn.addEventListener('click', () => { viewDate.setMonth(viewDate.getMonth() - 1); render(); });
                    nextBtn.addEventListener('click', () => { viewDate.setMonth(viewDate.getMonth() + 1); render(); });

                    applyBtn.addEventListener('click', () => {
                        if (!selStart || !selEnd) return;
                        dateRangeField.value = `${fmt(selStart)} to ${fmt(selEnd)}`;
                        rangeField.value = 'custom';
                        dateRangeForm.submit();
                    });

                    updateSelectedLabel();
                    render();
                })();
            </script>
        @endpush

@endsection