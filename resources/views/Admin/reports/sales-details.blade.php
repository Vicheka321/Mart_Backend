@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('reports.sales') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300
                               hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        ← Back
                    </a>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Sales Details
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Orders for
                    <span class="font-semibold text-gray-700 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="openExportModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 hover:bg-black
                           dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200 text-white text-sm font-medium transition">
                    Export
                </button>
            </div>
        </div>

        {{-- KPI --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($totalOrders) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                <h3 class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                    ${{ number_format($totalRevenue, 2) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Coupon Discount</p>
                <h3 class="mt-2 text-2xl font-bold text-amber-600 dark:text-amber-400">
                    ${{ number_format($couponDiscount, 2) }}
                </h3>
            </div>

            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Promotion Discount</p>
                <h3 class="mt-2 text-2xl font-bold text-orange-600 dark:text-orange-400">
                    ${{ number_format($promotionDiscount, 2) }}
                </h3>
            </div>
        </div>

        {{-- Card --}}
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm">

            {{-- Filters --}}
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('reports.sales.details', $date) }}"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="all">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment
                            Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="all">All</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="khqr" {{ request('payment_method') == 'khqr' ? 'selected' : '' }}>KHQR</option>
                            <option value="aba" {{ request('payment_method') == 'aba' ? 'selected' : '' }}>ABA</option>
                            <option value="acleda" {{ request('payment_method') == 'acleda' ? 'selected' : '' }}>Acleda
                            </option>
                        </select>
                    </div>

                    <div class="xl:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Order ID / customer / phone / address / coupon" class="w-full px-3 py-2 rounded-xl text-sm border border-gray-200 dark:border-gray-600
                                           bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="xl:col-span-4 flex flex-wrap gap-2 pt-1">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                            Apply Filters
                        </button>

                        <a href="{{ route('reports.sales.details', $date) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600
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
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Order</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Phone</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Address</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Payment</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Total</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Discount</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Net</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Time</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                            @php
                                $discount = ($order->coupon_discount ?? 0) + ($order->promotion_discount ?? 0);
                                $net = ($order->total_amount ?? 0) - $discount;
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-5 py-4 font-semibold text-indigo-600 dark:text-indigo-400">
                                    #{{ $order->id }}
                                </td>

                                <td class="px-5 py-4 text-gray-900 dark:text-white">
                                    {{ $order->user->full_name ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                    {{ $order->user->phone ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4 text-gray-700 dark:text-gray-300 max-w-[240px]">
                                    {{ $order->delivery_address ?? 'N/A' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-gray-900 dark:text-white font-medium">
                                        {{ strtoupper($order->payment->payment_method ?? 'N/A') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($order->payment->payment_status ?? 'N/A') }}
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $statusColor = match ($order->status) {
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                            'processing' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                    @endphp

                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 font-semibold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>

                                <td class="px-5 py-4 text-red-500 dark:text-red-400">
                                    ${{ number_format($discount, 2) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($net, 2) }}
                                </td>

                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $order->created_at?->format('d M Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-12 text-center text-gray-400 dark:text-gray-500">
                                    No orders found for this date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div
                class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if($orders->total())
                        Showing
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->firstItem() }}–{{ $orders->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $orders->total() }}</span>
                        orders
                    @else
                        No records found
                    @endif
                </p>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

        {{-- Export Modal --}}
        <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
            <div
                class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Export Sales Details
                    </h3>
                    <button onclick="closeExportModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">
                        ×
                    </button>
                </div>

                <div class="p-5 space-y-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Export current sales detail data with current filters.
                    </p>

                    <a href="{{ route('reports.sales.details.export.csv', $date) }}?{{ http_build_query(request()->query()) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition">
                        Download CSV
                    </a>

                    <a href="{{ route('reports.sales.details.export.pdf', $date) }}?{{ http_build_query(request()->query()) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium transition">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <script>
            function openExportModal() {
                const modal = document.getElementById('exportModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeExportModal() {
                const modal = document.getElementById('exportModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.addEventListener('click', function (e) {
                const modal = document.getElementById('exportModal');
                if (e.target === modal) {
                    closeExportModal();
                }
            });
        </script>
    </div>

@endsection