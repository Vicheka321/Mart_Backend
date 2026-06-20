@extends('layouts.app')

@section('content')

<div class="space-y-4">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800
        border border-gray-200 dark:border-gray-700
        rounded-2xl p-5">

        <h1 class="text-lg font-semibold
            text-gray-900 dark:text-white">

            Orders Report

        </h1>

        <p class="mt-1 text-sm
            text-gray-500 dark:text-gray-400">

            View and export order transactions

        </p>

    </div>

    {{-- Table Card --}}
    <div class="table-card
        bg-white dark:bg-gray-800
        border border-gray-200 dark:border-gray-700
        rounded-2xl overflow-hidden">

        {{-- Filter --}}
        <div class="p-5 border-b
            border-gray-100 dark:border-gray-700">

            <form method="GET">

                <div class="flex flex-col lg:flex-row
                    gap-3 lg:items-center
                    lg:justify-between">

                    <div class="flex flex-wrap gap-2">

                        <select
                            name="status"
                            class="px-3 py-2 rounded-xl
                            border border-gray-200
                            dark:border-gray-600
                            bg-gray-50 dark:bg-gray-700">

                            <option value="">
                                All Status
                            </option>

                            <option value="pending">
                                Pending
                            </option>

                            <option value="processing">
                                Processing
                            </option>

                            <option value="completed">
                                Completed
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>

                        <select
                            name="payment_method"
                            class="px-3 py-2 rounded-xl
                            border border-gray-200
                            dark:border-gray-600
                            bg-gray-50 dark:bg-gray-700">

                            <option value="">
                                Payment Method
                            </option>

                            <option value="cash">
                                Cash
                            </option>

                            <option value="aba">
                                ABA
                            </option>

                            <option value="khqr">
                                KHQR
                            </option>

                            <option value="wing">
                                Wing
                            </option>

                        </select>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search order..."
                            class="px-3 py-2 rounded-xl
                            border border-gray-200
                            dark:border-gray-600
                            bg-gray-50 dark:bg-gray-700">

                        <button
                            class="px-4 py-2 rounded-xl
                            bg-indigo-600
                            hover:bg-indigo-700
                            text-white">

                            Filter

                        </button>

                    </div>

                    <div class="flex gap-2">

                        <a href="#"
                            class="px-4 py-2 rounded-xl
                            border border-green-200
                            text-green-600">

                            Export Excel

                        </a>

                        <a href="#"
                            class="px-4 py-2 rounded-xl
                            border border-red-200
                            text-red-600">

                            Export PDF

                        </a>

                    </div>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                <tr class="border-b
                    border-gray-100
                    dark:border-gray-700
                    bg-gray-50/60
                    dark:bg-gray-700/30">

                    <th class="px-5 py-3 text-left">
                        Order #
                    </th>

                    <th class="px-5 py-3 text-left">
                        Customer
                    </th>

                    <th class="px-5 py-3 text-left">
                        Payment
                    </th>

                    <th class="px-5 py-3 text-left">
                        Amount
                    </th>

                    <th class="px-5 py-3 text-left">
                        Status
                    </th>

                    <th class="px-5 py-3 text-left">
                        Date
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($orders as $order)

                    <tr class="border-b
                        border-gray-100
                        dark:border-gray-700">

                        <td class="px-5 py-4">
                            #{{ $order->id }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $order->user->full_name ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ strtoupper($order->payment_method) }}
                        </td>

                        <td class="px-5 py-4 font-semibold text-green-600">
                            ${{ number_format(
                                $order->total_amount,
                                2
                            ) }}
                        </td>

                        <td class="px-5 py-4">

                            @if($order->status == 'pending')

                                <span class="px-2.5 py-1
                                    rounded-full text-xs
                                    bg-amber-100
                                    text-amber-700">

                                    Pending

                                </span>

                            @elseif($order->status == 'processing')

                                <span class="px-2.5 py-1
                                    rounded-full text-xs
                                    bg-blue-100
                                    text-blue-700">

                                    Processing

                                </span>

                            @elseif($order->status == 'completed')

                                <span class="px-2.5 py-1
                                    rounded-full text-xs
                                    bg-green-100
                                    text-green-700">

                                    Completed

                                </span>

                            @else

                                <span class="px-2.5 py-1
                                    rounded-full text-xs
                                    bg-red-100
                                    text-red-700">

                                    Cancelled

                                </span>

                            @endif

                        </td>

                        <td class="px-5 py-4">
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="py-12 text-center
                            text-gray-400">

                            No orders found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t
            border-gray-100
            dark:border-gray-700">

            {{ $orders->links() }}

        </div>

    </div>

</div>

@endsection