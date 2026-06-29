@extends('layouts.app')

@section('content')
    @php
        $totalPromos = $promotions->total();
        $activePromos = $promotions->getCollection()->where('status', 1)->count();
        $inactivePromos = $promotions->getCollection()->where('status', 0)->count();
        $totalProducts = $promotions->getCollection()->sum('products_count');
        $activePct = $totalPromos > 0 ? round(($activePromos / $totalPromos) * 100) : 0;
        $inactivePct = $totalPromos > 0 ? round(($inactivePromos / $totalPromos) * 100) : 0;
    @endphp
    <div class="toast-wrap" id="toastWrap"></div>

    <div class="space-y-4">

        {{-- ==================== TABLE CARD ==================== --}}
            <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

                {{-- CARD HEADER --}}
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                            flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Promotion List</h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                        {{-- STATUS FILTER PILLS --}}
                        {{-- <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                    bg-gray-50 dark:bg-gray-700 p-1 gap-1">
                            @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                                <a href="{{ request()->fullUrlWithQuery(['status' => $value, 'page' => 1]) }}" 
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                {{ ($statusFilter ?? 'all') === $value
                                ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                                </a>
                            @endforeach
                        </div> --}}

                        {{-- DISCOUNT TYPE FILTER --}}
                        <select id="typeFilter" onchange="filterPromoTable()"
                            class="px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <option value="">All Types</option>
                            <option value="percent">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>

                        {{-- SEARCH --}}
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" />
                            </svg>
                            <input type="text" id="promoSearch" placeholder="Search promotions…" oninput="filterPromoTable()"
                                autocomplete="off"
                                class="w-full sm:w-52 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                        </div>

                        {{-- ADD --}}
                        <button type="button" onclick="openCreateModal()"
                            class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                   bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                                   shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            <span class="hidden sm:inline">Add Promotion</span>
                        </button>
                    </div>
                </div>

                {{-- ACTIVE FILTER BADGE --}}
                @if(($statusFilter ?? 'all') !== 'all')
                    <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                                flex items-center justify-between">
                        <p class="text-xs text-indigo-600 dark:text-indigo-400">
                            Filtering by: <span class="font-semibold capitalize">{{ $statusFilter }}</span>
                            &mdash; {{ number_format($promotions->count()) }} {{ Str::plural('result', $promotions->count()) }}
                        </p>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}" 
                            class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">Clear filter</a>
                    </div>
                @endif

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    @if($promotions->isEmpty())
                        <div class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                            No promotions found. Create your first one!
                        </div>
                    @else
                        <table class="w-full text-sm" id="promosTable">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-700/30">
                                    <th class="px-5 py-3 text-left">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Promotion</span>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Discount</span>
                                    </th>
                                    <th class="px-5 py-3 text-left col-period">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Period</span>
                                    </th>
                                    <th class="px-5 py-3 text-left col-products">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Products</span>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</span>
                                    </th>
                                    <th class="px-5 py-3 text-right">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="promosBody">
                                @forelse($promotions as $promotion)
                                    <tr class="promo-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors"
                                        data-name="{{ strtolower($promotion->name) }}"
                                        data-type="{{ $promotion->discount_type }}"
                                        data-status="{{ $promotion->status ? 'active' : 'inactive' }}">

                                        {{-- Promotion --}}
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                @if($promotion->image_url)
                                                    <img src="{{ $promotion->image_url }}" alt="{{ $promotion->name }}"
                                                         class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0
                                                                transition-transform duration-300 hover:scale-110">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-violet-100
                                                                dark:from-indigo-900/30 dark:to-violet-900/30
                                                                flex items-center justify-center flex-shrink-0 text-base">
                                                        🎁
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-sm text-gray-900 dark:text-white truncate">
                                                        {{ $promotion->name }}
                                                    </p>
                                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 sm:hidden">
                                                        {{ \Carbon\Carbon::parse($promotion->start_date)->format('d M') }}
                                                        → {{ \Carbon\Carbon::parse($promotion->end_date)->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Discount --}}
                                        <td class="px-5 py-3.5">
                                            @if($promotion->discount_type === 'percent')
                                                <span class="discount-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                                                             bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400
                                                             border border-indigo-100 dark:border-indigo-800">
                                                    <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 14L15 8m-5.5-.5h.01m5.5 7h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ number_format($promotion->discount_value, 0) }}% OFF
                                                </span>
                                            @else
                                                <span class="discount-badge inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                                                             bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400
                                                             border border-amber-100 dark:border-amber-800">
                                                    <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33" />
                                                    </svg>
                                                    ${{ number_format($promotion->discount_value, 2) }} OFF
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Period --}}
                                        <td class="px-5 py-3.5 col-period">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    {{ \Carbon\Carbon::parse($promotion->start_date)->format('d M Y') }}
                                                </span>
                                                <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                                    → {{ \Carbon\Carbon::parse($promotion->end_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Products count --}}
                                        <td class="px-5 py-3.5 col-products">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                                         bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                                                         border border-gray-200 dark:border-gray-600">
                                                <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                {{ $promotion->products_count }} {{ Str::plural('product', $promotion->products_count) }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-5 py-3.5">
                                            @if($promotion->status)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                                             bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                                                             border border-emerald-100 dark:border-emerald-800">
                                                    <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
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

                                        {{-- Actions --}}
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center justify-end gap-1.5">
                                                {{-- Products --}}
                                                <button type="button"
                                                    onclick="openProductsModal(
                                                        {{ $promotion->id }},
                                                        @js($promotion->name),
                                                        @js($promotion->products->pluck('id'))
                                                    )"
                                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                           bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400
                                                           hover:bg-violet-100 dark:hover:bg-violet-900/40 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Products</span>
                                                </button>

                                                {{-- Edit --}}
                                                <button type="button" onclick="openEditModal(
                                                            {{ $promotion->id }},
                                                            @js($promotion->name),
                                                            @js($promotion->discount_type),
                                                            {{ $promotion->discount_value }},
                                                            '{{ $promotion->start_date }}',
                                                            '{{ $promotion->end_date }}',
                                                            {{ $promotion->status ? 'true' : 'false' }},
                                                    
                                                        )"
                                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                           bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                                           hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Edit</span>
                                                </button>

                                                {{-- Delete --}}
                                                <form id="delete-form-{{ $promotion->id }}"
                                                    action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete({{ $promotion->id }}, @js($promotion->name))"
                                                        class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                               bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400
                                                               hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                                            No promotions found. Create your first one!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No promotions match your search.
                        </div>
                    @endif
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700
                            flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Showing {{ number_format($promotions->count()) }} {{ Str::plural('promotion', $promotions->count()) }}
                        &nbsp;·&nbsp; <span class="text-emerald-500 font-medium">{{ $activePromos }} active</span>
                        @if($inactivePromos > 0)
                            &nbsp;·&nbsp; <span class="text-gray-400 font-medium">{{ $inactivePromos }} inactive</span>
                        @endif
                        &nbsp;·&nbsp; <span class="text-amber-500 font-medium">{{ $totalProducts }} products</span>
                    </p>
                    <div class="text-sm">{{ $promotions->links() }}</div>
                </div>
            </div>
    </div>


        {{-- ==================== CREATE MODAL ==================== --}}
        <div id="createPromotionModal"
            class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="modal-inner w-full max-w-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        rounded-2xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Add Promotion</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Create a new product promotion</p>
                    </div>
                    <button type="button" onclick="closeModal('createPromotionModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700
                               text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Promotion Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Summer Sale 2026"
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400 transition-all">
                            </div>

                            {{-- ── STATUS SELECT (replaces checkbox) ── --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                                <div class="relative">
                                    <select name="status" id="create_status" onchange="updateStatusStyle('create_status', 'create_status_icon')"
                                        class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               pl-9 pr-4 py-2.5 appearance-none
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    {{-- Status dot icon inside select --}}
                                    <span id="create_status_icon"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-emerald-500 transition-colors"></span>
                                    {{-- Chevron --}}
                                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Discount Type</label>
                                <select name="discount_type" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed"   {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Discount Value</label>
                                <input type="number" step="0.01" min="0" name="discount_value"
                                    value="{{ old('discount_value') }}" required placeholder="10"
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                        </div>

                        @if($errors->any())
                            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20
                                        text-red-700 dark:text-red-400 text-xs">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" onclick="closeModal('createPromotionModal')"
                                class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                       text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                class="action-btn px-5 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700
                                       text-white rounded-xl shadow-md shadow-indigo-500/25 transition-all">
                                Save Promotion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ==================== EDIT MODAL ==================== --}}
        <div id="editPromotionModal"
            class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="modal-inner w-full max-w-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        rounded-2xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Edit Promotion</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Update promotion details</p>
                    </div>
                    <button type="button" onclick="closeModal('editPromotionModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700
                               text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <form id="editPromotionForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Promotion Name</label>
                                <input type="text" name="name" id="edit_name" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                                <div class="relative">
                                    <select name="status" id="edit_status" onchange="updateStatusStyle('edit_status', 'edit_status_icon')"
                                        class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               pl-9 pr-4 py-2.5 appearance-none
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <span id="edit_status_icon"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-emerald-500 transition-colors"></span>
                                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Discount Type</label>
                                <select name="discount_type" id="edit_discount_type" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="percent">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Discount Value</label>
                                <input type="number" step="0.01" min="0" name="discount_value" id="edit_discount_value" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Start Date</label>
                                <input type="date" name="start_date" id="edit_start_date" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">End Date</label>
                                <input type="date" name="end_date" id="edit_end_date" required
                                    class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2.5
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            {{-- ── STATUS SELECT (replaces checkbox) ── --}}


                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" onclick="closeModal('editPromotionModal')"
                                class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                       text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                class="action-btn px-5 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700
                                       text-white rounded-xl shadow-md shadow-indigo-500/25 transition-all">
                                Update Promotion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ==================== PRODUCTS MODAL ==================== --}}
        <div id="productsModal"
            class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="modal-inner w-full max-w-5xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Add Products to Promotion</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                            Promotion: <span id="productsModalPromotionName" class="font-semibold text-indigo-600 dark:text-indigo-400"></span>
                        </p>
                    </div>
                    <button type="button" onclick="closeModal('productsModal')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700
                               text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="productsForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    {{-- Filters --}}
                    <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 flex-shrink-0 space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" />
                                </svg>
                                <input type="text" id="productSearch" placeholder="Search products…"
                                    onkeyup="filterPromoProducts()"
                                    class="w-full text-sm pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <select id="categoryFilter" onchange="filterPromoProducts()"
                                class="text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select id="brandFilter" onchange="filterPromoProducts()"
                                class="text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">All Brands</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ strtolower($brand->name) }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-gray-500 dark:text-gray-400">
                            <input type="checkbox" onchange="toggleAllProducts(this.checked)"
                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                            Select all visible
                        </label>
                    </div>

                    {{-- Products Grid --}}
                    <div class="p-5 overflow-y-auto flex-1">
                        <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                            @foreach($allProducts ?? [] as $product)
                                <label class="product-card relative cursor-pointer rounded-xl border border-gray-200 dark:border-gray-700
                                              hover:border-indigo-400 dark:hover:border-indigo-500 overflow-hidden
                                              bg-white dark:bg-gray-800"
                                    data-name="{{ strtolower($product->name) }}"
                                    data-category="{{ strtolower($product->category->name ?? '') }}"
                                    data-brand="{{ strtolower($product->brand->name ?? '') }}">

                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                        class="product-checkbox absolute top-2 left-2 z-10 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                                    @php $productImage = optional($product->image->first())->image_url; @endphp
                                    @if($productImage)
                                        <img src="{{ $productImage }}" alt="{{ $product->name }}"
                                            class="w-full aspect-square object-cover">
                                    @else
                                        <div class="w-full aspect-square bg-gradient-to-br from-gray-100 to-gray-200
                                                    dark:from-gray-700 dark:to-gray-600
                                                    flex items-center justify-center text-2xl">📦</div>
                                    @endif

                                    <div class="p-2">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-white truncate leading-tight">
                                            {{ $product->name }}
                                        </p>
                                        <p class="text-[11px] text-indigo-600 dark:text-indigo-400 font-medium mt-0.5">
                                            ${{ number_format($product->sale_price ?? $product->price ?? 0, 2) }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex-shrink-0">
                        <button type="button" onclick="closeModal('productsModal')"
                            class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600
                                   text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="action-btn px-5 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700
                                   text-white rounded-xl shadow-md shadow-indigo-500/25 transition-all">
                            Save Products
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <link rel="stylesheet" href="{{ asset('css/promotions.css') }}">

        @push('scripts')
            <script>
            // ══════════════════════════════════════════════════════
            //  STATUS DOT — updates the coloured dot beside the select
            // ══════════════════════════════════════════════════════
            function updateStatusStyle(selectId, iconId) {
                const sel  = document.getElementById(selectId);
                const icon = document.getElementById(iconId);
                if (!sel || !icon) return;

                if (sel.value === '1') {
                    icon.className = icon.className.replace(/bg-\w+-\d+/, '');
                    icon.classList.add('bg-emerald-500');
                } else {
                    icon.className = icon.className.replace(/bg-\w+-\d+/, '');
                    icon.classList.add('bg-gray-400');
                }
            }

            // ── Init dots on page load ──
            document.addEventListener('DOMContentLoaded', () => {
                updateStatusStyle('create_status', 'create_status_icon');
            });

            // ══════════════════════════════════════════════════════
            //  ANIMATED NUMBER COUNTER
            // ══════════════════════════════════════════════════════
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    document.querySelectorAll('[data-count]').forEach(el => {
                        const target = parseInt(el.dataset.count, 10) || 0;
                        const dur = 1000;
                        const start = performance.now();
                        function ease(t) { return 1 - Math.pow(1 - t, 3); }
                        (function tick(now) {
                            const p = Math.min((now - start) / dur, 1);
                            el.textContent = Math.round(ease(p) * target).toLocaleString();
                            if (p < 1) requestAnimationFrame(tick);
                            else { el.textContent = target.toLocaleString(); el.classList.add('count-done'); }
                        })(performance.now());
                    });
                }, 300);

                @if($errors->any())
                    openCreateModal();
                @endif
            });

            // ══════════════════════════════════════════════════════
            //  MODAL HELPERS
            // ══════════════════════════════════════════════════════
            function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
            function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

            ['createPromotionModal', 'editPromotionModal', 'productsModal'].forEach(id => {
                document.getElementById(id)?.addEventListener('click', function (e) {
                    if (e.target === this) closeModal(id);
                });
            });

            function openCreateModal() {
                updateStatusStyle('create_status', 'create_status_icon');
                showModal('createPromotionModal');
            }

            // ══════════════════════════════════════════════════════
            //  EDIT MODAL
            // ══════════════════════════════════════════════════════
            function openEditModal(id, name, discountType, discountValue, startDate, endDate, status) {
                document.getElementById('editPromotionForm').action = `/admin/promotions/${id}`;
                document.getElementById('edit_name').value          = name;
                document.getElementById('edit_discount_type').value  = discountType;
                document.getElementById('edit_discount_value').value = discountValue;
                document.getElementById('edit_start_date').value    = startDate;
                document.getElementById('edit_end_date').value      = endDate;

                // Set the status select (1 = Active, 0 = Inactive)
                const statusSel = document.getElementById('edit_status');
                statusSel.value = status ? '1' : '0';
                updateStatusStyle('edit_status', 'edit_status_icon');
                showModal('editPromotionModal');
            }

            // ══════════════════════════════════════════════════════
            //  PRODUCTS MODAL
            // ══════════════════════════════════════════════════════
            function openProductsModal(
                promotionId,
                promotionName,
                selectedProducts = []
            ) {

                document.getElementById('productsForm').action =
                    `/admin/promotions/${promotionId}/products`;

                document.getElementById(
                    'productsModalPromotionName'
                ).textContent = promotionName;

                document.getElementById('productSearch').value = '';
                document.getElementById('categoryFilter').value = '';
                document.getElementById('brandFilter').value = '';

                // Clear Checkbox
                document.querySelectorAll('.product-checkbox')
                    .forEach(cb => cb.checked = false);

                // Tick Product ដែលមានក្នុង Promotion
                selectedProducts.forEach(id => {

                    const checkbox = document.querySelector(
                        `.product-checkbox[value="${id}"]`
                    );

                    if (checkbox) {
                        checkbox.checked = true;
                    }

                });

                filterPromoProducts();

                showModal('productsModal');
            }

            function filterPromoProducts() {
                const q        = document.getElementById('productSearch').value.toLowerCase();
                const category = document.getElementById('categoryFilter').value;
                const brand    = document.getElementById('brandFilter').value;
                document.querySelectorAll('.product-card').forEach(card => {
                    const ok = (!q || card.dataset.name.includes(q))
                        && (!category || card.dataset.category === category)
                        && (!brand    || card.dataset.brand    === brand);
                    card.classList.toggle('hidden', !ok);
                });
            }

            function toggleAllProducts(checked) {
                document.querySelectorAll('.product-card:not(.hidden) .product-checkbox')
                    .forEach(cb => cb.checked = checked);
            }

            // ══════════════════════════════════════════════════════
            //  TABLE FILTER
            // ══════════════════════════════════════════════════════
            function filterPromoTable() {
                const q    = (document.getElementById('promoSearch')?.value ?? '').toLowerCase().trim();
                const type = (document.getElementById('typeFilter')?.value  ?? '').toLowerCase();
                const rows = document.querySelectorAll('#promosBody .promo-row');
                const empty = document.getElementById('searchEmpty');
                let vis = 0;
                rows.forEach(row => {
                    const matchQ    = !q    || (row.dataset.name ?? '').includes(q);
                    const matchType = !type || (row.dataset.type ?? '') === type;
                    const show = matchQ && matchType;
                    row.style.display = show ? '' : 'none';
                    if (show) vis++;
                });
                if (empty) empty.classList.toggle('hidden', vis !== 0);
            }

            // ══════════════════════════════════════════════════════
            //  DELETE CONFIRM
            // ══════════════════════════════════════════════════════
            function confirmDelete(id, name) {
                Swal.fire({
                    title: 'Delete Promotion?',
                    text: `"${name}" will be permanently removed.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, delete it',
                    reverseButtons: true,
                }).then(result => {
                    if (result.isConfirmed) document.getElementById(`delete-form-${id}`).submit();
                });
            }
            </script>
        @endpush

@endsection