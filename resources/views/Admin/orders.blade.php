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
        @keyframes rowMoveOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(-16px); }
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

        .order-grid-card { animation: fadeSlideUp .35s ease both; }

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
        .row-moving-out { animation: rowMoveOut .25s ease both; }

        .status-badge { transition: all .3s ease; }

        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover:not(:disabled)  { transform: translateY(-1px); }
        .action-btn:active:not(:disabled) { transform: translateY(0); }

        .filter-pill { transition: background .2s ease, color .2s ease, box-shadow .2s ease; }
        .filter-pill.active { box-shadow: 0 1px 4px rgba(0,0,0,.1); }

        .view-toggle-btn { transition: background .15s ease, color .15s ease, box-shadow .15s ease; }

        /* Disabled FIFO buttons */
        .accept-btn:disabled, .reject-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .stat-card { padding: .75rem; }
            .stat-number { font-size: 1.5rem !important; }
        }
    </style>

    <div class="toast-container" id="toastContainer"></div>

    <div class="space-y-4" x-data="{ view: 'list' }">

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

            {{-- Total Orders --}}
            <a href="{{ route('orders.index', ['status' => 'all']) }}"
                 class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
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
            </a>

            {{-- Pending Orders --}}
            @php $pendingPct = ($totalOrders ?? 0) > 0 ? round((($pendingOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <a href="{{ route('orders.index', ['status' => 'pending']) }}"
                 class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
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
            </a>

            {{-- Processing Orders --}}
            @php $processingPct = ($totalOrders ?? 0) > 0 ? round((($processingOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <a href="{{ route('orders.index', ['status' => 'processing']) }}"
                 class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
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
            </a>

            {{-- Completed Orders --}}
            @php $completedPct = ($totalOrders ?? 0) > 0 ? round((($completedOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <a href="{{ route('orders.index', ['status' => 'completed']) }}"
                 class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
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
            </a>

            {{-- Cancelled Orders --}}
            @php $cancelledPct = ($totalOrders ?? 0) > 0 ? round((($cancelledOrders ?? 0) / $totalOrders) * 100) : 0; @endphp
            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}"
                 class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
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
            </a>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order List</h2>

                <div class="flex items-center gap-2 flex-wrap">

                    {{-- GRID / LIST TOGGLE --}}
                    <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700 p-1 gap-1">
                        <button type="button" @click="view = 'list'"
                            :class="view === 'list' ? 'bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-400 dark:text-gray-500'"
                            class="view-toggle-btn w-8 h-8 flex items-center justify-center rounded-lg"
                            title="List view">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <line x1="4" y1="6" x2="20" y2="6" stroke-linecap="round" />
                                <line x1="4" y1="12" x2="20" y2="12" stroke-linecap="round" />
                                <line x1="4" y1="18" x2="20" y2="18" stroke-linecap="round" />
                            </svg>
                        </button>
                        <button type="button" @click="view = 'grid'"
                            :class="view === 'grid' ? 'bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-400 dark:text-gray-500'"
                            class="view-toggle-btn w-8 h-8 flex items-center justify-center rounded-lg"
                            title="Grid view">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            </svg>
                        </button>
                    </div>

                    {{-- EXPORT --}}
                    <button type="button" onclick="openExportModal()"
                        class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                               border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                </div>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if($current !== 'all')
                <div class="px-4 sm:px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                            flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold capitalize">{{ $current }}</span>
                        &mdash; {{ number_format($orders->total()) }} {{ Str::plural('result', $orders->total()) }}
                    </p>
                    <a href="?status=all"  class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline self-start sm:self-auto">Clear filter</a>
                </div>
            @endif

            {{-- ==================== LIST VIEW ==================== --}}
            <div x-show="view === 'list'" x-cloak>

                {{-- DESKTOP / TABLET TABLE (md and up) --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/40">
                            <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                <th class="px-6 py-3">Client</th>
                                <th class="px-6 py-3 hidden lg:table-cell">Phone</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3 hidden lg:table-cell">Payment</th>
                                <th class="px-6 py-3 hidden xl:table-cell">Payment Status</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 hidden xl:table-cell">Date</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody id="ordersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($orders as $order)
                                @php
                                    $fullName = $order['full_name'] ?? 'Customer';
                                    $initials  = strtoupper(substr($fullName, 0, 1));
                                    $avatar    = $order['avatar'] ?? null;

                                    $badge = match ($order['status']) {
                                        'completed'  => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                        'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                        'pending'    => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                        'cancelled'  => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                        default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                    };

                                    $canAccept = $order['status'] === 'pending' && $order['id'] == ($firstPendingId ?? null);
                                @endphp

                                <tr id="order-row-{{ $order['id'] }}"
                                    data-order-id="{{ $order['id'] }}"
                                    data-status="{{ $order['status'] }}"
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
                                                    {{ $initials ?: strtoupper(substr($fullName, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $fullName }}</span>
                                        </div>
                                    </td>

                                    {{-- PHONE --}}
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap hidden lg:table-cell">
                                        {{ $order['phone'] ?? '—' }}
                                    </td>

                                    {{-- TOTAL --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        ${{ number_format($order['total'], 2) }}
                                    </td>

                                    {{-- PAYMENT --}}
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
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

                                    @php
                                    $paymentStatusBadge = match(strtolower($order['payment_status'] ?? '')) {

                                        'paid' =>
                                        'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',

                                        'pending' =>
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',

                                        'unpaid' =>
                                        'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',

                                        'failed' =>
                                        'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',

                                        default =>
                                        'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400',
                                    };
                                    @endphp

                                    <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                        <span
                                            id="payment-badge-{{ $order['id'] }}"
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $paymentStatusBadge }}">
                                            {{ ucfirst($order['payment_status']) }}
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
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs hidden xl:table-cell">
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
                                                <span class="hidden xl:inline">View</span>
                                            </button>

                                            @if($order['status'] === 'pending')
                                                <button type="button"
                                                    class="accept-btn action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                           border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                           text-blue-600 dark:text-blue-400"
                                                    data-order-id="{{ $order['id'] }}"
                                                    title="{{ $canAccept ? 'Accept this order' : 'Process the older pending order first' }}"
                                                    {{ !$canAccept ? 'disabled' : '' }}>
                                                    Accept
                                                </button>
                                                <button type="button"
                                                    class="reject-btn action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
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
                                                <button
                                                        onclick="printInvoice({{ $order['id'] }})"
                                                        class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                            border border-purple-200 dark:border-purple-500/30
                                                            bg-purple-50 dark:bg-purple-500/10
                                                            text-purple-600 dark:text-purple-400">

                                                        Print
                                                </button>
                                                <button type="button" onclick="confirmChange({{ $order['id'] }}, 'completed', this)"
                                                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                           border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                                                           text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all duration-200">
                                                    Successful
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="confirmChange({{ $order['id'] }}, 'cancelled', this)"
                                                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                        border border-red-200 dark:border-red-500/30
                                                        bg-red-50 dark:bg-red-500/10
                                                        text-red-600 dark:text-red-400">
                                                    Cancel
                                                </button>
                                            @endif

                                            @if($order['status'] === 'completed')
                                                <button
                                                        onclick="printInvoice({{ $order['id'] }})"
                                                        class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
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
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                        No orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARD LIST (below md) --}}
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($orders as $order)
                        @php
                            $fullNameM = $order['full_name'] ?? 'Customer';
                            $initialsM  = strtoupper(substr($fullNameM, 0, 1));
                            $avatarM    = $order['avatar'] ?? null;

                            $badgeM = match ($order['status']) {
                                'completed'  => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                'pending'    => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                'cancelled'  => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                            };

                            $canAcceptM = $order['status'] === 'pending' && $order['id'] == ($firstPendingId ?? null);
                        @endphp

                        <div id="order-row-mobile-{{ $order['id'] }}"
                             data-order-id="{{ $order['id'] }}"
                             data-status="{{ $order['status'] }}"
                             class="p-4 flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($avatarM)
                                        <img src="{{ $avatarM }}" alt="{{ $fullNameM }}"
                                             class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                    flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                            {{ $initialsM ?: strtoupper(substr($fullNameM, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-sm text-gray-900 dark:text-white truncate">{{ $fullNameM }}</p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $order['phone'] ?? '—' }}</p>
                                    </div>
                                </div>
                                <span class="status-badge flex-shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badgeM }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($order['total'], 2) }}</span>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $order['created_at'] }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <button type="button" onclick='openOrderModal(@json($order))'
                                    class="action-btn flex-1 inline-flex items-center justify-center gap-1.5 h-9 rounded-lg text-xs font-medium
                                           border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                           text-gray-600 dark:text-gray-300">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View
                                </button>

                                @if($order['status'] === 'pending')
                                    <button type="button"
                                        class="accept-btn action-btn flex-1 inline-flex items-center justify-center h-9 rounded-lg text-xs font-medium
                                               border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                               text-blue-600 dark:text-blue-400"
                                        data-order-id="{{ $order['id'] }}"
                                        {{ !$canAcceptM ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                    <button type="button"
                                        class="reject-btn action-btn flex-1 inline-flex items-center justify-center h-9 rounded-lg text-xs font-medium
                                               border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                                        data-order-id="{{ $order['id'] }}"
                                        {{ !$canAcceptM ? 'disabled' : '' }}>
                                        Reject
                                    </button>
                                @endif

                                @if($order['status'] === 'processing')
                                    <button type="button" onclick="confirmChange({{ $order['id'] }}, 'completed', this)"
                                        class="action-btn flex-1 inline-flex items-center justify-center h-9 rounded-lg text-xs font-medium
                                               border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10
                                               text-emerald-600 dark:text-emerald-400">
                                        Complete
                                    </button>
                                @endif

                                @if(in_array($order['status'], ['processing', 'completed']))
                                    <button type="button" onclick="printInvoice({{ $order['id'] }})"
                                        class="action-btn w-9 h-9 flex-shrink-0 inline-flex items-center justify-center rounded-lg
                                               border border-purple-200 dark:border-purple-500/30 bg-purple-50 dark:bg-purple-500/10
                                               text-purple-600 dark:text-purple-400">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No orders found.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ==================== GRID VIEW ==================== --}}
            <div x-show="view === 'grid'" x-cloak class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @forelse($orders as $order)
                        @php
                            $fullNameG = $order['full_name'] ?? 'Customer';
                            $initialsG  = strtoupper(substr($fullNameG, 0, 1));
                            $avatarG    = $order['avatar'] ?? null;

                            $badgeG = match ($order['status']) {
                                'completed'  => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                'pending'    => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                'cancelled'  => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                default      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                            };

                            $accentG = match ($order['status']) {
                                'completed'  => 'border-l-emerald-400',
                                'processing' => 'border-l-blue-400',
                                'pending'    => 'border-l-amber-400',
                                'cancelled'  => 'border-l-red-400',
                                default      => 'border-l-gray-300',
                            };

                            $canAcceptGrid = $order['status'] === 'pending' && $order['id'] == ($firstPendingId ?? null);
                        @endphp

                        <button type="button" onclick='openOrderModal(@json($order))'
                            class="order-grid-card text-left rounded-xl border border-gray-200 dark:border-gray-700 border-l-4 {{ $accentG }}
                                   bg-white dark:bg-gray-800 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-4 flex flex-col gap-3">

                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if($avatarG)
                                        <img src="{{ $avatarG }}" alt="{{ $fullNameG }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                    flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                            {{ $initialsG ?: strtoupper(substr($fullNameG, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $fullNameG }}</p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">#{{ $order['id'] }} · {{ $order['phone'] ?? '—' }}</p>
                                    </div>
                                </div>
                                <span class="status-badge flex-shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badgeG }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($order['total'], 2) }}</span>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $order['created_at'] }}</span>
                            </div>

                            <div class="flex items-center gap-1.5 pt-1 border-t border-gray-100 dark:border-gray-700 text-[11px]">
                                <span class="text-gray-400 dark:text-gray-500">{{ strtoupper($order['payment_method'] ?? 'N/A') }}</span>
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <span class="text-gray-400 dark:text-gray-500">{{ ucfirst($order['payment_status'] ?? '—') }}</span>
                                @if($order['status'] === 'pending' && !$canAcceptGrid)
                                    <span class="ml-auto text-amber-500 dark:text-amber-400 font-medium">Queued</span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                            No orders found.
                        </div>
                    @endforelse
                </div>
                <p class="text-center text-[11px] text-gray-400 dark:text-gray-500 mt-4">
                    Tap a card to view full details and actions.
                </p>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">

                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($orders->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->firstItem() }}–{{ $orders->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($orders->total()) }}</span>
                        results
                    @else
                        No orders found
                    @endif
                </p>

                @if($orders->hasPages())
                    @php
                        $orders->appends(['status' => $current]);
                        $currentPage = $orders->currentPage();
                        $last        = $orders->lastPage();
                        $start       = max(1, $currentPage - 2);
                        $end         = min($last, $currentPage + 2);
                    @endphp
                    <nav class="flex items-center gap-1 overflow-x-auto">
                        {{-- Previous --}}
                        @if($orders->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- First page + leading ellipsis --}}
                        @if($start > 1)
                            <a href="{{ $orders->url(1) }}"
                               class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                      text-sm font-medium text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                      transition-colors duration-150 flex-shrink-0">
                                1
                            </a>
                            @if($start > 2)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none flex-shrink-0">…</span>
                            @endif
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($orders->getUrlRange($start, $end) as $page => $url)
                            @if($page == $currentPage)
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                            bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25 flex-shrink-0">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                        text-sm font-medium text-gray-500 dark:text-gray-400
                                        hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                        transition-colors duration-150 flex-shrink-0">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Trailing ellipsis + last page --}}
                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none flex-shrink-0">…</span>
                            @endif
                            <a href="{{ $orders->url($last) }}"
                               class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                      text-sm font-medium text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                      transition-colors duration-150 flex-shrink-0">
                                {{ $last }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-0 sm:p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full h-full sm:h-auto sm:max-w-sm sm:rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
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
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3">
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
            <div class="px-4 sm:px-6 pb-4 sm:pb-6 flex-shrink-0">
                <button onclick="closeExportModal()" class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancel</button>
            </div>
        </div>
    </div>


    {{-- ==================== ORDER DETAIL MODAL ==================== --}}
    <div id="orderModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-0 sm:p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full h-full sm:h-auto sm:max-w-lg sm:rounded-2xl shadow-2xl flex flex-col sm:max-h-[90vh] overflow-hidden">

            <div class="bg-indigo-700 px-4 sm:px-6 pt-6 pb-12 flex-shrink-0">
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

            <div id="orderContent" class="flex-1 overflow-y-auto px-4 sm:px-5 pb-5 space-y-3 text-sm text-gray-700 dark:text-gray-300"></div>
        </div>
    </div>


    @push('scripts')
    <script defer>
    // ══════════════════════════════════════════════════════
    //  GLOBAL FIFO STATE
    //  Note: after the very first status change, we don't rely on
    //  a server-provided "next id" — the pending rows in the DOM
    //  are already rendered oldest-first (matches the backend sort),
    //  so the first tr[data-status="pending"] IS always the next one
    //  eligible to be accepted/rejected. refreshFifoButtons() reads
    //  that directly from the DOM, which stays correct even across
    //  realtime inserts/removals without extra round-trips.
    // ══════════════════════════════════════════════════════
    window.firstPendingId = {{ $firstPendingId ?? 'null' }};

    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER
    // ══════════════════════════════════════════════════════
    function animateCounter(el) {
        const target   = parseInt(el.dataset.count, 10) || 0;
        const duration = 1000;
        const startTime = performance.now();
        function ease(t) { return 1 - Math.pow(1 - t, 3); }
        function tick(now) {
            const elapsed  = Math.max(0, now - startTime);
            const progress = Math.min(elapsed / duration, 1);
            const current  = Math.round(ease(progress) * target);
            el.textContent = current.toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
            else { el.textContent = target.toLocaleString(); el.classList.add('count-done'); }
        }
        requestAnimationFrame(tick);
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(animateCounter);
        }, 320);

        // Ensure button state matches DOM order on first paint too
        // (Blade already renders this correctly, but this keeps the
        // logic in one place so later DOM mutations stay consistent.)
        refreshFifoButtons();
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
        const fullName = order.full_name ?? 'Customer';

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

        // FIFO guard inside the modal too — a pending order that isn't
        // first in queue shows a locked notice instead of action buttons.
        const isBlockedPending = order.status === 'pending' && order.id !== window.firstPendingId;

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
        }

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
    //  FIFO BUTTON STATE (single source of truth)
    //  Disables every accept-btn/reject-btn, then re-enables only
    //  the FIRST pending row in DOM order (desktop table + mobile
    //  cards). Since rows are always rendered oldest-pending-first
    //  (matches the backend sort), this is always correct without
    //  needing a server round-trip.
    // ══════════════════════════════════════════════════════
    function refreshFifoButtons() {
        document.querySelectorAll('.accept-btn, .reject-btn').forEach(btn => {
            btn.disabled = true;
        });

        // Desktop table
        const tbody = document.getElementById('ordersTableBody');
        if (tbody) {
            const firstPendingRow = tbody.querySelector('tr[data-status="pending"]');
            if (firstPendingRow) {
                firstPendingRow.querySelectorAll('.accept-btn, .reject-btn').forEach(btn => btn.disabled = false);
                window.firstPendingId = parseInt(firstPendingRow.dataset.orderId, 10);
            } else {
                window.firstPendingId = null;
            }
        }

        // Mobile cards mirror the same order/id, so just sync by id
        if (window.firstPendingId) {
            const mobileRow = document.getElementById('order-row-mobile-' + window.firstPendingId);
            if (mobileRow) {
                mobileRow.querySelectorAll('.accept-btn, .reject-btn').forEach(btn => btn.disabled = false);
            }
        }
    }

    // Delegated click handling for Accept/Reject — works for buttons
    // added later by realtime inserts too, no re-binding needed.
    document.addEventListener('click', function (e) {
        const acceptBtn = e.target.closest('.accept-btn');
        if (acceptBtn && !acceptBtn.disabled) {
            confirmChange(parseInt(acceptBtn.dataset.orderId, 10), 'processing', acceptBtn);
            return;
        }
        const rejectBtn = e.target.closest('.reject-btn');
        if (rejectBtn && !rejectBtn.disabled) {
            confirmChange(parseInt(rejectBtn.dataset.orderId, 10), 'cancelled', rejectBtn);
        }
    });

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

            // Prevent double-click / concurrent requests: disable ALL
            // accept/reject buttons while this one is in flight, not
            // just the clicked one.
            document.querySelectorAll('.accept-btn, .reject-btn').forEach(btn => btn.disabled = true);

            let origHTML = null;
            if (triggerBtn) {
                origHTML = triggerBtn.innerHTML;
                triggerBtn.innerHTML = `<span class="btn-spinner"></span>`;
            }

            fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (res.status === 422) {
                    // Backend FIFO guard rejected it (stale client state, race condition, etc.)
                    showToast(data.error || 'Please process the oldest pending order first.', 'error');
                    refreshFifoButtons(); // resync in case our local DOM order was stale
                    return null;
                }
                if (!res.ok) throw new Error(res.status);
                return data;
            })
            .then((data) => {
                if (!data) return; // handled above (422 case)

                const badge = document.getElementById('status-badge-' + orderId);
                if (badge && cfg.badge) {
                    badge.className  = 'status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ' + cfg.badge;
                    badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }

                moveOrderRow(orderId, newStatus);
                updateRowActions(orderId, newStatus);
                closeOrderModal();
                showToast(cfg.toast, cfg.toastType);

                // Re-enable the next pending row (reads fresh DOM order,
                // which is already correct after moveOrderRow()).
                refreshFifoButtons();
            })
            .catch(err => {
                console.error(err);
                showToast('Something went wrong. Please try again.', 'error');
            })
            .finally(() => {
                if (triggerBtn && origHTML !== null && document.body.contains(triggerBtn)) {
                    triggerBtn.innerHTML = origHTML;
                }
                // refreshFifoButtons() already re-enabled the correct button(s);
                // this is just a safety net if that row no longer needs the button at all.
            });
        });
    }

    // ══════════════════════════════════════════════════════
    //  MOVE ROW BETWEEN STATUS SECTIONS
    //  Approximation: moves the row to the end of its new status
    //  group (exact FIFO position within Processing/Completed/
    //  Cancelled would need full timestamp data for every row on
    //  the page — this keeps ordering correct enough for a live
    //  view and matches on the next full page load regardless).
    // ══════════════════════════════════════════════════════
    function moveOrderRow(orderId, newStatus) {
        const tbody = document.getElementById('ordersTableBody');
        const row = document.getElementById('order-row-' + orderId);
        if (!tbody || !row) return;

        row.dataset.status = newStatus;
        row.classList.add('row-moving-out');

        setTimeout(() => {
            row.classList.remove('row-moving-out');

            const groupOrder = ['pending', 'processing', 'completed', 'cancelled'];
            const targetIndex = groupOrder.indexOf(newStatus);

            // Find the row to insert after: the last row whose status
            // group index is <= targetIndex.
            const rows = Array.from(tbody.querySelectorAll('tr[data-status]')).filter(r => r !== row);
            let insertAfter = null;
            for (const r of rows) {
                const idx = groupOrder.indexOf(r.dataset.status);
                if (idx <= targetIndex) insertAfter = r;
                else break;
            }

            if (insertAfter) {
                insertAfter.insertAdjacentElement('afterend', row);
            } else {
                tbody.insertAdjacentElement('afterbegin', row);
            }

            row.classList.add('new-order-row');
            setTimeout(() => row.classList.remove('new-order-row'), 1200);
        }, 200);

        // Mirror on mobile: just remove the pending-only action buttons,
        // full re-sort of the mobile list happens on next page load.
        const mobileRow = document.getElementById('order-row-mobile-' + orderId);
        if (mobileRow) mobileRow.dataset.status = newStatus;
    }

    function updateRowActions(orderId, newStatus) {
        const container = document.getElementById('actions-' + orderId);
        if (!container) return;

        const viewBtn = container.querySelector('button:first-child');
        let html = '';

        if (newStatus === 'processing') {
            html = `
                <button onclick="printInvoice(${orderId})"
                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        border border-purple-200 bg-purple-50 text-purple-600">
                    Print
                </button>
                <button type="button" onclick="confirmChange(${orderId}, 'completed', this)"
                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        border border-emerald-200 bg-emerald-50 text-emerald-600">
                    Complete
                </button>
                <button type="button" onclick="confirmChange(${orderId}, 'cancelled', this)"
                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        border border-red-200 bg-red-50 text-red-600">
                    Cancel
                </button>
            `;
        }

        if (newStatus === 'completed') {
            html = `
                <button onclick="printInvoice(${orderId})"
                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        border border-purple-200 bg-purple-50 text-purple-600">
                    Print
                </button>
            `;
        }

        container.innerHTML = '';
        if (viewBtn) container.appendChild(viewBtn);
        container.insertAdjacentHTML('beforeend', html);
    }

    // Fired when ANOTHER admin changes an order's status (Pusher).
    function updateOrderRealtime(orderId, newStatus) {
        const badge = document.getElementById('status-badge-' + orderId);
        if (!badge) return; // order isn't on this page/view

        const statusMap = {
            pending:    'bg-amber-100 text-amber-700',
            processing: 'bg-blue-100 text-blue-700',
            completed:  'bg-emerald-100 text-emerald-700',
            cancelled:  'bg-red-100 text-red-700',
        };

        badge.className  = 'status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ' + statusMap[newStatus];
        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

        moveOrderRow(orderId, newStatus);
        updateRowActions(orderId, newStatus);

        // Recompute which pending row (if any) is now first-in-queue.
        refreshFifoButtons();

        showToast(`Order #${orderId} updated to ${newStatus}`, 'success');
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

            // Per spec: only Page 1 receives realtime inserts; other
            // pages/pending-only pages get a toast telling them to refresh.
            if (currentPage !== 1) return;
            if (status !== 'all' && status !== 'pending') return;

            const tbody = document.getElementById('ordersTableBody');
            if (!tbody || document.getElementById('order-row-' + order.id)) return;

            // Only count PENDING rows toward the 10-row page cap — a page
            // full of processing/completed rows shouldn't block a new
            // pending order from appearing.
            const pendingRows = Array.from(tbody.querySelectorAll('tr[data-status="pending"]'));
            if (pendingRows.length >= 10) {
                showToast(`1 new pending order received. Refresh to view newest orders.`, 'info');
                return;
            }

            const total      = parseFloat(order.total_amount ?? 0).toFixed(2);
            const createdAt  = new Date(order.created_at || Date.now()).toLocaleString();
            const fullName   = order.full_name ?? 'Customer';
            const initials   = ((fullName[0] ?? '')).toUpperCase();

            // A brand-new pending order can only ever be "first in queue"
            // if there is currently NO enabled accept-btn anywhere on the
            // page (meaning the pending queue was empty before this arrival).
            const queueWasEmpty = !document.querySelector('.accept-btn:not([disabled])');

            const rowHtml = `
                <tr id="order-row-${order.id}" data-order-id="${order.id}" data-status="pending"
                    class="new-order-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                ${initials || '?'}
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">${fullName}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 hidden lg:table-cell">${order.phone ?? '—'}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">$${total}</td>
                    <td class="px-6 py-4 text-gray-500 hidden lg:table-cell">${order.payment_method ?? '—'}</td>
                    <td class="px-6 py-4 hidden xl:table-cell"></td>
                    <td class="px-6 py-4">
                        <span id="status-badge-${order.id}" class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500 hidden xl:table-cell">${createdAt}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end items-center gap-2" id="actions-${order.id}">
                            <button type="button" onclick='openOrderModal(${JSON.stringify(order).replace(/'/g, "&#39;")})'
                                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                       border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                       text-gray-600 dark:text-gray-300">
                                <span class="hidden xl:inline">View</span>
                            </button>
                            <button type="button" class="accept-btn action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                    border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400"
                                    data-order-id="${order.id}" ${queueWasEmpty ? '' : 'disabled'}>
                                Accept
                            </button>
                            <button type="button" class="reject-btn action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                    border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                                    data-order-id="${order.id}" ${queueWasEmpty ? '' : 'disabled'}>
                                Reject
                            </button>
                        </div>
                    </td>
                </tr>`;

            // Insert AFTER the last pending row (never at the top, per spec).
            // Falls back to before the first processing row, or the very
            // top of the table if neither pending nor processing rows exist.
            if (pendingRows.length > 0) {
                pendingRows[pendingRows.length - 1].insertAdjacentHTML('afterend', rowHtml);
            } else {
                const firstProcessing = tbody.querySelector('tr[data-status="processing"]');
                if (firstProcessing) {
                    firstProcessing.insertAdjacentHTML('beforebegin', rowHtml);
                } else {
                    tbody.insertAdjacentHTML('afterbegin', rowHtml);
                }
            }

            // Keep total visible rows capped at 10 — trim from the end
            // (lowest priority: typically a cancelled/completed row).
            const allRows = tbody.querySelectorAll('tr[data-order-id]');
            if (allRows.length > 10) allRows[allRows.length - 1].remove();

            refreshFifoButtons();
            showToast(`New order #${order.id} arrived!`, 'success');

            setTimeout(() => {
                const row = document.getElementById('order-row-' + order.id);
                if (row) row.classList.remove('new-order-row');
            }, 5000);
        });

        channel.bind('order-status-changed', function (data) {
            updateOrderRealtime(data.orderId, data.status);
        });

        channel.bind('payment-status-changed', function (data) {
            const badge = document.getElementById('payment-badge-' + data.orderId);
            if (!badge) return;
            badge.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700';
            badge.textContent = 'Paid';
        });
    });


    function printInvoice(orderId) {
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
    @endpush

@endsection