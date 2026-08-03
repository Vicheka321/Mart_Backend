@extends('layouts.app')

@section('content')
    @php
        // ── Stats (derived from the paginated collection on this page;
        //    move to the controller if you want true global totals) ──
        $zonesCollection = method_exists($deliveryFees, 'getCollection')
            ? $deliveryFees->getCollection()
            : collect($deliveryFees);

        $totalZones = method_exists($deliveryFees, 'total') ? $deliveryFees->total() : $zonesCollection->count();
        $activeZones = $zonesCollection->where('status', 1)->count();
        $inactiveZones = $zonesCollection->where('status', 0)->count();
        $highestFee = $zonesCollection->max('fee') ?? 0;
    @endphp

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

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.94) translateY(16px);
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
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

        @keyframes spinPulse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        @keyframes pulseDot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: .5;
            }
        }

        .stat-card {
            animation: fadeSlideUp .45s ease both;
        }

        .stat-card:nth-child(1) {
            animation-delay: .05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: .11s;
        }

        .stat-card:nth-child(3) {
            animation-delay: .17s;
        }

        .stat-card:nth-child(4) {
            animation-delay: .23s;
        }

        .table-card {
            animation: fadeSlideUp .5s .2s ease both;
        }

        .fee-row {
            animation: rowSlideIn .3s ease both;
        }

        .fee-row:nth-child(1) {
            animation-delay: .06s;
        }

        .fee-row:nth-child(2) {
            animation-delay: .11s;
        }

        .fee-row:nth-child(3) {
            animation-delay: .16s;
        }

        .fee-row:nth-child(4) {
            animation-delay: .21s;
        }

        .fee-row:nth-child(5) {
            animation-delay: .26s;
        }

        .fee-row:nth-child(6) {
            animation-delay: .31s;
        }

        .fee-row:nth-child(7) {
            animation-delay: .36s;
        }

        .fee-row:nth-child(8) {
            animation-delay: .41s;
        }

        .modal-overlay.flex {
            animation: overlayIn .18s ease;
        }

        .modal-inner {
            animation: modalIn .22s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .action-icon-btn {
            transition: transform .15s ease, background .15s ease, color .15s ease, box-shadow .15s ease;
        }

        .action-icon-btn:hover {
            transform: translateY(-1px);
        }

        .action-icon-btn:active {
            transform: translateY(0);
        }

        .filter-pill {
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
        }

        .filter-pill.active {
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        .sort-th {
            cursor: pointer;
            user-select: none;
        }

        .sort-th:hover .sort-icon {
            opacity: .8;
        }

        .status-dot {
            animation: pulseDot 2.2s ease-in-out infinite;
        }

        .skeleton {
            background: linear-gradient(90deg, rgba(148, 163, 184, .15) 25%, rgba(148, 163, 184, .3) 37%, rgba(148, 163, 184, .15) 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease infinite;
        }

        .dark .skeleton {
            background: linear-gradient(90deg, rgba(71, 85, 105, .25) 25%, rgba(71, 85, 105, .45) 37%, rgba(71, 85, 105, .25) 63%);
            background-size: 400% 100%;
        }

        .toast {
            animation: toastSlide .3s ease;
        }

        .toast.leaving {
            animation: toastOut .3s ease forwards;
        }

        .btn-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinPulse .6s linear infinite;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="toast-container fixed top-5 right-5 z-[9999] flex flex-col gap-2" id="toastContainer"></div>

    <div class="space-y-4" x-data="deliveryFeesPage()">

        {{-- ==================== PAGE HEADER ==================== --}}
        <div class="flex flex-col gap-2">
            {{-- <nav class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                <a href="{{ route('admin.dashboard') }}"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <span>Commerce</span>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 dark:text-gray-300 font-medium">Delivery Fees</span>
            </nav> --}}

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Delivery Fees
                    </h1>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">Manage delivery fee zones by driving
                        distance.</p>
                </div>
            </div>
        </div>

        {{-- ==================== STATISTIC CARDS ==================== --}}


        {{-- Stat card skeletons --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="loadingSkeleton" x-cloak>
            @for ($i = 0; $i < 4; $i++)
                <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4">
                    <div class="w-9 h-9 rounded-xl skeleton mb-4"></div>
                    <div class="h-2.5 w-20 rounded skeleton mb-2"></div>
                    <div class="h-6 w-14 rounded skeleton"></div>
                </div>
            @endfor
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div
            class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- TOOLBAR --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                            flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Delivery Zones</h2>

                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

                    {{-- STATUS FILTER PILLS --}}
                    <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 p-1 gap-1 w-full sm:w-auto">
                        <button type="button" @click="statusFilter = 'all'; filterTable()"
                            :class="statusFilter === 'all' ? 'active bg-white dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                            class="filter-pill flex-1 sm:flex-none px-3 py-1.5 text-xs font-medium rounded-lg">
                            All
                        </button>
                        <button type="button" @click="statusFilter = 'active'; filterTable()"
                            :class="statusFilter === 'active' ? 'active bg-white dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                            class="filter-pill flex-1 sm:flex-none px-3 py-1.5 text-xs font-medium rounded-lg">
                            Active
                        </button>
                        <button type="button" @click="statusFilter = 'inactive'; filterTable()"
                            :class="statusFilter === 'inactive' ? 'active bg-white dark:bg-gray-600 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                            class="filter-pill flex-1 sm:flex-none px-3 py-1.5 text-xs font-medium rounded-lg">
                            Inactive
                        </button>
                    </div>

                    {{-- SEARCH --}}
                    <div class="relative w-full sm:w-56">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="feeSearch" placeholder="Search by distance…"
                            oninput="window.dispatchEvent(new Event('fee-search'))" autocomplete="off" class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                    </div>

                    {{-- ADD BUTTON --}}
                    @can('create_delivery_fees')
                        <button type="button" @click="openCreate()" class="action-btn w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                           bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                                           shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                            </svg>
                            <span>Add Delivery Zone</span>
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Table skeleton --}}
            <div class="p-5 space-y-3" x-show="loadingSkeleton" x-cloak>
                @for ($i = 0; $i < 5; $i++)
                    <div class="flex items-center gap-4">
                        <div class="h-4 w-6 rounded skeleton"></div>
                        <div class="h-4 flex-1 max-w-[140px] rounded skeleton"></div>
                        <div class="h-4 w-16 rounded skeleton"></div>
                        <div class="h-6 w-16 rounded-full skeleton"></div>
                        <div class="h-4 w-24 rounded skeleton hidden sm:block"></div>
                        <div class="h-8 w-24 rounded-lg skeleton ml-auto"></div>
                    </div>
                @endfor
            </div>

            <div x-show="!loadingSkeleton" x-cloak>

                @if($zonesCollection->isEmpty())
                    {{-- ==================== EMPTY STATE ==================== --}}
                    <div class="py-16 flex flex-col items-center justify-center text-center px-6">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-gray-700/40 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 16.5V15m8 1.5V15m-13.5 0h19M4.5 15l1.42-6.39A2 2 0 017.86 7h8.28a2 2 0 011.94 1.61L19.5 15M4.5 15v2a1.5 1.5 0 001.5 1.5h1a1.5 1.5 0 001.5-1.5v-.5h6v.5A1.5 1.5 0 0016 18.5h1a1.5 1.5 0 001.5-1.5v-2" />
                                <circle cx="8" cy="17.5" r="1.25" />
                                <circle cx="16" cy="17.5" r="1.25" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No delivery zones found</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 mb-5">Create your first delivery zone to start
                            charging fees by distance.</p>
                        @can('create_delivery_fees')
                            <button type="button" @click="openCreate()"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                                   bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-500/25 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                                </svg>
                                Add Delivery Zone
                            </button>
                        @endcan
                    </div>
                @else

                    {{-- ==================== DESKTOP / TABLET TABLE ==================== --}}
                    <div class="hidden md:block overflow-x-auto max-h-[560px] overflow-y-auto">
                        <table class="w-full text-sm" id="feesTable">
                            <thead class="sticky top-0 z-10">
                                <tr
                                    class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/95 dark:bg-gray-700/70 backdrop-blur">
                                    <th class="px-5 py-3 text-left w-14">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">#</span>
                                    </th>
                                    <th class="px-5 py-3 text-left sort-th" onclick="sortTable('distance', this)">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Distance Range
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left sort-th" onclick="sortTable('fee', this)">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Delivery Fee
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left sort-th" onclick="sortTable('status', this)">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Status
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left hidden lg:table-cell sort-th"
                                        onclick="sortTable('updated', this)">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Updated At
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-right">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="feesBody">
                                @foreach($zonesCollection as $index => $zone)
                                    @php
                                        $isActive = (bool) $zone->status;
                                        $distanceLabel = $zone->max_km
                                            ? number_format($zone->min_km, 2) . ' - ' . number_format($zone->max_km, 2) . ' KM'
                                            : number_format($zone->min_km, 2) . '+ KM';
                                    @endphp

                                    <tr class="fee-row hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                                        data-distance="{{ strtolower($distanceLabel) }}" data-min="{{ $zone->min_km }}"
                                        data-fee="{{ $zone->fee }}" data-status="{{ $isActive ? 'active' : 'inactive' }}"
                                        data-updated="{{ $zone->updated_at?->timestamp }}">

                                        <td class="px-5 py-3.5 text-gray-400 dark:text-gray-500 text-xs font-medium">
                                            {{ $loop->iteration + (method_exists($deliveryFees, 'firstItem') ? ($deliveryFees->firstItem() - 1) : 0) }}
                                        </td>

                                        <td class="px-5 py-3.5">
                                            <span
                                                class="inline-flex items-center gap-1.5 font-medium text-gray-900 dark:text-white">
                                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                                {{ $distanceLabel }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-3.5">
                                            <span class="font-bold text-sm text-gray-900 dark:text-white">
                                                ${{ number_format($zone->fee, 2) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-3.5">
                                            @if($isActive)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                                                             bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                                                             border border-emerald-100 dark:border-emerald-800">
                                                    <span
                                                        class="status-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                                             bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                                                                             border border-gray-200 dark:border-gray-600">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500 hidden lg:table-cell">
                                            {{ $zone->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                        </td>

                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button"
                                                    @click="openView({{ $zone->id }}, {{ $zone->min_km }}, {{ $zone->max_km ?? 'null' }}, {{ $zone->fee }}, {{ $isActive ? 'true' : 'false' }}, @js($zone->updated_at?->format('d M Y, h:i A')))"
                                                    class="action-icon-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                border border-gray-200 bg-white text-gray-500
                                                                hover:text-indigo-600 hover:border-indigo-200 hover:shadow-sm
                                                                dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-indigo-400"
                                                    title="View">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </button>

                                                @can('edit_delivery_fees')
                                                    <button type="button"
                                                        @click="openEdit({{ $zone->id }}, {{ $zone->min_km }}, {{ $zone->max_km ?? 'null' }}, {{ $zone->fee }}, {{ $isActive ? 'true' : 'false' }})"
                                                        class="action-icon-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                        border border-gray-200 bg-white text-gray-500
                                                                        hover:text-blue-600 hover:border-blue-200 hover:shadow-sm
                                                                        dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-blue-400"
                                                        title="Edit">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="1.8">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg>
                                                    </button>
                                                @endcan

                                                @can('delete_delivery_fees')
                                                    <button type="button" @click="openDelete({{ $zone->id }}, @js($distanceLabel))"
                                                        class="action-icon-btn inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                                        border border-gray-200 bg-white text-gray-500
                                                                        hover:text-red-500 hover:border-red-200 hover:shadow-sm
                                                                        dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-red-400"
                                                        title="Delete">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" />
                                                        </svg>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="feeSearchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No delivery zones match your search.
                        </div>
                    </div>

                    {{-- ==================== MOBILE CARD LIST ==================== --}}
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700" id="feesBodyMobile">
                        @foreach($zonesCollection as $zone)
                            @php
                                $isActiveM = (bool) $zone->status;
                                $distanceLabelM = $zone->max_km
                                    ? number_format($zone->min_km, 2) . ' - ' . number_format($zone->max_km, 2) . ' KM'
                                    : number_format($zone->min_km, 2) . '+ KM';
                            @endphp

                            <div class="fee-row p-4 flex flex-col gap-3" data-distance="{{ strtolower($distanceLabelM) }}"
                                data-status="{{ $isActiveM ? 'active' : 'inactive' }}">

                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900 dark:text-white flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            {{ $distanceLabelM }}
                                        </p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $zone->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                        </p>
                                    </div>
                                    @if($isActiveM)
                                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                                                     bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                                                     border border-emerald-100 dark:border-emerald-800">
                                            <span class="status-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="flex-shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                                     bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                                                                     border border-gray-200 dark:border-gray-600">
                                            Inactive
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($zone->fee, 2) }}</p>

                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button"
                                        @click="openView({{ $zone->id }}, {{ $zone->min_km }}, {{ $zone->max_km ?? 'null' }}, {{ $zone->fee }}, {{ $isActiveM ? 'true' : 'false' }}, @js($zone->updated_at?->format('d M Y, h:i A')))"
                                        class="action-icon-btn flex-1 inline-flex items-center justify-center gap-1.5 h-9 rounded-lg text-xs font-medium
                                                    border border-gray-200 bg-white text-gray-600
                                                    dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View
                                    </button>

                                    @can('edit_delivery_fees')
                                        <button type="button"
                                            @click="openEdit({{ $zone->id }}, {{ $zone->min_km }}, {{ $zone->max_km ?? 'null' }}, {{ $zone->fee }}, {{ $isActiveM ? 'true' : 'false' }})"
                                            class="action-icon-btn flex-1 inline-flex items-center justify-center gap-1.5 h-9 rounded-lg text-xs font-medium
                                                            border border-blue-200 bg-blue-50 text-blue-600
                                                            dark:bg-blue-500/10 dark:border-blue-500/30 dark:text-blue-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                            Edit
                                        </button>
                                    @endcan

                                    @can('delete_delivery_fees')
                                        <button type="button" @click="openDelete({{ $zone->id }}, @js($distanceLabelM))" class="action-icon-btn w-9 h-9 flex-shrink-0 inline-flex items-center justify-center rounded-lg
                                                            border border-red-200 bg-red-50 text-red-500
                                                            dark:bg-red-500/10 dark:border-red-500/30 dark:text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endforeach

                        <div id="feeSearchEmptyMobile"
                            class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No delivery zones match your search.
                        </div>
                    </div>

                    {{-- FOOTER / PAGINATION --}}
                    @if(method_exists($deliveryFees, 'links'))
                        <div class="px-4 sm:px-5 py-4 border-t border-gray-100 dark:border-gray-700
                                                flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                Showing <span
                                    class="font-medium text-gray-600 dark:text-gray-300">{{ $deliveryFees->firstItem() ?? 0 }}–{{ $deliveryFees->lastItem() ?? 0 }}</span>
                                of <span
                                    class="font-medium text-gray-600 dark:text-gray-300">{{ number_format($deliveryFees->total()) }}</span>
                                zones
                            </p>
                            <div class="text-sm w-full sm:w-auto overflow-x-auto">{{ $deliveryFees->links() }}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>


        {{-- ==================== CREATE / EDIT MODAL ==================== --}}
        <div x-show="modals.form" x-cloak
            class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-0 sm:p-4">
            <div class="modal-inner w-full h-full sm:h-auto sm:max-w-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                            sm:rounded-2xl shadow-2xl overflow-hidden sm:max-h-[92vh] flex flex-col"
                @click.outside="closeModal('form')">

                <div
                    class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white"
                            x-text="form.id ? 'Edit Delivery Zone' : 'Add Delivery Zone'"></h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"
                            x-text="form.id ? 'Update this distance zone' : 'Create a new distance-based fee zone'"></p>
                    </div>
                    <button type="button" @click="closeModal('form')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700
                                   text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Validation error alert --}}
                <div x-show="errors.length" x-cloak
                    class="mx-5 sm:mx-6 mt-4 p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 flex items-start gap-2">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <ul class="text-xs text-red-600 dark:text-red-400 space-y-0.5">
                        <template x-for="err in errors" :key="err">
                            <li x-text="err"></li>
                        </template>
                    </ul>
                </div>

                <form @submit.prevent="submitForm()" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                Minimum Distance (KM) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" x-model="form.min_km" required placeholder="0" class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                Maximum Distance (KM)
                                <span class="font-normal text-gray-400">(blank = no limit)</span>
                            </label>
                            <input type="number" step="0.01" min="0" x-model="form.max_km" placeholder="e.g. 5" class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                            Delivery Fee ($) <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">$</span>
                            <input type="number" step="0.01" min="0" x-model="form.fee" required placeholder="0.00" class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                        <select x-model="form.status" class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                        <button type="button" @click="closeModal('form')"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                       text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="saving" class="action-btn w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium
                                       bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white rounded-xl
                                       shadow-md shadow-indigo-500/25 transition-all">
                            <span x-show="saving" class="btn-spinner"></span>
                            <span x-text="saving ? 'Saving…' : 'Save'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ==================== VIEW MODAL ==================== --}}
        <div x-show="modals.view" x-cloak
            class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-0 sm:p-4">
            <div class="modal-inner w-full h-full sm:h-auto sm:max-w-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                            sm:rounded-2xl shadow-2xl overflow-hidden flex flex-col" @click.outside="closeModal('view')">

                <div
                    class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Zone Details</h2>
                    <button type="button" @click="closeModal('view')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700
                                   text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/25 flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="viewData.distance"></p>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold mt-1"
                                :class="viewData.active ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600'">
                                <span x-show="viewData.active"
                                    class="status-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                <span x-text="viewData.active ? 'Active' : 'Inactive'"></span>
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Delivery Fee</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'$' + viewData.fee"></span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-gray-100 dark:border-gray-700 pt-2">
                            <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                            <span class="font-medium text-gray-900 dark:text-white" x-text="viewData.updated"></span>
                        </div>
                    </div>

                    <button type="button" @click="closeModal('view')" class="w-full py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                   text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>


        {{-- ==================== DELETE CONFIRM MODAL ==================== --}}
        <div x-show="modals.delete" x-cloak
            class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="modal-inner w-full max-w-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                            rounded-2xl shadow-2xl overflow-hidden" @click.outside="closeModal('delete')">
                <div class="p-6 text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Delete this delivery zone?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5">
                        <span class="font-medium text-gray-700 dark:text-gray-300" x-text="deleteData.distance"></span>
                        will be permanently removed. This action cannot be undone.
                    </p>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="button" @click="closeModal('delete')"
                            class="flex-1 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                       text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="button" @click="confirmDelete()" :disabled="saving"
                            class="flex-1 inline-flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-xl
                                       bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white shadow-md shadow-red-500/25 transition">
                            <span x-show="saving" class="btn-spinner"></span>
                            <span x-text="saving ? 'Deleting…' : 'Delete'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function deliveryFeesPage() {
                return {
                    loadingSkeleton: true,
                    statusFilter: 'all',
                    saving: false,
                    errors: [],
                    modals: { form: false, view: false, delete: false },
                    form: { id: null, min_km: '', max_km: '', fee: '', status: '1' },
                    viewData: { distance: '', fee: '', active: true, updated: '' },
                    deleteData: { id: null, distance: '' },

                    init() {
                        // Brief skeleton flash for a premium initial-load feel
                        setTimeout(() => { this.loadingSkeleton = false; }, 400);

                        window.addEventListener('fee-search', () => filterTable());
                    },

                    openCreate() {
                        this.errors = [];
                        this.form = { id: null, min_km: '', max_km: '', fee: '', status: '1' };
                        this.modals.form = true;
                    },

                    openEdit(id, minKm, maxKm, fee, isActive) {
                        this.errors = [];
                        this.form = {
                            id,
                            min_km: minKm,
                            max_km: maxKm ?? '',
                            fee,
                            status: isActive ? '1' : '0',
                        };
                        this.modals.form = true;
                    },

                    openView(id, minKm, maxKm, fee, isActive, updated) {
                        const distance = maxKm
                            ? `${Number(minKm).toFixed(2)} - ${Number(maxKm).toFixed(2)} KM`
                            : `${Number(minKm).toFixed(2)}+ KM`;
                        this.viewData = {
                            distance,
                            fee: Number(fee).toFixed(2),
                            active: !!isActive,
                            updated: updated ?? '—',
                        };
                        this.modals.view = true;
                    },

                    openDelete(id, distanceLabel) {
                        this.deleteData = { id, distance: distanceLabel };
                        this.modals.delete = true;
                    },

                    closeModal(name) {
                        this.modals[name] = false;
                    },

                    async submitForm() {
                        this.errors = [];
                        this.saving = true;

                        const isEdit = !!this.form.id;
                        const url = isEdit
                            ? `{{ url('admin/delivery-fees') }}/${this.form.id}`
                            : `{{ route('delivery-fees.store') }}`;

                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    ...(isEdit ? { 'X-HTTP-Method-Override': 'PUT' } : {}),
                                },
                                body: JSON.stringify({
                                    min_km: this.form.min_km,
                                    max_km: this.form.max_km || null,
                                    fee: this.form.fee,
                                    status: this.form.status,
                                }),
                            });

                            if (response.status === 422) {
                                const data = await response.json();
                                this.errors = Object.values(data.errors || {}).flat();
                                this.saving = false;
                                return;
                            }

                            if (!response.ok) throw new Error('Request failed');

                            showToast(isEdit ? 'Delivery zone updated.' : 'Delivery zone created.', 'success');
                            this.modals.form = false;
                            setTimeout(() => location.reload(), 600);
                        } catch (e) {
                            console.error(e);
                            showToast('Something went wrong. Please try again.', 'error');
                            this.saving = false;
                        }
                    },

                    async confirmDelete() {
                        this.saving = true;
                        try {
                            const response = await fetch(`{{ url('admin/delivery-fees') }}/${this.deleteData.id}`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-HTTP-Method-Override': 'DELETE',
                                },
                            });

                            if (!response.ok) throw new Error('Delete failed');

                            showToast('Delivery zone deleted.', 'success');
                            this.modals.delete = false;
                            setTimeout(() => location.reload(), 600);
                        } catch (e) {
                            console.error(e);
                            showToast('Could not delete this zone. Please try again.', 'error');
                        } finally {
                            this.saving = false;
                        }
                    },
                };
            }

            // ══════════════════════════════════════════════════════
            //  TOAST
            // ══════════════════════════════════════════════════════
            function showToast(message, type = 'success') {
                const colors = { success: '#10b981', error: '#ef4444', info: '#6366f1', warning: '#f59e0b' };
                const toast = document.createElement('div');
                toast.className = 'toast flex items-center gap-2.5 px-4 py-3 rounded-2xl shadow-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 text-sm font-medium min-w-[220px]';
                toast.innerHTML = `<span class="w-2 h-2 rounded-full flex-shrink-0" style="background:${colors[type] ?? colors.info}"></span><span>${message}</span>`;
                document.getElementById('toastContainer').appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('leaving');
                    toast.addEventListener('animationend', () => toast.remove(), { once: true });
                }, 3200);
            }

            // ══════════════════════════════════════════════════════
            //  SEARCH + STATUS FILTER (applies to table rows AND mobile cards)
            // ══════════════════════════════════════════════════════
            function filterTable() {
                const q = (document.getElementById('feeSearch')?.value ?? '').toLowerCase().trim();
                const statusFilter = document.querySelector('[x-data]')?.__x?.$data?.statusFilter ?? 'all';

                const desktopRows = document.querySelectorAll('#feesBody .fee-row');
                const mobileRows = document.querySelectorAll('#feesBodyMobile .fee-row');
                let visibleDesktop = 0, visibleMobile = 0;

                desktopRows.forEach(row => {
                    const matchQ = !q || (row.dataset.distance ?? '').includes(q);
                    const matchStatus = statusFilter === 'all' || row.dataset.status === statusFilter;
                    const show = matchQ && matchStatus;
                    row.style.display = show ? '' : 'none';
                    if (show) visibleDesktop++;
                });

                mobileRows.forEach(row => {
                    const matchQ = !q || (row.dataset.distance ?? '').includes(q);
                    const matchStatus = statusFilter === 'all' || row.dataset.status === statusFilter;
                    const show = matchQ && matchStatus;
                    row.style.display = show ? '' : 'none';
                    if (show) visibleMobile++;
                });

                document.getElementById('feeSearchEmpty')?.classList.toggle('hidden', visibleDesktop !== 0);
                document.getElementById('feeSearchEmptyMobile')?.classList.toggle('hidden', visibleMobile !== 0);
            }

            // ══════════════════════════════════════════════════════
            //  SORT (desktop table)
            // ══════════════════════════════════════════════════════
            let sortCol = null, sortDir = 1;

            function sortTable(col, thEl) {
                const tbody = document.getElementById('feesBody');
                if (!tbody) return;
                const rows = Array.from(tbody.querySelectorAll('.fee-row'));

                if (sortCol === col) sortDir *= -1;
                else { sortCol = col; sortDir = 1; }

                document.querySelectorAll('#feesTable th').forEach(t => t.classList.remove('sorted-asc', 'sorted-desc'));
                thEl.classList.add(sortDir === 1 ? 'sorted-desc' : 'sorted-asc');

                const keyMap = { distance: 'min', fee: 'fee', status: 'status', updated: 'updated' };
                const key = keyMap[col] ?? col;

                rows.sort((a, b) => {
                    if (key === 'status') {
                        return (a.dataset.status ?? '').localeCompare(b.dataset.status ?? '') * sortDir;
                    }
                    const av = parseFloat(a.dataset[key] ?? 0);
                    const bv = parseFloat(b.dataset[key] ?? 0);
                    return (av - bv) * sortDir;
                });

                rows.forEach(r => tbody.appendChild(r));
            }
        </script>
    @endpush

@endsection