{{-- ==================== ORDER DETAILS PARTIAL ==================== --}}
{{-- Loaded via AJAX into the order modal --}}

@php
    $payment = optional($order->payment);
    $itemCount = $order->orderItems->sum('qty');
    $subtotal = $order->orderItems->sum(fn($i) => $i->qty * $i->price);
    $initials = strtoupper(substr($order->user->full_name ?? 'U', 0, 1));

    $statusConfig = [
        'completed' => ['from' => 'from-emerald-500', 'to' => 'to-green-600', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10'],
        'processing' => ['from' => 'from-blue-500', 'to' => 'to-indigo-600', 'dot' => 'bg-blue-500', 'text' => 'text-blue-600 dark:text-blue-400', 'bg' => 'bg-blue-50 dark:bg-blue-500/10'],
        'pending' => ['from' => 'from-amber-500', 'to' => 'to-yellow-600', 'dot' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400', 'bg' => 'bg-amber-50 dark:bg-amber-500/10'],
        'cancelled' => ['from' => 'from-rose-500', 'to' => 'to-red-600', 'dot' => 'bg-rose-500', 'text' => 'text-rose-600 dark:text-rose-400', 'bg' => 'bg-rose-50 dark:bg-rose-500/10'],
    ];

    $payStatusConfig = [
        'paid' => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'label' => 'Paid'],
        'pending' => ['dot' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400', 'bg' => 'bg-amber-50 dark:bg-amber-500/10', 'label' => 'Pending'],
        'failed' => ['dot' => 'bg-rose-500', 'text' => 'text-rose-600 dark:text-rose-400', 'bg' => 'bg-rose-50 dark:bg-rose-500/10', 'label' => 'Failed'],
    ];

    $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];
    $psc = $payStatusConfig[$payment->payment_status ?? ''] ?? $payStatusConfig['pending'];
@endphp

<div class="space-y-5">

    {{-- ── Top meta bar ── --}}
    <div class="flex flex-wrap items-center gap-2 pb-4 border-b border-gray-100 dark:border-gray-700">
        <span
            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2.5 py-1 rounded-lg">
            #{{ $order->id }}
        </span>
        <span
            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $sc['bg'] }} {{ $sc['text'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
            {{ ucfirst($order->status) }}
        </span>
        <span
            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $psc['bg'] }} {{ $psc['text'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $psc['dot'] }}"></span>
            {{ $psc['label'] }}
        </span>
        <span class="text-xs text-gray-400 dark:text-gray-500 ml-auto">
            {{ $order->created_at->format('d M Y · h:i A') }}
        </span>
    </div>

    {{-- ── Info grid: Customer + Payment + Delivery ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Customer --}}
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-2 mb-3">
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Customer
                </h4>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                            flex items-center justify-center text-sm font-bold text-white shadow-sm flex-shrink-0">
                    {{ $initials }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                        {{ $order->user->full_name ?? '—' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $order->user->phone ?? '—' }}</p>
                    @if($order->user->email ?? false)
                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $order->user->email }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-2 mb-3">
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Payment</h4>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Method</span>
                    <span
                        class="text-xs font-semibold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-lg">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Status</span>
                    <span
                        class="inline-flex items-center gap-1 text-xs font-semibold {{ $psc['bg'] }} {{ $psc['text'] }} px-2 py-0.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full {{ $psc['dot'] }}"></span>
                        {{ $psc['label'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-gray-200 dark:border-gray-600">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Total</span>
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ number_format($order->total_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Delivery Address --}}
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-2 mb-3">
                <div
                    class="w-6 h-6 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Delivery
                    Address</h4>
            </div>
            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $order->delivery_address ?: '—' }}
            </p>
        </div>
    </div>

    {{-- ── Order Items Table ── --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="px-4 py-3 bg-gray-50 dark:bg-gray-700/40 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
            <div
                class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Order Items</h4>
            <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                {{ $itemCount }} items
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50/80 dark:bg-gray-700/20">
                    <tr
                        class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3 text-center">Qty</th>
                        <th class="px-5 py-3 text-right">Unit Price</th>
                        <th class="px-5 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100
                                                    dark:from-blue-900/20 dark:to-indigo-900/20
                                                    flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->product->name ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                    × {{ $item->qty }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-sm text-gray-600 dark:text-gray-400">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($item->qty * $item->price, 2) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Totals footer --}}
                <tfoot class="border-t-2 border-gray-200 dark:border-gray-600 bg-gray-50/80 dark:bg-gray-700/30">
                    <tr>
                        <td colspan="3"
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Subtotal
                        </td>
                        <td class="px-5 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($subtotal, 2) }}
                        </td>
                    </tr>
                    @if($order->total_amount != $subtotal)
                        <tr>
                            <td colspan="3"
                                class="px-5 py-2 text-right text-xs font-semibold text-rose-500 uppercase tracking-wider">
                                Discount / Adjustments
                            </td>
                            <td class="px-5 py-2 text-right text-sm font-semibold text-rose-500">
                                -${{ number_format($subtotal - $order->total_amount, 2) }}
                            </td>
                        </tr>
                    @endif
                    <tr class="border-t border-gray-200 dark:border-gray-600">
                        <td colspan="3"
                            class="px-5 py-3 text-right text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                            Total
                        </td>
                        <td class="px-5 py-3 text-right">
                            <span class="text-base font-bold text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>