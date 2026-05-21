@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics</h1>
                    <p class="text-gray-500 dark:text-gray-400">Business insights and performance reports.</p>
            </div>
            <button type="button" onclick="openExportModal()"
                class="action-btn inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
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

        <!-- Top Cards -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @php
            $cards = [
            ['Conversion Rate', number_format($conversionRate, 1) . '%'],
            ['Avg. Order Value', '$' . number_format($averageOrderValue, 2)],
            ['Sessions (Orders)', number_format($totalOrders)],
            ['Bounce Rate', number_format($bounceRate, 1) . '%'],
            ];
            @endphp

            @foreach($cards as [$label, $value])
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
                <p class="mt-2 text-sm text-emerald-600">Updated automatically</p>
            </div>
            @endforeach
        </div> --}}

        <!-- Charts -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Monthly Revenue</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Revenue vs Profit </p>
                <div class="mt-6 h-40">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Sales by Category</h2>
                <div class="mt-6 h-40">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Traffic Sources</h2>
                <div class="mt-6 space-y-5">
                    @foreach($trafficSources as $source)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ $source['name'] }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $source['percent'] }}%</span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $source['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Top Selling Products</h2>
                <div class="mt-6 space-y-4">
                    @forelse($topProducts as $product)
                        <div class="flex items-center gap-4">
                            @if(optional($product->image->first())->image_url)
                                <img src="{{ $product->image->first()->image_url }}" class="w-12 h-12 rounded-xl object-cover">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700"></div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $product->name }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($product->sold_qty ?? 0) }} sold
                                </p>
                            </div>

                            <div class="font-semibold text-indigo-600">
                                ${{ number_format(($product->sale_price ?? 0) * ($product->sold_qty ?? 0), 2) }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No sales data available.</p>
                    @endforelse
                </div>
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
                        tension: 0.4
                    },
                    {
                        label: 'Profit',
                        data: monthlyProfit,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.05)',
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($salesByCategory->pluck('name')),
                datasets: [{
                    data: @json($salesByCategory->pluck('total_sold'))
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
@endpush