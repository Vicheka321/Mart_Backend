@extends('layouts.app')

@section('content')
    @php
        $totalCount     = $banners->count();
        $activeCount    = $banners->where('display_status', 'active')->count();
        $inactiveCount  = $banners->where('display_status', 'inactive')->count();
        $scheduledCount = $banners->where('display_status', 'scheduled')->count();
        $expiredCount   = $banners->where('display_status', 'expired')->count();

        $activePct    = $totalCount > 0 ? round(($activeCount    / $totalCount) * 100) : 0;
        $scheduledPct = $totalCount > 0 ? round(($scheduledCount / $totalCount) * 100) : 0;
        $inactivePct  = $totalCount > 0 ? round(($inactiveCount  / $totalCount) * 100) : 0;
    @endphp

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.93) translateY(14px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.8); opacity: 0; }
            70%  { transform: scale(1.07); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.93) translateY(24px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes toastSlide {
            from { opacity: 0; transform: translateX(48px) scale(.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastOut {
            from { opacity: 1; }
            to   { opacity: 0; transform: translateX(48px); }
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .5; transform: scale(1.5); }
        }

        /* Stat cards */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .13s; }
        .stat-card:nth-child(3) { animation-delay: .21s; }
        .stat-card:nth-child(4) { animation-delay: .29s; }

        /* Table card */
        .table-card { animation: fadeSlideUp .5s .30s ease both; }

        /* Banner grid cards */
        .banner-card {
            animation: cardPop .38s ease both;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .banner-card:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(0,0,0,.12); }
        .banner-card:nth-child(1)  { animation-delay: .33s; }
        .banner-card:nth-child(2)  { animation-delay: .38s; }
        .banner-card:nth-child(3)  { animation-delay: .43s; }
        .banner-card:nth-child(4)  { animation-delay: .48s; }
        .banner-card:nth-child(5)  { animation-delay: .53s; }
        .banner-card:nth-child(6)  { animation-delay: .58s; }
        .banner-card:nth-child(7)  { animation-delay: .63s; }
        .banner-card:nth-child(8)  { animation-delay: .68s; }

        /* Progress */
        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }

        /* Counter pop */
        .count-done { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        /* Modal */
        #bannerModal.flex { animation: overlayIn .2s ease; }
        .modal-inner      { animation: modalIn .28s cubic-bezier(.34,1.56,.64,1) both; }

        /* Toast */
        .toast-wrap {
            position: fixed; top: 1.25rem; right: 1.25rem;
            z-index: 9999; display: flex; flex-direction: column; gap: .5rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex; align-items: center; gap: .625rem;
            padding: .75rem 1rem; min-width: 230px;
            background: white; border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12);
            font-size: .8125rem; font-weight: 500; color: #111827;
            animation: toastSlide .3s cubic-bezier(.34,1.3,.64,1) both;
        }
        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.leaving { animation: toastOut .25s ease forwards; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* Action button lift */
        .action-btn { transition: transform .14s ease, box-shadow .14s ease; }
        .action-btn:hover  { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.18); }
        .action-btn:active { transform: translateY(0); }

        /* Banner image zoom */
        .banner-img { transition: transform .4s cubic-bezier(.25,.46,.45,.94); }
        .banner-card:hover .banner-img { transform: scale(1.06); }

        /* Active pulse dot */
        .pulse-dot { animation: pulseDot 2s ease-in-out infinite; }

        /* Search glow */
        #bannerSearch:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }

        /* Upload overlay */
        #uploadBox:hover #editOverlay {
            background: rgba(0,0,0,.3) !important;
            display: flex !important;
        }
    </style>

    <div class="toast-wrap" id="toastWrap"></div>

    <div class="space-y-4">

        {{-- ==================== STAT CARDS ==================== --}}
        {{-- <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600
                                    flex items-center justify-center shadow-md shadow-indigo-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Banners</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">All banners</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400
                                 ring-1 ring-indigo-200 dark:ring-indigo-800 text-[10px] font-semibold">
                        {{ $totalCount }}
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"
                        data-count="{{ $totalCount }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All registered</span>
                        <span class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400">100%</span>
                    </div>
                </div>
            </div>

   
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Active</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Currently live</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">
                        <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                        {{ $activePct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent"
                        data-count="{{ $activeCount }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600"
                             style="width: {{ $activePct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $activePct }}% of total</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ $totalCount }} total</span>
                    </div>
                </div>
            </div>

       
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-blue-50 to-cyan-100 dark:from-blue-900/20 dark:to-cyan-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600
                                    flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Scheduled</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Upcoming</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">
                        {{ $scheduledPct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                        data-count="{{ $scheduledCount }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-cyan-600"
                             style="width: {{ $scheduledPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $scheduledPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ $totalCount }} total</span>
                    </div>
                </div>
            </div>

           
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-gray-50 to-slate-100 dark:from-gray-700/50 dark:to-slate-800/50"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-gray-400 to-slate-500
                                    flex items-center justify-center shadow-md shadow-gray-400/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Inactive</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Disabled banners</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                                 ring-1 ring-gray-200 dark:ring-gray-600 text-[10px] font-semibold">
                        {{ $inactivePct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-gray-500 to-slate-500 bg-clip-text text-transparent"
                        data-count="{{ $inactiveCount }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-gray-400 to-slate-500"
                             style="width: {{ $inactivePct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $inactivePct }}% of total</span>
                        <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400">{{ $totalCount }} total</span>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                        flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Banner List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                    {{-- STATUS FILTER PILLS --}}
                    {{-- <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700 p-1 gap-1 flex-wrap">
                        @foreach(['all' => 'All', 'active' => 'Active', 'scheduled' => 'Scheduled', 'expired' => 'Expired', 'inactive' => 'Inactive'] as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['status' => $value, 'page' => 1]) }}" wire:navigate
                               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                      {{ ($statusFilter ?? 'all') === $value
                                          ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div> --}}

                    <form method="GET" action="{{ url()->current() }}">
                        @foreach(request()->except(['status', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="status"
                                onchange="this.form.submit()"
                                class="px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            
                            @foreach([
                                'all' => 'All',
                                'active' => 'Active',
                                'scheduled' => 'Scheduled',
                                'expired' => 'Expired',
                                'inactive' => 'Inactive'
                            ] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ ($statusFilter ?? 'all') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>
                    </form>
                    {{-- SEARCH --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input type="text" id="bannerSearch" placeholder="Search banners…" oninput="filterBanners()"
                               autocomplete="off"
                               class="w-full sm:w-52 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                      bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                    </div>

                    {{-- ADD --}}
                    <button type="button" onclick="openCreate()"
                        class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                               bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                               shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        <span class="hidden sm:inline">Add Banner</span>
                    </button>
                </div>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if(($statusFilter ?? 'all') !== 'all')
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                            flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold capitalize">{{ $statusFilter }}</span>
                        &mdash; {{ number_format($banners->count()) }} {{ Str::plural('result', $banners->count()) }}
                    </p>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}" wire:navigate
                       class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">Clear filter</a>
                </div>
            @endif

            {{-- BANNER GRID --}}
            <div class="p-4 sm:p-5">
                @if($banners->isEmpty())
                    <div class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">No banners found.</div>
                @else
                    <div id="bannersGrid"
                         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($banners as $banner)
                            <div class="banner-card banner-row group relative rounded-2xl overflow-hidden
                                        border border-gray-200 dark:border-gray-700
                                        bg-white dark:bg-gray-800 flex flex-col"
                                 data-id="{{ $banner->id }}"
                                 data-title="{{ strtolower($banner->title) }}">

                                {{-- IMAGE --}}
                                <div class="relative aspect-[16/7] overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                         class="banner-img w-full h-full object-cover">

                                    {{-- Status badge --}}
                                    <div class="absolute top-2 left-2">
                                        @if($banner->display_status === 'active')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/90 text-white" style="backdrop-filter:blur(4px)">
                                                <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                                Active
                                            </span>
                                        @elseif($banner->display_status === 'scheduled')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-500/90 text-white" style="backdrop-filter:blur(4px)">
                                                Scheduled
                                            </span>
                                        @elseif($banner->display_status === 'inactive')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold bg-gray-500/90 text-white" style="backdrop-filter:blur(4px)">
                                                Inactive
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold bg-red-500/90 text-white" style="backdrop-filter:blur(4px)">
                                                Expired
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Lifetime badge --}}
                                    @if($banner->is_lifetime)
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold bg-violet-500/90 text-white" style="backdrop-filter:blur(4px)">
                                                ∞ Lifetime
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- BODY --}}
                                <div class="p-3 flex flex-col gap-2 flex-1">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900 dark:text-white truncate leading-tight">
                                            {{ $banner->title }}
                                        </p>

                                        {{-- Schedule info --}}
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            @if($banner->is_lifetime)
                                                <span class="text-violet-500 dark:text-violet-400">Displays forever</span>
                                            @elseif($banner->display_status === 'active')
                                                Ends:
                                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('d M Y') : '—' }}
                                                </span>
                                            @elseif($banner->display_status === 'scheduled')
                                                Starts:
                                                <span class="font-medium text-blue-600 dark:text-blue-400">
                                                    {{ $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('d M Y') : '—' }}
                                                </span>
                                            @else
                                                @if($banner->end_date && \Carbon\Carbon::parse($banner->end_date)->isPast())
                                                    <span class="text-red-400">Ended {{ \Carbon\Carbon::parse($banner->end_date)->format('d M Y') }}</span>
                                                @else
                                                    <span class="text-gray-400">No schedule</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1.5 mt-auto pt-1">
                                        <button type="button"
                                            onclick='openEdit(
                                                {{ $banner->id }},
                                                @json($banner->title),
                                                {{ (int) $banner->status }},
                                                @json($banner->start_date),
                                                @json($banner->end_date),
                                                @json($banner->image_url)
                                            )'
                                            class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                   bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors
                                                   dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            Edit
                                        </button>

                                        <form class="delete-form flex-1" action="{{ route('banners.destroy', $banner->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" data-name="{{ addslashes($banner->title) }}"
                                                class="action-btn w-full inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                       bg-red-50 text-red-500 hover:bg-red-100 transition-colors
                                                       dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                        No banners match your search.
                    </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Showing {{ number_format($banners->count()) }} {{ Str::plural('banner', $banners->count()) }}
                    &nbsp;·&nbsp; <span class="text-emerald-500 font-medium">{{ $activeCount }} active</span>
                    @if($scheduledCount > 0)
                        &nbsp;·&nbsp; <span class="text-blue-500 font-medium">{{ $scheduledCount }} scheduled</span>
                    @endif
                    @if($inactiveCount > 0)
                        &nbsp;·&nbsp; <span class="text-gray-400 font-medium">{{ $inactiveCount }} inactive</span>
                    @endif
                    @if($expiredCount > 0)
                        &nbsp;·&nbsp; <span class="text-red-400 font-medium">{{ $expiredCount }} expired</span>
                    @endif
                </p>
            </div>
        </div>
    </div>


    {{-- ==================== CREATE / EDIT MODAL ==================== --}}
    <div id="bannerModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 id="modalTitle" class="text-base font-semibold text-gray-900 dark:text-white">Add Banner</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="bannerForm" action="{{ route('banners.store') }}" method="POST"
                  enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                {{-- Title + Status --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Title</label>
                        <input type="text" name="title" id="bannerTitle" placeholder="Banner title" required
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                        <select name="status" id="bannerStatus"
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- Schedule --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        Schedule
                        <span class="font-normal text-gray-400">(leave blank to display forever)</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] text-gray-400 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="bannerStartDate" min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-400 mb-1">End Date</label>
                            <input type="date" name="end_date" id="bannerEndDate" min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>
                    <p class="mt-1.5 text-[11px] text-violet-500 dark:text-violet-400 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        No dates = Lifetime banner (always displays while active)
                    </p>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                        Banner Image
                        <span id="imageOptionalLabel" class="hidden font-normal text-gray-400">(optional — leave empty to keep current)</span>
                    </label>

                    <div id="uploadBox" onclick="document.getElementById('imageInput').click()"
                         class="relative w-full h-44 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700
                                flex flex-col items-center justify-center cursor-pointer overflow-hidden
                                transition hover:border-indigo-400 dark:hover:border-indigo-500
                                hover:bg-indigo-50/30 dark:hover:bg-gray-600/50">

                        <div id="uploadPlaceholder" class="flex flex-col items-center gap-2 pointer-events-none select-none">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Click to upload image</span>
                            <span class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</span>
                        </div>

                        <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover" alt="Preview">

                        <div id="editOverlay" class="hidden absolute inset-0 items-center justify-center pointer-events-none">
                            <span class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium px-4 py-1.5 rounded-full shadow">
                                Change Image
                            </span>
                        </div>

                        <button type="button" id="removeImageBtn" onclick="removeImage(event)"
                            class="hidden absolute top-2 right-2 w-7 h-7 bg-gray-900/70 hover:bg-red-500 text-white
                                   rounded-full flex items-center justify-center transition z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                </div>

                <button type="submit"
                    class="action-btn w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                           rounded-xl transition-all shadow-md shadow-indigo-500/25">
                    <span id="submitLabel">Create Banner</span>
                </button>
            </form>
        </div>
    </div>


    @push('scripts')
    <script>
    // ══════════════════════════════════════════════════════
    //  ANIMATED NUMBER COUNTER
    // ══════════════════════════════════════════════════════
    function animateCounter(el) {
        const target   = parseInt(el.dataset.count, 10) || 0;
        const duration = 1000;
        const start    = performance.now();
        function ease(t) { return 1 - Math.pow(1 - t, 3); }
        function tick(now) {
            const progress = Math.min((now - start) / duration, 1);
            el.textContent = Math.round(ease(progress) * target).toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
            else { el.textContent = target.toLocaleString(); el.classList.add('count-done'); }
        }
        requestAnimationFrame(tick);
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(animateCounter);
        }, 300);

        // Delete confirm
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = this.querySelector('[data-name]')?.dataset.name ?? 'this banner';
                Swal.fire({
                    title: 'Delete banner?',
                    text: `"${name}" will be permanently removed.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });
    });

    // ══════════════════════════════════════════════════════
    //  MODAL HELPERS
    // ══════════════════════════════════════════════════════
    function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function hideModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    document.getElementById('bannerModal').addEventListener('click', function(e) { if (e.target === this) hideModal('bannerModal'); });

    function closeModal() { hideModal('bannerModal'); }

    // ══════════════════════════════════════════════════════
    //  IMAGE PREVIEW
    // ══════════════════════════════════════════════════════
    function previewImage(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => showPreview(e.target.result);
        reader.readAsDataURL(input.files[0]);
    }

    function showPreview(src) {
        document.getElementById('uploadPlaceholder').classList.add('hidden');
        const img = document.getElementById('imagePreview');
        img.src = src; img.classList.remove('hidden');
        document.getElementById('editOverlay').classList.remove('hidden');
        document.getElementById('editOverlay').classList.add('flex');
        document.getElementById('removeImageBtn').classList.remove('hidden');
        document.getElementById('removeImageBtn').classList.add('flex');
    }

    function clearPreview() {
        document.getElementById('uploadPlaceholder').classList.remove('hidden');
        const img = document.getElementById('imagePreview');
        img.src = ''; img.classList.add('hidden');
        document.getElementById('editOverlay').classList.add('hidden');
        document.getElementById('editOverlay').classList.remove('flex');
        document.getElementById('removeImageBtn').classList.add('hidden');
        document.getElementById('removeImageBtn').classList.remove('flex');
    }

    function removeImage(e) { e.stopPropagation(); document.getElementById('imageInput').value = ''; clearPreview(); }

    // ══════════════════════════════════════════════════════
    //  CREATE / EDIT
    // ══════════════════════════════════════════════════════
    function openCreate() {
        document.getElementById('modalTitle').textContent  = 'Add Banner';
        document.getElementById('submitLabel').textContent = 'Create Banner';
        document.getElementById('formMethod').value        = 'POST';
        document.getElementById('bannerForm').action       = '{{ route('banners.store') }}';
        document.getElementById('imageOptionalLabel').classList.add('hidden');
        document.getElementById('bannerTitle').value       = '';
        document.getElementById('bannerStatus').value      = '1';
        document.getElementById('bannerStartDate').value   = '';
        document.getElementById('bannerEndDate').value     = '';
        document.getElementById('imageInput').value        = '';
        clearPreview();
        showModal('bannerModal');
    }

    function openEdit(id, title, status, startDate, endDate, imageUrl) {
        document.getElementById('modalTitle').textContent  = 'Edit Banner';
        document.getElementById('submitLabel').textContent = 'Save Changes';
        document.getElementById('formMethod').value        = 'PUT';
        document.getElementById('bannerForm').action       = `{{ url('admin/banners') }}/${id}`;
        document.getElementById('imageOptionalLabel').classList.remove('hidden');
        document.getElementById('bannerTitle').value       = title;
        document.getElementById('bannerStatus').value      = String(status);
        document.getElementById('bannerStartDate').value   = startDate ? String(startDate).substring(0, 10) : '';
        document.getElementById('bannerEndDate').value     = endDate   ? String(endDate).substring(0, 10)   : '';
        document.getElementById('imageInput').value        = '';
        if (imageUrl) showPreview(imageUrl);
        else clearPreview();
        showModal('bannerModal');
    }

    // ══════════════════════════════════════════════════════
    //  SEARCH
    // ══════════════════════════════════════════════════════
    function filterBanners() {
        const q     = document.getElementById('bannerSearch').value.toLowerCase().trim();
        const rows  = document.querySelectorAll('.banner-row');
        const empty = document.getElementById('searchEmpty');
        let vis     = 0;
        rows.forEach(row => {
            const match = (row.dataset.title || '').includes(q) || (row.dataset.id || '').includes(q);
            row.style.display = match ? '' : 'none';
            if (match) vis++;
        });
        if (empty) empty.classList.toggle('hidden', !(q && vis === 0));
    }
    </script>
    @endpush

@endsection