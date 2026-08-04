@extends('layouts.app')

@section('content')

    <style>
        /* All original styles kept exactly as-is (no Alpine involved here) */
        .bx-hidden {
            display: none !important;
        }

        /* ── Entry animations (mirrors Products page) ── */
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
                transform: scale(0.94) translateY(12px);
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
                transform: scale(1.06);
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
                transform: translateX(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateX(48px) scale(.95);
            }
        }

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            from {
                background-position: -300px 0;
            }

            to {
                background-position: 300px 0;
            }
        }

        .bx-header {
            animation: fadeSlideUp .45s ease both;
        }

        .stat-card {
            animation: fadeSlideUp .5s ease both;
        }

        .stat-card:nth-child(1) {
            animation-delay: .05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: .14s;
        }

        .stat-card:nth-child(3) {
            animation-delay: .23s;
        }

        .stat-card:nth-child(4) {
            animation-delay: .32s;
        }

        .count-done {
            animation: numberPop .32s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .progress-bar {
            animation: progressFill .9s .7s cubic-bezier(.4, 0, .2, 1) both;
        }

        .table-card {
            animation: fadeSlideUp .5s .28s ease both;
        }

        .bx-row {
            animation: rowSlideIn .3s ease both;
        }

        .bx-modal-overlay.flex {
            animation: overlayIn .2s ease;
        }

        .modal-inner {
            animation: modalIn .28s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .action-btn {
            transition: transform .14s ease, box-shadow .14s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, .15);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .bx-hover-lift {
            transition: box-shadow .3s ease, transform .3s ease;
        }

        .bx-hover-lift:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }

        /* ── View toggle buttons (Grid / List), mirrors Products page ── */
        .view-toggle-btn {
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
            color: #9ca3af;
        }

        .view-toggle-btn.active {
            background: white;
            color: #4f46e5;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
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

        /* ── Branch grid cards ── */
        .branch-card {
            animation: cardPop .38s ease both;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .branch-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .1);
        }

        .branch-card-map {
            transition: transform .35s cubic-bezier(.25, .46, .45, .94);
        }

        .branch-card:hover .branch-card-map {
            transform: scale(1.06);
        }

        .bx-status-badge {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* ── Toast (mirrors Products page, responsive full-width on mobile) ── */
        .toast-wrap {
            position: fixed;
            top: 1rem;
            right: 1rem;
            left: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            pointer-events: none;
        }

        @media (min-width: 640px) {
            .toast-wrap {
                top: 1.25rem;
                right: 1.25rem;
                left: auto;
            }
        }

        .toast {
            pointer-events: all;
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .75rem 1rem;
            min-width: 0;
            width: 100%;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            font-size: .8125rem;
            font-weight: 500;
            color: #111827;
            animation: toastSlide .3s cubic-bezier(.34, 1.3, .64, 1) both;
        }

        @media (min-width: 640px) {
            .toast {
                min-width: 240px;
                width: auto;
            }
        }

        .dark .toast {
            background: #1f2937;
            color: #f3f4f6;
        }

        .toast.leaving {
            animation: toastOut .28s ease forwards;
        }

        .toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .bx-skel {
            background: linear-gradient(90deg, #eef0f4 0%, #f8f9fb 40%, #eef0f4 80%);
            background-size: 600px 100%;
            animation: shimmer 1.4s ease-in-out infinite;
            border-radius: 8px;
        }

        .dark .bx-skel {
            background: linear-gradient(90deg, #29303f 0%, #333c4e 40%, #29303f 80%);
            background-size: 600px 100%;
        }

        #branchMap,
        #viewMap {
            border-radius: 16px;
            overflow: hidden;
        }

        .bx-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .2rem .55rem;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        #productSearchB:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
        }

        @media (max-width: 640px) {
            .stat-number {
                font-size: 1.5rem !important;
            }
        }

        /* Simple dropdown chevron rotation, replaces Alpine's :class rotate-180 */
        #filterDropdownBtn .bx-chevron {
            transition: transform .15s ease;
        }

        #filterDropdownBtn.open .bx-chevron {
            transform: rotate(180deg);
        }
    </style>

    <div id="branchesPage" class="space-y-4">

        {{-- Toast container (rendered entirely via JS) --}}
        <div class="toast-wrap" id="toastWrap"></div>

        {{-- ══════════════════ TABLE CARD ══════════════════ --}}
        <div
            class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- Card header / toolbar --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                                        flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Branch List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap w-full lg:w-auto">

                    {{-- Search --}}
                    <div class="relative w-full sm:w-auto">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="productSearchB" placeholder="Search branches…" autocomplete="off" class="w-full sm:w-56 md:w-64 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                                      bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                                      focus:outline-none transition-all duration-200">
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- VIEW TOGGLE: GRID / LIST --}}
                        <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                                bg-gray-50 dark:bg-gray-700 p-1 gap-1">
                            <button type="button" id="branchGridViewBtn" title="Grid view"
                                class="view-toggle-btn w-9 h-8 flex items-center justify-center rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                                </svg>
                            </button>
                            <button type="button" id="branchListViewBtn" title="List view"
                                class="view-toggle-btn w-9 h-8 flex items-center justify-center rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        {{-- Filter dropdown --}}
                        <div class="relative" id="filterDropdownWrap">
                            <button type="button" id="filterDropdownBtn"
                                class="action-btn flex items-center gap-2 px-3.5 py-2 text-sm font-medium rounded-xl
                                                           border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                                                           text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                                </svg>
                                <span id="filterDropdownLabel">All</span>
                                <svg class="w-3.5 h-3.5 text-gray-400 bx-chevron" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="filterDropdownMenu" class="bx-hidden absolute right-0 mt-2 w-44 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        shadow-xl overflow-hidden z-20 py-1.5">
                                <button type="button" data-filter-value="all"
                                    class="filter-option w-full flex items-center justify-between px-3.5 py-2 text-xs font-medium text-left
                                                               text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                    <span>All</span>
                                    <svg class="w-3.5 h-3.5 text-indigo-500 filter-check bx-hidden" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button type="button" data-filter-value="active"
                                    class="filter-option w-full flex items-center justify-between px-3.5 py-2 text-xs font-medium text-left
                                                               text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                    <span>Active</span>
                                    <svg class="w-3.5 h-3.5 text-indigo-500 filter-check bx-hidden" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button type="button" data-filter-value="inactive"
                                    class="filter-option w-full flex items-center justify-between px-3.5 py-2 text-xs font-medium text-left
                                                               text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                    <span>Inactive</span>
                                    <svg class="w-3.5 h-3.5 text-indigo-500 filter-check bx-hidden" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button type="button" data-filter-value="main"
                                    class="filter-option w-full flex items-center justify-between px-3.5 py-2 text-xs font-medium text-left
                                                               text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                    <span>Main Branch</span>
                                    <svg class="w-3.5 h-3.5 text-indigo-500 filter-check bx-hidden" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Add --}}
                        @can('create_branches')
                            <button type="button" id="addBranchBtn"
                                class="action-btn flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 text-sm font-medium rounded-xl
                                                                       bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200 shadow-md shadow-indigo-500/25">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                <span>Add Branch</span>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Active filter badge --}}
            <div id="filterBadge" class="bx-hidden px-4 sm:px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-0">
                <p class="text-xs text-indigo-600 dark:text-indigo-400">
                    Filtering by: <span class="font-semibold capitalize" id="filterBadgeLabel"></span>
                    — <span id="filterBadgeCount"></span> <span id="filterBadgeResultWord"></span>
                </p>
                <button type="button" id="clearFilterBtn"
                    class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline text-left">Clear filter</button>
            </div>

            {{-- ══════ LIST VIEW (table, horizontally scrollable) ══════ --}}
            <div id="branchListWrap" class="bx-hidden overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr
                            class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-3 sm:px-5 py-3 w-10">#</th>
                            <th class="px-3 sm:px-5 py-3">Branch Name</th>
                            <th class="px-3 sm:px-5 py-3 hidden sm:table-cell">Phone</th>
                            <th class="px-3 sm:px-5 py-3 hidden md:table-cell">Email</th>
                            <th class="px-3 sm:px-5 py-3 hidden lg:table-cell">Address</th>
                            <th class="px-3 sm:px-5 py-3 hidden lg:table-cell">Coordinates</th>
                            <th class="px-3 sm:px-5 py-3 text-center">Main</th>
                            <th class="px-3 sm:px-5 py-3 text-center">Status</th>
                            <th class="px-3 sm:px-5 py-3 hidden sm:table-cell">Updated</th>
                            <th class="px-3 sm:px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="branchTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        {{-- Populated entirely by JS: skeleton rows, data rows, or empty message --}}
                    </tbody>
                </table>
            </div>

            {{-- ══════ GRID VIEW (cards with map thumbnail) ══════ --}}
            <div id="branchGridWrap" class="p-3 sm:p-4">
                <div id="branchGridBody"
                    class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                    {{-- Populated entirely by JS: skeleton cards, data cards, or empty message --}}
                </div>
            </div>

            {{-- Empty state (first-run, no branches at all) --}}
            <div id="emptyState"
                class="bx-hidden flex-col items-center justify-center text-center py-16 px-6 border-t border-gray-100 dark:border-gray-700">
                <svg class="w-20 h-20 text-gray-200 dark:text-gray-700 mb-4 mx-auto" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7M9 9v.01M9 12v.01M9 15v.01" />
                </svg>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">No branches found.</p>
                @can('create_branches')
                    <button type="button" id="emptyStateAddBtn"
                        class="action-btn mt-4 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                                               text-white text-sm font-medium shadow-md shadow-indigo-500/25 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Create First Branch
                    </button>
                @endcan
            </div>
        </div>


        {{-- ══════════════════════════════════════════
        ADD / EDIT MODAL
        ══════════════════════════════════════════ --}}
        <div id="branchModalOverlay"
            class="bx-modal-overlay bx-hidden fixed inset-0 bg-black/40 backdrop-blur-sm items-start justify-center z-[70] px-2 sm:px-4 py-4 sm:py-8 overflow-y-auto">
            <div id="branchModalInner"
                class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl w-full max-w-3xl mx-auto shadow-2xl">

                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white" id="branchModalTitle">Add Branch</h2>
                    <button type="button" id="branchModalCloseBtn"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="branchForm">
                    <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">

                        {{-- LEFT: Fields --}}
                        <div class="space-y-4">

                            {{-- General Info --}}
                            <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-3 sm:p-4 space-y-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    General Info</p>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Branch
                                        Name</label>
                                    <input type="text" id="formName" placeholder="e.g. Phnom Penh HQ"
                                        class="w-full px-3 py-2 text-sm rounded-xl border bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all border-gray-200 dark:border-gray-600">
                                    <p class="mt-1 text-xs text-red-500 bx-hidden" id="errorName"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone</label>
                                        <input type="text" id="formPhone" placeholder="+855 12 345 678"
                                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email</label>
                                        <input type="email" id="formEmail" placeholder="branch@company.com"
                                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                                                      bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Address</label>
                                    <input type="text" id="branchAddressInput" placeholder="Search an address in Cambodia…"
                                        class="w-full px-3 py-2 text-sm rounded-xl border bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all border-gray-200 dark:border-gray-600">
                                    <p class="mt-1 text-xs text-red-500 bx-hidden" id="errorAddress"></p>
                                </div>
                            </div>

                            {{-- Coordinates (auto-filled from map) --}}
                            <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-3 sm:p-4 space-y-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    Coordinates</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Latitude</label>
                                        <input type="text" id="formLat" readonly
                                            class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-gray-200 dark:border-gray-600
                                                                      bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-not-allowed">
                                        <p class="mt-1 text-xs text-red-500 bx-hidden" id="errorLat"></p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Longitude</label>
                                        <input type="text" id="formLng" readonly
                                            class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-gray-200 dark:border-gray-600
                                                                      bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-not-allowed">
                                        <p class="mt-1 text-xs text-red-500 bx-hidden" id="errorLng"></p>
                                    </div>
                                </div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">Auto-filled when you search, drag,
                                    or click the map.</p>
                            </div>

                            {{-- Status --}}
                            <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-3 sm:p-4">
                                <p
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">
                                    Status</p>
                                <div class="flex items-center gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" id="formStatusToggle" class="sr-only peer">
                                            <div
                                                class="w-9 h-5 rounded-full bg-gray-200 dark:bg-gray-600 peer-checked:bg-emerald-500 transition-colors">
                                            </div>
                                            <div
                                                class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform peer-checked:translate-x-4">
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Active</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" id="formIsMain"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Main
                                            Branch</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: Map --}}
                        <div class="flex flex-col bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-3 sm:p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    Location</p>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" id="currentLocationBtn"
                                        class="action-btn flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold
                                                                   bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600
                                                                   text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Current location
                                    </button>
                                    <button type="button" id="resetMapBtn"
                                        class="action-btn flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold
                                                                   bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600
                                                                   text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </button>
                                </div>
                            </div>

                            <div id="branchMap"
                                class="w-full h-56 sm:h-64 lg:flex-1 lg:min-h-[220px] bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                            </div>

                            <p class="mt-2 text-[11px] text-gray-400 dark:text-gray-500">Search an address above, drag the
                                pin, or click anywhere on the map.</p>

                            <div id="outsideCambodiaWarning"
                                class="bx-hidden mt-2 flex items-start gap-2 px-3 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-400">
                                <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <p class="text-[11px] font-medium leading-snug">This pin looks like it's outside Cambodia.
                                    Double-check the address before saving.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-2 px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" id="branchModalCancelBtn"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                       text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="branchSubmitBtn" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white
                                                       bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/25 transition-all
                                                       disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg id="branchSubmitSpinner" class="w-4 h-4 animate-spin bx-hidden" viewBox="0 0 24 24"
                                fill="none">
                                <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,.25)" stroke-width="3" />
                                <path d="M22 12a10 10 0 0 0-10-10" stroke="white" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            <span id="branchSubmitLabel">Create Branch</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ══════════════════════════════════════════
        VIEW MODAL
        ══════════════════════════════════════════ --}}
        <div id="viewModalOverlay"
            class="bx-modal-overlay bx-hidden fixed inset-0 bg-black/40 backdrop-blur-sm items-center justify-center z-[70] p-4">
            <div id="viewModalInner"
                class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
                <div>
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 min-w-0">
                            <div id="viewAvatar"
                                class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate" id="viewName">
                                </h3>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500" id="viewMainLabel"></p>
                            </div>
                        </div>
                        <button type="button" id="viewModalCloseBtn"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 sm:p-6 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div
                                class="bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 rounded-xl p-3">
                                <p
                                    class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">
                                    Phone</p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white" id="viewPhone"></p>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 rounded-xl p-3">
                                <p
                                    class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">
                                    Email</p>
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate" id="viewEmail"></p>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 rounded-xl p-3">
                            <p
                                class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">
                                Address</p>
                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed" id="viewAddress"></p>
                        </div>

                        <div id="viewMap" class="w-full h-44"></div>

                        <div class="flex items-center gap-2">
                            <span class="bx-badge" id="viewStatusBadge"></span>
                            <span class="bx-badge" id="viewMainBadge"></span>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 ml-auto" id="viewUpdated"></span>
                        </div>

                        <button id="viewModalCloseBtn2"
                            class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        {{-- Initial branch data injected from the server for vanilla JS to consume --}}
        <script id="branchesInitialData" type="application/json">@json($branches ?? [])</script>

        <script>
            (function () {
                const CSRF = '{{ csrf_token() }}';
                const CAN_EDIT = @json(auth()->user()?->can('edit_branches') ?? false);
                const CAN_DELETE = @json(auth()->user()?->can('delete_branches') ?? false);
                const GOOGLE_KEY = @json(config('services.google.api_key'));
                console.log('Google Key:', GOOGLE_KEY);
                const routes = {
                    store: '{{ route('branches.store') }}',
                    update: '{{ route('branches.update', ':id') }}',
                    destroy: '{{ route('branches.destroy', ':id') }}',
                };

                function initGoogleMaps() {
                    window.dispatchEvent(new Event('google-maps-ready'));
                }
                window.initGoogleMaps = initGoogleMaps;

                function escapeHtml(str) {
                    return String(str ?? '').replace(/[&<>"']/g, s => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s]));
                }

                function staticMapUrl(lat, lng) {
                    if (!lat || !lng || !GOOGLE_KEY) return null;
                    const params = new URLSearchParams({
                        center: `${lat},${lng}`,
                        zoom: '15',
                        size: '400x220',
                        scale: '2',
                        maptype: 'roadmap',
                        markers: `color:0x4f46e5|${lat},${lng}`,
                        key: GOOGLE_KEY,
                    });
                    return `https://maps.googleapis.com/maps/api/staticmap?${params.toString()}`;
                }

                function show(el) { if (el) { el.classList.remove('bx-hidden'); } }
                function hide(el) { if (el) { el.classList.add('bx-hidden'); } }


                /* ══════════════════════════════════════════
                   BranchPage — vanilla JS replacement for the
                   old Alpine.js "branchesPage()" component.
                   ══════════════════════════════════════════ */
                const BranchPage = {
                    // ── State (replaces Alpine's x-data reactive props) ──
                    branches: [],
                    searchTerm: '',
                    statusFilter: 'all',
                    tableLoading: true,
                    viewMode: 'list',

                    modalMode: 'add',
                    saving: false,

                    viewBranch: null,

                    toasts: [],

                    form: { id: null, name: '', phone: '', email: '', address: '', lat: '', lng: '', status: 'active', is_main: false },
                    errors: {},
                    outsideCambodia: false,

                    _map: null,
                    _marker: null,
                    _autocomplete: null,
                    _geocoder: null,
                    _originalCoords: null,

                    /* ══════════════════════════════════════════
                       INIT
                       ══════════════════════════════════════════ */
                    init() {
                        const dataEl = document.getElementById('branchesInitialData');
                        try {
                            this.branches = dataEl ? JSON.parse(dataEl.textContent || '[]') : [];
                        } catch (e) {
                            this.branches = [];
                        }

                        let savedMode = 'list';
                        try { savedMode = localStorage.getItem('branchesViewMode') || 'list'; } catch (e) { }
                        this.setViewMode(savedMode);

                        this.bindEvents();
                        this.renderTable(); // shows skeleton rows/cards immediately

                        setTimeout(() => {
                            this.tableLoading = false;
                            this.renderTable();
                        }, 420);
                    },

                    /* ══════════════════════════════════════════
                       VIEW MODE: GRID / LIST
                       ══════════════════════════════════════════ */
                    setViewMode(mode) {
                        this.viewMode = mode === 'grid' ? 'grid' : 'list';

                        const listWrap = document.getElementById('branchListWrap');
                        const gridWrap = document.getElementById('branchGridWrap');
                        const gridBtn = document.getElementById('branchGridViewBtn');
                        const listBtn = document.getElementById('branchListViewBtn');

                        if (this.viewMode === 'grid') {
                            hide(listWrap);
                            listWrap.classList.remove('overflow-x-auto');
                            show(gridWrap);
                            gridBtn.classList.add('active');
                            listBtn.classList.remove('active');
                        } else {
                            show(listWrap);
                            listWrap.classList.add('overflow-x-auto');
                            hide(gridWrap);
                            listBtn.classList.add('active');
                            gridBtn.classList.remove('active');
                        }

                        try { localStorage.setItem('branchesViewMode', this.viewMode); } catch (e) { }
                    },

                    /* ══════════════════════════════════════════
                       EVENT BINDING (replaces @click / @change / @input / @submit / @click.outside)
                       ══════════════════════════════════════════ */
                    bindEvents() {
                        // Search input (replaces x-model="search")
                        const searchInput = document.getElementById('productSearchB');
                        searchInput.addEventListener('input', (e) => this.search(e.target.value));

                        // Grid / List toggle
                        document.getElementById('branchGridViewBtn').addEventListener('click', () => this.setViewMode('grid'));
                        document.getElementById('branchListViewBtn').addEventListener('click', () => this.setViewMode('list'));

                        // Filter dropdown toggle
                        const filterBtn = document.getElementById('filterDropdownBtn');
                        const filterMenu = document.getElementById('filterDropdownMenu');
                        filterBtn.addEventListener('click', () => {
                            const isOpen = !filterMenu.classList.contains('bx-hidden');
                            if (isOpen) { hide(filterMenu); filterBtn.classList.remove('open'); }
                            else { show(filterMenu); filterBtn.classList.add('open'); }
                        });

                        // Filter option buttons (event delegation)
                        filterMenu.querySelectorAll('.filter-option').forEach(btn => {
                            btn.addEventListener('click', () => {
                                this.filterTable(btn.dataset.filterValue);
                                hide(filterMenu);
                                filterBtn.classList.remove('open');
                            });
                        });

                        // Click outside to close dropdown (replaces @click.outside)
                        document.addEventListener('click', (e) => {
                            const wrap = document.getElementById('filterDropdownWrap');
                            if (wrap && !wrap.contains(e.target)) {
                                hide(filterMenu);
                                filterBtn.classList.remove('open');
                            }
                        });

                        // Clear filter badge button
                        document.getElementById('clearFilterBtn').addEventListener('click', () => this.filterTable('all'));

                        // Add branch buttons
                        const addBtn = document.getElementById('addBranchBtn');
                        if (addBtn) addBtn.addEventListener('click', () => this.openAddModal());
                        const emptyAddBtn = document.getElementById('emptyStateAddBtn');
                        if (emptyAddBtn) emptyAddBtn.addEventListener('click', () => this.openAddModal());

                        // Modal close / cancel
                        document.getElementById('branchModalCloseBtn').addEventListener('click', () => this.closeAddModal());
                        document.getElementById('branchModalCancelBtn').addEventListener('click', () => this.closeAddModal());
                        document.getElementById('branchModalOverlay').addEventListener('click', (e) => {
                            if (e.target.id === 'branchModalOverlay' && !this.saving) this.closeAddModal();
                        });

                        // View modal close
                        document.getElementById('viewModalCloseBtn').addEventListener('click', () => this.closeViewModal());
                        document.getElementById('viewModalCloseBtn2').addEventListener('click', () => this.closeViewModal());
                        document.getElementById('viewModalOverlay').addEventListener('click', (e) => {
                            if (e.target.id === 'viewModalOverlay') this.closeViewModal();
                        });

                        // Form submit (replaces @submit.prevent)
                        document.getElementById('branchForm').addEventListener('submit', (e) => {
                            e.preventDefault();
                            this.submitForm();
                        });

                        // Status toggle + is_main checkbox (replaces x-model / @change)
                        document.getElementById('formStatusToggle').addEventListener('change', (e) => {
                            this.form.status = e.target.checked ? 'active' : 'inactive';
                        });
                        document.getElementById('formIsMain').addEventListener('change', (e) => {
                            this.form.is_main = e.target.checked;
                        });

                        // Text field bindings (replaces x-model)
                        ['name', 'phone', 'email', 'address'].forEach(field => {
                            const map = { name: 'formName', phone: 'formPhone', email: 'formEmail', address: 'formAddress' };
                            // address input id is actually "branchAddressInput"
                            const el = field === 'address' ? document.getElementById('branchAddressInput') : document.getElementById(map[field]);
                            el.addEventListener('input', (e) => { this.form[field] = e.target.value; });
                        });

                        // Map action buttons
                        document.getElementById('currentLocationBtn').addEventListener('click', () => this.useCurrentLocation());
                        document.getElementById('resetMapBtn').addEventListener('click', () => this.resetMap());

                        // Table body + grid body: event delegation for view / edit / delete
                        document.getElementById('branchTableBody').addEventListener('click', (e) => this.handleTableClick(e));
                        document.getElementById('branchGridBody').addEventListener('click', (e) => this.handleTableClick(e));
                    },

                    handleTableClick(e) {
                        const viewBtn = e.target.closest('[data-action="view"]');
                        const editBtn = e.target.closest('[data-action="edit"]');
                        const deleteBtn = e.target.closest('[data-action="delete"]');

                        if (viewBtn) {
                            const branch = this.branches.find(b => String(b.id) === viewBtn.dataset.id);
                            if (branch) this.openViewModal(branch);
                        } else if (editBtn) {
                            const branch = this.branches.find(b => String(b.id) === editBtn.dataset.id);
                            if (branch) this.openEditModal(branch);
                        } else if (deleteBtn && !deleteBtn.disabled) {
                            const branch = this.branches.find(b => String(b.id) === deleteBtn.dataset.id);
                            if (branch) this.openDeleteModal(branch);
                        }
                    },

                    /* ══════════════════════════════════════════
                       DERIVED DATA (replaces Alpine computed "get" properties)
                       ══════════════════════════════════════════ */
                    get filteredBranches() {
                        let list = this.branches;
                        const q = this.searchTerm.trim().toLowerCase();
                        if (q) {
                            list = list.filter(b =>
                                (b.name || '').toLowerCase().includes(q) ||
                                (b.address || '').toLowerCase().includes(q) ||
                                (b.phone || '').toLowerCase().includes(q) ||
                                (b.email || '').toLowerCase().includes(q)
                            );
                        }
                        if (this.statusFilter === 'active') list = list.filter(b => b.status === 'active');
                        if (this.statusFilter === 'inactive') list = list.filter(b => b.status === 'inactive');
                        if (this.statusFilter === 'main') list = list.filter(b => !!b.is_main);
                        return list;
                    },

                    /* ══════════════════════════════════════════
                       SEARCH / FILTER (replaces x-model="search" reactivity)
                       ══════════════════════════════════════════ */
                    search(value) {
                        this.searchTerm = value;
                        this.renderTable();
                    },

                    filterTable(value) {
                        this.statusFilter = value;

                        const label = { all: 'All', active: 'Active', inactive: 'Inactive', main: 'Main Branch' }[value];
                        document.getElementById('filterDropdownLabel').textContent = label;

                        document.querySelectorAll('.filter-option').forEach(btn => {
                            const check = btn.querySelector('.filter-check');
                            if (btn.dataset.filterValue === value) show(check); else hide(check);
                        });

                        const badge = document.getElementById('filterBadge');
                        if (value === 'all') {
                            hide(badge);
                        } else {
                            show(badge);
                            document.getElementById('filterBadgeLabel').textContent = value;
                            const count = this.filteredBranches.length;
                            document.getElementById('filterBadgeCount').textContent = count;
                            document.getElementById('filterBadgeResultWord').textContent = count === 1 ? 'result' : 'results';
                        }

                        this.renderTable();
                    },

                    /* ══════════════════════════════════════════
                       RENDERING (replaces x-for / x-if / x-show / x-text)
                       Renders both the table body and the grid body;
                       setViewMode() controls which wrapper is visible.
                       ══════════════════════════════════════════ */
                    renderTable() {
                        const tbody = document.getElementById('branchTableBody');
                        const gridBody = document.getElementById('branchGridBody');
                        const emptyState = document.getElementById('emptyState');

                        // Update filter badge count if a filter is active
                        if (this.statusFilter !== 'all') {
                            const badge = document.getElementById('filterBadge');
                            if (!badge.classList.contains('bx-hidden')) {
                                const count = this.filteredBranches.length;
                                document.getElementById('filterBadgeCount').textContent = count;
                                document.getElementById('filterBadgeResultWord').textContent = count === 1 ? 'result' : 'results';
                            }
                        }

                        if (this.tableLoading) {
                            tbody.innerHTML = this.skeletonRowsHtml(5);
                            gridBody.innerHTML = this.skeletonGridHtml(6);
                            hide(emptyState);
                            return;
                        }

                        const list = this.filteredBranches;

                        if (list.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="10" class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">No branches found.</td></tr>`;
                            gridBody.innerHTML = `<div class="col-span-full py-16 text-center text-sm text-gray-400 dark:text-gray-500">No branches found.</div>`;
                        } else {
                            tbody.innerHTML = list.map((b, i) => this.rowHtml(b, i)).join('');
                            gridBody.innerHTML = list.map(b => this.gridCardHtml(b)).join('');
                        }

                        // Global empty state (no branches at all, regardless of filters)
                        if (this.branches.length === 0) {
                            emptyState.classList.remove('bx-hidden');
                            emptyState.classList.add('flex');
                        } else {
                            emptyState.classList.add('bx-hidden');
                            emptyState.classList.remove('flex');
                        }
                    },

                    skeletonRowsHtml(n) {
                        let rows = '';
                        for (let i = 0; i < n; i++) {
                            rows += `
                                                    <tr>
                                                        <td class="px-3 sm:px-5 py-4"><div class="bx-skel h-3 w-4"></div></td>
                                                        <td class="px-3 sm:px-5 py-4"><div class="bx-skel h-3 w-32"></div></td>
                                                        <td class="px-3 sm:px-5 py-4 hidden sm:table-cell"><div class="bx-skel h-3 w-24"></div></td>
                                                        <td class="px-3 sm:px-5 py-4 hidden md:table-cell"><div class="bx-skel h-3 w-28"></div></td>
                                                        <td class="px-3 sm:px-5 py-4 hidden lg:table-cell"><div class="bx-skel h-3 w-40"></div></td>
                                                        <td class="px-3 sm:px-5 py-4 hidden lg:table-cell"><div class="bx-skel h-3 w-24"></div></td>
                                                        <td class="px-3 sm:px-5 py-4"><div class="bx-skel h-4 w-12 mx-auto rounded-full"></div></td>
                                                        <td class="px-3 sm:px-5 py-4"><div class="bx-skel h-4 w-14 mx-auto rounded-full"></div></td>
                                                        <td class="px-3 sm:px-5 py-4 hidden sm:table-cell"><div class="bx-skel h-3 w-16"></div></td>
                                                        <td class="px-3 sm:px-5 py-4"><div class="bx-skel h-6 w-20 ml-auto"></div></td>
                                                    </tr>`;
                        }
                        return rows;
                    },

                    skeletonGridHtml(n) {
                        let cards = '';
                        for (let i = 0; i < n; i++) {
                            cards += `
                                                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                                        <div class="bx-skel w-full aspect-[16/9] rounded-none"></div>
                                                        <div class="p-3 space-y-2.5">
                                                            <div class="bx-skel h-4 w-2/3"></div>
                                                            <div class="bx-skel h-3 w-1/2"></div>
                                                            <div class="bx-skel h-3 w-3/4"></div>
                                                        </div>
                                                    </div>`;
                        }
                        return cards;
                    },

                    rowHtml(branch, index) {
                        const statusClasses = branch.status === 'active'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                            : 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400';
                        const mainClasses = branch.is_main
                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';

                        const editBtn = CAN_EDIT ? `
                                                <button type="button" data-action="edit" data-id="${branch.id}" title="Edit"
                                                    class="action-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                   border border-gray-200 bg-white text-gray-600
                                                                   hover:text-gray-900 hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                </button>` : '';

                        const deleteBtn = CAN_DELETE ? `
                                                <button type="button" data-action="delete" data-id="${branch.id}" ${branch.is_main ? 'disabled' : ''}
                                                    title="${branch.is_main ? 'Main branch cannot be deleted' : 'Delete'}"
                                                    class="action-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                   border border-gray-200 bg-white text-gray-600
                                                                   hover:bg-gray-50 hover:text-red-500 transition-all duration-200
                                                                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-red-400
                                                                   disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:text-gray-600 disabled:hover:shadow-none">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" />
                                                    </svg>
                                                </button>` : '';

                        return `
                                                <tr class="bx-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                                                    <td class="px-3 sm:px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500 font-medium">${index + 1}</td>
                                                    <td class="px-3 sm:px-5 py-3.5">
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="min-w-0">
                                                                <span class="block text-sm font-semibold text-gray-900 dark:text-white truncate">${escapeHtml(branch.name)}</span>
                                                                <span class="block sm:hidden text-[11px] text-gray-400 dark:text-gray-500 truncate">${escapeHtml(branch.phone || '—')}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-gray-600 dark:text-gray-300 hidden sm:table-cell">${escapeHtml(branch.phone || '—')}</td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-gray-600 dark:text-gray-300 hidden md:table-cell">${escapeHtml(branch.email || '—')}</td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-gray-600 dark:text-gray-300 max-w-[220px] truncate hidden lg:table-cell" title="${escapeHtml(branch.address || '')}">${escapeHtml(branch.address || '—')}</td>
                                                    <td class="px-3 sm:px-5 py-3.5 hidden lg:table-cell">
                                                        <div class="font-mono text-[10.5px] leading-tight text-gray-500 dark:text-gray-400">
                                                            <div>Lat ${branch.lat ? Number(branch.lat).toFixed(6) : '—'}</div>
                                                            <div>Lng ${branch.lng ? Number(branch.lng).toFixed(6) : '—'}</div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-center">
                                                        <span class="bx-badge ${mainClasses}">${branch.is_main ? 'Main' : 'Secondary'}</span>
                                                    </td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-center">
                                                        <span class="bx-badge ${statusClasses}">${branch.status === 'active' ? 'Active' : 'Inactive'}</span>
                                                    </td>
                                                    <td class="px-3 sm:px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap hidden sm:table-cell">${this.formatDate(branch.updated_at)}</td>
                                                    <td class="px-3 sm:px-5 py-3.5">
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            <button type="button" data-action="view" data-id="${branch.id}" title="View"
                                                                class="action-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                               border border-gray-200 bg-white text-gray-600
                                                                               hover:text-indigo-600 hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                                               dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-gray-700">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                                                    <circle cx="12" cy="12" r="3" />
                                                                </svg>
                                                            </button>
                                                            ${editBtn}
                                                            ${deleteBtn}
                                                        </div>
                                                    </td>
                                                </tr>`;
                    },

                    gridCardHtml(branch) {
                        const statusClasses = branch.status === 'active'
                            ? 'bg-emerald-100/90 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                            : 'bg-red-100/90 text-red-600 dark:bg-red-500/20 dark:text-red-400';
                        const mainClasses = branch.is_main
                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';

                        const mapUrl = staticMapUrl(branch.lat, branch.lng);
                        const mapMarkup = mapUrl
                            ? `<img src="${mapUrl}" alt="${escapeHtml(branch.name)} map" loading="lazy"
                                                        class="branch-card-map w-full h-full object-cover"
                                                        onerror="this.closest('.branch-card-map-wrap').classList.add('bx-map-fallback'); this.remove();">`
                            : '';

                        const editBtn = CAN_EDIT ? `
                                                <button type="button" data-action="edit" data-id="${branch.id}" title="Edit"
                                                    class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-2 sm:px-3 py-1.5 text-xs font-medium rounded-xl
                                                                   border border-gray-200 bg-white text-gray-600
                                                                   hover:text-gray-900 hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                    Edit
                                                </button>` : '';

                        const deleteBtn = CAN_DELETE ? `
                                                <button type="button" data-action="delete" data-id="${branch.id}" ${branch.is_main ? 'disabled' : ''}
                                                    title="${branch.is_main ? 'Main branch cannot be deleted' : 'Delete'}"
                                                    class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-2 sm:px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                                   border border-gray-200 bg-white text-gray-600
                                                                   hover:bg-gray-50 hover:text-red-500 transition-all duration-200
                                                                   dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-red-400
                                                                   disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:text-gray-600 disabled:hover:shadow-none">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" />
                                                    </svg>
                                                    Delete
                                                </button>` : '';

                        return `
                                                <div class="branch-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                            rounded-2xl overflow-hidden flex flex-col">

                                                    <div class="branch-card-map-wrap relative aspect-[16/9] overflow-hidden
                                                                bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                                                        ${mapMarkup}
                                                        <div class="bx-map-fallback-icon absolute inset-0 flex items-center justify-center ${mapMarkup ? 'bx-hidden' : ''}">
                                                            <svg class="w-9 h-9 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>

                                                        <span class="bx-status-badge absolute top-2 left-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ${statusClasses}">
                                                            ${branch.status === 'active' ? 'Active' : 'Inactive'}
                                                        </span>
                                                        <span class="bx-status-badge absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                                     ${branch.is_main ? 'bg-blue-100/90 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-gray-100/90 text-gray-600 dark:bg-gray-700/80 dark:text-gray-300'}">
                                                            ${branch.is_main ? 'Main' : 'Secondary'}
                                                        </span>
                                                    </div>

                                                    <div class="p-2.5 sm:p-3 flex flex-col gap-2 flex-1">
                                                        <div class="flex items-start gap-2">

                                                            <div class="min-w-0">
                                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">${escapeHtml(branch.name)}</p>
                                                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">${escapeHtml(branch.address || '—')}</p>
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 text-[11px] text-gray-500 dark:text-gray-400">
                                                            <div class="truncate"><span class="text-gray-400 dark:text-gray-500">Phone:</span> ${escapeHtml(branch.phone || '—')}</div>
                                                            <div class="truncate"><span class="text-gray-400 dark:text-gray-500">Email:</span> ${escapeHtml(branch.email || '—')}</div>
                                                        </div>

                                                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Updated ${this.formatDate(branch.updated_at)}</p>

                                                        <div class="flex gap-1.5 mt-auto pt-1">
                                                            <button type="button" data-action="view" data-id="${branch.id}"
                                                                class="action-btn flex-1 inline-flex items-center justify-center gap-1 px-2 sm:px-3 py-1.5 text-xs font-medium rounded-xl
                                                                               border border-gray-200 bg-white text-gray-600
                                                                               hover:text-indigo-600 hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                                               dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-gray-700">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                                                    <circle cx="12" cy="12" r="3" />
                                                                </svg>
                                                                View
                                                            </button>
                                                            ${editBtn}
                                                            ${deleteBtn}
                                                        </div>
                                                    </div>
                                                </div>`;
                    },

                    /* ══════════════════════════════════════════
                       STAT COUNTER ANIMATION (unchanged logic, still usable
                       if a stats block is added elsewhere on the page)
                       ══════════════════════════════════════════ */
                    animateCount(el, target) {
                        if (!el) return;
                        target = Number(target) || 0;
                        const start = parseInt((el.textContent || '0').replace(/,/g, ''), 10) || 0;
                        if (start === target) { el.textContent = target.toLocaleString(); return; }
                        const duration = 700;
                        const startTime = performance.now();
                        const ease = t => 1 - Math.pow(1 - t, 3);
                        const tick = (now) => {
                            const progress = Math.min((now - startTime) / duration, 1);
                            el.textContent = Math.round(start + (target - start) * ease(progress)).toLocaleString();
                            if (progress < 1) {
                                requestAnimationFrame(tick);
                            } else {
                                el.textContent = target.toLocaleString();
                                el.classList.remove('count-done');
                                void el.offsetWidth;
                                el.classList.add('count-done');
                            }
                        };
                        requestAnimationFrame(tick);
                    },

                    formatDate(iso) {
                        if (!iso) return '—';
                        const d = new Date(iso);
                        if (isNaN(d.getTime())) return '—';
                        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    },

                    /* ══════════════════════════════════════════
                       TOASTS (replaces x-for over toasts array)
                       ══════════════════════════════════════════ */
                    toast(message, type = 'success') {
                        const id = Date.now() + Math.random();
                        const wrap = document.getElementById('toastWrap');

                        const dotColor = type === 'error' ? '#ef4444' : (type === 'warning' ? '#f59e0b' : '#10b981');
                        const el = document.createElement('div');
                        el.className = 'toast';
                        el.dataset.toastId = id;
                        el.innerHTML = `<span class="toast-dot" style="background:${dotColor}"></span><span>${escapeHtml(message)}</span>`;
                        wrap.appendChild(el);

                        setTimeout(() => {
                            el.classList.add('leaving');
                            setTimeout(() => el.remove(), 280);
                        }, 3400);
                    },

                    /* ══════════════════════════════════════════
                       FORM STATE
                       ══════════════════════════════════════════ */
                    resetForm() {
                        this.form = { id: null, name: '', phone: '', email: '', address: '', lat: '', lng: '', status: 'active', is_main: false };
                        this.errors = {};
                        this.outsideCambodia = false;
                        this._originalCoords = null;
                        this.syncFormToDom();
                    },

                    syncFormToDom() {
                        document.getElementById('formName').value = this.form.name;
                        document.getElementById('formPhone').value = this.form.phone;
                        document.getElementById('formEmail').value = this.form.email;
                        document.getElementById('branchAddressInput').value = this.form.address;
                        document.getElementById('formLat').value = this.form.lat;
                        document.getElementById('formLng').value = this.form.lng;
                        document.getElementById('formStatusToggle').checked = this.form.status === 'active';
                        document.getElementById('formIsMain').checked = !!this.form.is_main;

                        this.clearFieldErrors();
                        hide(document.getElementById('outsideCambodiaWarning'));
                    },

                    clearFieldErrors() {
                        ['errorName', 'errorAddress', 'errorLat', 'errorLng'].forEach(id => {
                            const el = document.getElementById(id);
                            el.textContent = '';
                            hide(el);
                        });
                        ['formName', 'branchAddressInput'].forEach(id => {
                            document.getElementById(id).classList.remove('border-red-400');
                            document.getElementById(id).classList.add('border-gray-200', 'dark:border-gray-600');
                        });
                    },

                    showFieldErrors() {
                        this.clearFieldErrors();
                        const map = { name: 'errorName', address: 'errorAddress', lat: 'errorLat', lng: 'errorLng' };
                        Object.keys(this.errors).forEach(key => {
                            const el = document.getElementById(map[key]);
                            if (!el) return;
                            el.textContent = this.errors[key];
                            show(el);
                        });
                        if (this.errors.name) {
                            document.getElementById('formName').classList.add('border-red-400');
                            document.getElementById('formName').classList.remove('border-gray-200', 'dark:border-gray-600');
                        }
                        if (this.errors.address) {
                            document.getElementById('branchAddressInput').classList.add('border-red-400');
                            document.getElementById('branchAddressInput').classList.remove('border-gray-200', 'dark:border-gray-600');
                        }
                    },

                    setSaving(saving) {
                        this.saving = saving;
                        const btn = document.getElementById('branchSubmitBtn');
                        const spinner = document.getElementById('branchSubmitSpinner');
                        const label = document.getElementById('branchSubmitLabel');
                        btn.disabled = saving;
                        if (saving) { show(spinner); } else { hide(spinner); }
                        label.textContent = saving ? 'Saving…' : (this.modalMode === 'edit' ? 'Save Changes' : 'Create Branch');
                    },

                    /* ══════════════════════════════════════════
                       ADD MODAL
                       ══════════════════════════════════════════ */
                    openAddModal() {
                        this.modalMode = 'add';
                        this.resetForm();
                        document.getElementById('branchModalTitle').textContent = 'Add Branch';
                        this.setSaving(false);
                        const overlay = document.getElementById('branchModalOverlay');
                        overlay.classList.remove('bx-hidden');
                        overlay.classList.add('flex');
                        this.initMap(false);
                    },

                    closeAddModal() {
                        const overlay = document.getElementById('branchModalOverlay');
                        overlay.classList.add('bx-hidden');
                        overlay.classList.remove('flex');
                    },

                    /* ══════════════════════════════════════════
                       EDIT MODAL
                       ══════════════════════════════════════════ */
                    openEditModal(branch) {
                        this.modalMode = 'edit';
                        this.form = {
                            id: branch.id,
                            name: branch.name || '',
                            phone: branch.phone || '',
                            email: branch.email || '',
                            address: branch.address || '',
                            lat: branch.lat ?? '',
                            lng: branch.lng ?? '',
                            status: branch.status || 'active',
                            is_main: !!branch.is_main,
                        };
                        this.errors = {};
                        this.outsideCambodia = false;
                        this._originalCoords = (branch.lat && branch.lng) ? { lat: parseFloat(branch.lat), lng: parseFloat(branch.lng) } : null;

                        this.syncFormToDom();
                        document.getElementById('branchModalTitle').textContent = 'Edit Branch';
                        this.setSaving(false);

                        const overlay = document.getElementById('branchModalOverlay');
                        overlay.classList.remove('bx-hidden');
                        overlay.classList.add('flex');
                        this.initMap(true);
                    },

                    closeEditModal() {
                        this.closeAddModal();
                    },

                    /* ══════════════════════════════════════════
                       VIEW MODAL
                       ══════════════════════════════════════════ */
                    openViewModal(branch) {
                        this.viewBranch = branch;

                        document.getElementById('viewAvatar').textContent = (branch.name || '?').charAt(0).toUpperCase();
                        document.getElementById('viewName').textContent = branch.name || '';
                        document.getElementById('viewMainLabel').textContent = branch.is_main ? 'Main Branch' : 'Branch';
                        document.getElementById('viewPhone').textContent = branch.phone || '—';
                        document.getElementById('viewEmail').textContent = branch.email || '—';
                        document.getElementById('viewAddress').textContent = branch.address || '—';

                        const statusBadge = document.getElementById('viewStatusBadge');
                        statusBadge.textContent = branch.status === 'active' ? 'Active' : 'Inactive';
                        statusBadge.className = 'bx-badge ' + (branch.status === 'active'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                            : 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400');

                        const mainBadge = document.getElementById('viewMainBadge');
                        mainBadge.textContent = branch.is_main ? 'Main' : 'Secondary';
                        mainBadge.className = 'bx-badge ' + (branch.is_main
                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400');

                        document.getElementById('viewUpdated').textContent = 'Updated ' + this.formatDate(branch.updated_at);

                        const overlay = document.getElementById('viewModalOverlay');
                        overlay.classList.remove('bx-hidden');
                        overlay.classList.add('flex');

                        this.initViewMap(branch);
                    },

                    closeViewModal() {
                        this.viewBranch = null;
                        const overlay = document.getElementById('viewModalOverlay');
                        overlay.classList.add('bx-hidden');
                        overlay.classList.remove('flex');
                    },

                    /* ══════════════════════════════════════════
                       DELETE (SweetAlert2 confirm, same as before)
                       ══════════════════════════════════════════ */
                    openDeleteModal(branch) {
                        if (branch.is_main) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Main branch cannot be deleted',
                                text: 'Set another branch as main before deleting this one.',
                                confirmButtonColor: '#4f46e5',
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Delete branch?',
                            html: `<p style="font-size:13px;color:#6b7280;margin-bottom:4px;">You are about to delete</p>
                                                       <p style="font-weight:700;color:#111827;">${escapeHtml(branch.name)}</p>
                                                       <p style="font-size:12px;color:#9ca3af;">${escapeHtml(branch.address || '')}</p>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) this.confirmDelete(branch);
                        });
                    },

                    closeDeleteModal() {
                        Swal.close();
                    },

                    async confirmDelete(branch) {
                        try {
                            const url = routes.destroy.replace(':id', branch.id);
                            const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
                            const data = await res.json();
                            if (data.success) {
                                this.branches = this.branches.filter(b => b.id !== branch.id);
                                this.renderTable();
                                this.toast(data.message || 'Branch deleted.');
                            } else {
                                this.toast(data.message || 'Delete failed.', 'error');
                            }
                        } catch (e) {
                            this.toast('Something went wrong.', 'error');
                        }
                    },

                    /* ══════════════════════════════════════════
                       VALIDATION
                       ══════════════════════════════════════════ */
                    validate() {
                        this.errors = {};
                        if (!this.form.name || !this.form.name.trim()) this.errors.name = 'Branch name is required.';
                        if (!this.form.address || !this.form.address.trim()) this.errors.address = 'Address is required.';
                        if (!this.form.lat) this.errors.lat = 'Latitude is required.';
                        if (!this.form.lng) this.errors.lng = 'Longitude is required.';
                        this.showFieldErrors();
                        return Object.keys(this.errors).length === 0;
                    },

                    flattenErrors(errors) {
                        const out = {};
                        Object.keys(errors || {}).forEach(k => { out[k] = Array.isArray(errors[k]) ? errors[k][0] : errors[k]; });
                        return out;
                    },

                    /* ══════════════════════════════════════════
                       SUBMIT (create / update)
                       ══════════════════════════════════════════ */
                    async submitForm() {
                        if (!this.validate()) return;
                        this.setSaving(true);

                        const isEdit = this.modalMode === 'edit';
                        const url = isEdit ? routes.update.replace(':id', this.form.id) : routes.store;

                        try {
                            const res = await fetch(url, {
                                method: isEdit ? 'PUT' : 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                                body: JSON.stringify(this.form),
                            });
                            const data = await res.json();

                            if (data.success) {
                                if (data.branch && data.branch.is_main) {
                                    this.branches.forEach(b => { if (b.id !== data.branch.id) b.is_main = false; });
                                }
                                if (isEdit) {
                                    const idx = this.branches.findIndex(b => b.id === this.form.id);
                                    if (idx !== -1) this.branches[idx] = data.branch;
                                } else {
                                    this.branches.unshift(data.branch);
                                }
                                this.renderTable();
                                this.toast(data.message || (isEdit ? 'Branch updated successfully.' : 'Branch created successfully.'));
                                this.closeAddModal();
                            } else {
                                if (data.errors) {
                                    this.errors = this.flattenErrors(data.errors);
                                    this.showFieldErrors();
                                }
                                this.toast(data.message || 'Please check the form for errors.', 'error');
                            }
                        } catch (e) {
                            this.toast('Something went wrong. Please try again.', 'error');
                        } finally {
                            this.setSaving(false);
                        }
                    },

                    /* ══════════════════════════════════════════
                       GOOGLE MAPS
                       ══════════════════════════════════════════ */
                    checkCambodia(lat, lng) {
                        const inBounds = lat >= 9.2 && lat <= 14.7 && lng >= 102.3 && lng <= 107.7;
                        this.outsideCambodia = !inBounds;
                        const warning = document.getElementById('outsideCambodiaWarning');
                        if (this.outsideCambodia) show(warning); else hide(warning);
                    },

                    initMap(isEdit) {
                        const build = () => {
                            const el = document.getElementById('branchMap');
                            if (!el || !window.google || !window.google.maps) return;

                            const defaultPos = { lat: 11.5564, lng: 104.9282 };
                            const startLat = parseFloat(this.form.lat) || defaultPos.lat;
                            const startLng = parseFloat(this.form.lng) || defaultPos.lng;
                            const start = { lat: startLat, lng: startLng };

                            this._geocoder = this._geocoder || new google.maps.Geocoder();

                            this._map = new google.maps.Map(el, {
                                center: start, zoom: 16, disableDefaultUI: true, zoomControl: true,
                                streetViewControl: false, mapTypeControl: false, fullscreenControl: false, clickableIcons: false,
                                styles: [{ featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }],
                            });

                            this._marker = new google.maps.Marker({ position: start, map: this._map, draggable: true, animation: google.maps.Animation.DROP });

                            this._marker.addListener('dragend', () => {
                                const pos = this._marker.getPosition();
                                this.setCoords(pos.lat(), pos.lng());
                            });

                            this._map.addListener('click', (e) => {
                                this._marker.setPosition(e.latLng);
                                this.setCoords(e.latLng.lat(), e.latLng.lng());
                            });

                            const addressInput = document.getElementById('branchAddressInput');
                            if (addressInput && google.maps.places) {
                                this._autocomplete = new google.maps.places.Autocomplete(addressInput, {
                                    componentRestrictions: { country: 'kh' },
                                    fields: ['formatted_address', 'geometry', 'name'],
                                });
                                this._autocomplete.addListener('place_changed', () => {
                                    const place = this._autocomplete.getPlace();
                                    if (!place.geometry || !place.geometry.location) return;
                                    const loc = place.geometry.location;
                                    this._map.setCenter(loc);
                                    this._map.setZoom(16);
                                    this._marker.setPosition(loc);
                                    this.form.address = place.formatted_address || this.form.address;
                                    addressInput.value = this.form.address;
                                    this.setCoords(loc.lat(), loc.lng());
                                });
                            }

                            if (isEdit) {
                                this.checkCambodia(startLat, startLng);
                                this.form.lat = startLat.toFixed(6);
                                this.form.lng = startLng.toFixed(6);
                                document.getElementById('formLat').value = this.form.lat;
                                document.getElementById('formLng').value = this.form.lng;
                            }
                        };

                        if (window.google && window.google.maps) build();
                        else window.addEventListener('google-maps-ready', build, { once: true });
                    },

                    initViewMap(branch) {
                        const build = () => {
                            const el = document.getElementById('viewMap');
                            if (!el || !window.google || !window.google.maps || !branch.lat || !branch.lng) return;
                            const pos = { lat: parseFloat(branch.lat), lng: parseFloat(branch.lng) };
                            const map = new google.maps.Map(el, {
                                center: pos, zoom: 15, disableDefaultUI: true, zoomControl: true, clickableIcons: false,
                                styles: [{ featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }],
                            });
                            new google.maps.Marker({ position: pos, map });
                        };
                        if (window.google && window.google.maps) build();
                        else window.addEventListener('google-maps-ready', build, { once: true });
                    },

                    setCoords(lat, lng) {
                        this.form.lat = lat.toFixed(6);
                        this.form.lng = lng.toFixed(6);
                        document.getElementById('formLat').value = this.form.lat;
                        document.getElementById('formLng').value = this.form.lng;
                        this.checkCambodia(lat, lng);
                        this.reverseGeocode(lat, lng);
                    },

                    reverseGeocode(lat, lng) {
                        if (!this._geocoder) return;
                        this._geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                            if (status === 'OK' && results && results[0]) {
                                this.form.address = results[0].formatted_address;
                                document.getElementById('branchAddressInput').value = this.form.address;
                            }
                        });
                    },

                    useCurrentLocation() {
                        if (!navigator.geolocation) { this.toast('Geolocation is not supported by this browser.', 'error'); return; }
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                const lat = pos.coords.latitude, lng = pos.coords.longitude;
                                const point = { lat, lng };
                                if (this._map) { this._map.setCenter(point); this._map.setZoom(16); }
                                if (this._marker) this._marker.setPosition(point);
                                this.setCoords(lat, lng);
                            },
                            () => this.toast('Unable to retrieve your location. Check browser permissions.', 'error')
                        );
                    },

                    resetMap() {
                        const defaultPos = { lat: 11.5564, lng: 104.9282 };
                        const target = (this.modalMode === 'edit' && this._originalCoords) ? this._originalCoords : defaultPos;
                        if (this._map) { this._map.setCenter(target); this._map.setZoom(16); }
                        if (this._marker) this._marker.setPosition(target);
                        this.setCoords(target.lat, target.lng);
                        if (this.modalMode === 'add') {
                            this.form.address = '';
                            document.getElementById('branchAddressInput').value = '';
                        }
                    },
                };

                // Expose globally in case other scripts need it
                window.BranchPage = BranchPage;

                document.addEventListener('DOMContentLoaded', () => {
                    BranchPage.init();
                });
            })();
        </script>

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&libraries=places&callback=initGoogleMaps"
            async defer></script>
    @endpush

@endsection