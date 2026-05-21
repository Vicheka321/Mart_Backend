@extends('layouts.app')

@section('content')
    <div class="toast-wrap" id="toastWrap"></div>

        <div class="space-y-4">

            {{-- ==================== TABLE CARD ==================== --}}
            <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

                {{-- CARD HEADER --}}
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700
                            flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <h2 class="text-sm font-medium text-gray-900 dark:text-white">Coupon List</h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">

                        {{-- STATUS FILTER --}}
                        <select id="statusFilter" onchange="filterTable()"
                            class="px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="expired">Expired</option>
                        </select>

                        {{-- TYPE FILTER --}}
                        <select id="typeFilter" onchange="filterTable()"
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
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input type="text" id="couponSearch" placeholder="Search coupons…" oninput="filterTable()"
                                autocomplete="off"
                                class="w-full sm:w-52 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                        </div>

                        {{-- ADD BUTTON --}}
                        <button type="button" onclick="openCreate()"
                            class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                   bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                                   shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            <span class="hidden sm:inline">Add Coupon</span>
                        </button>
                    </div>
                </div>

                {{-- ACTIVE FILTER BADGE --}}
                <div id="filterBadge" class="hidden px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                                              flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400" id="filterBadgeText"></p>
                    <button onclick="clearFilters()" class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">
                        Clear filters
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    @if($coupons->isEmpty())
                        <div class="py-16 text-center text-sm text-gray-400 dark:text-gray-500">
                            No coupons found.
                        </div>
                    @else
                        <table class="w-full text-sm" id="couponsTable">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-700/30">
                                    <th class="px-5 py-3 text-left cursor-pointer select-none" onclick="sortTable('code', this)">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Code
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left cursor-pointer select-none" onclick="sortTable('type', this)">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Type
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left cursor-pointer select-none" onclick="sortTable('value', this)">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Value
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left hidden md:table-cell">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Usage</span>
                                    </th>
                                    <th class="px-5 py-3 text-left hidden lg:table-cell cursor-pointer select-none" onclick="sortTable('expiry', this)">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Valid Until
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-left cursor-pointer select-none" onclick="sortTable('status', this)">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide inline-flex items-center gap-1">
                                            Status
                                            <svg class="w-3 h-3 sort-icon opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                        </span>
                                    </th>
                                    <th class="px-5 py-3 text-right">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Actions</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="couponsBody">
                                @foreach($coupons as $coupon)
                                    @php
                                        $isExpired = $coupon->end_date && $coupon->end_date->isPast();
                                        $isActive = $coupon->status === true || $coupon->status === 1 || $coupon->status === '1' || $coupon->status === 'active';
                                        $pct = ($coupon->usage_limit && $coupon->usage_limit > 0)
                                            ? min(100, ($coupon->used_count / $coupon->usage_limit) * 100)
                                            : 0;

                                        // Derive status label for JS filtering
                                        if ($isExpired)
                                            $statusLabel = 'expired';
                                        elseif ($isActive)
                                            $statusLabel = 'active';
                                        else
                                            $statusLabel = 'inactive';
                                    @endphp

                                    <tr class="coupon-row hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                                        data-code="{{ strtolower($coupon->code) }}"
                                        data-type="{{ $coupon->discount_type }}"
                                        data-value="{{ $coupon->discount_value }}"
                                        data-status="{{ $statusLabel }}"
                                        data-expiry="{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '9999-12-31' }}">

                                        {{-- CODE --}}
                                        <td class="px-5 py-3.5">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="code-badge inline-flex items-center gap-1.5 w-fit px-2.5 py-1 rounded-lg
                                                             bg-indigo-50 dark:bg-indigo-900/30
                                                             text-indigo-700 dark:text-indigo-300
                                                             text-xs font-mono font-bold tracking-widest
                                                             border border-indigo-100 dark:border-indigo-800">
                                                    {{ $coupon->code }}
                                                </span>
                                                @if($coupon->description)
                                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 pl-0.5 truncate max-w-[180px]">
                                                        {{ $coupon->description }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- TYPE --}}
                                        <td class="px-5 py-3.5">
                                            @if($coupon->discount_type === 'percent')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                             bg-violet-50 dark:bg-violet-900/20
                                                             text-violet-600 dark:text-violet-400
                                                             border border-violet-100 dark:border-violet-800">
                                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 14L15 8m-5.5-.5h.01m5.5 7h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Percentage
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                                                             bg-amber-50 dark:bg-amber-900/20
                                                             text-amber-600 dark:text-amber-400
                                                             border border-amber-100 dark:border-amber-800">
                                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33"/>
                                                    </svg>
                                                    Fixed
                                                </span>
                                            @endif
                                        </td>

                                        {{-- VALUE --}}
                                        <td class="px-5 py-3.5">
                                            <span class="font-bold text-sm text-gray-900 dark:text-white">
                                                @if($coupon->discount_type === 'percent')
                                                    {{ rtrim(rtrim(number_format($coupon->discount_value, 2, '.', ''), '0'), '.') }}
                                                    <span class="text-xs font-normal text-gray-400">%</span>
                                                @else
                                                    <span class="text-xs font-normal text-gray-400">$</span>{{ number_format($coupon->discount_value, 2) }}
                                                @endif
                                            </span>
                                            @if($coupon->min_order_amount > 0)
                                                <div class="text-[11px] text-gray-400 mt-0.5">
                                                    Min ${{ number_format($coupon->min_order_amount, 2) }}
                                                </div>
                                            @endif
                                            @if($coupon->max_discount)
                                                <div class="text-[11px] text-gray-400">
                                                    Cap ${{ number_format($coupon->max_discount, 2) }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- USAGE --}}
                                        <td class="px-5 py-3.5 hidden md:table-cell">
                                            <div class="w-28">
                                                <div class="flex justify-between text-[11px] text-gray-400 dark:text-gray-500 mb-1">
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">{{ $coupon->used_count }}</span>
                                                    <span>/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                                </div>
                                                <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500"
                                                        style="width: {{ $pct }}%"></div>
                                                </div>
                                                @if($coupon->usage_limit_per_user)
                                                    <div class="text-[11px] text-gray-400 mt-1">{{ $coupon->usage_limit_per_user }}x per user</div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- VALID UNTIL --}}
                                        <td class="px-5 py-3.5 hidden lg:table-cell">
                                            @if($coupon->end_date)
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $coupon->end_date->format('d M Y') }}
                                                    </span>
                                                    @if(!$isExpired)
                                                        <span class="text-[11px] text-gray-400">{{ $coupon->end_date->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-[11px] font-medium text-violet-500 dark:text-violet-400">No Expiry</span>
                                            @endif
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-5 py-3.5">
                                            @if($isExpired)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                             bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400
                                                             border border-red-100 dark:border-red-800">
                                                    Expired
                                                </span>
                                            @elseif($isActive)
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

                                        {{-- ACTIONS --}}
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" onclick='openEdit(
                                                    {{ $coupon->id }},
                                                    @json($coupon->code),
                                                    @json($coupon->discount_type),
                                                    {{ $coupon->discount_value }},
                                                    @json($coupon->description),
                                                    @json($coupon->start_date?->format("Y-m-d")),
                                                    @json($coupon->end_date?->format("Y-m-d")),
                                                    {{ $coupon->usage_limit ?? "null" }},
                                                    {{ $coupon->usage_limit_per_user ?? "null" }},
                                                    {{ $coupon->min_order_amount ??"null"}},
                                                    {{ $coupon->max_discount ?? "null" }},
                                                    {{ (int) ($coupon->status ? 1 : 0) }}
                                                )'
                                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                           bg-indigo-50 text-indigo-600 hover:bg-indigo-100
                                                           dark:bg-indigo-900/30 dark:text-indigo-400 transition-colors">
                                                    Edit
                                                </button>

                                                <form class="delete-form" action="{{ route('coupons.destroy', $coupon->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" data-name="{{ $coupon->code }}">
                                                    <button type="submit"
                                                        class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                               bg-red-50 text-red-500 hover:bg-red-100
                                                               dark:bg-red-900/20 dark:text-red-400 transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="searchEmpty" class="hidden py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                            No coupons match your filters.
                        </div>
                    @endif
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700
                            flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500" id="footerCount">
                        Showing <span id="visibleCount">{{ $coupons->count() }}</span>
                        of {{ $coupons->total() ?? $coupons->count() }} coupons
                        &nbsp;·&nbsp;
                        <span class="text-emerald-500 font-medium">{{ $activeCoupons }} active</span>
                        @if($expiredCoupons > 0)
                            &nbsp;·&nbsp;<span class="text-red-400 font-medium">{{ $expiredCoupons }} expired</span>
                        @endif
                        @if($inactiveCoupons > 0)
                            &nbsp;·&nbsp;<span class="text-gray-400 font-medium">{{ $inactiveCoupons }} inactive</span>
                        @endif
                    </p>
                    @if(method_exists($coupons, 'links'))
                        <div class="text-sm">{{ $coupons->links() }}</div>
                    @endif
                </div>
            </div>
        </div>


        {{-- ==================== CREATE / EDIT MODAL ==================== --}}
        <div id="couponModal"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                        rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- MODAL HEADER --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700 shrink-0">
                    <h2 id="modalTitle" class="text-base font-medium text-gray-900 dark:text-white">Add Coupon</h2>
                    <button onclick="closeModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- MODAL BODY (scrollable) --}}
                <div class="overflow-y-auto flex-1">
                    <form id="couponForm" action="{{ route('coupons.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        {{-- Code + Status --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Coupon Code <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="code" id="couponCode" placeholder="e.g. SAVE20" required
                                    class="w-full px-3 py-2 text-sm font-mono uppercase tracking-widest rounded-xl
                                           border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                                <select name="status" id="couponStatus"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                Description
                                <span class="font-normal text-gray-400">(optional)</span>
                            </label>
                            <input type="text" name="description" id="couponDescription" placeholder="e.g. Summer sale discount"
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        {{-- Discount Type + Value --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Discount Type <span class="text-red-400">*</span>
                                </label>
                                <select name="discount_type" id="couponType" onchange="updateValuePrefix()"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="percent">Percentage (%)</option>
                                    <option value="fixed">Fixed ($)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Value <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span id="valuePrefix"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">%</span>
                                    <input type="number" name="discount_value" id="couponValue"
                                        placeholder="0" min="0" step="0.01" required
                                        class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Min Order + Max Discount --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Min Order Amount
                                    <span class="font-normal text-gray-400">(optional)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">$</span>
                                    <input type="number" name="min_order_amount" id="couponMinOrder"
                                        placeholder="0.00" min="0" step="0.01"
                                        class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Max Discount Cap
                                    <span class="font-normal text-gray-400">(optional)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">$</span>
                                    <input type="number" name="max_discount" id="couponMaxDiscount"
                                        placeholder="e.g. 50.00" min="0" step="0.01"
                                        class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Validity Period --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                Validity Period
                                <span class="font-normal text-gray-400">(leave blank for no expiry)</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-400 mb-1">Start Date</label>
                                    <input type="date" name="start_date" id="couponStartDate"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-400 mb-1">End Date</label>
                                    <input type="date" name="end_date" id="couponEndDate"
                                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Usage Limit + Per User --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Usage Limit
                                    <span class="font-normal text-gray-400">(blank = unlimited)</span>
                                </label>
                                <input type="number" name="usage_limit" id="couponUsageLimit"
                                    placeholder="e.g. 100" min="1"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                                    Limit Per User
                                    <span class="font-normal text-gray-400">(blank = unlimited)</span>
                                </label>
                                <input type="number" name="usage_limit_per_user" id="couponUsageLimitPerUser"
                                    placeholder="e.g. 1" min="1"
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>

                        <button type="submit"
                            class="action-btn w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                                   rounded-xl transition-all shadow-md shadow-indigo-500/25">
                            <span id="submitLabel">Create Coupon</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="{{ asset('css/coupons.css') }}">

        @push('scripts')
            <script>
                // ══════════════════════════════════════════════════════
                //  MODAL HELPERS
                // ══════════════════════════════════════════════════════
                function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
                function hideModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

                document.getElementById('couponModal').addEventListener('click', function(e) {
                    if (e.target === this) hideModal('couponModal');
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') hideModal('couponModal');
                });

                function closeModal() { hideModal('couponModal'); }

                // ══════════════════════════════════════════════════════
                //  VALUE PREFIX (% or $)
                // ══════════════════════════════════════════════════════
                function updateValuePrefix() {
                    const type   = document.getElementById('couponType').value;
                    const prefix = document.getElementById('valuePrefix');
                    prefix.textContent = type === 'percent' ? '%' : '$';
                }

                // ══════════════════════════════════════════════════════
                //  OPEN CREATE
                // ══════════════════════════════════════════════════════
                function openCreate() {
                    document.getElementById('modalTitle').textContent   = 'Add Coupon';
                    document.getElementById('submitLabel').textContent  = 'Create Coupon';
                    document.getElementById('formMethod').value         = 'POST';
                    document.getElementById('couponForm').action        = '{{ route('coupons.store') }}';

                    document.getElementById('couponCode').value             = '';
                    document.getElementById('couponStatus').value           = '1';
                    document.getElementById('couponType').value             = 'percent';
                    document.getElementById('couponValue').value            = '';
                    document.getElementById('couponDescription').value      = '';
                    document.getElementById('couponStartDate').value        = '';
                    document.getElementById('couponEndDate').value          = '';
                    document.getElementById('couponUsageLimit').value       = '';
                    document.getElementById('couponUsageLimitPerUser').value = '';
                    document.getElementById('couponMinOrder').value         = '';
                    document.getElementById('couponMaxDiscount').value      = '';

                    updateValuePrefix();
                    showModal('couponModal');
                }

                // ══════════════════════════════════════════════════════
                //  OPEN EDIT
                // ══════════════════════════════════════════════════════
                function openEdit(id, code, type, value, description, startDate, endDate,
                                  usageLimit, usageLimitPerUser, minOrder, maxDiscount, status) {

                    document.getElementById('modalTitle').textContent   = 'Edit Coupon';
                    document.getElementById('submitLabel').textContent  = 'Save Changes';
                    document.getElementById('formMethod').value         = 'PUT';
                    document.getElementById('couponForm').action        = `{{ url('admin/coupons') }}/${id}`;

                    document.getElementById('couponCode').value             = code;
                    document.getElementById('couponStatus').value           = status;
                    document.getElementById('couponType').value             = type;
                    document.getElementById('couponValue').value            = value;
                    document.getElementById('couponDescription').value      = description ?? '';
                    document.getElementById('couponStartDate').value        = startDate ?? '';
                    document.getElementById('couponEndDate').value          = endDate ?? '';
                    document.getElementById('couponUsageLimit').value       = usageLimit ?? '';
                    document.getElementById('couponUsageLimitPerUser').value = usageLimitPerUser ?? '';
                    document.getElementById('couponMinOrder').value         = minOrder ?? '';
                    document.getElementById('couponMaxDiscount').value      = maxDiscount ?? '';

                    updateValuePrefix();
                    showModal('couponModal');
                }

                // ══════════════════════════════════════════════════════
                //  CLIENT-SIDE FILTER (search + type + status)
                // ══════════════════════════════════════════════════════
                function filterTable() {
                    const q      = (document.getElementById('couponSearch')?.value ?? '').toLowerCase().trim();
                    const type   = (document.getElementById('typeFilter')?.value ?? '').toLowerCase();
                    const status = (document.getElementById('statusFilter')?.value ?? '').toLowerCase();

                    const rows   = document.querySelectorAll('#couponsBody .coupon-row');
                    const empty  = document.getElementById('searchEmpty');
                    let vis = 0;

                    rows.forEach(row => {
                        const matchQ      = !q      || (row.dataset.code ?? '').includes(q);
                        const matchType   = !type   || (row.dataset.type ?? '') === type;
                        const matchStatus = !status || (row.dataset.status ?? '') === status;
                        const show = matchQ && matchType && matchStatus;
                        row.style.display = show ? '' : 'none';
                        if (show) vis++;
                    });

                    if (empty) empty.classList.toggle('hidden', vis > 0);

                    // Update visible count in footer
                    const countEl = document.getElementById('visibleCount');
                    if (countEl) countEl.textContent = vis;

                    // Show/hide filter badge
                    updateFilterBadge(q, type, status, vis);
                }

                function updateFilterBadge(q, type, status, vis) {
                    const badge     = document.getElementById('filterBadge');
                    const badgeText = document.getElementById('filterBadgeText');
                    const parts     = [];

                    if (q)      parts.push(`code contains "${q}"`);
                    if (type)   parts.push(`type: ${type === 'percent' ? 'Percentage' : 'Fixed'}`);
                    if (status) parts.push(`status: ${status.charAt(0).toUpperCase() + status.slice(1)}`);

                    if (parts.length > 0) {
                        badgeText.textContent = `Filtering by ${parts.join(' · ')} — ${vis} result${vis !== 1 ? 's' : ''}`;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    } else {
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                }

                function clearFilters() {
                    document.getElementById('couponSearch').value  = '';
                    document.getElementById('typeFilter').value    = '';
                    document.getElementById('statusFilter').value  = '';
                    filterTable();
                }

                // ══════════════════════════════════════════════════════
                //  CLIENT-SIDE SORT
                // ══════════════════════════════════════════════════════
                let sortCol = null, sortDir = 1;

                function sortTable(col, thEl) {
                    const tbody = document.getElementById('couponsBody');
                    if (!tbody) return;
                    const rows = Array.from(tbody.querySelectorAll('.coupon-row'));

                    if (sortCol === col) sortDir *= -1;
                    else { sortCol = col; sortDir = 1; }

                    document.querySelectorAll('#couponsTable th').forEach(t => t.classList.remove('sorted-asc', 'sorted-desc'));
                    thEl.classList.add(sortDir === 1 ? 'sorted-desc' : 'sorted-asc');

                    rows.sort((a, b) => {
                        if (col === 'value') {
                            return (parseFloat(a.dataset.value) - parseFloat(b.dataset.value)) * sortDir;
                        }
                        if (col === 'expiry') {
                            const av = a.dataset.expiry ?? '9999-12-31';
                            const bv = b.dataset.expiry ?? '9999-12-31';
                            return av.localeCompare(bv) * sortDir;
                        }
                        const av = (a.dataset[col] ?? '').toLowerCase();
                        const bv = (b.dataset[col] ?? '').toLowerCase();
                        return av.localeCompare(bv) * sortDir;
                    });

                    rows.forEach(r => tbody.appendChild(r));
                }

                // ══════════════════════════════════════════════════════
                //  DELETE CONFIRM
                // ══════════════════════════════════════════════════════
                document.addEventListener('DOMContentLoaded', () => {
                    updateValuePrefix();

                    document.querySelectorAll('.delete-form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const name = this.querySelector('[data-name]')?.dataset.name ?? 'this coupon';
                            Swal.fire({
                                title: 'Delete coupon?',
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
            </script>
        @endpush

@endsection