@extends('layouts.app')

@section('content')
    @php
        $totalCount = $customers->total();
        $inactivePct = 100 - $activePct;
    @endphp

    {{-- ==================== STYLES ==================== --}}
    <style>
        /* ── Entry animations ── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(24px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes lineDraw {
            from { stroke-dashoffset: 300; }
            to   { stroke-dashoffset: 0; }
        }
        @keyframes dotPop {
            0%   { transform: scale(0); }
            70%  { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(48px) scale(.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to   { opacity: 0; transform: translateX(48px) scale(.95); }
        }
        @keyframes spinnerRing {
            to { transform: rotate(360deg); }
        }
        @keyframes shimmer {
            0%   { background-position: -600px 0; }
            100% { background-position: 600px 0; }
        }
        @keyframes badgePop {
            0%   { transform: scale(0.7); opacity: 0; }
            70%  { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Staggered card reveal */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .13s; }
        .stat-card:nth-child(3) { animation-delay: .21s; }

        /* Table card */
        .table-card { animation: fadeSlideUp .55s .28s ease both; }

        /* Row stagger */
        .customer-row { animation: rowIn .32s ease both; }
        .customer-row:nth-child(1)  { animation-delay: .32s; }
        .customer-row:nth-child(2)  { animation-delay: .37s; }
        .customer-row:nth-child(3)  { animation-delay: .42s; }
        .customer-row:nth-child(4)  { animation-delay: .47s; }
        .customer-row:nth-child(5)  { animation-delay: .52s; }
        .customer-row:nth-child(6)  { animation-delay: .57s; }
        .customer-row:nth-child(7)  { animation-delay: .62s; }
        .customer-row:nth-child(8)  { animation-delay: .67s; }
        .customer-row:nth-child(9)  { animation-delay: .72s; }
        .customer-row:nth-child(10) { animation-delay: .77s; }

        /* Progress bars */
        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }

        /* SVG line draw */
        .anim-line {
            stroke-dasharray: 300;
            stroke-dashoffset: 300;
            animation: lineDraw 1.2s .8s ease forwards;
        }
        .anim-dot { animation: dotPop .4s 2s cubic-bezier(.34,1.56,.64,1) both; }

        /* Modal */
        .modal-overlay { animation: overlayIn .2s ease; }
        .modal-inner   { animation: modalIn .28s cubic-bezier(.34,1.56,.64,1) both; }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 14px; height: 14px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinnerRing .65s linear infinite;
        }

        /* Toast */
        .toast-wrap {
            position: fixed; top: 1.25rem; right: 1.25rem;
            z-index: 9999;
            display: flex; flex-direction: column; gap: .5rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex; align-items: center; gap: .625rem;
            padding: .75rem 1rem;
            min-width: 240px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06);
            font-size: .8125rem; font-weight: 500; color: #111827;
            animation: toastIn .3s cubic-bezier(.34,1.3,.64,1) both;
        }
        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.out { animation: toastOut .28s ease forwards; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* Action button */
        .action-btn {
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .action-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.15); }
        .action-btn:active { transform: translateY(0); }

        /* Badge animate */
        .badge-anim { animation: badgePop .35s cubic-bezier(.34,1.56,.64,1) both; }

        /* Avatar hover ring */
        .avatar-ring {
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .customer-row:hover .avatar-ring {
            box-shadow: 0 0 0 3px rgba(99,102,241,.35);
            transform: scale(1.05);
        }

        /* Search input focus glow */
        #customerSearch:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }

        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg,#f3f4f6 25%,#e5e7eb 50%,#f3f4f6 75%);
            background-size: 600px 100%;
            animation: shimmer 1.3s infinite;
            border-radius: 6px;
        }
        .dark .skeleton {
            background: linear-gradient(90deg,#374151 25%,#4b5563 50%,#374151 75%);
            background-size: 600px 100%;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .table-col-hide { display: none !important; }
            .table-col-sm   { font-size: .7rem !important; }
        }
        @media (max-width: 640px) {
            .stat-card { padding: .875rem; }
        }
    </style>

    {{-- ==================== TOAST CONTAINER ==================== --}}
    <div class="toast-wrap" id="toastWrap"></div>

    <div class="space-y-4">

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- ── Total Customers ── --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-3">
                <div class="absolute -bottom-6 -right-6 w-20 h-20 rounded-full
                            bg-gradient-to-br from-violet-100 to-purple-200 dark:from-violet-900/20 dark:to-purple-900/20"></div>
                <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full
                            bg-gradient-to-br from-violet-50 to-purple-100 dark:from-violet-900/10 dark:to-purple-900/10"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500
                                    flex items-center justify-center shadow-lg shadow-violet-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Customers</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">All registered</p>
                        </div>
                    </div>
                    @if(isset($totalGrowth))
                        <span class="badge-anim inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                     {{ $totalGrowth >= 0
                                         ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400 ring-1 ring-violet-200 dark:ring-violet-800'
                                         : 'bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 ring-1 ring-red-200 dark:ring-red-800' }}">
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $totalGrowth >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                            </svg>
                            {{ $totalGrowth >= 0 ? '+' : '' }}{{ $totalGrowth }}%
                        </span>
                    @endif
                </div>

                <div class="relative flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight leading-none
                                   bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 bg-clip-text text-transparent"
                            data-count="{{ $totalCustomers }}" data-prefix="" data-suffix="">
                            0
                        </h2>
                        @if(isset($totalGrowth))
                            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
                                {{ $totalGrowth >= 0 ? '+' : '' }}{{ $totalGrowth }}% vs last month
                            </p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-violet-50 dark:bg-violet-900/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                            <span class="text-[10px] font-medium text-violet-600 dark:text-violet-400">{{ $activePct }}% active</span>
                        </div>
                        <div class="flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-500"></span>
                            <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">{{ $inactivePct }}% inactive</span>
                        </div>
                    </div>
                </div>

                <div class="relative flex gap-0.5 h-1.5 rounded-full overflow-hidden">
                    <div class="progress-bar h-full rounded-l-full bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500"
                         style="width: {{ $activePct }}%"></div>
                    <div class="h-full flex-1 rounded-r-full bg-gray-100 dark:bg-gray-700"></div>
                </div>
            </div>

            {{-- ── New This Month ── --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-3">

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">New This Month</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Newly registered</p>
                        </div>
                    </div>
                    <span class="badge-anim inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                 {{ $thisMonthGrowth >= 0
                                     ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800'
                                     : 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 ring-1 ring-red-200 dark:ring-red-800' }}">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $thisMonthGrowth >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                        </svg>
                        {{ $thisMonthGrowth >= 0 ? '+' : '' }}{{ $thisMonthGrowth }}%
                    </span>
                </div>

                <div class="relative flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight leading-none
                                   bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 bg-clip-text text-transparent"
                            data-count="{{ $thisMonthCustomers }}">0</h2>
                        <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">{{ $thisMonthPct }}% of total customers</p>
                    </div>
                    <div class="w-24 h-10">
                        <svg viewBox="0 0 96 40" class="w-full h-full overflow-visible">
                            <path class="anim-line" d="M4 28 C14 26,20 24,30 25 C40 26,48 20,58 21 C68 22,76 16,92 18"
                                  fill="none" stroke="#D1D5DB" stroke-width="2.5" stroke-linecap="round"/>
                            <path class="anim-line" d="M4 30 C14 29,20 23,30 24 C40 25,48 15,58 16 C68 17,76 10,92 8"
                                  fill="none" stroke="url(#grad1)" stroke-width="2.5" stroke-linecap="round"/>
                            <circle class="anim-dot" cx="92" cy="8" r="3.5" fill="#10B981"/>
                            <defs>
                                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#10B981"/>
                                    <stop offset="100%" stop-color="#14B8A6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ── Customer Status ── --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-3
                        sm:col-span-2 lg:col-span-1">

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600
                                    flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Customer Status</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Active vs Inactive</p>
                        </div>
                    </div>
                    <span class="badge-anim inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800">
                        {{ $activePct }}% active
                    </span>
                </div>

                <div class="relative flex items-end justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-0.5">Active</p>
                                <h2 class="text-3xl font-bold tracking-tight leading-none
                                           bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent"
                                    data-count="{{ $activeCustomers ?? 0 }}">0</h2>
                            </div>
                            <div class="w-px h-8 bg-gray-100 dark:bg-gray-700"></div>
                            <div>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-0.5">Inactive</p>
                                <h2 class="text-3xl font-bold tracking-tight leading-none
                                           bg-gradient-to-r from-gray-400 to-gray-500 bg-clip-text text-transparent"
                                    data-count="{{ $inactiveCustomers ?? 0 }}">0</h2>
                            </div>
                        </div>
                        <div class="mt-1.5 flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-0.5 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 inline-block"></span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">Active {{ $activePct }}%</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-0.5 rounded-full bg-gray-300 dark:bg-gray-600 inline-block"></span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">Inactive {{ $inactivePct }}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-24 h-10">
                        <svg viewBox="0 0 96 40" class="w-full h-full overflow-visible">
                            <path class="anim-line" d="M4 18 C14 20,24 22,34 21 C44 20,54 24,64 23 C74 22,82 24,92 26"
                                  fill="none" stroke="#D1D5DB" stroke-width="2.5" stroke-linecap="round"/>
                            <circle class="anim-dot" cx="92" cy="26" r="3" fill="#9CA3AF"/>
                            <path class="anim-line" d="M4 34 C14 30,24 24,34 22 C44 20,54 14,64 11 C74 8,82 5,92 4"
                                  fill="none" stroke="url(#grad2)" stroke-width="2.5" stroke-linecap="round"/>
                            <circle class="anim-dot" cx="92" cy="4" r="3.5" fill="#6366F1"/>
                            <defs>
                                <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#3B82F6"/>
                                    <stop offset="100%" stop-color="#9333EA"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                        flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                    {{-- ROLE FILTER --}}
                    {{-- <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700 p-1 gap-1 flex-wrap">
                        @foreach(['all' => 'All', 'customer' => 'Customers', 'staff' => 'Staff'] as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['role' => $value, 'page' => 1]) }}" 
                               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                      {{ ($roleFilter ?? 'all') === $value
                                          ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div> --}}
                    <form method="GET" action="{{ url()->current() }}">
                        @foreach(request()->except(['role', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="role"
                                onchange="this.form.submit()"
                                class="px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            
                            @foreach([
                                'all' => 'All',
                                'customer' => 'Customers',
                                'staff' => 'Staff',
                                'admin' => 'Admin'
                            ] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ ($roleFilter ?? 'all') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>
                    </form>

                    {{-- SEARCH --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input type="text" id="customerSearch" placeholder="Search customers…" oninput="filterCustomers()"
                               autocomplete="off"
                               class="w-full sm:w-56 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                      bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                    </div>

                    {{-- EXPORT --}}
                    <button type="button" onclick="openExportModal()"
                        class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium
                               rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M4 21h16"/>
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>

                    {{-- ADD --}}
                    <button type="button" onclick="openModal()"
                        class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium
                               rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                               shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        <span class="hidden sm:inline">Add User</span>
                    </button>
                </div>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if(($roleFilter ?? 'all') !== 'all')
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                            flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold capitalize">{{ $roleFilter }}</span>
                        &mdash; {{ number_format($customers->total()) }} {{ Str::plural('result', $customers->total()) }}
                    </p>
                    <a href="{{ request()->fullUrlWithQuery(['role' => 'all', 'page' => 1]) }}" 
                       class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">Clear filter</a>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-4 sm:px-6 py-3">Customer</th>
                            <th class="px-4 sm:px-6 py-3 table-col-hide">Email + Phone</th>
                            <th class="px-4 sm:px-6 py-3 table-col-hide">Joined</th>
                            <th class="px-4 sm:px-6 py-3 text-center">Orders</th>
                            <th class="px-4 sm:px-6 py-3">Spent</th>
                            <th class="px-4 sm:px-6 py-3">Status</th>
                            <th class="px-4 sm:px-6 py-3 table-col-hide">Role</th>
                            <th class="px-4 sm:px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody id="customersTable" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($customers as $user)
                            @php
                                $isVip      = $user->total_spent > 1000;
                                $words      = preg_split('/\s+/', trim($user->name ?? ''));
                                $initials   = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                                $avatarGrad = match ($user->role ?? 'customer') {
                                    'staff'  => 'from-violet-500 to-purple-600',
                                    default  => $isVip
                                        ? 'from-emerald-500 to-teal-600'
                                        : 'from-indigo-500 to-blue-600',
                                };
                                $isActive   = $user->created_at >= now()->subDays(30) || $user->orders_count > 0;
                            @endphp

                            <tr class="customer-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200"
                                data-id="{{ $user->id }}"
                                data-first_name="{{ strtolower($user->first_name ?? '') }}"
                                data-last_name="{{ strtolower($user->last_name ?? '') }}"
                                data-email="{{ strtolower($user->email) }}"
                                data-phone="{{ strtolower($user->phone ?? '') }}"
                                data-role="{{ $user->role ?? 'customer' }}">

                                {{-- CUSTOMER --}}
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->first_name }}"
                                                 class="avatar-ring w-9 h-9 rounded-2xl object-cover shadow-md ring-2 ring-white dark:ring-gray-800">
                                        @else
                                            <div class="avatar-ring w-9 h-9 rounded-2xl bg-gradient-to-br {{ $avatarGrad }}
                                                        shadow-md ring-2 ring-white dark:ring-gray-800
                                                        flex items-center justify-center text-xs font-bold text-white">
                                                {{ $initials ?: strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-medium text-gray-900 dark:text-white truncate text-sm">
                                                {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) }}
                                            </div>
                                            {{-- Show email inline on mobile --}}
                                            <div class="text-[11px] text-gray-400 truncate md:hidden">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- EMAIL + PHONE --}}
                                <td class="px-4 sm:px-6 py-4 table-col-hide">
                                    <div class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[180px]">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $user->phone ?: 'No phone' }}</div>
                                </td>

                                {{-- JOINED --}}
                                <td class="px-4 sm:px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs table-col-hide">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>

                                {{-- ORDERS --}}
                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $user->orders_count }}
                                    </span>
                                </td>

                                {{-- SPENT --}}
                                <td class="px-4 sm:px-6 py-4 font-semibold text-gray-900 dark:text-white whitespace-nowrap text-sm">
                                    ${{ number_format($user->total_spent, 2) }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 sm:px-6 py-4">
                                    @if($user->role === 'staff')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                     bg-violet-100 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400">
                                            Staff
                                        </span>
                                    @elseif($isActive)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                                     bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                     bg-gray-100 dark:bg-gray-500/10 text-gray-600 dark:text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- ROLE --}}
                                <td class="px-4 sm:px-6 py-4 table-col-hide">
                                    @if(($user->role ?? 'customer') === 'staff')
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                     bg-violet-100 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400">
                                            Staff
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                     bg-indigo-100 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400">
                                            Customer
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-4 sm:px-6 py-4 text-right">
                                    <button onclick="editUser(
                                                {{ $user->id }},
                                                '{{ addslashes($user->first_name ?? '') }}',
                                                '{{ addslashes($user->last_name ?? '') }}',
                                                '{{ addslashes($user->email) }}',
                                                '{{ addslashes($user->phone ?? '') }}',
                                                '{{ $user->role ?? 'customer' }}',
                                                '{{ $user->avatar ?? '' }}')"
                                        class="action-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg
                                               bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                               hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="searchEmpty" class="hidden px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                    No customers match your search.
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($customers->total())
                        Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ number_format($customers->total()) }}
                    @else
                        No customers found
                    @endif
                </p>
                {{ $customers->links() }}
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="modal-overlay fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
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
                <a href="{{ route('customers.export.csv') }}"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/10
                          hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-emerald-300 flex items-center justify-center transition-all">
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
                <a href="{{ route('customers.export.pdf') }}"
                   class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600
                          bg-gray-50 dark:bg-gray-700/50 hover:bg-red-50 dark:hover:bg-red-500/10
                          hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                    group-hover:border-red-300 flex items-center justify-center transition-all">
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
                <button onclick="closeExportModal()"
                    class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                           text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    {{-- ==================== CREATE / EDIT USER MODAL ==================== --}}
    <div id="userModal" class="modal-overlay fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col">

            {{-- Indigo header --}}
            <div class="bg-indigo-700 px-6 sm:px-7 pt-6 sm:pt-7 pb-14 flex-shrink-0">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-medium tracking-widest text-indigo-300 uppercase mb-1">User account</p>
                        <h2 id="userModalTitle" class="text-lg font-semibold text-white">Edit profile</h2>
                    </div>
                    <button type="button" onclick="closeUserModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Avatar float --}}
            <div class="flex justify-center -mt-10 mb-1 relative z-10 flex-shrink-0">
                <div class="relative inline-block">
                    <img id="editAvatar"
                         src="https://ui-avatars.com/api/?name=User&background=4338ca&color=fff&size=80&bold=true"
                         alt="Avatar"
                         class="w-20 h-20 rounded-2xl object-cover border-[3px] border-white dark:border-gray-800 shadow-lg">
                    <span class="absolute -bottom-1.5 -right-1.5 w-6 h-6 flex items-center justify-center rounded-full
                                 bg-indigo-600 border-2 border-white dark:border-gray-800">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </span>
                </div>
            </div>

            <p id="avatarName"  class="text-center text-sm font-semibold text-gray-900 dark:text-white mt-2">—</p>
            <p id="avatarEmail" class="text-center text-xs text-gray-400 dark:text-gray-500 mb-4">—</p>

            {{-- Scrollable form --}}
            <div class="flex-1 overflow-y-auto">
                <form id="userForm" method="POST" action="" class="px-5 sm:px-6 pb-6 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="PATCH">

                    {{-- Personal details --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] font-medium tracking-widest text-gray-400 dark:text-gray-500 uppercase whitespace-nowrap">Personal details</span>
                            <div class="flex-1 h-px bg-gray-100 dark:bg-gray-700"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">First name</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" name="first_name" id="editFirstName" required
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Last name</label>
                                <input type="text" name="last_name" id="editLastName"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                    </svg>
                                    <input type="email" name="email" id="editEmail" required
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input type="text" name="phone" id="editPhone"
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Access & role --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] font-medium tracking-widest text-gray-400 dark:text-gray-500 uppercase whitespace-nowrap">Access & role</span>
                            <div class="flex-1 h-px bg-gray-100 dark:bg-gray-700"></div>
                        </div>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <select name="role" id="editRole"
                                class="w-full pl-9 pr-8 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none transition-all">
                                <option value="customer">Customer</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] font-medium tracking-widest text-gray-400 dark:text-gray-500 uppercase whitespace-nowrap">Password</span>
                            <div class="flex-1 h-px bg-gray-100 dark:bg-gray-700"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">New password</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="password" name="password" id="editPassword" placeholder="Leave blank to keep"
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Confirm password</label>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="password" name="password_confirmation" id="editPasswordConfirmation" placeholder="Repeat new password"
                                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center gap-2 pt-1">
                        <button type="button" onclick="closeUserModal()"
                            class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                   text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit" id="saveBtn"
                            class="action-btn flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium
                                   rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ==================== SCRIPTS ==================== --}}
    <script>
    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER
    // ══════════════════════════════════════════════════════
    function animateCount(el) {
        const target   = parseInt(el.dataset.count, 10) || 0;
        const prefix   = el.dataset.prefix  || '';
        const suffix   = el.dataset.suffix  || '';
        const duration = 1200;
        const start    = performance.now();

        function easeOut(t) { return 1 - Math.pow(1 - t, 3); }

        function step(now) {
            const elapsed  = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const current  = Math.round(easeOut(progress) * target);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // Run counters when page loads (with slight delay for visual polish)
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(animateCount);
        }, 300);
    });

    // ══════════════════════════════════════════════════════
    //  TOAST SYSTEM
    // ══════════════════════════════════════════════════════
    const TOAST_COLORS = {
        success: '#10b981',
        error:   '#ef4444',
        info:    '#6366f1',
        warning: '#f59e0b',
    };

    function showToast(msg, type = 'success') {
        const wrap  = document.getElementById('toastWrap');
        const toast = document.createElement('div');
        toast.className = 'toast dark:toast';
        toast.innerHTML = `<span class="toast-dot" style="background:${TOAST_COLORS[type]??TOAST_COLORS.info}"></span><span>${msg}</span>`;
        wrap.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('out');
            toast.addEventListener('animationend', () => toast.remove());
        }, 3500);
    }

    // ══════════════════════════════════════════════════════
    //  MODAL HELPERS
    // ══════════════════════════════════════════════════════
    function showModal(id) {
        const m = document.getElementById(id);
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function hideModal(id) {
        const m = document.getElementById(id);
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    ['userModal', 'exportModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) hideModal(id);
        });
    });

    function openExportModal()  { showModal('exportModal'); }
    function closeExportModal() { hideModal('exportModal'); }
    function closeUserModal()   { hideModal('userModal'); }

    // ══════════════════════════════════════════════════════
    //  EDIT USER
    // ══════════════════════════════════════════════════════
    function editUser(id, firstName, lastName, email, phone, role, avatar) {
        document.getElementById('userModalTitle').textContent = 'Edit profile';
        document.getElementById('userForm').action = '/admin/customers/' + id;
        document.getElementById('formMethod').value = 'PATCH';

        document.getElementById('editFirstName').value = firstName ?? '';
        document.getElementById('editLastName').value  = lastName  ?? '';
        document.getElementById('editEmail').value     = email     ?? '';
        document.getElementById('editPhone').value     = phone     ?? '';
        document.getElementById('editRole').value      = role      ?? 'customer';

        const fullName = [firstName, lastName].filter(Boolean).join(' ') || 'User';
        document.getElementById('avatarName').textContent  = fullName;
        document.getElementById('avatarEmail').textContent = email ?? '';
        document.getElementById('editAvatar').src = (avatar && avatar.trim())
            ? avatar
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(fullName) + '&background=4338ca&color=fff&size=80&bold=true';

        // Password requirement
        const roleSelect = document.getElementById('editRole');
        const pw1 = document.getElementById('editPassword');
        const pw2 = document.getElementById('editPasswordConfirmation');
        pw1.value = ''; pw2.value = '';

        function togglePwRequired() {
            const r = roleSelect.value;
            const need = r === 'staff' || r === 'admin';
            pw1.required = pw2.required = need;
            pw1.placeholder = need ? 'Required for staff/admin' : 'Leave blank to keep';
            pw2.placeholder = need ? 'Confirm required password' : 'Repeat new password';
        }
        roleSelect.onchange = togglePwRequired;
        togglePwRequired();

        showModal('userModal');
    }

    // ══════════════════════════════════════════════════════
    //  ADD USER
    // ══════════════════════════════════════════════════════
    function openModal() {
        document.getElementById('userModalTitle').textContent = 'Add User';
        document.getElementById('userForm').action = '/admin/customers';
        document.getElementById('formMethod').value = 'POST';
        ['editFirstName','editLastName','editEmail','editPhone','editPassword','editPasswordConfirmation'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('editRole').value = 'customer';
        document.getElementById('avatarName').textContent  = 'New User';
        document.getElementById('avatarEmail').textContent = '';
        document.getElementById('editAvatar').src =
            'https://ui-avatars.com/api/?name=New+User&background=4338ca&color=fff&size=80&bold=true';
        document.getElementById('editRole').onchange = null;
        showModal('userModal');
    }

    // ══════════════════════════════════════════════════════
    //  AJAX FORM SUBMIT (no full page reload)
    // ══════════════════════════════════════════════════════
    document.getElementById('userForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btn      = document.getElementById('saveBtn');
        const origHTML = btn.innerHTML;
        btn.disabled   = true;
        btn.innerHTML  = `<span class="spinner"></span><span>Saving…</span>`;

        const formData = new FormData(this);
        const method   = formData.get('_method') || 'POST';
        const url      = this.action;

        // FormData doesn't send _method correctly for PUT/PATCH with fetch; send JSON instead
        const payload = {};
        formData.forEach((v, k) => { if (k !== '_method' && k !== '_token') payload[k] = v; });

        try {
            const res = await fetch(url, {
                method: method === 'PATCH' ? 'POST' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': method,
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                closeUserModal();
                showToast(method === 'PATCH' ? 'Profile updated successfully.' : 'User created successfully.', 'success');

                // Patch the table row in-place if editing
                if (method === 'PATCH') {
                    const rowId   = url.split('/').pop();
                    const row     = document.querySelector(`tr[data-id="${rowId}"]`);
                    if (row) {
                        const fullName = [payload.first_name, payload.last_name].filter(Boolean).join(' ');
                        const nameCell = row.querySelector('td:first-child .font-medium');
                        if (nameCell) nameCell.textContent = fullName;

                        // Update data attributes for search
                        row.dataset.first_name = (payload.first_name || '').toLowerCase();
                        row.dataset.last_name  = (payload.last_name  || '').toLowerCase();
                        row.dataset.email      = (payload.email      || '').toLowerCase();
                        row.dataset.phone      = (payload.phone      || '').toLowerCase();

                        // Flash row
                        row.style.background = 'rgba(99,102,241,.08)';
                        setTimeout(() => { row.style.background = ''; }, 1200);
                    }
                } else {
                    // New user: reload after short delay so the toast shows
                    setTimeout(() => window.Livewire?.navigate(window.location.href), 1000);
                }
            } else {
                const msg = data?.message || (data?.errors ? Object.values(data.errors).flat().join(' ') : 'Something went wrong.');
                showToast(msg, 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error. Please try again.', 'error');
        } finally {
            btn.disabled  = false;
            btn.innerHTML = origHTML;
        }
    });

    // ══════════════════════════════════════════════════════
    //  CLIENT-SIDE SEARCH FILTER
    // ══════════════════════════════════════════════════════
    function filterCustomers() {
        const q     = document.getElementById('customerSearch').value.toLowerCase().trim();
        const rows  = document.querySelectorAll('.customer-row');
        const empty = document.getElementById('searchEmpty');
        let vis     = 0;

        rows.forEach(row => {
            const match = ['first_name','last_name','email','phone','id'].some(k =>
                (row.dataset[k] || '').includes(q));
            row.style.display = match ? '' : 'none';
            if (match) vis++;
        });

        empty.classList.toggle('hidden', !(q && vis === 0));
    }
    </script>

@endsection