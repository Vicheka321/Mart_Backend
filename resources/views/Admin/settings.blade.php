{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Settings
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Manage your application settings.
            </p>
        </div>

        {{-- Settings Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            {{-- General Settings --}}
            {{-- <a href="{{ route('admin.settings.general') }}" --}}
            <a href="#"

                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                       rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1
                       transition-all duration-300">
                <div
                    class="w-14 h-14 rounded-2xl bg-blue-500 text-white flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.325 4.317a1 1 0 011.35-.936l1.658.829a1 1 0 00.894 0l1.658-.829a1 1 0 011.35.936l.187 1.87a1 1 0 00.573.82l1.682.841a1 1 0 01.444 1.342l-.842 1.683a1 1 0 000 .894l.842 1.683a1 1 0 01-.444 1.342l-1.682.841a1 1 0 00-.573.82l-.187 1.87a1 1 0 01-1.35.936l-1.658-.829a1 1 0 00-.894 0l-1.658.829a1 1 0 01-1.35-.936l-.187-1.87a1 1 0 00-.573-.82l-1.682-.841a1 1 0 01-.444-1.342l.842-1.683a1 1 0 000-.894l-.842-1.683a1 1 0 01.444-1.342l1.682-.841a1 1 0 00.573-.82l.187-1.87z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>

                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                    General Settings
                </h3>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Update store name, logo, contact information, and address.
                </p>

                <div
                    class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400">
                    Manage
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            {{-- Payment Settings --}}
            {{-- <a href="{{ route('admin.settings.payment') }}" --}}
            <a href="#"

                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                       rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1
                       transition-all duration-300">
                <div
                    class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                    </svg>
                </div>

                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                    Payment Settings
                </h3>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Configure KHQR, ABA, and cash payment options.
                </p>

                <div
                    class="mt-4 inline-flex items-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
                    Manage
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            {{-- Notification Settings --}}
            {{-- <a href="{{ route('admin.settings.notifications') }}" --}}
            <a href="#"

                class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                       rounded-3xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1
                       transition-all duration-300">
                <div
                    class="w-14 h-14 rounded-2xl bg-purple-500 text-white flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>
                </div>

                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                    Notifications
                </h3>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Send push notifications to your Flutter application.
                </p>

                <div
                    class="mt-4 inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400">
                    Manage
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

        </div>
    </div>
@endsection