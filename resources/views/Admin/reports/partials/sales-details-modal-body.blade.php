{{-- ==================== SALES DETAILS PARTIAL ==================== --}}
{{-- Loaded via AJAX into #salesDetailsModalContent --}}

<div class="space-y-4">

    {{-- Sub-header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3">
        <div>
            <h4 class="text-base font-bold text-gray-900 dark:text-white">
                {{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}
            </h4>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                {{ $summary['total_orders'] }} orders
                · Gross ${{ number_format($summary['gross_sales'], 2) }}
                · Paid ${{ number_format($summary['paid_revenue'], 2) }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('reports.sales.details.export.csv', ['date' => $targetDate]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold
                      shadow-sm shadow-emerald-500/20 transition-colors">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16" />
                </svg>
                CSV
            </a>
            <a href="{{ route('reports.sales.details.export.pdf', ['date' => $targetDate]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold
                      shadow-sm shadow-rose-500/20 transition-colors">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16" />
                </svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Summary mini cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @php
            $detailKpis = [
                ['label' => 'Orders', 'value' => number_format($summary['total_orders']), 'from' => 'from-indigo-500', 'to' => 'to-violet-600', 'bg' => 'from-indigo-50 to-violet-100 dark:from-indigo-900/20 dark:to-violet-900/20'],
                ['label' => 'Gross Sales', 'value' => '$' . number_format($summary['gross_sales'], 2), 'from' => 'from-amber-500', 'to' => 'to-yellow-600', 'bg' => 'from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20'],
                ['label' => 'Discount', 'value' => '$' . number_format($summary['discount'], 2), 'from' => 'from-rose-500', 'to' => 'to-pink-600', 'bg' => 'from-rose-50 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20'],
                ['label' => 'Paid Revenue', 'value' => '$' . number_format($summary['paid_revenue'], 2), 'from' => 'from-emerald-500', 'to' => 'to-green-600', 'bg' => 'from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20'],
            ];
        @endphp

        @foreach($detailKpis as $dk)
            <div class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 p-3">
                <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full bg-gradient-to-br {{ $dk['bg'] }}"></div>
                <div class="relative">
                    <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ $dk['label'] }}</p>
                    <p
                        class="text-xl font-bold mt-0.5 bg-gradient-to-r {{ $dk['from'] }} {{ $dk['to'] }} bg-clip-text text-transparent">
                        {{ $dk['value'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Orders table --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/40">
                    <tr
                        class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <th class="px-5 py-3">Order</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Payment</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Address</th>
                        <th class="px-5 py-3">Time</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($orders as $order)
                        @php
                            $statusBadge = match ($order->status) {
                                'completed' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                'processing' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                'pending' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                'cancelled' => 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400',
                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                            };
                            $payMethod = strtolower($order->payment_method ?? '');
                            $methodBadge = match ($payMethod) {
                                'khqr' => 'bg-purple-100 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400',
                                'aba' => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                'wing' => 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400',
                                'cash' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                default => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                            };
                            $payStatus = strtolower(optional($order->payment)->payment_status ?? 'unpaid');
                            $payStatusBadge = match ($payStatus) {
                                'paid' => 'text-emerald-600 dark:text-emerald-400',
                                'pending' => 'text-amber-600 dark:text-amber-400',
                                'failed' => 'text-red-600 dark:text-red-400',
                                default => 'text-gray-400 dark:text-gray-500',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors align-top">

                            {{-- Order ID --}}
                            <td class="px-5 py-3.5">
                                <span
                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#{{ $order->id }}</span>
                            </td>

                            {{-- Customer --}}
                            <td class="px-5 py-3.5">
                                @php
                                    $name = optional($order->user)->full_name ?? '—';
                                    $initials = strtoupper(substr($name, 0, 1));
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600
                                                    flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $name }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate">
                                            {{ optional($order->user)->phone ?? optional($order->user)->email ?? '—' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Order Status --}}
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusBadge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            {{-- Payment --}}
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold {{ $methodBadge }}">
                                    {{ strtoupper($order->payment_method ?? 'N/A') }}
                                </span>
                                <p class="text-[10px] mt-0.5 {{ $payStatusBadge }} font-medium capitalize">{{ $payStatus }}
                                </p>
                            </td>

                            {{-- Total --}}
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    ${{ number_format($order->total_amount, 2) }}
                                </span>
                            </td>

                            {{-- Address --}}
                            <td class="px-5 py-3.5 max-w-xs">
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                    {{ $order->delivery_address ?? '—' }}
                                </p>
                            </td>

                            {{-- Time --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ optional($order->created_at)->format('h:i A') }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                    {{ optional($order->created_at)->format('d M Y') }}
                                </p>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">No orders found for this date.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>