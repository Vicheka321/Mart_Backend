@extends('layouts.app')

@section('content')
    @php
        $totalCount = $categories->total();
        $withImage  = $categories->getCollection()->filter(fn($c) => $c->image)->count();
        $noImage    = $categories->getCollection()->filter(fn($c) => !$c->image)->count();
        $withPct    = $totalCount > 0 ? round(($withImage / $totalCount) * 100) : 0;
        $noPct      = $totalCount > 0 ? round(($noImage  / $totalCount) * 100) : 0;
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
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Stat cards */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .13s; }
        .stat-card:nth-child(3) { animation-delay: .21s; }

        /* Table/grid card */
        .table-card { animation: fadeSlideUp .5s .26s ease both; }

        /* Category grid cards */
        .category-card {
            animation: cardPop .38s ease both;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .category-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }
        .category-card:nth-child(1)  { animation-delay: .30s; }
        .category-card:nth-child(2)  { animation-delay: .35s; }
        .category-card:nth-child(3)  { animation-delay: .40s; }
        .category-card:nth-child(4)  { animation-delay: .45s; }
        .category-card:nth-child(5)  { animation-delay: .50s; }
        .category-card:nth-child(6)  { animation-delay: .55s; }
        .category-card:nth-child(7)  { animation-delay: .60s; }
        .category-card:nth-child(8)  { animation-delay: .65s; }
        .category-card:nth-child(9)  { animation-delay: .70s; }
        .category-card:nth-child(10) { animation-delay: .75s; }

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
        .list-row:nth-child(9)  { animation-delay: .28s; }
        .list-row:nth-child(10) { animation-delay: .31s; }
        .list-row:hover { background-color: rgba(99,102,241,.04); }
        .dark .list-row:hover { background-color: rgba(99,102,241,.08); }

        /* Progress bars animate from 0 */
        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }

        /* Number pop after counter */
        .count-done { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        /* Modals */
        #exportModal.flex   { animation: overlayIn .2s ease; }
        #categoryModal.flex { animation: overlayIn .2s ease; }
        .modal-inner        { animation: modalIn .28s cubic-bezier(.34,1.56,.64,1) both; }

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

        /* Button lift */
        .action-btn { transition: transform .14s ease, box-shadow .14s ease; }
        .action-btn:hover  { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.18); }
        .action-btn:active { transform: translateY(0); }

        /* Image zoom in card */
        .cat-img { transition: transform .35s cubic-bezier(.25,.46,.45,.94); }
        .category-card:hover .cat-img { transform: scale(1.06); }

        /* Search glow */
        #categorySearch:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }

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
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Category List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                    {{-- SEARCH --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                        <form method="GET">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search categories..."
                                onchange="this.form.submit()"
                                autocomplete="off"
                                class="w-full sm:w-56 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                        </form>
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
                        <span class="hidden sm:inline">Add Category</span>
                    </button>
                </div>
            </div>

            {{-- ==================== GRID VIEW ==================== --}}
            <div id="gridViewWrap" class="p-4 sm:p-5">
                <div id="categoriesTable"
                     class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">

                    @forelse($categories as $category)
                        <div class="category-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                    rounded-2xl overflow-hidden flex flex-col"
                             data-name="{{ strtolower($category->name) }}">

                            {{-- Image / Initial --}}
                            @if($category->image)
                                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                         class="cat-img w-full h-full object-cover">
                                </div>
                            @else
                                <div class="relative aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200
                                            dark:from-gray-700 dark:to-gray-600
                                            flex items-center justify-center text-3xl font-bold text-gray-400 dark:text-gray-500">
                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                    <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full
                                                 text-[10px] font-semibold bg-amber-100/90 text-amber-700
                                                 dark:bg-amber-500/20 dark:text-amber-400">
                                        No img
                                    </span>
                                </div>
                            @endif

                            {{-- Info --}}
                            <div class="p-3 flex flex-col gap-2 flex-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">
                                        {{ $category->name }}
                                    </p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                                        {{ $category->created_at->format('M d, Y') }}
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-1.5 mt-auto pt-1">
                                    <button type="button"
                                        onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->image }}')"
                                        class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium rounded-xl
                                                border border-gray-200
                                                bg-white
                                                text-gray-600
                                                hover:text-gray-900
                                                hover:border-gray-300
                                                hover:shadow-sm
                                                transition-all duration-200
                                                dark:bg-gray-800
                                                dark:border-gray-700
                                                dark:text-gray-400
                                                dark:hover:text-white
                                                dark:hover:bg-gray-700">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Edit
                                    </button>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                          class="flex-1 delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="action-btn w-full inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                border border-gray-200
                                                bg-white
                                                text-gray-600
                                                hover:bg-gray-50
                                                hover:text-red-500
                                                transition-all duration-200
                                                dark:bg-gray-800
                                                dark:border-gray-700
                                                dark:text-gray-400
                                                dark:hover:bg-gray-700
                                                dark:hover:text-red-400">
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
                            No categories found.
                        </div>
                    @endforelse
                </div>

                <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                    No categories match your search.
                </div>
            </div>

            {{-- ==================== LIST (TABLE) VIEW ==================== --}}
            <div id="listViewWrap" class="hidden overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30">
                            <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Category</th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Image</th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Created</th>
                            <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($categories as $category)
                            <tr class="list-row" data-name="{{ strtolower($category->name) }}">

                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0
                                                    {{ $category->image ? 'bg-gray-50 dark:bg-gray-700' : 'bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600' }}
                                                    flex items-center justify-center">
                                            @if($category->image)
                                                <img src="{{ $category->image }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-sm font-bold text-gray-400 dark:text-gray-500">
                                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $category->name }}</p>
                                    </div>
                                </td>

                                <td class="px-5 py-3">
                                    @if($category->image)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                     bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Has image
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                     bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                            No image
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $category->created_at->format('M d, Y') }}
                                </td>

                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button"
                                            onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->image }}')"
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

                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit"
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
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
<div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">

                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($categories->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $categories->firstItem() }}–{{ $categories->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($categories->total()) }}</span>
                        results
                    @else
                        No categories found
                    @endif
                </p>

                @if($categories->hasPages())
                    @php
                        $categories->appends(request()->query());
                        $current = $categories->currentPage();
                        $last    = $categories->lastPage();
                        $start   = max(1, $current - 2);
                        $end     = min($last, $current + 2);
                    @endphp
                    <nav class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($categories->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $categories->previousPageUrl() }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- First page + leading ellipsis --}}
                        @if($start > 1)
                            <a href="{{ $categories->url(1) }}"
                               class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                      text-sm font-medium text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                      transition-colors duration-150">
                                1
                            </a>
                            @if($start > 2)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
                            @endif
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($categories->getUrlRange($start, $end) as $page => $url)
                            @if($page == $current)
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

                        {{-- Trailing ellipsis + last page --}}
                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
                            @endif
                            <a href="{{ $categories->url($last) }}"
                               class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                      text-sm font-medium text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                      transition-colors duration-150">
                                {{ $last }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if($categories->hasMorePages())
                            <a href="{{ $categories->nextPageUrl() }}"
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
                <a href="{{ route('categories.export.csv') }}"
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
                <a href="{{ route('categories.export.pdf') }}"
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
    <div id="categoryModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 id="modalTitle" class="text-base font-semibold text-gray-900 dark:text-white">Add Category</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST"
                  enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Category Name</label>
                    <input type="text" name="name" id="categoryName" placeholder="e.g. Beverages" required
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                            bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    <p id="categoryNameError" class="hidden mt-1 text-xs text-red-500"></p>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Category Image</label>

                    <div id="uploadBox" onclick="document.getElementById('imageInput').click()"
                         class="relative w-full h-48 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-600
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

                <button
                    id="saveCategoryBtn"
                    type="submit"
                    class="action-btn w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                           rounded-xl transition-all shadow-md shadow-indigo-500/25">
                    Save Category
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

        try { localStorage.setItem('categoriesViewMode', mode); } catch (e) {}
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
        try { savedMode = localStorage.getItem('categoriesViewMode') || 'grid'; } catch (e) {}
        setViewMode(savedMode);

        // Delete confirm
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete category?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',
                }).then(async (result) => {
                    if (!result.isConfirmed) return;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        }
                    } catch (err) {
                        console.log(err);
                        form.submit(); // fallback to normal submit if fetch fails
                    }
                });
            });
        });
    });

    // ══════════════════════════════════════════════════════
    //  MODAL HELPERS
    // ══════════════════════════════════════════════════════
    function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function hideModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    document.getElementById('exportModal').addEventListener('click',   function(e) { if (e.target === this) hideModal('exportModal'); });
    document.getElementById('categoryModal').addEventListener('click', function(e) { if (e.target === this) hideModal('categoryModal'); });

    function openExportModal()  { showModal('exportModal'); }
    function closeExportModal() { hideModal('exportModal'); }
    function openModal()        { resetForm(); showModal('categoryModal'); }
    function closeModal()       { hideModal('categoryModal'); }

    function resetForm() {
        document.getElementById('categoryForm').action  = "{{ route('categories.store') }}";
        document.getElementById('formMethod').value      = 'POST';
        document.getElementById('categoryName').value    = '';
        document.getElementById('modalTitle').innerText  = 'Add Category';
        clearFieldErrors();
        resetImagePreview();
    }

    function clearFieldErrors() {
        const input = document.getElementById('categoryName');
        const error = document.getElementById('categoryNameError');

        input.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
        error.textContent = '';
        error.classList.add('hidden');
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
    function editCategory(id, name, image) {
        openModal();
        document.getElementById('modalTitle').innerText = 'Edit Category';
        document.getElementById('categoryName').value   = name;
        document.getElementById('categoryForm').action  = '/admin/category/' + id;
        document.getElementById('formMethod').value     = 'PUT';
        if (image) showImagePreview(image);
        else resetImagePreview();
    }

    // ══════════════════════════════════════════════════════
    //  SAVE (ADD / EDIT) — AJAX
    // ══════════════════════════════════════════════════════
    const categoryForm = document.getElementById('categoryForm');
    categoryForm.addEventListener('submit', saveCategory);

    async function saveCategory(e) {
        e.preventDefault();

        clearFieldErrors();

        const form = e.target;
        const formData = new FormData(form);
        const btn = document.getElementById('saveCategoryBtn');

        btn.disabled = true;
        const originalLabel = btn.innerHTML;
        btn.innerHTML = `
            <span class="inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,.25)" stroke-width="3"/>
                    <path d="M22 12a10 10 0 0 0-10-10" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
                
            </span>
        `;

        try {
            const response = await fetch(form.action, {
                method: 'POST', // hidden _method field handles PUT for edit
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                window.location.reload();
                return;
            }

            // Validation errors (Laravel's default 422 shape: { message, errors: { name: [...] } })
            if (data.errors?.name) {
                const input = document.getElementById('categoryName');
                const error = document.getElementById('categoryNameError');

                input.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                error.textContent = data.errors.name[0];
                error.classList.remove('hidden');
            }

        } catch (err) {
            console.log(err);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalLabel;
        }
    }
    </script>
    @endpush

@endsection