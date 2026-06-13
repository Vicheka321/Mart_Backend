@extends('layouts.app')

@section('content')
    @php
        $totalCount  = $brands->total();
        $withImage   = $brands->getCollection()->filter(fn($b) => $b->image)->count();
        $withCountry = $brands->getCollection()->filter(fn($b) => $b->country)->count();
        $imgPct      = $totalCount > 0 ? round(($withImage   / $totalCount) * 100) : 0;
        $ctryPct     = $totalCount > 0 ? round(($withCountry / $totalCount) * 100) : 0;
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

        /* Stat cards */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .13s; }
        .stat-card:nth-child(3) { animation-delay: .21s; }

        /* Table wrapper */
        .table-card { animation: fadeSlideUp .5s .26s ease both; }

        /* Brand grid cards */
        .brand-card {
            animation: cardPop .38s ease both;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .brand-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }
        .brand-card:nth-child(1)  { animation-delay: .30s; }
        .brand-card:nth-child(2)  { animation-delay: .35s; }
        .brand-card:nth-child(3)  { animation-delay: .40s; }
        .brand-card:nth-child(4)  { animation-delay: .45s; }
        .brand-card:nth-child(5)  { animation-delay: .50s; }
        .brand-card:nth-child(6)  { animation-delay: .55s; }
        .brand-card:nth-child(7)  { animation-delay: .60s; }
        .brand-card:nth-child(8)  { animation-delay: .65s; }
        .brand-card:nth-child(9)  { animation-delay: .70s; }
        .brand-card:nth-child(10) { animation-delay: .75s; }

        /* Progress bars */
        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }

        /* Counter pop */
        .count-done { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        /* Modals */
        #exportModal.flex { animation: overlayIn .2s ease; }
        #brandModal.flex  { animation: overlayIn .2s ease; }
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

        /* Image zoom */
        .brand-img { transition: transform .35s cubic-bezier(.25,.46,.45,.94); }
        .brand-card:hover .brand-img { transform: scale(1.06); }

        /* Search glow */
        #brandSearch:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }

        /* Upload overlay */
        #uploadBox:hover #editOverlay {
            background: rgba(0,0,0,.3) !important;
            display: flex !important;
        }
    </style>

    <div class="toast-wrap" id="toastWrap"></div>

    <div class="space-y-4">

        {{-- ==================== STAT CARDS ==================== --}}
        {{-- <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

 
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-violet-50 to-indigo-100 dark:from-violet-900/20 dark:to-indigo-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600
                                    flex items-center justify-center shadow-md shadow-violet-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Brands</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">All registered</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full
                                 bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400
                                 ring-1 ring-violet-200 dark:ring-violet-800 text-[10px] font-semibold">
                        {{ number_format($totalCount) }}
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent"
                        data-count="{{ $totalCount }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-violet-500 to-indigo-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All brands</span>
                        <span class="text-[10px] font-semibold text-violet-600 dark:text-violet-400">100%</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">With Logo</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Have brand images</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400
                                 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">
                        {{ $imgPct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                        data-count="{{ $withImage }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-cyan-600"
                             style="width: {{ $imgPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $imgPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalCount) }} total</span>
                    </div>
                </div>
            </div>

      
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full
                            bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">With Country</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Origin specified</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">
                        {{ $ctryPct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent"
                        data-count="{{ $withCountry }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-600"
                             style="width: {{ $ctryPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $ctryPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($totalCount) }} total</span>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                        flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Brand List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">

                    {{-- SEARCH --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input type="text" id="brandSearch" placeholder="Search brands…" oninput="filterBrands()"
                               autocomplete="off"
                               class="w-full sm:w-56 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                      bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
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

                    {{-- ADD --}}
                    <button type="button" onclick="openModal()"
                        class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                               bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                               shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        <span class="hidden sm:inline">Add Brand</span>
                    </button>
                </div>
            </div>

            {{-- BRAND GRID --}}
            <div class="p-4 sm:p-5">
                <div id="brandsTable"
                     class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">

                    @forelse($brands as $brand)
                        <div class="brand-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                    rounded-2xl overflow-hidden flex flex-col"
                             data-name="{{ strtolower($brand->name) }}"
                             data-country="{{ strtolower($brand->country ?? '') }}">

                            {{-- Image / Initial --}}
                            @if($brand->image)
                                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}"
                                         class="brand-img w-full h-full object-cover">
                                </div>

                                                                {{-- <div class="relative aspect-square overflow-hidden
                                            bg-gradient-to-br
                                            from-slate-50
                                            to-slate-100
                                            dark:from-slate-800
                                            dark:to-slate-700">

                                    <div class="w-full h-full p-5 flex items-center justify-center">

                                        <img src="{{ asset($brand->image) }}"
                                            alt="{{ $brand->name }}"
                                            class="brand-img
                                                    max-w-full
                                                    max-h-full
                                                    object-contain">

                                    </div>

                                </div> --}}
                            @else
                                <div class="aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200
                                            dark:from-gray-700 dark:to-gray-600
                                            flex items-center justify-center text-3xl font-bold text-gray-400 dark:text-gray-500">
                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="p-3 flex flex-col gap-2 flex-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">
                                        {{ $brand->name }}
                                    </p>
                                    @if($brand->country)
                                        <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                     bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $brand->country }}
                                        </span>
                                    @else
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">No country</p>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-1.5 mt-auto pt-1">
                                    <button type="button"
                                        onclick="editBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}', '{{ addslashes($brand->country ?? '') }}', '{{ asset($brand->image) }}')"
                                        class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                               bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors
                                               dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </button>

                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                          class="flex-1 delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit"
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

                    @empty
                        <div class="col-span-full py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                            No brands found.
                        </div>
                    @endforelse
                </div>

                <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                    No brands match your search.
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">

                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($brands->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $brands->firstItem() }}–{{ $brands->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($brands->total()) }}</span>
                        results
                    @else
                        No brands found
                    @endif
                </p>

                @if($brands->hasPages())
                    <nav class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($brands->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $brands->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($brands->getUrlRange(max(1, $brands->currentPage() - 2), min($brands->lastPage(), $brands->currentPage() + 2)) as $page => $url)
                            @if($page == $brands->currentPage())
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                            bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                        text-sm font-medium text-gray-500 dark:text-gray-400
                                        hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                        transition-colors duration-150">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($brands->hasMorePages())
                            <a href="{{ $brands->nextPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
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
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
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
                <a href="{{ route('brands.export.csv') }}"
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
                <a href="{{ route('brands.export.pdf') }}"
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


    {{-- ==================== ADD / EDIT MODAL ==================== --}}
    <div id="brandModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 id="modalTitle" class="text-base font-semibold text-gray-900 dark:text-white">Add Brand</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="brandForm" action="{{ route('brands.store') }}" method="POST"
                  enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                {{-- Name + Country --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Brand Name</label>
                        <input type="text" name="name" id="brandName" placeholder="e.g. Nike" required
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Country</label>
                        <input type="text" name="country" id="brandCountry" placeholder="e.g. USA"
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Brand Logo</label>

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
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Click to upload logo</span>
                            <span class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</span>
                        </div>

                        <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-contain" alt="Preview">

                        <div id="editOverlay" class="hidden absolute inset-0 items-center justify-center pointer-events-none">
                            <span class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium px-4 py-1.5 rounded-full shadow">
                                Change Photo
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

                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                </div>

                <button type="submit"
                    class="action-btn w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                           rounded-xl transition-all shadow-md shadow-indigo-500/25">
                    Save Brand
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
                Swal.fire({
                    title: 'Delete brand?',
                    text: 'This action cannot be undone.',
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

    document.getElementById('exportModal').addEventListener('click', function(e) { if (e.target === this) hideModal('exportModal'); });
    document.getElementById('brandModal').addEventListener('click',  function(e) { if (e.target === this) hideModal('brandModal'); });

    function openExportModal()  { showModal('exportModal'); }
    function closeExportModal() { hideModal('exportModal'); }
    function openModal()        { resetForm(); showModal('brandModal'); }
    function closeModal()       { hideModal('brandModal'); }

    function resetForm() {
        document.getElementById('brandForm').action    = "{{ route('brands.store') }}";
        document.getElementById('formMethod').value    = 'POST';
        document.getElementById('brandName').value     = '';
        document.getElementById('brandCountry').value  = '';
        document.getElementById('modalTitle').innerText = 'Add Brand';
        resetImagePreview();
    }

    // ══════════════════════════════════════════════════════
    //  IMAGE PREVIEW
    // ══════════════════════════════════════════════════════
    function showImagePreview(src) {
        document.getElementById('imagePreview').src = src;
        document.getElementById('imagePreview').classList.remove('hidden');
        document.getElementById('uploadPlaceholder').classList.add('hidden');
        document.getElementById('removeImageBtn').classList.remove('hidden');
    }

    function resetImagePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('uploadPlaceholder').classList.remove('hidden');
        document.getElementById('editOverlay').classList.add('hidden');
        document.getElementById('editOverlay').classList.remove('flex');
        document.getElementById('removeImageBtn').classList.add('hidden');
    }

    function removeImage(e) { e.stopPropagation(); resetImagePreview(); }

    document.getElementById('imageInput').addEventListener('change', () => {
        const file = document.getElementById('imageInput').files[0];
        if (file) showImagePreview(URL.createObjectURL(file));
    });

    const uploadBox = document.getElementById('uploadBox');
    uploadBox.addEventListener('mouseenter', () => {
        if (!document.getElementById('imagePreview').classList.contains('hidden')) {
            document.getElementById('editOverlay').classList.remove('hidden');
            document.getElementById('editOverlay').classList.add('flex');
        }
    });
    uploadBox.addEventListener('mouseleave', () => {
        document.getElementById('editOverlay').classList.add('hidden');
        document.getElementById('editOverlay').classList.remove('flex');
    });

    // ══════════════════════════════════════════════════════
    //  EDIT
    // ══════════════════════════════════════════════════════
    function editBrand(id, name, country, image) {
        openModal();
        document.getElementById('modalTitle').innerText  = 'Edit Brand';
        document.getElementById('brandName').value       = name;
        document.getElementById('brandCountry').value    = (country && country !== 'N/A') ? country : '';
        document.getElementById('brandForm').action      = '/admin/brand/' + id;
        document.getElementById('formMethod').value      = 'PUT';
        if (image) showImagePreview(image);
        else resetImagePreview();
    }

    // ══════════════════════════════════════════════════════
    //  SEARCH FILTER
    // ══════════════════════════════════════════════════════
    function filterBrands() {
        const q     = document.getElementById('brandSearch').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.brand-card');
        const empty = document.getElementById('searchEmpty');
        let vis     = 0;
        cards.forEach(card => {
            const match = (card.dataset.name || '').includes(q) || (card.dataset.country || '').includes(q);
            card.style.display = match ? '' : 'none';
            if (match) vis++;
        });
        empty.classList.toggle('hidden', !(q && vis === 0));
    }
    </script>
    @endpush

@endsection