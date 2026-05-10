@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ─────────────────────────────────────────────
        PAGE HEADER
        ───────────────────────────────────────────── --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Payments</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Manage customer payment transactions.
            </p>
        </div>

        {{-- ─────────────────────────────────────────────
        STAT CARDS
        ───────────────────────────────────────────── --}}
        @php
            $totalCount = $payments->total();
            $successCount = $payments->getCollection()->where('payment_status', 'paid')->count();
            $totalRevenue = $payments->getCollection()->where('payment_status', 'paid')->sum('amount');

            // For progress bars (relative to total on current page)
            $successPct = $totalCount > 0 ? min(100, round(($successCount / max($payments->count(), 1)) * 100)) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Total Transactions --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Total Transactions
                    </p>
                    <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalCount) }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            {{-- Successful Payments --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Successful Payments
                    </p>
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($successCount) }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                        style="width: {{ $successPct }}%"></div>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Total Revenue
                    </p>
                    <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($totalRevenue, 2) }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full" style="width: 75%"></div>
                </div>
            </div>

        </div>

        {{-- ─────────────────────────────────────────────
        TABLE CARD
        ───────────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Toolbar --}}
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.payments.transactions') }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3">

                    {{-- Search --}}
                    <div class="relative flex-1 max-w-xs">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions…"
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-600
                                       rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white
                                       placeholder-gray-400 dark:placeholder-gray-500
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition-all duration-200" />
                    </div>

                    {{-- Status filter --}}
                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 dark:border-gray-600 rounded-xl
                                   bg-gray-50 dark:bg-gray-700 dark:text-white
                                   px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   transition-all duration-200">
                        <option value="">All Statuses</option>
                        @foreach(['paid' => 'Paid', 'pending' => 'Pending', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Method filter --}}
                    <select name="method" onchange="this.form.submit()" class="text-sm border border-gray-200 dark:border-gray-600 rounded-xl
                                   bg-gray-50 dark:bg-gray-700 dark:text-white
                                   px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   transition-all duration-200">
                        <option value="">All Methods</option>
                        @foreach(['card' => 'Card', 'paypal' => 'PayPal', 'bank_transfer' => 'Bank Transfer', 'crypto' => 'Crypto'] as $val => $label)
                            <option value="{{ $val }}" {{ request('method') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Submit search --}}
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700
                                       text-white rounded-xl transition-all duration-200 hidden sm:inline-flex items-center gap-1.5">
                        Search
                    </button>

                    {{-- Export --}}
                    <a href="{{ route('admin.payments.transactions', array_merge(request()->query(), ['export' => 'csv'])) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium
                                  border border-gray-200 dark:border-gray-600
                                  text-gray-600 dark:text-gray-300
                                  bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600
                                  rounded-xl transition-all duration-200 ml-auto sm:ml-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </a>

                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5 text-left font-medium">Transaction ID</th>
                            <th class="px-5 py-3.5 text-left font-medium">Customer</th>
                            <th class="px-5 py-3.5 text-left font-medium">Order ID</th>
                            <th class="px-5 py-3.5 text-left font-medium">Method</th>
                            <th class="px-5 py-3.5 text-left font-medium">Amount</th>
                            <th class="px-5 py-3.5 text-left font-medium">Status</th>
                            <th class="px-5 py-3.5 text-left font-medium">Paid Date</th>
                            <th class="px-5 py-3.5 text-right font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            @php
                                $statusMap = [
                                    'paid' => ['label' => 'Paid', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400'],
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-amber-100  text-amber-700  dark:bg-amber-500/15  dark:text-amber-400'],
                                    'failed' => ['label' => 'Failed', 'class' => 'bg-red-100    text-red-700    dark:bg-red-500/15    dark:text-red-400'],
                                    'refunded' => ['label' => 'Refunded', 'class' => 'bg-gray-100   text-gray-600   dark:bg-gray-700      dark:text-gray-400'],
                                ];
                                $status = $statusMap[$payment->payment_status] ?? $statusMap['pending'];

                                $methodIcons = [
                                    'card' => '💳',
                                    'paypal' => '🅿️',
                                    'bank_transfer' => '🏦',
                                    'crypto' => '₿',
                                ];
                                $methodIcon = $methodIcons[$payment->payment_method] ?? '💰';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">

                                {{-- Transaction ID --}}
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs font-medium text-gray-700 dark:text-gray-300
                                                             bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md">
                                        {{ $payment->transaction_id }}
                                    </span>
                                </td>

                                {{-- Customer --}}
                                <td class="px-5 py-3.5">
                                    @if($payment->user)
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-500/20
                                                                            text-indigo-600 dark:text-indigo-400
                                                                            flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                                {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 dark:text-white truncate">
                                                    {{ $payment->user->name }}
                                                </p>
                                                <p class="text-xs text-gray-400 truncate">{{ $payment->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Guest</span>
                                    @endif
                                </td>

                                {{-- Order ID --}}
                                <td class="px-5 py-3.5">
                                    <a href="/admin/orders/{{ $payment->order_id }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium text-xs">
                                        #{{ $payment->order_id }}
                                    </a>
                                </td>

                                {{-- Payment Method --}}
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                                        <span>{{ $methodIcon }}</span>
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold text-gray-800 dark:text-white">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>

                                {{-- Paid Date --}}
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 text-sm">
                                    @if($payment->paid_at)
                                        {{ \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') }}
                                        <span class="block text-xs text-gray-400 dark:text-gray-500">
                                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600 italic text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- View --}}
                                        <a href="/admin/payments/{{ $payment->id }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                              border border-gray-200 dark:border-gray-600
                                                              text-gray-600 dark:text-gray-300
                                                              bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600
                                                              rounded-lg transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>

                                        {{-- Refund (only if paid) --}}
                                        @if($payment->payment_status === 'paid')
                                            <form method="POST" action="{{ route('admin.payments.refund', $payment->id) }}"
                                                onsubmit="return confirm('Refund ${{ number_format($payment->amount, 2) }} for transaction {{ $payment->transaction_id }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                                               bg-amber-50 dark:bg-amber-500/10
                                                                               text-amber-700 dark:text-amber-400
                                                                               border border-amber-200 dark:border-amber-500/30
                                                                               hover:bg-amber-100 dark:hover:bg-amber-500/20
                                                                               rounded-lg transition-all duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    Refund
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700
                                                                flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No transactions found.</p>
                                        @if(request()->hasAny(['search', 'status', 'method']))
                                            <a href="{{ route('admin.payments.transactions') }}"
                                                class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Clear filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700
                            flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if($payments->total())
                        Showing
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $payments->firstItem() }}</span>–<span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ $payments->lastItem() }}</span>
                        of
                        <span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ number_format($payments->total()) }}</span>
                        transactions
                    @else
                        No transactions
                    @endif
                </p>
                <div>
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection