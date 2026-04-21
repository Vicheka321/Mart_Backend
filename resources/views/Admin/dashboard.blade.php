@extends('layouts.app')

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

@endsection