@extends('layouts.app')

@section('content')
    @php
        $totalCount = $orders->total();
        $pendingCount = $orders->getCollection()->filter(fn($o) => $o['status'] === 'pending')->count();
        $processingCount = $orders->getCollection()->filter(fn($o) => $o['status'] === 'processing')->count();
        $completedCount = $orders->getCollection()->filter(fn($o) => $o['status'] === 'completed')->count();
        $current = request('status', 'all');
    @endphp

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes spinPulse {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes toastSlide {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(40px); }
        }
        @keyframes newRowPop {
            0%   { opacity: 0; transform: scaleY(0.5); background-color: rgb(209 250 229); }
            60%  { transform: scaleY(1.02); }
            100% { opacity: 1; transform: scaleY(1); background-color: transparent; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.85); opacity: 0; }
            70%  { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Stat cards staggered */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .12s; }
        .stat-card:nth-child(3) { animation-delay: .19s; }
        .stat-card:nth-child(4) { animation-delay: .26s; }
        .stat-card:nth-child(5) { animation-delay: .33s; }

        .table-card { animation: fadeSlideUp .55s .3s ease both; }

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

        /* Number counter pop after count finishes */
        .count-done { animation: numberPop .35s cubic-bezier(.34,1.56,.64,1) both; }

        #orderModal.flex  { animation: overlayIn .2s ease; }
        #exportModal.flex { animation: overlayIn .2s ease; }
        .modal-inner      { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

        .btn-spinner {
            display: inline-block;
            width: 12px; height: 12px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinPulse .65s linear infinite;
            vertical-align: middle;
        }

        .toast-container {
            position: fixed; top: 1.25rem; right: 1.25rem;
            z-index: 9999; display: flex; flex-direction: column; gap: .5rem;
        }
        .toast {
            display: flex; align-items: center; gap: .625rem;
            padding: .75rem 1rem;
            background: white; border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12);
            font-size: .8125rem; font-weight: 500;
            animation: toastSlide .3s ease;
            min-width: 240px;
        }
        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.leaving { animation: toastOut .3s ease forwards; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        .new-order-row { animation: newRowPop .6s cubic-bezier(.34,1.2,.64,1) both; transform-origin: top; }

        .status-badge { transition: all .3s ease; }

        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        .filter-pill { transition: background .2s ease, color .2s ease, box-shadow .2s ease; }
        .filter-pill.active { box-shadow: 0 1px 4px rgba(0,0,0,.1); }

        /* Responsive */
        @media (max-width: 640px) {
            .stat-card { padding: .75rem; }
            .stat-number { font-size: 1.5rem !important; }
        }
    </style>

    <div class="toast-container" id="toastContainer"></div>

    <div class="space-y-4">

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">

            {{-- Total Orders --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-pink-50 via-rose-50 to-fuchsia-100
                            dark:from-pink-900/20 dark:via-rose-900/20 dark:to-fuchsia-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-pink-500 via-rose-500 to-fuchsia-600
                                    flex items-center justify-center shadow-md shadow-pink-500/25">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Orders</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Paid orders only</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full
                                 bg-gradient-to-r from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20
                                 text-pink-600 dark:text-pink-400 ring-1 ring-pink-200 dark:ring-pink-800
                                 shadow-sm text-[10px] font-semibold">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ number_format($totalOrders ?? 0) }}
                    </span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight
                               bg-gradient-to-r from-pink-600 via-rose-600 to-fuchsia-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $totalOrders ?? 0 }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-pink-500 via-rose-500 to-fuchsia-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">Successfully paid</span>
                        <span class="text-[10px] font-semibold text-pink-600 dark:text-pink-400">100%</span>
                    </div>
                </div>
            </div>

            {{-- Pending Orders --}}
            @php $pendingPct = ($totalOrders ?? 0) > 0 ? round((($pendingOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center shadow-md shadow-amber-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Pending</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Waiting</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-800 text-[10px] font-semibold">{{ $pendingPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-amber-600 to-yellow-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $pendingOrders ?? 0 }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-amber-500 to-yellow-600" style="width: {{ $pendingPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $pendingPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400">{{ number_format($totalOrders ?? 0) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Processing Orders --}}
            @php $processingPct = ($totalOrders ?? 0) > 0 ? round((($processingOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Processing</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Being prepared</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">{{ $processingPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $processingOrders ?? 0 }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $processingPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $processingPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalOrders ?? 0) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Completed Orders --}}
            @php $completedPct = ($totalOrders ?? 0) > 0 ? round((($completedOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Completed</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Delivered</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">{{ $completedPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $completedOrders ?? 0 }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600" style="width: {{ $completedPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $completedPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($totalOrders ?? 0) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Cancelled Orders --}}
            @php $cancelledPct = ($totalOrders ?? 0) > 0 ? round((($cancelledOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-md shadow-red-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Cancelled</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Orders cancelled</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 ring-1 ring-red-200 dark:ring-red-800 text-[10px] font-semibold">{{ $cancelledPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $cancelledOrders ?? 0 }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-red-500 to-rose-600" style="width: {{ $cancelledPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $cancelledPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-red-600 dark:text-red-400">{{ number_format($totalOrders ?? 0) }} total</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    {{-- STATUS FILTER PILLS --}}
                    <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 p-1 gap-1 flex-wrap">
                        @foreach(['all' => 'All', 'pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
                            <a href="?status={{ $value }}"
                               class="filter-pill px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                      {{ $current === $value
                                          ? 'active bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- EXPORT --}}
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
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if($current !== 'all')
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20 flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold capitalize">{{ $current }}</span>
                        &mdash; {{ number_format($orders->total()) }} {{ Str::plural('result', $orders->total()) }}
                    </p>
                    <a href="?status=all"  class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">Clear filter</a>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Client</th>
                            <th class="px-6 py-3">Phone</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Payment</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="ordersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                            @php
                                $firstName = $order['first_name'] ?? 'Customer';
                                $lastName  = $order['last_name'] ?? '';
                                $fullName  = trim($firstName . ' ' . $lastName);
                                $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                $avatar    = $order['avatar'] ?? null;

                                $badge = match ($order['status']) {
                                    'completed'  => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                    'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                    'pending'    => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'cancelled'  => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                    default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                };
                            @endphp

                            <tr id="order-row-{{ $order['id'] }}"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200">

                                {{-- CLIENT --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($avatar)
                                            <img src="{{ $avatar }}" alt="{{ $fullName }}"
                                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                        flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                                {{ $initials ?: strtoupper(substr($firstName, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $fullName }}</span>
                                    </div>
                                </td>

                                {{-- PHONE --}}
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $order['phone'] ?? '—' }}
                                </td>

                                {{-- TOTAL --}}
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    ${{ number_format($order['total'], 2) }}
                                </td>

                                {{-- PAYMENT --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentMethod = strtolower($order['payment_method'] ?? '');
                                        $styles = match ($paymentMethod) {
                                            'khqr' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400',
                                            'aba'  => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                            'wing' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                            'cash' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $styles }}">
                                        {{ strtoupper($order['payment_method'] ?? 'N/A') }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    <span id="status-badge-{{ $order['id'] }}"
                                          class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>

                                {{-- DATE --}}
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs">
                                    {{ $order['created_at'] }}
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2" id="actions-{{ $order['id'] }}">

                                        <button type="button" onclick='openOrderModal(@json($order))'
                                            class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                   border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                   text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            View
                                        </button>

                                        @if($order['status'] === 'pending')
                                            <button type="button" onclick="confirmChange({{ $order['id'] }}, 'processing', this)"
                                                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                       text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all duration-200">
                                                Accept
                                            </button>
                                        @endif

                                        @if($order['status'] === 'processing')
                                            <button type="button" onclick="confirmChange({{ $order['id'] }}, 'completed', this)"
                                                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                                                       text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all duration-200">
                                                Complete
                                            </button>
                                        @endif

                                        @if(!in_array($order['status'], ['completed', 'processing', 'cancelled']))
                                            <button type="button" onclick="confirmChange({{ $order['id'] }}, 'cancelled', this)"
                                                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                       border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                       text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400
                                                       hover:border-red-200 dark:hover:border-red-500/30 transition-all duration-200">
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($orders->total())
                        Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ number_format($orders->total()) }}
                    @else
                        No orders found
                    @endif
                </p>
                {{ $orders->appends(['status' => $current])->links() }}
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Export Data</h3>
                </div>
                <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Choose your preferred export format:</p>
                <a href="{{ route('orders.export.csv') }}"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                          hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 group-hover:border-emerald-300 flex items-center justify-center transition-all">
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
                <a href="{{ route('orders.export.pdf') }}"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-red-50 dark:hover:bg-red-500/10
                          hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 group-hover:border-red-300 flex items-center justify-center transition-all">
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
                <button onclick="closeExportModal()" class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancel</button>
            </div>
        </div>
    </div>


    {{-- ==================== ORDER DETAIL MODAL ==================== --}}
    <div id="orderModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-lg rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">

            <div class="bg-indigo-700 px-6 pt-6 pb-12 flex-shrink-0">
                <div class="flex items-start justify-between">
                    <div>
                        <p id="modalOrderId" class="text-[11px] font-medium tracking-widest text-indigo-300 uppercase mb-1">Order #—</p>
                        <p id="modalOrderTotal" class="text-2xl font-semibold text-white">—</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="modalStatusBadge" class="px-3 py-1 rounded-full text-[11px] font-semibold"></span>
                        <button onclick="closeOrderModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-center -mt-9 mb-1 relative z-10 flex-shrink-0">
                <div class="relative inline-block">
                    <img id="modalAvatar" src="" alt="Customer"
                         class="w-[72px] h-[72px] rounded-[18px] object-cover border-[3px] border-white dark:border-gray-800 shadow-lg">
                    <span id="modalStatusDot" class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800"></span>
                </div>
            </div>

            <p id="modalCustomerName" class="text-center text-sm font-semibold text-gray-900 dark:text-white mt-2"></p>
            <p id="modalCustomerMeta" class="text-center text-xs text-gray-400 dark:text-gray-500 mb-4"></p>

            <div id="orderContent" class="flex-1 overflow-y-auto px-5 pb-5 space-y-3 text-sm text-gray-700 dark:text-gray-300"></div>
        </div>
    </div>


    @push('scripts')
    <script defer>
    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER
    // ══════════════════════════════════════════════════════
    function animateCounter(el) {
        const target   = parseInt(el.dataset.count, 10) || 0;
        const duration = 1000;                    // ms total
        const startDelay = parseInt(el.closest('.stat-card')?.style.animationDelay || '0') * 1000;
        const startTime = performance.now();

        // cubic ease-out
        function ease(t) { return 1 - Math.pow(1 - t, 3); }

        function tick(now) {
            const elapsed  = Math.max(0, now - startTime);
            const progress = Math.min(elapsed / duration, 1);
            const current  = Math.round(ease(progress) * target);
            el.textContent = current.toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = target.toLocaleString();
                el.classList.add('count-done');
            }
        }
        requestAnimationFrame(tick);
    }

    // Kick off all counters once cards are visible (~300ms after page load)
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(animateCounter);
        }, 320);
    });

    // ══════════════════════════════════════════════════════
    //  TOAST
    // ══════════════════════════════════════════════════════
    function showToast(message, type = 'success') {
        const colors = { success:'#10b981', error:'#ef4444', info:'#6366f1', warning:'#f59e0b' };
        const toast  = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `<span class="toast-dot" style="background:${colors[type]??colors.info}"></span><span>${message}</span>`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => {
            toast.classList.add('leaving');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        }, 3500);
    }

    // ══════════════════════════════════════════════════════
    //  MODAL HELPERS
    // ══════════════════════════════════════════════════════
    function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function hideModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    ['orderModal','exportModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) { if (e.target === this) hideModal(id); });
    });

    function openExportModal()  { showModal('exportModal'); }
    function closeExportModal() { hideModal('exportModal'); }

    // ══════════════════════════════════════════════════════
    //  ORDER DETAIL MODAL
    // ══════════════════════════════════════════════════════
    function openOrderModal(order) {
        const firstName = order.first_name ?? 'Customer';
        const lastName  = order.last_name  ?? '';
        const fullName  = [firstName, lastName].filter(Boolean).join(' ');

        document.getElementById('modalOrderId').textContent    = 'Order #' + order.id;
        document.getElementById('modalOrderTotal').textContent = '$' + parseFloat(order.total).toFixed(2);

        const statusBadge  = document.getElementById('modalStatusBadge');
        const statusColors = {
            pending:    'bg-amber-400/20 text-amber-200',
            processing: 'bg-blue-400/20 text-blue-200',
            completed:  'bg-emerald-400/20 text-emerald-200',
            cancelled:  'bg-red-400/20 text-red-200',
        };
        statusBadge.className  = 'px-3 py-1 rounded-full text-[11px] font-semibold ' + (statusColors[order.status] || 'bg-white/10 text-white');
        statusBadge.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);

        const avatarEl = document.getElementById('modalAvatar');
        avatarEl.src = (order.avatar && order.avatar.trim())
            ? order.avatar
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(fullName || 'Customer') + '&background=4338ca&color=fff&size=72&bold=true';

        const dotEl    = document.getElementById('modalStatusDot');
        const dotClrs  = { pending:'bg-amber-400', processing:'bg-blue-500', completed:'bg-emerald-500', cancelled:'bg-red-400' };
        dotEl.className = 'absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 ' + (dotClrs[order.status] || 'bg-gray-400');

        document.getElementById('modalCustomerName').textContent = fullName;
        document.getElementById('modalCustomerMeta').textContent = [order.phone, order.created_at].filter(Boolean).join(' · ');

        const getImage = img => (!img || typeof img !== 'string') ? null : (img.startsWith('http') ? img : '/storage/' + img);

        const itemsHtml = (order.items || []).map(item => {
            const imgSrc = getImage(item.image);
            const imgTag = imgSrc
                ? `<img src="${imgSrc}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                        class="w-12 h-12 rounded-xl object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">
                   <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs font-semibold flex-shrink-0 items-center justify-center hidden">IMG</div>`
                : `<div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs font-semibold flex-shrink-0 flex items-center justify-center">IMG</div>`;
            return `
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                    ${imgTag}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${item.name}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${item.category ?? '—'} · ${item.brand ?? '—'}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Qty: ${item.qty} × $${parseFloat(item.price).toFixed(2)}</p>
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        $${(item.qty * item.price).toFixed(2)}
                    </span>
                </div>`;
        }).join('');

        const actionBtns = [
            order.status === 'pending' ? `
                <button onclick="confirmChange(${order.id}, 'processing', this)"
                    class="action-btn flex-1 py-2 text-xs font-medium rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">
                    Accept order
                </button>` : '',
            order.status === 'processing' ? `
                <button onclick="confirmChange(${order.id}, 'completed', this)"
                    class="action-btn flex-1 py-2 text-xs font-medium rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition">
                    Mark complete
                </button>` : '',
            ['pending','processing'].includes(order.status) ? `
                <button onclick="confirmChange(${order.id}, 'cancelled', this)"
                    class="action-btn flex-1 py-2 text-xs font-medium rounded-xl border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                    Cancel order
                </button>` : '',
        ].filter(Boolean).join('');

        document.getElementById('orderContent').innerHTML = `
            <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Delivery address</p>
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">${order.address ?? 'No address provided'}</p>
                </div>
            </div>
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
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Items (${(order.items || []).length})</p>
                <div class="space-y-2">${itemsHtml || '<p class="text-sm text-gray-400 dark:text-gray-500">No items.</p>'}</div>
            </div>
            ${actionBtns ? `<div class="flex gap-2 pt-1">${actionBtns}</div>` : ''}
        `;

        showModal('orderModal');
    }

    function closeOrderModal() { hideModal('orderModal'); }

    // ══════════════════════════════════════════════════════
    //  AJAX STATUS CHANGE
    // ══════════════════════════════════════════════════════
    const STATUS_CONFIG = {
        processing: { title:'Accept this order?', confirmText:'Accept', confirmColor:'#3b82f6', badge:'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', toast:'Order accepted!', toastType:'info' },
        completed:  { title:'Mark as completed?', confirmText:'Complete', confirmColor:'#10b981', badge:'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', toast:'Order marked as completed.', toastType:'success' },
        cancelled:  { title:'Cancel this order?', confirmText:'Yes, cancel', confirmColor:'#ef4444', badge:'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400', toast:'Order cancelled.', toastType:'warning' },
    };

    function confirmChange(orderId, newStatus, triggerBtn) {
        const cfg = STATUS_CONFIG[newStatus] || { title:'Confirm?', confirmText:'Yes', confirmColor:'#6366f1', toast:'Done.', toastType:'info' };

        Swal.fire({
            title: 'Confirm', text: cfg.title, icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: cfg.confirmColor, cancelButtonColor: '#6b7280',
            confirmButtonText: cfg.confirmText,
        }).then(result => {
            if (!result.isConfirmed) return;

            if (triggerBtn) {
                triggerBtn.disabled   = true;
                triggerBtn._origHTML  = triggerBtn.innerHTML;
                triggerBtn.innerHTML  = `<span class="btn-spinner"></span>`;
            }

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(res => { if (!res.ok) throw new Error(res.status); return res.json(); })
            .then(() => {
                const badge = document.getElementById('status-badge-' + orderId);
                if (badge && cfg.badge) {
                    badge.className  = 'status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ' + cfg.badge;
                    badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }
                updateRowActions(orderId, newStatus);
                closeOrderModal();
                showToast(cfg.toast, cfg.toastType);
            })
            .catch(err => {
                console.error(err);
                if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.innerHTML = triggerBtn._origHTML; }
                showToast('Something went wrong. Please try again.', 'error');
            });
        });
    }

    function updateRowActions(orderId, newStatus) {
        const container = document.getElementById('actions-' + orderId);
        if (!container) return;
        const viewBtn = container.querySelector('button:first-child');
        let html = '';
        if (newStatus === 'processing') {
            html = `<button type="button" onclick="confirmChange(${orderId},'completed',this)"
                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                       border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                       text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-all duration-200">
                Complete
            </button>`;
        }
        container.innerHTML = '';
        if (viewBtn) container.appendChild(viewBtn);
        if (html)    container.insertAdjacentHTML('beforeend', html);
    }

    // ══════════════════════════════════════════════════════
    //  REAL-TIME (Pusher)
    // ══════════════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.pusher) return;

        const channel = window.pusher.subscribe('orders');

        channel.bind('new-order', function (data) {
            if (!data?.order) return;
            const order       = data.order;
            const url         = new URL(window.location.href);
            const currentPage = parseInt(url.searchParams.get('page') || '1');
            const status      = url.searchParams.get('status') || 'all';

            if (currentPage !== 1) return;
            if (status !== 'all' && status !== order.status) return;

            const tbody = document.getElementById('ordersTableBody');
            if (!tbody || document.getElementById('order-row-' + order.id)) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));
            if (rows.length >= 10) { showToast(`New Order #${order.id} added to next page.`, 'info'); return; }

            const badgeClass = { pending:'bg-amber-100 text-amber-700', processing:'bg-blue-100 text-blue-700', completed:'bg-emerald-100 text-emerald-700', cancelled:'bg-red-100 text-red-700' }[order.status] || 'bg-gray-100 text-gray-600';
            const total      = parseFloat(order.total_amount ?? 0).toFixed(2);
            const createdAt  = new Date(order.created_at || Date.now()).toLocaleString();
            const firstName  = order.first_name ?? 'Customer';
            const lastName   = order.last_name  ?? '';
            const initials   = ((firstName[0] ?? '') + (lastName[0] ?? '')).toUpperCase();

            tbody.insertAdjacentHTML('afterbegin', `
                <tr id="order-row-${order.id}" class="new-order-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                ${initials || firstName[0]?.toUpperCase() || '?'}
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">${[firstName, lastName].filter(Boolean).join(' ')}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">${order.phone ?? '—'}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">$${total}</td>
                    <td class="px-6 py-4 text-gray-500">${order.payment_method ?? '—'}</td>
                    <td class="px-6 py-4">
                        <span id="status-badge-${order.id}" class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${badgeClass}">
                            ${order.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">${createdAt}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end items-center gap-2" id="actions-${order.id}">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 animate-pulse">New</span>
                        </div>
                    </td>
                </tr>`);

            const allRows = tbody.querySelectorAll('tr');
            if (allRows.length > 10) allRows[allRows.length - 1].remove();

            showToast(`New order #${order.id} arrived!`, 'success');

            setTimeout(() => {
                const row = document.getElementById('order-row-' + order.id);
                if (row) row.classList.remove('new-order-row');
            }, 5000);
        });
    });
    </script>
    @endpush

@endsection
