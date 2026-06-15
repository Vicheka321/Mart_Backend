@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics</h3>
                <p class="text-gray-500 dark:text-gray-400">Business insights and performance reports.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Date Range Filter --}}
                <select id="dateRange" onchange="applyDateFilter(this.value)"
                    class="text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                           text-gray-700 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="10">Last 10 months</option>
                    <option value="6">Last 6 months</option>
                    <option value="3">Last 3 months</option>
                </select>
                <button type="button" onclick="openExportModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                           border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                           text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3v12" />
                        <path d="m7 10 5 5 5-5" />
                        <path d="M4 21h16" />
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            @php
                $kpis = [
                    [
                        'label' => 'Total Revenue',
                        'value' => '$' . number_format($totalRevenue, 2),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171.243 1.179-.243 1.17-.66 1.179.243 1.17.66 1.178-.244 1.17-.659M3 4h18M4 4h16v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4Z"/>',
                        'color' => 'indigo',
                    ],
                    [
                        'label' => 'Net Profit',
                        'value' => '$' . number_format($profit, 2),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>',
                        'color' => 'emerald',
                    ],
                    [
                        'label' => 'Total Orders',
                        'value' => number_format($totalOrders),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/>',
                        'color' => 'violet',
                    ],
                    [
                        'label' => 'Avg. Order Value',
                        'value' => '$' . number_format($averageOrderValue, 2),
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>',
                        'color' => 'amber',
                    ],
                ];
                $colorMap = [
                    'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'icon' => 'text-indigo-600 dark:text-indigo-400'],
                    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'icon' => 'text-emerald-600 dark:text-emerald-400'],
                    'violet' => ['bg' => 'bg-violet-50 dark:bg-violet-900/20', 'icon' => 'text-violet-600 dark:text-violet-400'],
                    'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'icon' => 'text-amber-600 dark:text-amber-400'],
                ];
            @endphp

            @foreach($kpis as $kpi)
                @php $c = $colorMap[$kpi['color']]; @endphp
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                        <div class="p-2 rounded-xl {{ $c['bg'] }}">
                            <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                {!! $kpi['icon'] !!}
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Revenue Chart (wider) --}}
            <div
                class="xl:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-1">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Revenue</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Revenue vs Profit</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-indigo-600 inline-block"></span> Revenue</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Profit</span>
                    </div>
                </div>
                <div class="mt-4 h-52">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Category Doughnut --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales by Category</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Units sold per category</p>
                <div class="mt-4 h-52 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bottom Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Top Selling Products --}}
            <div
                class="xl:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Selling Products</h2>
                <div class="space-y-3">
                    @forelse($topProducts as $i => $product)
                        <div
                            class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <span class="w-6 text-sm font-bold text-gray-400 dark:text-gray-500 text-center">{{ $i + 1 }}</span>

                            @if(optional($product->image->first())->image_url)
                                <img src="{{ $product->image->first()->image_url }}"
                                    class="w-11 h-11 rounded-xl object-cover border border-gray-100 dark:border-gray-600">
                            @else
                                <div class="w-11 h-11 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        @php
                                            $maxSold = $topProducts->max('sold_qty') ?: 1;
                                            $pct = ($product->sold_qty / $maxSold) * 100;
                                        @endphp
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ number_format($product->sold_qty ?? 0) }}
                                        sold</span>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format(($product->sale_price ?? 0) * ($product->sold_qty ?? 0), 2) }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    ${{ number_format($product->sale_price ?? 0, 2) }} each</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400 dark:text-gray-500">
                            <p class="text-sm">No sales data available.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">

                {{-- Traffic Sources --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Traffic Sources</h2>
                    <div class="space-y-4">
                        @foreach($trafficSources as $source)
                            <div>
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $source['name'] }}</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $source['percent'] }}%</span>
                                </div>
                                <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-700"
                                        style="width: {{ $source['percent'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Payment Methods Breakdown --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Methods</h2>
                    <div class="h-36">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

        {{-- Coupon Performance --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Coupon Performance</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active promotions and usage stats</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Code</th>
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Discount</th>
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Min. Order</th>
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Used</th>
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                Expires</th>
                            <th
                                class="text-left pb-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3 pr-4">
                                    <span
                                        class="font-mono font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-2 py-0.5 rounded-lg text-xs">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 font-medium text-gray-900 dark:text-white">
                                    @if($coupon->discount_type === 'percent')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        ${{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                    {{ $coupon->min_order_amount ? '$' . number_format($coupon->min_order_amount, 2) : '—' }}
                                </td>
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $coupon->used_count }}</span>
                                        @if($coupon->usage_limit)
                                            <span class="text-gray-400 dark:text-gray-500">/ {{ $coupon->usage_limit }}</span>
                                            <div
                                                class="w-16 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden hidden sm:block">
                                                <div class="h-full bg-indigo-500 rounded-full"
                                                    style="width: {{ min(100, ($coupon->used_count / $coupon->usage_limit) * 100) }}%">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-gray-500 dark:text-gray-400 hidden lg:table-cell">
                                    {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') : '—' }}
                                </td>
                                <td class="py-3">
                                    @if($coupon->status)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No coupons
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order Status + KHQR Stats --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Order Status Breakdown --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Status Breakdown</h2>
                @php
                    $statusColors = [
                        'pending' => ['dot' => 'bg-amber-400', 'badge' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400'],
                        'processing' => ['dot' => 'bg-blue-500', 'badge' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'],
                        'completed' => ['dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'],
                        'cancelled' => ['dot' => 'bg-red-400', 'badge' => 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400'],
                    ];
                    $totalAllOrders = $ordersByStatus->sum('count') ?: 1;
                @endphp
                <div class="space-y-4">
                    @foreach($ordersByStatus as $row)
                        @php $s = $statusColors[$row->status] ?? ['dot' => 'bg-gray-400', 'badge' => 'bg-gray-100 text-gray-600']; @endphp
                        <div class="flex items-center gap-3">
                            <span
                                class="w-24 text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $row->status }}</span>
                            <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full {{ $s['dot'] }} rounded-full"
                                    style="width: {{ ($row->count / $totalAllOrders) * 100 }}%"></div>
                            </div>
                            <span
                                class="w-8 text-sm font-semibold text-gray-900 dark:text-white text-right">{{ $row->count }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 w-10 text-right">
                                {{ number_format(($row->count / $totalAllOrders) * 100, 1) }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KHQR Stats --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">KHQR Payments</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">QR code payment stats</p>
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $khqrStats = [
                            ['label' => 'Total QR Sessions', 'value' => number_format($khqrTotal ?? 0), 'color' => 'text-indigo-600 dark:text-indigo-400'],
                            ['label' => 'Successful', 'value' => number_format($khqrSuccess ?? 0), 'color' => 'text-emerald-600 dark:text-emerald-400'],
                            ['label' => 'Pending', 'value' => number_format($khqrPending ?? 0), 'color' => 'text-amber-600 dark:text-amber-400'],
                            ['label' => 'Expired / Failed', 'value' => number_format(($khqrExpired ?? 0) + ($khqrFailed ?? 0)), 'color' => 'text-red-600 dark:text-red-400'],
                        ];
                    @endphp
                    @foreach($khqrStats as $stat)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                            <p class="mt-1 text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
                @if(isset($khqrSuccessRate))
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="text-gray-600 dark:text-gray-300">Success Rate</span>
                            <span
                                class="font-semibold text-gray-900 dark:text-white">{{ number_format($khqrSuccessRate, 1) }}%</span>
                        </div>
                        <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $khqrSuccessRate }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = @json($months);
        const monthlyRevenue = @json($monthlyRevenue);
        const monthlyProfit = @json($monthlyProfit);
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
        const labelColor = isDark ? '#9ca3af' : '#6b7280';

        // Revenue chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Revenue',
                        data: monthlyRevenue,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#4f46e5',
                    },
                    {
                        label: 'Profit',
                        data: monthlyProfit,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.06)',
                        fill: false,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 11 } } },
                    y: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 11 }, callback: v => '$' + v.toLocaleString() } }
                }
            }
        });

        // Category doughnut
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($salesByCategory->pluck('name')),
                datasets: [{
                    data: @json($salesByCategory->pluck('total_sold')),
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: labelColor, font: { size: 11 }, boxWidth: 10, padding: 12 }
                    }
                },
                cutout: '68%'
            }
        });

        // Payment method bar chart
        new Chart(document.getElementById('paymentChart'), {
            type: 'bar',
            data: {
                labels: @json($paymentMethods->pluck('method') ?? []),
                datasets: [{
                    label: 'Orders',
                    data: @json($paymentMethods->pluck('count') ?? []),
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: labelColor, font: { size: 10 } } },
                    y: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 10 } } }
                }
            }
        });
    </script>
@endpush