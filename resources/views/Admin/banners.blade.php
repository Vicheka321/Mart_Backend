@extends('layouts.app')

@section('content')
    <div x-data="bannerManager()" class="min-h-screen bg-[#0d0d0f] text-[#f0ede8] font-sans">



        {{-- Page Container --}}
        <div class="max-w-7xl mx-auto px-6 py-10">

            {{-- PAGE HEADER --}}
            <div class="mb-8">
                <p class="text-[10px] uppercase tracking-[0.16em] text-[#c9a84c] font-medium">
                    Content / Banners
                </p>
                <h1 class="mt-2 text-4xl font-serif font-medium text-[#f0ede8]">
                    Banner Management
                </h1>
                <p class="mt-2 text-sm text-[#888580]">
                    Manage homepage banners and promotions.
                </p>
            </div>

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                {{-- Total Banners --}}
                <div class="bg-[#161618] border border-[#c9a84c]/25 rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.16em] text-[#888580] font-medium">
                        Total Banners
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-[#c9a84c]">
                        {{ number_format($banners->count()) }}
                    </p>
                    <div class="mt-4 h-1 rounded-full bg-[#252528] overflow-hidden">
                        <div class="h-full w-full bg-[#c9a84c]"></div>
                    </div>
                </div>

                {{-- Active Banners --}}
                <div class="bg-[#161618] border border-white/7 rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.16em] text-[#888580] font-medium">
                        Active Banners
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-[#5aad7a]">
                        {{ number_format($banners->where('status', 1)->count()) }}
                    </p>
                    <div class="mt-4 h-1 rounded-full bg-[#252528] overflow-hidden">
                        <div class="h-full bg-[#5aad7a]"
                            style="width: {{ $banners->count() ? round(($banners->where('status', 1)->count() / $banners->count()) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>

                {{-- Inactive Banners --}}
                <div class="bg-[#161618] border border-white/7 rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.16em] text-[#888580] font-medium">
                        Inactive Banners
                    </p>
                    <p class="mt-2 text-3xl font-semibold text-[#e05555]">
                        {{ number_format($banners->where('status', 0)->count()) }}
                    </p>
                    <div class="mt-4 h-1 rounded-full bg-[#252528] overflow-hidden">
                        <div class="h-full bg-[#e05555]"
                            style="width: {{ $banners->count() ? round(($banners->where('status', 0)->count() / $banners->count()) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-[#161618] border border-white/7 rounded-2xl overflow-hidden">

                {{-- CARD HEADER --}}
                <div
                    class="p-4 sm:p-5 border-b border-white/7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                    <h2 class="text-sm font-semibold text-[#f0ede8]">
                        Banner List
                    </h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">

                        {{-- SEARCH --}}
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#555250]"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>

                            <input type="text" id="bannerSearch" placeholder="Search banners..." oninput="filterBanners()"
                                autocomplete="off" class="w-full sm:w-64 pl-10 pr-4 py-2 text-sm rounded-xl
                               border border-white/7
                               bg-[#1e1e21]
                               text-[#f0ede8]
                               placeholder-[#555250]
                               focus:outline-none
                               focus:ring-4
                               focus:ring-[#c9a84c]/10
                               focus:border-[#c9a84c]/25">
                        </div>

                        {{-- ADD BUTTON --}}
                        <button type="button" @click="openCreate = true" class="inline-flex items-center justify-center gap-2 px-4 py-2
                           text-sm font-medium rounded-xl
                           bg-[#c9a84c]
                           hover:bg-[#e8c97a]
                           text-[#0d0d0f]
                           transition-all duration-200">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Add Banner
                        </button>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-[#1e1e21]">
                            <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-[#555250]">
                                <th class="px-6 py-3">Banner</th>
                                <th class="px-6 py-3">Order</th>
                                <th class="px-6 py-3">Schedule</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody id="bannersTable" class="divide-y divide-white/7">
                            @forelse($banners as $banner)
                                <tr class="banner-row hover:bg-[#1e1e21] transition-all duration-200"
                                    data-id="{{ $banner->id }}" data-title="{{ strtolower($banner->title) }}">
                                    {{-- BANNER --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $banner->image }}" alt="{{ $banner->title }}"
                                                class="w-20 h-12 rounded-lg object-cover border border-white/7 bg-[#252528]">

                                            <div class="min-w-0">
                                                <div class="font-medium text-[#f0ede8] truncate">
                                                    {{ $banner->title }}
                                                </div>
                                                <div class="text-xs text-[#555250]">
                                                    Banner #{{ $banner->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ORDER --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center
                                                 px-3 py-1 rounded-full text-xs font-medium
                                                 bg-[#252528] text-[#888580] border border-white/7">
                                            {{ $banner->sort_order ?? 0 }}
                                        </span>
                                    </td>

                                    {{-- SCHEDULE --}}
                                    <td class="px-6 py-4 text-[#888580] text-xs whitespace-nowrap">
                                        <div>{{ $banner->start_date ?? '—' }}</div>
                                        <div class="text-[#555250]">{{ $banner->end_date ?? '—' }}</div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @if($banner->status)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                         bg-[#5aad7a]/10 text-[#5aad7a] border border-[#5aad7a]/20">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                         bg-[#e05555]/10 text-[#e05555] border border-[#e05555]/20">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center gap-2">
                                            <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                   rounded-lg border border-white/7
                                                   bg-[#1e1e21] text-[#888580]
                                                   hover:text-[#c9a84c]
                                                   hover:border-[#c9a84c]/25
                                                   transition-all duration-200">
                                                Edit
                                            </button>

                                            <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium
                                                   rounded-lg border border-white/7
                                                   bg-[#1e1e21] text-[#888580]
                                                   hover:text-[#e05555]
                                                   hover:border-[#e05555]/20
                                                   transition-all duration-200">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-[#555250]">
                                        No banners found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="searchEmpty" class="hidden px-6 py-12 text-center text-sm text-[#555250]">
                        No banners match your search.
                    </div>
                </div>

                {{-- PAGINATION FOOTER --}}
                <div class="px-6 py-4 border-t border-white/7 flex items-center justify-between">
                    <p class="text-xs text-[#555250]">
                        Showing {{ number_format($banners->count()) }} banners
                    </p>
                </div>
            </div>
        </div>

        {{-- Alpine.js --}}
        <script>
            function bannerManager() {
                return {
                    openCreate: false,
                    openEdit: false,
                    deleteModal: false,
                    deleteId: null,

                    edit: {
                        id: '',
                        title: '',
                        sort_order: '',
                        status: 1,
                        start_date: '',
                        end_date: ''
                    },

                    editBanner(banner) {
                        this.edit = banner;
                        this.openEdit = true;
                    }
                }
            }
        </script>
@endsection