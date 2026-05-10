@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ─────────────────────────────────────────────
        PAGE HEADER
        ───────────────────────────────────────────── --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Payment Methods</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Configure available payment options.
                </p>
            </div>

            {{-- Add Method --}}
            <button onclick="document.getElementById('add-method-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium
                       bg-indigo-600 hover:bg-indigo-700 text-white
                       rounded-xl shadow-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Method
            </button>
        </div>

        {{-- ─────────────────────────────────────────────
        STAT CARDS
        ───────────────────────────────────────────── --}}
        @php
            $totalMethods = $paymentMethods->count();
            $enabledMethods = $paymentMethods->where('is_active', true)->count();
            $disabledMethods = $paymentMethods->where('is_active', false)->count();
            $enabledPct = $totalMethods > 0 ? round(($enabledMethods / $totalMethods) * 100) : 0;
            $disabledPct = $totalMethods > 0 ? round(($disabledMethods / $totalMethods) * 100) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Total Methods --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Methods
                    </p>
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalMethods }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            {{-- Enabled --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Enabled</p>
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $enabledMethods }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                        style="width: {{ $enabledPct }}%"></div>
                </div>
            </div>

            {{-- Disabled --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Disabled</p>
                    <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $disabledMethods }}</p>
                <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gray-400 rounded-full transition-all duration-500"
                        style="width: {{ $disabledPct }}%"></div>
                </div>
            </div>

        </div>

        {{-- ─────────────────────────────────────────────
        TABLE CARD
        ───────────────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white">All Payment Methods</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5 text-left font-medium">Logo</th>
                            <th class="px-5 py-3.5 text-left font-medium">Method Name</th>
                            <th class="px-5 py-3.5 text-left font-medium">Type</th>
                            <th class="px-5 py-3.5 text-left font-medium">Processing Fee</th>
                            <th class="px-5 py-3.5 text-left font-medium">Status</th>
                            <th class="px-5 py-3.5 text-right font-medium">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($paymentMethods as $method)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">

                                            {{-- Logo --}}
                                            <td class="px-5 py-3.5">
                                                @if($method->logo)
                                                    <img src="{{ asset('storage/' . $method->logo) }}" alt="{{ $method->name }}"
                                                        class="w-9 h-9 rounded-lg object-contain border border-gray-200 dark:border-gray-600 p-1 bg-white dark:bg-gray-700" />
                                                @else
                                                    <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700
                                                                        border border-gray-200 dark:border-gray-600
                                                                        flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Method Name --}}
                                            <td class="px-5 py-3.5">
                                                <span class="font-medium text-gray-800 dark:text-white">{{ $method->name }}</span>
                                            </td>

                                            {{-- Type --}}
                                            <td class="px-5 py-3.5">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                 bg-indigo-50 dark:bg-indigo-500/10
                                                                 text-indigo-700 dark:text-indigo-400">
                                                    {{ ucfirst($method->type) }}
                                                </span>
                                            </td>

                                            {{-- Fee --}}
                                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">
                                                {{ $method->fee > 0 ? number_format($method->fee, 2) . '%' : 'Free' }}
                                            </td>

                                            {{-- Status --}}
                                            <td class="px-5 py-3.5">
                                                @if($method->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                         bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                                        Enabled
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                         bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                                        Disabled
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center justify-end gap-2">

                                                    {{-- Edit --}}
                                                    <a href="/admin/payments/methods/{{ $method->id }}/edit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                                  border border-gray-200 dark:border-gray-600
                                                                  text-gray-600 dark:text-gray-300
                                                                  bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600
                                                                  rounded-lg transition-all duration-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>

                                                    {{-- Toggle Enable/Disable --}}
                                                    <form method="POST" action="{{ route('admin.payments.toggle-method', $method->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                                       transition-all duration-200
                                                                       {{ $method->is_active
                            ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 hover:bg-amber-100 dark:hover:bg-amber-500/20'
                            : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' }}">
                                                            @if($method->is_active)
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                                    viewBox="0 0 24 24">
                                                                    <circle cx="12" cy="12" r="10" />
                                                                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                                                </svg>
                                                                Disable
                                                            @else
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Enable
                                                            @endif
                                                        </button>
                                                    </form>

                                                    {{-- Delete --}}
                                                    <form method="POST" action="/admin/payments/methods/{{ $method->id }}"
                                                        onsubmit="return confirm('Delete payment method {{ $method->name }}? This cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                                       bg-red-50 dark:bg-red-500/10
                                                                       text-red-600 dark:text-red-400
                                                                       border border-red-200 dark:border-red-500/30
                                                                       hover:bg-red-100 dark:hover:bg-red-500/20
                                                                       rounded-lg transition-all duration-200">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8"
                                                                viewBox="0 0 24 24">
                                                                <polyline points="3 6 5 6 21 6" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 6l-1 14H6L5 6m5 0V4h4v2" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>

                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700
                                                        flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No payment methods configured.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($paymentMethods instanceof \Illuminate\Pagination\LengthAwarePaginator && $paymentMethods->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700
                                flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing
                        <span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ $paymentMethods->firstItem() }}</span>–<span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ $paymentMethods->lastItem() }}</span>
                        of
                        <span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ number_format($paymentMethods->total()) }}</span>
                        methods
                    </p>
                    <div>{{ $paymentMethods->links() }}</div>
                </div>
            @endif

        </div>
    </div>

    {{-- ─────────────────────────────────────────────
    ADD METHOD MODAL
    ───────────────────────────────────────────── --}}
    <div id="add-method-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        onclick="if(event.target===this) this.classList.add('hidden')">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        {{-- Modal --}}
        <div class="relative w-full max-w-md bg-white dark:bg-gray-800
                    rounded-2xl border border-gray-200 dark:border-gray-700
                    shadow-2xl p-6 space-y-5">

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Add Payment Method</h3>
                <button onclick="document.getElementById('add-method-modal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-lg
                           text-gray-400 hover:text-gray-600 dark:hover:text-gray-300
                           hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/admin/payments/methods" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Method Name</label>
                    <input type="text" name="name" required placeholder="e.g. Stripe, PayPal" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                  rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <select name="type" required class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                   rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                        <option value="">Select type…</option>
                        <option value="card">Card</option>
                        <option value="wallet">Wallet</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="crypto">Crypto</option>
                        <option value="buy_now_pay_later">Buy Now Pay Later</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Processing Fee (%)</label>
                    <input type="number" name="fee" min="0" max="100" step="0.01" value="0" placeholder="0.00" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                  rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                  rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white
                                  file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                                  file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100 transition-all duration-200" />
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="button" onclick="document.getElementById('add-method-modal').classList.add('hidden')"
                        class="flex-1 px-4 py-2.5 text-sm font-medium
                                   border border-gray-200 dark:border-gray-600
                                   text-gray-600 dark:text-gray-300
                                   bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600
                                   rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium
                                   bg-indigo-600 hover:bg-indigo-700 text-white
                                   rounded-xl transition-all duration-200">
                        Add Method
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection