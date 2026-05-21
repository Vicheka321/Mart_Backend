<style>
    @keyframes sidebarSlideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes itemFadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes activeGlow {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
        }

        50% {
            box-shadow: 0 0 12px 2px rgba(99, 102, 241, .15);
        }
    }

    @keyframes dotPulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.6);
            opacity: .4;
        }
    }

    @keyframes indicatorSlide {
        from {
            transform: scaleY(0);
            opacity: 0;
        }

        to {
            transform: scaleY(1);
            opacity: 1;
        }
    }

    @keyframes badgePop {
        0% {
            transform: scale(0);
        }

        70% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Wrapper */
    .sidebar-nav {
        animation: sidebarSlideIn .35s cubic-bezier(.4, 0, .2, 1) both;
    }

    /* Section labels */
    .nav-section-label {
        animation: itemFadeUp .3s .1s ease both;
    }

    /* Nav items staggered */
    .nav-item {
        animation: itemFadeUp .3s ease both;
        position: relative;
    }

    .nav-item:nth-child(1) {
        animation-delay: .08s;
    }

    .nav-item:nth-child(2) {
        animation-delay: .13s;
    }

    .nav-item:nth-child(3) {
        animation-delay: .18s;
    }

    .nav-item:nth-child(4) {
        animation-delay: .23s;
    }

    .nav-item:nth-child(5) {
        animation-delay: .28s;
    }

    .nav-item:nth-child(6) {
        animation-delay: .33s;
    }

    .nav-item:nth-child(7) {
        animation-delay: .38s;
    }

    .nav-item:nth-child(8) {
        animation-delay: .43s;
    }

    /* Active indicator */
    .nav-link-active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        bottom: 20%;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: linear-gradient(to bottom, #6366f1, #818cf8);
        animation: indicatorSlide .25s cubic-bezier(.34, 1.56, .64, 1) both;
    }

    .nav-link-active {
        animation: activeGlow 3s ease-in-out infinite;
    }

    /* Link hover */
    .nav-link {
        transition: transform .18s ease, background .18s ease, color .18s ease, box-shadow .18s ease;
    }

    .nav-link:hover {
        transform: translateX(4px);
        box-shadow: 2px 0 8px rgba(99, 102, 241, .08);
    }

    /* Icon */
    .icon-wrap {
        transition: transform .2s ease, background .2s ease;
    }

    .nav-link:hover .icon-wrap {
        transform: scale(1.08);
    }

    .nav-link-active .icon-wrap {
        background: linear-gradient(135deg, rgba(99, 102, 241, .2), rgba(129, 140, 248, .1));
    }

    /* Sub-links */
    .sub-link {
        transition: transform .15s ease, color .15s ease;
        position: relative;
    }

    .sub-link::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0;
        transition: opacity .15s ease, transform .15s ease;
    }

    .sub-link:hover::before,
    .sub-link.active::before {
        opacity: 1;
        transform: translateY(-50%) scale(1.2);
    }

    .sub-link:hover {
        transform: translateX(4px);
    }

    /* Chevron */
    .chevron {
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
    }

    /* Divider */
    .nav-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, rgba(99, 102, 241, .12), transparent);
        margin: 4px 0;
    }

    /* Badge pop */
    .notif-badge {
        animation: badgePop .35s cubic-bezier(.34, 1.56, .64, 1) both;
        animation-delay: .5s;
    }

    /* User strip hover */
    .user-strip {
        transition: background .18s ease;
    }

    /* Scrollbar */
    aside::-webkit-scrollbar {
        width: 4px;
    }

    aside::-webkit-scrollbar-track {
        background: transparent;
    }

    aside::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, .2);
        border-radius: 99px;
    }

    aside::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, .4);
    }

    [x-cloak] {
        display: none !important;
    }
</style>

<aside class="sidebar-nav w-72 h-full bg-white dark:bg-gray-800 flex flex-col overflow-hidden
              border-r border-gray-100 dark:border-gray-700/60">

    {{-- ── Scrollable nav ── --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-0.5">

            {{-- ─── Overview ─── --}}
            <p
                class="nav-section-label px-3 pt-2 pb-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[.12em]">
                Overview
            </p>

            {{-- Dashboard --}}
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('admin.dashboard')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2V10M22 6L14 6" />
                            <path
                                d="M2 6C2 4.6 2 3.9 2.27 3.37A2.5 2.5 0 0 1 3.37 2.27C3.9 2 4.6 2 6 2s2.1 0 2.64.27A2.5 2.5 0 0 1 9.73 3.37C10 3.9 10 4.6 10 6s0 2.1-.27 2.64A2.5 2.5 0 0 1 8.64 9.73C8.1 10 7.4 10 6 10s-2.1 0-2.63-.27A2.5 2.5 0 0 1 2.27 8.63C2 8.1 2 7.4 2 6Z" />
                            <path
                                d="M2 18c0-1.4 0-2.1.27-2.64a2.5 2.5 0 0 1 1.1-1.09C3.9 14 4.6 14 6 14s2.1 0 2.64.27a2.5 2.5 0 0 1 1.09 1.09C10 15.9 10 16.6 10 18s0 2.1-.27 2.64a2.5 2.5 0 0 1-1.09 1.1C8.1 22 7.4 22 6 22s-2.1 0-2.63-.27a2.5 2.5 0 0 1-1.1-1.1C2 20.1 2 19.4 2 18Z" />
                            <path
                                d="M14 18c0-1.4 0-2.1.27-2.64a2.5 2.5 0 0 1 1.1-1.09C15.9 14 16.6 14 18 14s2.1 0 2.64.27a2.5 2.5 0 0 1 1.09 1.09C22 15.9 22 16.6 22 18s0 2.1-.27 2.64a2.5 2.5 0 0 1-1.09 1.1C20.1 22 19.4 22 18 22s-2.1 0-2.63-.27a2.5 2.5 0 0 1-1.1-1.1C14 20.1 14 19.4 14 18Z" />
                        </svg>
                    </span>
                    <span>Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-500"
                            style="animation: dotPulse 2.5s ease-in-out infinite"></span>
                    @endif
                </a>
            </div>

            <div class="nav-divider mx-3 my-2"></div>

            {{-- ─── Catalog ─── --}}
            <p
                class="nav-section-label px-3 pt-2 pb-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[.12em]">
                Catalog
            </p>

            {{-- Products accordion --}}
            <div class="nav-item"
                x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('brands.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" :aria-expanded="open.toString()"
                    class="nav-link group w-full relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                           {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('brands.*')
    ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M7,2H3A1,1,0,0,0,2,3V21a1,1,0,0,0,1,1H7a1,1,0,0,0,1-1V3A1,1,0,0,0,7,2ZM5,21a2,2,0,1,1,2-2A2,2,0,0,1,5,21Zm2-9H3V3H7ZM6,19a1,1,0,1,1-1-1A1,1,0,0,1,6,19ZM14,2H10A1,1,0,0,0,9,3V21a1,1,0,0,0,1,1h4a1,1,0,0,0,1-1V3A1,1,0,0,0,14,2ZM12,21a2,2,0,1,1,2-2A2,2,0,0,1,12,21Zm2-9H10V3h4Zm-1,7a1,1,0,1,1-1-1A1,1,0,0,1,13,19ZM21,2H17a1,1,0,0,0-1,1V21a1,1,0,0,0,1,1h4a1,1,0,0,0,1-1V3A1,1,0,0,0,21,2ZM19,21a2,2,0,1,1,2-2A2,2,0,0,1,19,21Zm2-9H17V3h4Zm-1,7a1,1,0,1,1-1-1A1,1,0,0,1,20,19Z" />
                        </svg>
                    </span>
                    <span>Products</span>
                    <svg class="chevron w-3 h-3 ml-auto text-gray-400" :class="open ? 'rotate-90' : ''"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="ml-9 mt-1 space-y-0.5 border-l border-gray-100 dark:border-gray-700 pl-3">
                    <a href="{{ route('products.index') }}"
                        class="sub-link {{ request()->routeIs('products.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('products.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        All Products
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="sub-link {{ request()->routeIs('categories.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('categories.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        Categories
                    </a>
                    <a href="{{ route('brands.index') }}"
                        class="sub-link {{ request()->routeIs('brands.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('brands.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        Brands
                    </a>
                </div>
            </div>

            <div class="nav-divider mx-3 my-2"></div>

            {{-- ─── Commerce ─── --}}
            <p
                class="nav-section-label px-3 pt-2 pb-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[.12em]">
                Commerce
            </p>

            {{-- Orders --}}
            <div class="nav-item">
                <a href="{{ route('orders.index') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('orders.*')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M13.28 4.78a.75.75 0 0 0-1.06-1.06l-2.97 2.97-1.22-1.22a.75.75 0 0 0-1.06 1.06l1.75 1.75a.75.75 0 0 0 1.06 0l3.5-3.5Z" />
                            <path fill-rule="evenodd"
                                d="M4.86 6.883a.75.75 0 0 1 .632.852l-.336 2.265h2.484a1.25 1.25 0 0 1 1.185.855l.159.474a.25.25 0 0 0 .237.171h1.558a.25.25 0 0 0 .237-.17l.159-.475a1.25 1.25 0 0 1 1.185-.855h2.484l-.336-2.265a.75.75 0 1 1 1.484-.22l.413 2.792c.063.425.095.853.095 1.282v1.661a3.25 3.25 0 0 1-3.25 3.25h-6.5a3.25 3.25 0 0 1-3.25-3.25v-1.66c0-.43.032-.858.094-1.283l.414-2.792a.75.75 0 0 1 .852-.632Zm.14 4.706v-.089h2.46l.1.303a1.75 1.75 0 0 0 1.66 1.197h1.56a1.75 1.75 0 0 0 1.66-1.197l.1-.303h2.46v1.75a1.75 1.75 0 0 1-1.75 1.75h-6.5a1.75 1.75 0 0 1-1.75-1.75v-1.66Z" />
                        </svg>
                    </span>
                    <span>Orders</span>
                    @if(request()->routeIs('orders.*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-500"
                            style="animation: dotPulse 2.5s ease-in-out infinite"></span>
                    @endif
                </a>
            </div>

            {{-- Customers --}}
            <div class="nav-item">
                <a href="{{ route('customers.index') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('customers.*')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 32 32" fill="currentColor">
                            <path
                                d="m26,30h-2v-5c-.0033-2.76-2.24-4.997-5-5h-6c-2.76.003-4.997 2.24-5,5v5h-2v-5c.004-3.864 3.136-6.996 7-7h6c3.864.004 6.996 3.136 7,7v5Z" />
                            <path
                                d="m22,6v4c0,1.103-.897,2-2,2h-1c-.552,0-1,.448-1,1s.448,1,1,1h1c2.206,0,4-1.794,4-4v-4h-2Z" />
                            <path
                                d="m16,16c-3.86,0-7-3.14-7-7s3.14-7,7-7c1.988,0,3.89.85,5.217,2.333l-1.49 1.334c-.948-1.059-2.307-1.667-3.727-1.667-2.757,0-5,2.243-5,5s2.243,5,5,5v2Z" />
                        </svg>
                    </span>
                    <span>Customers</span>
                    @if(request()->routeIs('customers.*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-500"
                            style="animation: dotPulse 2.5s ease-in-out infinite"></span>
                    @endif
                </a>
            </div>

            <div class="nav-divider mx-3 my-2"></div>

            {{-- ─── Marketing ─── --}}
            <p
                class="nav-section-label px-3 pt-2 pb-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[.12em]">
                Marketing
            </p>

            {{-- Marketing accordion --}}
            <div class="nav-item"
                x-data="{ open: {{ request()->routeIs('coupons.*') || request()->routeIs('banners.*') || request()->routeIs('promotions.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" :aria-expanded="open.toString()"
                    class="nav-link group w-full relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                           {{ request()->routeIs('coupons.*') || request()->routeIs('banners.*') || request()->routeIs('promotions.*')
    ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <ellipse cx="18" cy="10" rx="4" ry="8" />
                            <path
                                d="M18 2C14.9 2 8.47 4.38 4.77 5.85 3.08 6.53 2 8.18 2 10c0 1.82 1.08 3.47 2.77 4.15C8.47 15.62 14.9 18 18 18" />
                            <path d="M11 22 9.06 20.93C6.94 19.77 5.75 17.41 6.05 15" />
                        </svg>
                    </span>
                    <span>Marketing</span>
                    <svg class="chevron w-3 h-3 ml-auto text-gray-400" :class="open ? 'rotate-90' : ''"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="ml-9 mt-1 space-y-0.5 border-l border-gray-100 dark:border-gray-700 pl-3">
                    <a href="{{ route('coupons.index') }}"
                        class="sub-link {{ request()->routeIs('coupons.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('coupons.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        Coupons
                    </a>
                    <a href="{{ route('banners.index') }}"
                        class="sub-link {{ request()->routeIs('banners.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('banners.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        Banners
                    </a>
                    <a href="{{ route('promotions.index') }}"
                        class="sub-link {{ request()->routeIs('promotions.*') ? 'active' : '' }} block py-1.5 text-xs font-medium
                              {{ request()->routeIs('promotions.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                        Promotions
                    </a>
                </div>
            </div>

            <div class="nav-divider mx-3 my-2"></div>

            {{-- ─── System ─── --}}
            <p
                class="nav-section-label px-3 pt-2 pb-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[.12em]">
                System
            </p>

            {{-- Reports --}}
            <div class="nav-item">
                <a href="{{ route('reports.index') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('reports.*')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M9 21H15M9 21V16M9 21H3.6A.6.6 0 0 1 3 20.4V16.6A.6.6 0 0 1 3.6 16H9M15 21V9M15 21H20.4A.6.6 0 0 0 21 20.4V3.6A.6.6 0 0 0 20.4 3H15.6A.6.6 0 0 0 15 3.6V9M15 9H9.6A.6.6 0 0 0 9 9.6V16" />
                        </svg>
                    </span>
                    <span>Reports</span>
                </a>
            </div>

            {{-- Notifications --}}
            <div class="nav-item">
                {{-- @php $unreadCount = auth()->user()?->unreadNotifications()->count() ?? 0; @endphp --}}
                <a href="{{ route('notifitions.index') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('notifitions.*')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 512 512" fill="none" stroke="currentColor">
                            <path
                                d="M427.68,351.43C402,320,383.87,304,383.87,217.35c0-79.35-40.52-107.63-73.87-121.35-4.43-1.82-8.6-6-9.95-10.55C294.2,65.54,277.8,48,256,48S217.79,65.55,212,85.47c-1.35,4.6-5.52,8.71-9.95,10.53-33.39,13.75-73.87,41.92-73.87,121.35C128.13,304,110,320,84.32,351.43,73.68,364.45,83,384,101.61,384H410.49C429,384,438.26,364.39,427.68,351.43Z"
                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" />
                            <path d="M320,384v16a64,64,0,0,1-128,0V384"
                                style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" />
                        </svg>
                    </span>
                    <span>Notifications</span>
                    {{-- @if($unreadCount > 0) --}}
                        <span class="notif-badge ml-auto inline-flex items-center justify-center
                                         px-1.5 py-0.5 min-w-[20px] h-5
                                         rounded-full bg-indigo-500 text-white text-[10px] font-bold leading-none">
                            {{-- {{ $unreadCount > 99 ? '99+' : $unreadCount }} --}}
                        </span>
                    {{-- @endif --}}
                </a>
            </div>

            {{-- Settings --}}
            <div class="nav-item">
                <a href="{{ route('settings.index') }}"
                    class="nav-link group relative flex items-center gap-3 px-3 h-10 rounded-xl text-sm font-medium
                          {{ request()->routeIs('settings.*')
    ? 'nav-link-active bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                    <span class="icon-wrap w-6 h-6 flex items-center justify-center rounded-lg flex-shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 1024 1024" fill="currentColor">
                            <path
                                d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357 357 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a352 352 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357 357 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088-24.512 11.968a294 294 0 0 0-34.816 20.096l-22.656 15.36-116.224-25.088-65.28 113.152 79.68 88.192-1.92 27.136a293 293 0 0 0 0 40.192l1.92 27.136-79.808 88.192 65.344 113.152 116.224-25.024 22.656 15.296a294 294 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152 24.448-11.904a288 288 0 0 0 34.752-20.096l22.592-15.296 116.288 25.024 65.28-113.152-79.744-88.192 1.92-27.136a293 293 0 0 0 0-40.256l-1.92-27.136 79.808-88.128-65.344-113.152-116.288 24.96-22.592-15.232a288 288 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384 192 192 0 0 1 0-384m0 64a128 128 0 1 0 0 256 128 128 0 0 0 0-256" />
                        </svg>
                    </span>
                    <span>Settings</span>
                </a>
            </div>

        </div>
    </nav>

</aside>