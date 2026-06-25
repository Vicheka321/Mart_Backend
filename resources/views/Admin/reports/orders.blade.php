@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Order Report
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Track order volume, order status, and fulfillment performance.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 hover:bg-black
                           dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200 text-white text-sm font-medium transition">
                    Export
                </button>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($totalOrders) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pending</p>
                <h3 class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400">
                    {{ number_format($pendingOrders) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Processing</p>
                <h3 class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ number_format($processingOrders) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Completed</p>
                <h3 class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ number_format($completedOrders) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Cancelled</p>
                <h3 class="mt-2 text-2xl font-bold text-red-600 dark:text-red-400">
                    {{ number_format($cancelledOrders) }}
                </h3>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm">

            {{-- Filters --}}
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('reports.orders') }}"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3">

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Start Date
                        </label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            End Date
                        </label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Status
                        </label>
                        <select name="status"
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="all">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Payment Method
                        </label>
                        <select name="payment_method"
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="all">All</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="khqr" {{ request('payment_method') == 'khqr' ? 'selected' : '' }}>KHQR</option>
                            <option value="aba" {{ request('payment_method') == 'aba' ? 'selected' : '' }}>ABA</option>
                            <option value="acleda" {{ request('payment_method') == 'acleda' ? 'selected' : '' }}>Acleda</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Address
                        </label>
                        <input type="text" name="address" value="{{ request('address') }}"
                            placeholder="Search address..."
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Order ID / customer / phone / coupon"
                            class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="xl:col-span-6 flex flex-wrap gap-2 pt-1">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                            Apply Filters
                        </button>

                        <a href="{{ route('reports.orders') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200
                                   hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Orders</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Pending</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Processing</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Completed</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Cancelled</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Gross</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Discount</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Net</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-5 py-4 font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ \Carbon\Carbon::parse($row->order_date)->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-gray-900 dark:text-white font-medium">
                                    {{ number_format($row->total_orders) }}
                                </td>

                                <td class="px-5 py-4 text-amber-600 dark:text-amber-400 font-medium">
                                    {{ number_format($row->pending_count) }}
                                </td>

                                <td class="px-5 py-4 text-blue-600 dark:text-blue-400 font-medium">
                                    {{ number_format($row->processing_count) }}
                                </td>

                                <td class="px-5 py-4 text-emerald-600 dark:text-emerald-400 font-medium">
                                    {{ number_format($row->completed_count) }}
                                </td>

                                <td class="px-5 py-4 text-red-600 dark:text-red-400 font-medium">
                                    {{ number_format($row->cancelled_count) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($row->gross_amount, 2) }}
                                </td>

                                <td class="px-5 py-4 text-red-500 dark:text-red-400 font-medium">
                                    ${{ number_format($row->total_discount, 2) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($row->net_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500">
                                    No order report data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if($orders->total())
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->firstItem() }}–{{ $orders->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->total() }}</span>
                        days
                    @else
                        No records found
                    @endif
                </p>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection