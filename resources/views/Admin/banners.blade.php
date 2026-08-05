@extends('layouts.app')

@section('content')
    @php
        $totalCount = $banners->count();
        $activeCount = $banners->where('display_status', 'active')->count();
        $inactiveCount = $banners->where('display_status', 'inactive')->count();
        $scheduledCount = $banners->where('display_status', 'scheduled')->count();
        $expiredCount = $banners->where('display_status', 'expired')->count();

        $activePct = $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0;
        $scheduledPct = $totalCount > 0 ? round(($scheduledCount / $totalCount) * 100) : 0;
        $inactivePct = $totalCount > 0 ? round(($inactiveCount / $totalCount) * 100) : 0;
    @endphp

    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes cardPop {
            from {
                opacity: 0;
                transform: scale(0.93) translateY(14px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes progressFill {
            from {
                width: 0 !important;
            }
        }

        @keyframes numberPop {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            70% {
                transform: scale(1.07);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.93) translateY(24px);
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

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateX(48px) scale(.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                transform: translateX(48px);
            }
        }

        @keyframes pulseDot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(1.5);
            }
        }

        @keyframes rowSlideIn {
                from { opacity: 0; transform: translateX(-8px); }
                to   { opacity: 1; transform: translateX(0); }
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

            /* List rows staggered */
            .list-row { animation: rowSlideIn .3s ease both; transition: background-color .15s ease; }
            .list-row:nth-child(1)  { animation-delay: .04s; }
            .list-row:nth-child(2)  { animation-delay: .07s; }
            .list-row:nth-child(3)  { animation-delay: .10s; }
            .list-row:nth-child(4)  { animation-delay: .13s; }
            .list-row:nth-child(5)  { animation-delay: .16s; }
            .list-row:nth-child(6)  { animation-delay: .19s; }
            .list-row:nth-child(7)  { animation-delay: .22s; }
            .list-row:nth-child(8)  { animation-delay: .25s; }
            .list-row:hover { background-color: rgba(99,102,241,.04); }
            .dark .list-row:hover { background-color: rgba(99,102,241,.08); }

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

            /* View toggle buttons */
            .view-toggle-btn {
                transition: background .18s ease, color .18s ease, box-shadow .18s ease;
                color: #9ca3af;
            }
            .view-toggle-btn.active {
                background: white;
                color: #4f46e5;
                box-shadow: 0 1px 4px rgba(0,0,0,.1);
            }
            .dark .view-toggle-btn.active {
                background: #4b5563;
                color: #a5b4fc;
            }
            .view-toggle-btn:not(.active):hover {
                color: #4b5563;
            }
            .dark .view-toggle-btn:not(.active):hover {
                color: #e5e7eb;
            }
        </style>

        <div class="toast-wrap" id="toastWrap"></div>

        <div class="space-y-4">

            {{-- ==================== TABLE CARD ==================== --}}
            <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

                {{-- CARD HEADER --}}
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                            flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Banner List</h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

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
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="text" id="bannerSearch" placeholder="Search banners…" oninput="filterBanners()"
                                   autocomplete="off"
                                   class="w-full sm:w-52 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                          bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                        </div>

                        {{-- VIEW TOGGLE: GRID / LIST --}}
                        <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 p-1 gap-1">
                            <button type="button" id="gridViewBtn" onclick="setViewMode('grid')"
                                title="Grid view"
                                class="view-toggle-btn w-9 h-8 flex items-center justify-center rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                </svg>
                            </button>
                            <button type="button" id="listViewBtn" onclick="setViewMode('list')"
                                title="List view"
                                class="view-toggle-btn w-9 h-8 flex items-center justify-center rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
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

                @if($banners->isEmpty())
                    <div class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">No banners found.</div>
                @else

                    {{-- ==================== GRID VIEW ==================== --}}
                    <div id="gridViewWrap" class="p-4 sm:p-5">
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
                                        <div class="grid grid-cols-2 gap-2 mt-auto pt-2">

                                            {{-- Edit --}}
                                            <button
                                                type="button"
                                                onclick='openEdit(
                                                    {{ $banner->id }},
                                                    @json($banner->title),
                                                    {{ (int) $banner->status }},
                                                    @json($banner->start_date),
                                                    @json($banner->end_date),
                                                    @json($banner->image_url)
                                                )'
                                                class="action-btn flex items-center justify-center gap-1 h-9 rounded-lg
                                                    border border-gray-200 dark:border-gray-700
                                                    bg-white dark:bg-gray-800
                                                    text-gray-600 dark:text-gray-300
                                                    hover:bg-indigo-50 hover:text-indigo-600
                                                    dark:hover:bg-gray-700 transition">

                                                <svg class="w-3.5 h-3.5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>

                                                <span class="text-xs font-medium">Edit</span>
                                            </button>

                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('banners.destroy',$banner->id) }}"
                                                method="POST"
                                                class="delete-form">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    data-name="{{ addslashes($banner->title) }}"
                                                    class="action-btn w-full flex items-center justify-center gap-1 h-9 rounded-lg
                                                        border border-gray-200 dark:border-gray-700
                                                        bg-white dark:bg-gray-800
                                                        text-gray-600 dark:text-gray-300
                                                        hover:bg-red-50 hover:text-red-600
                                                        dark:hover:bg-gray-700 transition">


                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z"/>
                                                        </svg>

                                                    <span class="text-xs font-medium">Delete</span>
                                                </button>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="searchEmptyGrid" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No banners match your search.
                        </div>
                    </div>

                    {{-- ==================== LIST (TABLE) VIEW ==================== --}}
                    <div id="listViewWrap" class="hidden overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30">
                                    <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Banner</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Schedule</th>
                                    <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($banners as $banner)
                                    <tr class="list-row banner-row"
                                        data-id="{{ $banner->id }}"
                                        data-title="{{ strtolower($banner->title) }}">

                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-16 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                                         class="w-full h-full object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $banner->title }}</p>
                                                    @if($banner->is_lifetime)
                                                        <span class="inline-flex items-center gap-1 mt-0.5 text-[10px] font-semibold text-violet-500 dark:text-violet-400">
                                                            ∞ Lifetime
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-3">
                                            @if($banner->display_status === 'active')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                             bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                    <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                                    Active
                                                </span>
                                            @elseif($banner->display_status === 'scheduled')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                             bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                                    Scheduled
                                                </span>
                                            @elseif($banner->display_status === 'inactive')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                             bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                    Inactive
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                             bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                                                    Expired
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                            @if($banner->is_lifetime)
                                                <span class="text-violet-500 dark:text-violet-400">Displays forever</span>
                                            @elseif($banner->display_status === 'active')
                                                Ends: {{ $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('d M Y') : '—' }}
                                            @elseif($banner->display_status === 'scheduled')
                                                Starts: {{ $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('d M Y') : '—' }}
                                            @else
                                                @if($banner->end_date && \Carbon\Carbon::parse($banner->end_date)->isPast())
                                                    <span class="text-red-400">Ended {{ \Carbon\Carbon::parse($banner->end_date)->format('d M Y') }}</span>
                                                @else
                                                    <span class="text-gray-400">No schedule</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="px-5 py-3">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button"
                                                    onclick='openEdit(
                                                        {{ $banner->id }},
                                                        @json($banner->title),
                                                        {{ (int) $banner->status }},
                                                        @json($banner->start_date),
                                                        @json($banner->end_date),
                                                        @json($banner->image_url)
                                                    )'
                                                    class="action-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                            border border-gray-200 bg-white text-gray-600
                                                            hover:text-gray-900 hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                            dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700"
                                                    title="Edit">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                </button>

                                                <form class="delete-form" action="{{ route('banners.destroy', $banner->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" data-name="{{ addslashes($banner->title) }}"
                                                        class="action-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                border border-gray-200 bg-white text-gray-600
                                                                hover:bg-gray-50 hover:text-red-500 transition-all duration-200
                                                                dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-red-400"
                                                        title="Delete">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="searchEmptyList" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No banners match your search.
                        </div>
                    </div>
                @endif

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
            //  VIEW MODE: GRID / LIST
            // ══════════════════════════════════════════════════════
            function setViewMode(mode) {
                const gridWrap = document.getElementById('gridViewWrap');
                const listWrap = document.getElementById('listViewWrap');
                const gridBtn  = document.getElementById('gridViewBtn');
                const listBtn  = document.getElementById('listViewBtn');

                if (!gridWrap || !listWrap) return; // no banners at all

                if (mode === 'list') {
                    gridWrap.classList.add('hidden');
                    listWrap.classList.remove('hidden');
                    listBtn.classList.add('active');
                    gridBtn.classList.remove('active');
                } else {
                    listWrap.classList.add('hidden');
                    gridWrap.classList.remove('hidden');
                    gridBtn.classList.add('active');
                    listBtn.classList.remove('active');
                }

                try { localStorage.setItem('bannersViewMode', mode); } catch (e) {}
            }

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

                // Restore saved view mode
                let savedMode = 'grid';
                try { savedMode = localStorage.getItem('bannersViewMode') || 'grid'; } catch (e) {}
                setViewMode(savedMode);

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
                const q          = document.getElementById('bannerSearch').value.toLowerCase().trim();
                const rows       = document.querySelectorAll('.banner-row');
                const emptyGrid  = document.getElementById('searchEmptyGrid');
                const emptyList  = document.getElementById('searchEmptyList');
                let vis          = 0;

                rows.forEach(row => {
                    const match = (row.dataset.title || '').includes(q) || (row.dataset.id || '').includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) vis++;
                });

                const noResults = q && vis === 0;
                if (emptyGrid) emptyGrid.classList.toggle('hidden', !noResults);
                if (emptyList) emptyList.classList.toggle('hidden', !noResults);
            }
            </script>
        @endpush

@endsection