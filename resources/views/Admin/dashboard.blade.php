{{-- @extends('layouts.app')

@section('content')

<div>

    <!-- TOP CARDS -->
    <div class="grid grid-cols-4 gap-6 mb-6">

        <!-- CARD -->
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition 
        border border-gray-100 
        dark:bg-slate-800 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Products</p>
            <div class="flex items-center justify-between mt-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">120</h2>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    📦
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition 
        border border-gray-100 
        dark:bg-slate-800 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Orders</p>
            <div class="flex items-center justify-between mt-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">75</h2>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg 
    bg-blue-100 dark:bg-blue-500/10 
    text-blue-600 dark:text-blue-400">
                    🧾
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition 
        border border-gray-100 
        dark:bg-slate-800 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Customers</p>
            <div class="flex items-center justify-between mt-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">50</h2>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition 
        border border-gray-100 
        dark:bg-slate-800 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Revenue</p>
            <div class="flex items-center justify-between mt-2">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">$2,500</h2>
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-pink-100 text-pink-600">
                    💰
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-3 gap-6">

        <!-- REVENUE FORECAST -->
        <div class="col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg text-gray-800">Revenue Forecast</h2>

                <select class="border border-gray-200 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-indigo-400">
                    <option>This Week</option>
                    <option>This Month</option>
                </select>
            </div>

            <!-- Chart -->
            <div class="flex items-end justify-between h-56 mt-6">
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-24 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-36 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-28 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-40 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-32 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-36 rounded"></div>
                <div class="w-4 bg-gradient-to-t from-indigo-400 to-indigo-600 h-30 rounded"></div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">

            <!-- NEW CUSTOMERS -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                        🌐
                    </div>
                    <h3 class="font-semibold text-gray-800">New Customers</h3>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-500">New goals</p>

                    <div class="w-full bg-gray-200 h-2 rounded mt-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-400 to-teal-500 h-2 rounded w-[83%]"></div>
                    </div>

                    <p class="text-sm text-right mt-1 font-medium text-gray-600">83%</p>
                </div>
            </div>

            <!-- TOTAL INCOME -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-pink-100 text-pink-600">
                        📊
                    </div>
                    <h3 class="font-semibold text-gray-800">Total Income</h3>
                </div>

                <h2 class="text-2xl font-bold mt-4 text-gray-800">$680</h2>

                <span class="inline-block mt-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-lg">
                    +18%
                </span>
            </div>

        </div>

    </div>

    <!-- LOWER SECTION -->
    <div class="grid grid-cols-3 gap-6 mt-6">

        <!-- REVENUE BY PRODUCT -->
        <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">

            <div class="p-5 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Revenue by Product</h2>
            </div>

            <div class="divide-y">

                <!-- ROW -->
                <div class="flex items-center justify-between p-5 hover:bg-gray-50 transition">

                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/50?img=1" class="w-12 h-12 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Minecraft App</p>
                            <p class="text-sm text-gray-500">Jason Roy</p>
                        </div>
                    </div>

                    <p class="text-gray-600">73.2%</p>

                    <span class="px-3 py-1 text-sm rounded-lg bg-green-100 text-green-600">
                        Low
                    </span>

                    <p class="font-medium text-gray-800">$3.5k</p>

                </div>

                <!-- ROW -->
                <div class="flex items-center justify-between p-5 hover:bg-gray-50 transition">

                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/50?img=2" class="w-12 h-12 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Web App Project</p>
                            <p class="text-sm text-gray-500">Mathew Flintoff</p>
                        </div>
                    </div>

                    <p class="text-gray-600">73.2%</p>

                    <span class="px-3 py-1 text-sm rounded-lg bg-yellow-100 text-yellow-600">
                        Medium
                    </span>

                    <p class="font-medium text-gray-800">$3.5k</p>

                </div>

                <!-- ROW -->
                <div class="flex items-center justify-between p-5 hover:bg-gray-50 transition">

                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/50?img=3" class="w-12 h-12 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Modernize Dashboard</p>
                            <p class="text-sm text-gray-500">Anil Kumar</p>
                        </div>
                    </div>

                    <p class="text-gray-600">73.2%</p>

                    <span class="px-3 py-1 text-sm rounded-lg bg-purple-100 text-purple-600">
                        Very High
                    </span>

                    <p class="font-medium text-gray-800">$3.5k</p>

                </div>

                <!-- ROW -->
                <div class="flex items-center justify-between p-5 hover:bg-gray-50 transition">

                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/50?img=4" class="w-12 h-12 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Dashboard Co</p>
                            <p class="text-sm text-gray-500">George Cruize</p>
                        </div>
                    </div>

                    <p class="text-gray-600">73.2%</p>

                    <span class="px-3 py-1 text-sm rounded-lg bg-red-100 text-red-600">
                        High
                    </span>

                    <p class="font-medium text-gray-800">$3.5k</p>

                </div>

            </div>
        </div>

        <!-- DAILY ACTIVITIES -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

            <h2 class="text-lg font-semibold text-gray-800 mb-5">Daily Activities</h2>

            <div class="space-y-6">

                <!-- ITEM -->
                <div class="flex gap-4">
                    <span class="text-sm text-gray-400">09:46</span>

                    <div class="relative">
                        <span class="w-3 h-3 bg-indigo-500 rounded-full block"></span>
                        <span class="absolute left-1.5 top-3 w-px h-10 bg-gray-200"></span>
                    </div>

                    <p class="text-sm text-gray-700">
                        Payment received from John Doe of <span class="font-medium">$385.90</span>
                    </p>
                </div>

                <div class="flex gap-4">
                    <span class="text-sm text-gray-400">09:46</span>

                    <div class="relative">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full block"></span>
                        <span class="absolute left-1.5 top-3 w-px h-10 bg-gray-200"></span>
                    </div>

                    <p class="text-sm text-gray-700">
                        New sale recorded <span class="text-indigo-600">#ML-3467</span>
                    </p>
                </div>

                <div class="flex gap-4">
                    <span class="text-sm text-gray-400">09:46</span>

                    <div class="relative">
                        <span class="w-3 h-3 bg-teal-500 rounded-full block"></span>
                        <span class="absolute left-1.5 top-3 w-px h-10 bg-gray-200"></span>
                    </div>

                    <p class="text-sm text-gray-700">
                        Payment was made of $64.95 to Michael
                    </p>
                </div>

                <div class="flex gap-4">
                    <span class="text-sm text-gray-400">09:46</span>

                    <div class="relative">
                        <span class="w-3 h-3 bg-pink-500 rounded-full block"></span>
                    </div>

                    <p class="text-sm text-gray-700">
                        Project meeting
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection --}}


@extends('layouts.app')

@section('content')

    <div class="dash">

        {{-- TOP HEADER --}}
        <div class="topbar">
            <h1>Overview</h1>
            <div class="topbar-right">
                <span class="date-pill">{{ now()->format('d M Y') }}</span>
                <button class="btn-export">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="vertical-align:-1px;margin-right:4px">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="stat-grid">

            <div class="stat-card">
                <div class="stat-icon icon-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                        <path d="M12 3v3m0 12v3" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
                </div>
                <div class="stat-delta delta-up">↑ +41% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17h-11v-14h-2" />
                        <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-value">{{ number_format($totalSales) }}</div>
                </div>
                <div class="stat-delta delta-up">↑ +41% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                        <path d="M12 12l8 -4.5" />
                        <path d="M12 12l0 9" />
                        <path d="M12 12l-8 -4.5" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                </div>
                <div class="stat-delta delta-down">↓ -50% from last month</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 17l4 -4l4 4l4 -8l4 4l2 -2" />
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Profit</div>
                    <div class="stat-value">${{ number_format($todaySales, 2) }}</div>
                </div>
                <div class="stat-delta delta-up">↑ +41% from last month</div>
            </div>

        </div>

        {{-- CHARTS --}}
        <div class="chart-grid">

            {{-- BAR CHART --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Total Sales</div>
                        <div class="chart-val">{{ number_format($totalOrders) }}</div>
                        <div class="chart-delta">↑ +283% from last month</div>
                    </div>
                    <button class="btn-period">Last 30 days</button>
                </div>
                <div class="bar-chart" id="barChart"
                    data-sales="{{ json_encode($weeklySales->map(fn($s) => ['date' => \Carbon\Carbon::parse($s->date)->format('d M'), 'total' => $s->total])) }}">
                </div>
            </div>

            {{-- LINE CHART --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Total Revenue</div>
                        <div class="chart-val">${{ number_format($totalRevenue, 2) }}</div>
                        <div class="chart-delta">↑ +20.5% from last month</div>
                    </div>
                    <button class="btn-period">Last 30 days</button>
                </div>
                <div class="line-chart">
                    <svg id="lineChart" viewBox="0 0 300 100" preserveAspectRatio="none"
                        data-sales="{{ json_encode($weeklySales->map(fn($s) => $s->total)) }}">
                    </svg>
                </div>
            </div>

        </div>

        {{-- RECENT SALES TABLE --}}
        <div class="table-card">

            <div class="table-header">
                <span class="table-title">Recent Sales</span>
                <div class="table-actions">
                    <button class="btn-sm">View all</button>
                    <button class="btn-sm">Last 30 days</button>
                    <button class="btn-sm btn-primary">+ Add Order</button>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th style="text-align:right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            @php
                                $statusClass = match ($order->status) {
                                    'pending' => 'badge-pending',
                                    'processing' => 'badge-processing',
                                    'completed' => 'badge-completed',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'badge-default',
                                };
                            @endphp
                            <tr>
                                <td class="client-name">{{ $order->user->name ?? 'Customer' }}</td>
                                <td class="muted">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="price">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="muted">
                                    @if($order->orderItems->count())
                                        {{ $order->orderItems->first()->product->name ?? 'Product' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td style="text-align:right">
                                    <button class="btn-view">View</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .dash {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            padding: 1.5rem 0;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }

        /* ── Top bar ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h1 {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
        }

        @media (prefers-color-scheme: dark) {
            .topbar h1 {
                color: #f9fafb;
            }
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-pill {
            font-size: 13px;
            color: #6b7280;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 12px;
        }

        @media (prefers-color-scheme: dark) {
            .date-pill {
                background: #1e293b;
                border-color: #334155;
                color: #94a3b8;
            }
        }

        .btn-export {
            font-size: 13px;
            font-weight: 500;
            background: #4338ca;
            color: #e0e7ff;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: background 0.15s;
        }

        .btn-export:hover {
            background: #4f46e5;
        }

        /* ── Stat cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 900px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        @media (prefers-color-scheme: dark) {
            .stat-card {
                background: #1e293b;
                border-color: #334155;
            }
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-purple {
            background: #ede9fe;
            color: #5b21b6;
        }

        .icon-teal {
            background: #ccfbf1;
            color: #0f766e;
        }

        .icon-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .icon-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            line-height: 1.1;
            margin-top: 2px;
        }

        @media (prefers-color-scheme: dark) {
            .stat-value {
                color: #f1f5f9;
            }
        }

        .stat-delta {
            font-size: 12px;
        }

        .delta-up {
            color: #059669;
        }

        .delta-down {
            color: #dc2626;
        }

        /* ── Charts ── */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 700px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        @media (prefers-color-scheme: dark) {
            .chart-card {
                background: #1e293b;
                border-color: #334155;
            }
        }

        .chart-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
        }

        .chart-val {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-top: 2px;
            line-height: 1.1;
        }

        @media (prefers-color-scheme: dark) {
            .chart-val {
                color: #f1f5f9;
            }
        }

        .chart-delta {
            font-size: 12px;
            color: #059669;
            margin-top: 2px;
        }

        .btn-period {
            font-size: 12px;
            color: #6b7280;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 5px 10px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }

        @media (prefers-color-scheme: dark) {
            .btn-period {
                background: #0f172a;
                border-color: #334155;
                color: #94a3b8;
            }
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 5px;
            height: 110px;
        }

        .bar-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            height: 100%;
            justify-content: flex-end;
        }

        .bar {
            width: 100%;
            border-radius: 4px 4px 0 0;
            background: #ede9fe;
            position: relative;
            overflow: hidden;
            min-height: 6px;
        }

        .bar-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #4f46e5;
            border-radius: 4px 4px 0 0;
            height: 100%;
        }

        .bar-lbl {
            font-size: 10px;
            color: #9ca3af;
            white-space: nowrap;
        }

        .line-chart {
            position: relative;
            height: 110px;
        }

        .line-chart svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        /* ── Table ── */
        .table-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        @media (prefers-color-scheme: dark) {
            .table-card {
                background: #1e293b;
                border-color: #334155;
            }
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (prefers-color-scheme: dark) {
            .table-header {
                border-color: #334155;
            }
        }

        .table-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        @media (prefers-color-scheme: dark) {
            .table-title {
                color: #f1f5f9;
            }
        }

        .table-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .btn-sm {
            font-size: 12px;
            color: #6b7280;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 12px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-sm:hover {
            background: #f3f4f6;
        }

        @media (prefers-color-scheme: dark) {
            .btn-sm {
                background: #0f172a;
                border-color: #334155;
                color: #94a3b8;
            }

            .btn-sm:hover {
                background: #1e293b;
            }
        }

        .btn-primary {
            background: #4338ca !important;
            color: #e0e7ff !important;
            border-color: #4338ca !important;
        }

        .btn-primary:hover {
            background: #4f46e5 !important;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #f9fafb;
        }

        @media (prefers-color-scheme: dark) {
            thead {
                background: #0f172a;
            }
        }

        th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        td {
            padding: 13px 16px;
            color: #374151;
            border-top: 1px solid #f3f4f6;
        }

        @media (prefers-color-scheme: dark) {
            td {
                color: #cbd5e1;
                border-color: #334155;
            }
        }

        tr:hover td {
            background: #f9fafb;
        }

        @media (prefers-color-scheme: dark) {
            tr:hover td {
                background: #0f172a;
            }
        }

        .client-name {
            font-weight: 600;
            color: #111827;
        }

        @media (prefers-color-scheme: dark) {
            .client-name {
                color: #f1f5f9;
            }
        }

        .price {
            font-weight: 600;
        }

        .muted {
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            .muted {
                color: #64748b;
            }
        }

        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 100px;
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-processing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-default {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-view {
            font-size: 12px;
            color: #6b7280;
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 5px 12px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-view:hover {
            background: #f9fafb;
        }

        @media (prefers-color-scheme: dark) {
            .btn-view {
                border-color: #334155;
                color: #94a3b8;
            }

            .btn-view:hover {
                background: #0f172a;
            }
        }
    </style>

    <script defer>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Bar chart ──
            const bc = document.getElementById('barChart');
            const raw = JSON.parse(bc.dataset.sales || '[]');
            const maxV = Math.max(...raw.map(s => s.total), 1);

            raw.forEach(s => {
                const pct = Math.round((s.total / maxV) * 100);
                const lbl = s.date.split(' ')[0];
                const wrap = document.createElement('div');
                wrap.className = 'bar-wrap';
                wrap.innerHTML = `
                <div class="bar" style="height:${pct}%">
                    <div class="bar-fill"></div>
                </div>
                <span class="bar-lbl">${lbl}</span>
            `;
                bc.appendChild(wrap);
            });

            // ── Line chart ──
            const svg = document.getElementById('lineChart');
            const totals = JSON.parse(svg.dataset.sales || '[]');

            if (totals.length > 1) {
                const maxT = Math.max(...totals, 1);
                const pts = totals.map((v, i) => {
                    const x = Math.round((i / (totals.length - 1)) * 300);
                    const y = Math.round(100 - (v / maxT) * 88);
                    return [x, y];
                });

                const ptStr = pts.map(p => p.join(',')).join(' ');
                const linePath = 'M' + pts.map(p => p.join(',')).join(' L');
                const areaPath = linePath + ` L300,100 L0,100 Z`;

                svg.innerHTML = `
                <defs>
                    <linearGradient id="ag" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%"   stop-color="#4f46e5" stop-opacity="0.18"/>
                        <stop offset="100%" stop-color="#4f46e5" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="${areaPath}" fill="url(#ag)"/>
                <polyline points="${ptStr}" fill="none" stroke="#4f46e5" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round"/>
                ${pts.map(p => `<circle cx="${p[0]}" cy="${p[1]}" r="3"
                    fill="#ede9fe" stroke="#4f46e5" stroke-width="1.8"/>`).join('')}
            `;
            }

        });
    </script>

@endsection