@extends('layouts.app')

@section('content')

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .page-header  { animation: fadeSlideUp .4s ease both; }
        .filter-card  { animation: fadeSlideUp .45s .06s ease both; }
        .table-card   { animation: fadeSlideUp .5s .14s ease both; }

        #salesTableBody tr { animation: rowSlideIn .35s ease both; }
        #salesTableBody tr:nth-child(1)  { animation-delay: .20s; }
        #salesTableBody tr:nth-child(2)  { animation-delay: .25s; }
        #salesTableBody tr:nth-child(3)  { animation-delay: .30s; }
        #salesTableBody tr:nth-child(4)  { animation-delay: .35s; }
        #salesTableBody tr:nth-child(5)  { animation-delay: .40s; }
        #salesTableBody tr:nth-child(6)  { animation-delay: .45s; }
        #salesTableBody tr:nth-child(7)  { animation-delay: .50s; }
        #salesTableBody tr:nth-child(8)  { animation-delay: .55s; }
        #salesTableBody tr:nth-child(9)  { animation-delay: .60s; }
        #salesTableBody tr:nth-child(10) { animation-delay: .65s; }

        .btn-sm { transition: transform .14s ease, box-shadow .14s ease; }
        .btn-sm:hover  { transform: translateY(-1px); }
        .btn-sm:active { transform: translateY(0); }
    </style>

    <div class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="page-header flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Sales Report</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">View and export sales transactions.</p>
            </div>

            <div class="flex gap-2">
                <a href="#"
                   class="btn-sm inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                          border border-emerald-200 dark:border-emerald-500/30
                          bg-emerald-50 dark:bg-emerald-500/10
                          text-emerald-600 dark:text-emerald-400
                          hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    Export Excel
                </a>
                <a href="#"
                   class="btn-sm inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                          border border-red-200 dark:border-red-500/30
                          bg-red-50 dark:bg-red-500/10
                          text-red-600 dark:text-red-400
                          hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        {{-- ==================== FILTER BAR ==================== --}}
        <div class="filter-card bg-white dark:bg-gray-800
                    border border-gray-100 dark:border-gray-700
                    rounded-2xl shadow-sm p-4">
            <form method="GET">
                <div class="flex flex-wrap gap-2 items-center">

                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="px-3 py-2 text-sm rounded-xl
                               border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                    <span class="text-xs text-gray-400">→</span>

                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="px-3 py-2 text-sm rounded-xl
                               border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                    <select name="status"
                        class="px-3 py-2 text-sm rounded-xl
                               border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">All Status</option>
                        <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed"  {{ request('status') === 'completed'  ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search…"
                        class="px-3 py-2 text-sm rounded-xl
                               border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-700
                               text-gray-700 dark:text-gray-200
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition
                               min-w-[160px]">

                    <button type="submit"
                        class="btn-sm px-4 py-2 rounded-xl
                               bg-indigo-600 hover:bg-indigo-700
                               text-white text-sm font-medium shadow-sm transition">
                        Filter
                    </button>

                    @if(request()->hasAny(['start_date','end_date','status','search']))
                        <a href="{{ request()->url() }}"
                           class="px-3 py-2 text-sm text-gray-400 dark:text-gray-500
                                  hover:text-gray-600 dark:hover:text-gray-300 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800
                    border border-gray-100 dark:border-gray-700
                    rounded-2xl overflow-hidden shadow-sm">

            {{-- Active filter badge --}}
            @if(request()->hasAny(['start_date','end_date','status','search']))
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10
                            border-b border-indigo-100 dark:border-indigo-500/20
                            flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtered results &mdash;
                        <span class="font-semibold">{{ $sales->total() }} {{ Str::plural('row', $sales->total()) }}</span>
                    </p>
                    <a href="{{ request()->url() }}"
                       class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">
                        Clear filter
                    </a>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider
                                   text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Orders</th>
                            <th class="px-6 py-3">Revenue</th>
                            <th class="px-6 py-3">Discount</th>
                        </tr>
                    </thead>

                    <tbody id="salesTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($sales as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- Date --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-900/30
                                                    flex items-center justify-center flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <path d="M16 2v4M8 2v4M3 10h18"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($row->sale_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Orders --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                 bg-blue-50 dark:bg-blue-500/10
                                                 text-blue-600 dark:text-blue-400
                                                 border border-blue-100 dark:border-blue-500/20">
                                        {{ number_format($row->total_orders) }}
                                    </span>
                                </td>

                                {{-- Revenue --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                        ${{ number_format($row->revenue, 2) }}
                                    </span>
                                </td>

                                {{-- Discount --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($row->discount, 2) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">

                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($sales->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $sales->firstItem() }}–{{ $sales->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($sales->total()) }}</span>
                        results
                    @else
                        No results
                    @endif
                </p>

                @if($sales->hasPages())
                    <nav class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($sales->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $sales->previousPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700
                                      hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($sales->getUrlRange(max(1, $sales->currentPage() - 2), min($sales->lastPage(), $sales->currentPage() + 2)) as $page => $url)
                            @if($page == $sales->currentPage())
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                             bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                          text-sm font-medium text-gray-500 dark:text-gray-400
                                          hover:bg-gray-100 dark:hover:bg-gray-700
                                          hover:text-gray-900 dark:hover:text-white transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($sales->hasMorePages())
                            <a href="{{ $sales->nextPageUrl() }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-gray-500 dark:text-gray-400
                                      hover:bg-gray-100 dark:hover:bg-gray-700
                                      hover:text-gray-900 dark:hover:text-white transition-colors">
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

@endsection