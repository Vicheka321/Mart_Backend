@extends('layouts.app')

@section('content')

    <style>
        /* ── Entry animations ── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.94) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.8); opacity: 0; }
            70%  { transform: scale(1.06); }
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
            from { opacity: 1; transform: translateX(0) scale(1); }
            to   { opacity: 0; transform: translateX(48px) scale(.95); }
        }
        @keyframes imgHoverPop {
            from { transform: scale(1) translateY(0); }
            to   { transform: scale(1.04) translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,.18); }
        }

        /* Stat cards staggered */
        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .14s; }
        .stat-card:nth-child(3) { animation-delay: .23s; }

        /* Table card */
        .table-card { animation: fadeSlideUp .5s .28s ease both; }

        /* Product grid cards staggered */
        .product-row {
            animation: cardPop .38s ease both;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .product-row:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }

        .product-row:nth-child(1)  { animation-delay: .32s; }
        .product-row:nth-child(2)  { animation-delay: .37s; }
        .product-row:nth-child(3)  { animation-delay: .42s; }
        .product-row:nth-child(4)  { animation-delay: .47s; }
        .product-row:nth-child(5)  { animation-delay: .52s; }
        .product-row:nth-child(6)  { animation-delay: .57s; }
        .product-row:nth-child(7)  { animation-delay: .62s; }
        .product-row:nth-child(8)  { animation-delay: .67s; }
        .product-row:nth-child(9)  { animation-delay: .72s; }
        .product-row:nth-child(10) { animation-delay: .77s; }

        /* Progress bars */
        .progress-bar { animation: progressFill .9s .7s cubic-bezier(.4,0,.2,1) both; }

        /* Number pop after counter finishes */
        .count-done { animation: numberPop .32s cubic-bezier(.34,1.56,.64,1) both; }

        /* Modals */
        #exportModal.flex   { animation: overlayIn .2s ease; }
        #productModal.flex  { animation: overlayIn .2s ease; }
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
            padding: .75rem 1rem; min-width: 240px;
            background: white; border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12);
            font-size: .8125rem; font-weight: 500; color: #111827;
            animation: toastSlide .3s cubic-bezier(.34,1.3,.64,1) both;
        }
        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.leaving { animation: toastOut .28s ease forwards; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* Action button lift */
        .action-btn { transition: transform .14s ease, box-shadow .14s ease; }
        .action-btn:hover  { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.18); }
        .action-btn:active { transform: translateY(0); }

        /* Image zoom on product card */
        .product-img { transition: transform .35s cubic-bezier(.25,.46,.45,.94); }
        .product-row:hover .product-img { transform: scale(1.07); }

        /* Status badge on card */
        .status-badge-card {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Search focus */
        #productSearch:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }

        /* Thumb inner */
        .thumb-inner { border-radius: 11px; overflow: hidden; width: 100%; height: 100%; }
        #uploadBox:hover #editOverlay { background: rgba(0,0,0,.28) !important; display: flex !important; }

        /* Filter pill */
        .filter-pill { transition: background .18s ease, color .18s ease, box-shadow .18s ease; }
        .filter-pill.active { box-shadow: 0 1px 4px rgba(0,0,0,.1); }

        /* Responsive */
        @media (max-width: 640px) {
            .stat-card { padding: .75rem; }
            .stat-number { font-size: 1.5rem !important; }
        }
    </style>

    {{-- Toast container --}}
    <div class="toast-wrap" id="toastWrap"></div>

    <div class="space-y-4">

        {{-- ==================== STAT CARDS ==================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Total Products --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-12 -right-10 w-32 h-32 rounded-full
                            bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600
                                    flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Products</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">In inventory</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full
                                 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20
                                 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800
                                 text-[10px] font-semibold">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                        </svg>
                        +{{ $totalProducts }}
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="stat-number text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent"
                        data-count="{{ $totalProducts }}">0</h2>
                </div>

                {{-- Mini product avatars --}}
                <div class="relative flex items-center gap-2">
                    <div class="flex -space-x-2">
                        @foreach($products->take(4) as $product)
                            @php $image = $product->image->first(); $imageUrl = $image?->image_url ?? asset('images/no-image.png'); @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                 class="w-5 h-5 rounded-full border-2 border-white dark:border-gray-800 object-cover shadow-sm hover:scale-110 transition-transform duration-200">
                        @endforeach
                        @if($totalProducts > 4)
                            <div class="w-5 h-5 rounded-full border-2 border-white dark:border-gray-800
                                        bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600
                                        flex items-center justify-center text-[9px] font-semibold text-gray-600 dark:text-gray-300">
                                +{{ $totalProducts - 4 }}
                            </div>
                        @endif
                    </div>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Recent products</span>
                </div>
            </div>

            {{-- Active Products --}}
            @php $activePct = $totalProducts > 0 ? round(($totalActive / $totalProducts) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-12 -right-10 w-32 h-32 rounded-full
                            bg-gradient-to-br from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600
                                    flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Active Products</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Available for sale</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">
                        {{ $activePct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="stat-number text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent"
                        data-count="{{ $totalActive }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600 shadow-sm shadow-emerald-500/30"
                             style="width: {{ $activePct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $activePct }}% of total</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($totalProducts) }} total</span>
                    </div>
                </div>
            </div>

            {{-- Low Stock --}}
            @php $lowPct = $totalProducts > 0 ? round(($totalLowStock / $totalProducts) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-4 flex flex-col gap-2">
                <div class="absolute -top-12 -right-10 w-32 h-32 rounded-full
                            bg-gradient-to-br from-amber-50 to-orange-100 dark:from-amber-900/20 dark:to-orange-900/20"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600
                                    flex items-center justify-center shadow-md shadow-amber-500/25">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8 14A1 1 0 003.16 19h17.68a1 1 0 00.87-1.5l-8-14a1 1 0 00-1.74 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Low Stock</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Need restock</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400
                                 ring-1 ring-amber-200 dark:ring-amber-800 text-[10px] font-semibold">
                        {{ $lowPct }}%
                    </span>
                </div>

                <div class="relative pl-1">
                    <h2 class="stat-number text-2xl font-bold tracking-tight leading-none
                               bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent"
                        data-count="{{ $totalLowStock }}">0</h2>
                </div>

                <div class="relative">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-amber-500 to-orange-600 shadow-sm shadow-amber-500/30"
                             style="width: {{ $lowPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $lowPct }}% of total</span>
                        <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400">{{ number_format($totalProducts) }} total</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                        flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Product List</h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                    {{-- STATUS FILTER --}}
                    {{-- <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700 p-1 gap-1 flex-wrap">
                        @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive', 'low-stock' => 'Low Stock'] as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['status' => $value, 'page' => 1]) }}" 
                               class="filter-pill px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                      {{ ($statusFilter ?? 'all') === $value
                                          ? 'active bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div> --}}
                    <form method="GET" action="{{ url()->current() }}">
                        {{-- Keep existing query parameters except status and page --}}
                        @foreach(request()->except(['status', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="status"
                                onchange="this.form.submit()"
                                class="px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            @foreach([
                                'all'        => 'All',
                                'active'     => 'Active',
                                'inactive'   => 'Inactive',
                                'low-stock'  => 'Low Stock'
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
                        <input type="text" id="productSearch" placeholder="Search products…" oninput="filterProducts()"
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
                        <span class="hidden sm:inline">Add Product</span>
                    </button>
                </div>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if(($statusFilter ?? 'all') !== 'all')
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                            flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold capitalize">{{ $statusFilter }}</span>
                        &mdash; {{ number_format($products->total()) }} {{ Str::plural('result', $products->total()) }}
                    </p>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}"
                       class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">Clear filter</a>
                </div>
            @endif

            {{-- PRODUCT GRID --}}
            <div class="p-4 sm:p-5">
                <div id="productsTable"
                     class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">

                    @forelse($products as $product)
                        @php
                            $img      = optional($product->image->first())->image_url;
                            $isLow    = ($product->quantity ?? 0) < 10;
                            $isActive = ($product->status ?? 1) == 1;
                        @endphp

                        <div class="product-row bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                    rounded-2xl overflow-hidden flex flex-col cursor-pointer"
                             data-name="{{ strtolower($product->name) }}"
                             data-category="{{ strtolower($product->category->name ?? '') }}"
                             data-brand="{{ strtolower($product->brand->name ?? '') }}">

                            {{-- Image --}}
                            <div class="relative aspect-[4/3] overflow-hidden
                                        {{ $img ? 'bg-gray-100 dark:bg-gray-700' : 'bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600' }}">
                                @if($img)
                                    <img src="{{ $img }}" loading="lazy" alt="{{ $product->name }}"
                                         class="product-img w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-gray-400 dark:text-gray-500">
                                        {{ strtoupper(substr($product->name ?? 'P', 0, 1)) }}
                                    </div>
                                @endif

                                {{-- Status badge --}}
                                <span class="status-badge-card absolute top-2 left-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                             {{ $isActive
                                                 ? 'bg-emerald-100/90 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                                                 : 'bg-red-100/90 text-red-600 dark:bg-red-500/20 dark:text-red-400' }}">
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>

                                @if($isLow)
                                    <span class="status-badge-card absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                 bg-amber-100/90 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                        Low stock
                                    </span>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="p-3 flex flex-col gap-2 flex-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                        {{ $product->category->name ?? '—' }} · {{ $product->brand->name ?? '—' }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            ${{ number_format($product->sale_price ?? 0, 2) }}
                                        </p>
                                        @if($product->cost_price)
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                                Cost ${{ number_format($product->cost_price, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                                 {{ $isLow
                                                     ? 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400'
                                                     : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                                        {{ $product->quantity ?? 0 }}
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-1.5 mt-auto pt-1">
                                    <button type="button"
                                        onclick='editProduct(
                                            {{ $product->id }},
                                            @json($product->name),
                                            @json($product->description),
                                            {{ $product->categories_id }},
                                            {{ $product->brand_id }},
                                            {{ $product->cost_price ?? 0 }},
                                            {{ $product->sale_price ?? 0 }},
                                            {{ $product->quantity ?? 0 }},
                                            {{ (int) $product->status }},
                                            @json($product->image->map(fn($img) => ["image_url" => $img->image_url])->values())
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

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
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
                            No products found.
                        </div>
                    @endforelse
                </div>

                <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                    No products match your search.
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($products->total())
                        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ number_format($products->total()) }}
                    @else
                        No products found
                    @endif
                </p>
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>


    {{-- ==================== EXPORT MODAL ==================== --}}
    <div id="exportModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[70] p-4">
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
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" backdropstroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Choose your preferred export format:</p>
                <a href="{{ route('products.export.csv') }}"
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
                <a href="{{ route('products.export.pdf') }}"
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


    {{-- ==================== ADD / EDIT PRODUCT MODAL ==================== --}}
    <div id="productModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-start justify-center z-[70] px-4 py-8 overflow-y-auto">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    rounded-2xl w-full max-w-3xl mx-auto shadow-2xl">

            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 id="modalTitle" class="text-base font-semibold text-gray-900 dark:text-white">Add Product</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- LEFT: Fields --}}
                    <div class="space-y-4">

                        {{-- General Info --}}
                        <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-4 space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">General Info</p>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Product Name</label>
                                <input type="text" id="productName" name="name" placeholder="e.g. Pepsi" required
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-white dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Description</label>
                                <textarea name="description" rows="3" placeholder="Enter product description…"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-white dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none transition-all"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Category</label>
                                    <select name="categories_id"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Brand</label>
                                    <select name="brand_id"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing & Stock --}}
                        <div class="bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-4 space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pricing & Stock</p>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Cost Price</label>
                                    <input type="number" step="0.01" name="cost_price" placeholder="0.50"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Sale Price</label>
                                    <input type="number" step="0.01" name="sale_price" placeholder="1.00" required
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Quantity</label>
                                    <input type="number" name="quantity" placeholder="100" required
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                                    <select name="status"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Image Upload --}}
                    <div class="flex flex-col bg-gray-50 dark:bg-gray-700/40 rounded-2xl p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Product Images</p>

                        <div id="uploadBox" onclick="handleMainClick()"
                             class="relative w-full h-48 rounded-2xl bg-white dark:bg-gray-700
                                    border-2 border-dashed border-gray-200 dark:border-gray-600
                                    flex items-center justify-center cursor-pointer overflow-hidden
                                    transition-colors hover:border-indigo-300 dark:hover:border-indigo-500 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <div id="uploadPlaceholder" class="flex flex-col items-center gap-2 pointer-events-none select-none">
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Click to upload images</span>
                                <span class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</span>
                            </div>
                            <img id="mainPreview" class="hidden absolute inset-0 w-full h-full object-contain" alt="Preview">
                            <div id="editOverlay" class="hidden absolute inset-0 items-center justify-center">
                                <span id="editLabel" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium px-4 py-1.5 rounded-full shadow">
                                    Change Photo
                                </span>
                            </div>
                        </div>

                        <input type="file" id="imageInputNew" name="images[]" class="hidden" accept="image/*" multiple>
                        <input type="file" id="imageInputSwap" class="hidden" accept="image/*">

                        <div id="thumbGrid" class="grid grid-cols-4 gap-2 mt-3"></div>
                        <p id="imgCount" class="hidden mt-1.5 text-xs text-gray-400"></p>

                        <div class="flex-1 min-h-4"></div>

                        <button type="submit"
                            class="action-btn w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                                   py-2.5 rounded-xl transition-all shadow-md shadow-indigo-500/25">
                            Save Product
                        </button>
                    </div>
                </div>
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
        const startTime = performance.now();
        function ease(t) { return 1 - Math.pow(1 - t, 3); }
        function tick(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            el.textContent = Math.round(ease(progress) * target).toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
            else { el.textContent = target.toLocaleString(); el.classList.add('count-done'); }
        }
        requestAnimationFrame(tick);
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('[data-count]').forEach(animateCounter);
        }, 320);

        // Delete confirm
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete product?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
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
    document.getElementById('productModal').addEventListener('click', function(e) { if (e.target === this) hideModal('productModal'); });

    function openExportModal()  { showModal('exportModal'); }
    function closeExportModal() { hideModal('exportModal'); }

    function openModal()  { resetModal(); showModal('productModal'); }
    function closeModal() { hideModal('productModal'); }

    function resetModal() {
        document.getElementById('productForm').action = "{{ route('products.store') }}";
        document.getElementById('formMethod').value   = 'POST';
        document.getElementById('productName').value  = '';
        document.getElementById('modalTitle').innerText = 'Add Product';
        clearPreview();
    }

    // ══════════════════════════════════════════════════════
    //  SEARCH FILTER
    // ══════════════════════════════════════════════════════
    function filterProducts() {
        const q     = document.getElementById('productSearch').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-row');
        const empty = document.getElementById('searchEmpty');
        let vis     = 0;
        cards.forEach(card => {
            const match = ['name','category','brand'].some(k => (card.dataset[k] || '').includes(q));
            card.style.display = match ? '' : 'none';
            if (match) vis++;
        });
        empty.classList.toggle('hidden', !(q && vis === 0));
    }

    // ══════════════════════════════════════════════════════
    //  IMAGE UPLOAD LOGIC
    // ══════════════════════════════════════════════════════
    const inputNew    = document.getElementById('imageInputNew');
    const inputSwap   = document.getElementById('imageInputSwap');
    const mainPreview = document.getElementById('mainPreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    const uploadBox   = document.getElementById('uploadBox');
    const editOverlay = document.getElementById('editOverlay');
    const thumbGrid   = document.getElementById('thumbGrid');
    const imgCount    = document.getElementById('imgCount');
    const MAX = 8;

    let images = [], selectedIdx = 0, swapTarget = null;

    function handleMainClick() {
        if (images.length > 0) { swapTarget = selectedIdx; inputSwap.click(); }
        else inputNew.click();
    }

    inputNew?.addEventListener('change', () => {
        const slots = MAX - images.length;
        Array.from(inputNew.files).slice(0, slots).forEach(file => {
            if (file.size <= 2 * 1024 * 1024) images.push({ url: URL.createObjectURL(file), file });
        });
        if (images.length) render(images.length - 1);
        inputNew.value = '';
        syncInput();
    });

    inputSwap?.addEventListener('change', () => {
        if (inputSwap.files.length && swapTarget !== null) {
            const file = inputSwap.files[0];
            if (file.size <= 2 * 1024 * 1024) { images[swapTarget] = { url: URL.createObjectURL(file), file }; render(swapTarget); syncInput(); }
        }
        swapTarget = null; inputSwap.value = '';
    });

    function removeImage(idx, e) {
        e.stopPropagation();
        images.splice(idx, 1);
        if (!images.length) { clearPreview(); syncInput(); return; }
        render(Math.min(selectedIdx, images.length - 1));
        syncInput();
    }

    function clearPreview() {
        images = []; selectedIdx = 0;
        mainPreview.classList.add('hidden'); mainPreview.src = '';
        placeholder.classList.remove('hidden');
        editOverlay.classList.add('hidden'); editOverlay.classList.remove('flex');
        uploadBox.classList.remove('border-solid'); uploadBox.classList.add('border-dashed');
        thumbGrid.innerHTML = ''; imgCount.classList.add('hidden');
        syncInput();
    }

    function render(idx) {
        selectedIdx = idx;
        mainPreview.src = images[idx].url;
        mainPreview.classList.remove('hidden');
        placeholder.classList.add('hidden');
        editOverlay.classList.remove('hidden'); editOverlay.classList.add('flex');
        uploadBox.classList.add('border-solid'); uploadBox.classList.remove('border-dashed');

        thumbGrid.innerHTML = '';
        images.forEach((img, i) => {
            const slot = document.createElement('div');
            slot.className = `relative aspect-square rounded-xl cursor-pointer transition-all ${i === idx ? 'ring-2 ring-indigo-500' : 'ring-1 ring-gray-200 dark:ring-gray-600'}`;
            const rmBtn = document.createElement('div');
            rmBtn.className = 'absolute -top-1.5 -right-1.5 z-10 w-[18px] h-[18px] bg-gray-900 hover:bg-red-500 border-2 border-white rounded-full flex items-center justify-center cursor-pointer transition-colors';
            rmBtn.innerHTML = `<svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`;
            rmBtn.onclick = (e) => removeImage(i, e);
            const inner = document.createElement('div');
            inner.className = 'thumb-inner bg-gray-100 dark:bg-gray-700';
            inner.innerHTML = `<img src="${img.url}" class="w-full h-full object-cover">`;
            inner.onclick = () => render(i);
            slot.appendChild(rmBtn); slot.appendChild(inner);
            thumbGrid.appendChild(slot);
        });

        if (images.length < MAX) {
            const add = document.createElement('div');
            add.className = 'aspect-square rounded-xl ring-1 ring-gray-200 dark:ring-gray-600 bg-white dark:bg-gray-700 flex items-center justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors';
            add.innerHTML = `<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>`;
            add.onclick = (e) => { e.stopPropagation(); inputNew.click(); };
            thumbGrid.appendChild(add);
        }

        imgCount.textContent = `${images.length}/8 images selected`;
        imgCount.classList.remove('hidden');
    }

    function syncInput() {
        const dt = new DataTransfer();
        images.forEach(img => { if (img.file) dt.items.add(img.file); });
        inputNew.files = dt.files;
    }

    // ══════════════════════════════════════════════════════
    //  EDIT PRODUCT
    // ══════════════════════════════════════════════════════
    function editProduct(id, name, description, categories_id, brand_id, cost_price, sale_price, quantity, status, imagesData = []) {
        openModal();
        document.getElementById('modalTitle').innerText = 'Edit Product';
        document.getElementById('productForm').action   = '/admin/products/' + id;
        document.getElementById('formMethod').value     = 'PUT';
        document.getElementById('productName').value    = name ?? '';

        const fields = {
            'textarea[name="description"]'  : description,
            'select[name="categories_id"]'  : categories_id,
            'select[name="brand_id"]'       : brand_id,
            'input[name="cost_price"]'      : cost_price,
            'input[name="sale_price"]'      : sale_price,
            'input[name="quantity"]'        : quantity,
            'select[name="status"]'         : status,
        };
        Object.entries(fields).forEach(([sel, val]) => {
            const el = document.querySelector(sel);
            if (el) el.value = val ?? '';
        });

        if (imagesData?.length) {
            images = imagesData.map(img => ({ url: typeof img === 'string' ? img : img.image_url, file: null }));
            render(0);
        } else {
            clearPreview();
        }
        syncInput();
    }
    </script>
    @endpush

@endsection