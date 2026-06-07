<style>
    /* ── Navbar entry ── */
    @keyframes navSlideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes logoFadeIn {
        from {
            opacity: 0;
            transform: translateX(-12px) scale(.95);
        }

        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @keyframes navItemFadeIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Badge ── */
    @keyframes badgePop {
        0% {
            transform: scale(0);
        }

        70% {
            transform: scale(1.3);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes badgePulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.55);
        }

        50% {
            box-shadow: 0 0 0 5px rgba(239, 68, 68, 0);
        }
    }

    /* ── Dropdown ── */
    @keyframes dropdownReveal {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes notifItemIn {
        from {
            opacity: 0;
            transform: translateX(8px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ── Avatar ── */
    @keyframes avatarEntrance {
        from {
            opacity: 0;
            transform: scale(.8) rotate(-8deg);
        }

        to {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    /* ── Bell shake ── */
    @keyframes bellShake {

        0%,
        100% {
            transform: rotate(0);
        }

        15% {
            transform: rotate(14deg);
        }

        30% {
            transform: rotate(-12deg);
        }

        45% {
            transform: rotate(10deg);
        }

        60% {
            transform: rotate(-8deg);
        }

        75% {
            transform: rotate(4deg);
        }
    }

    /* ── Sun spin ── */
    @keyframes sunSpin {
        from {
            transform: rotate(0deg) scale(.7);
            opacity: 0;
        }

        to {
            transform: rotate(360deg) scale(1);
            opacity: 1;
        }
    }

    @keyframes moonFadeIn {
        from {
            transform: scale(.7) rotate(30deg);
            opacity: 0;
        }

        to {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    /* Applied classes */
    nav {
        animation: navSlideDown .4s cubic-bezier(.22, 1, .36, 1) both;
    }

    .nav-logo {
        animation: logoFadeIn .5s .05s cubic-bezier(.22, 1, .36, 1) both;
    }

    .nav-item-1 {
        animation: navItemFadeIn .4s .10s ease both;
    }

    .nav-item-2 {
        animation: navItemFadeIn .4s .18s ease both;
    }

    .nav-item-3 {
        animation: navItemFadeIn .4s .26s ease both;
    }

    .nav-avatar {
        animation: avatarEntrance .5s .32s cubic-bezier(.34, 1.56, .64, 1) both;
    }

    /* Badge */
    #notifCount:not(.hidden) {
        animation: badgePop .35s cubic-bezier(.34, 1.56, .64, 1) both,
            badgePulse 2s 1s ease-in-out infinite;
    }

    /* Bell button hover */
    .bell-btn {
        transition: background .2s ease, transform .2s ease;
    }

    .bell-btn:hover svg {
        animation: bellShake .5s ease;
    }

    .bell-btn:active {
        transform: scale(.92);
    }

    /* Dark mode button */
    .theme-btn {
        transition: transform .2s ease, color .2s ease;
    }

    .theme-btn:hover {
        transform: scale(1.15) rotate(12deg);
    }

    .theme-btn:active {
        transform: scale(.9);
    }

    .dark .theme-btn svg.dark\:block {
        animation: sunSpin .5s cubic-bezier(.22, 1, .36, 1);
    }

    .theme-btn svg.dark\:hidden {
        animation: moonFadeIn .4s ease;
    }

    /* Avatar */
    .nav-avatar img {
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1),
            box-shadow .25s ease,
            border-color .2s ease;
    }

    .nav-avatar img:hover {
        transform: scale(1.1) rotate(-3deg);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .35);
        border-color: #6366f1;
    }

    .nav-avatar img:active {
        transform: scale(.95);
    }

    /* Dropdown (profile) */
    [x-cloak] {
        display: none !important;
    }

    /* Notification dropdown */
    #notifDropdown:not(.hidden) {
        animation: dropdownReveal .22s cubic-bezier(.34, 1.2, .64, 1) both;
    }

    #notifDropdown .notif-item {
        animation: notifItemIn .28s ease both;
    }

    /* Profile dropdown items */
    .profile-link {
        position: relative;
        overflow: hidden;
        transition: background .18s ease, color .18s ease, transform .18s ease, box-shadow .18s ease;
    }

    .profile-link::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(99, 102, 241, .08), transparent);
        opacity: 0;
        transition: opacity .2s ease;
    }

    .profile-link:hover::after {
        opacity: 1;
    }

    .profile-link:hover {
        transform: translateX(4px);
    }

    .profile-link:active {
        transform: translateX(2px) scale(.98);
    }

    /* Logo text shimmer on hover */
    .logo-text {
        background: linear-gradient(90deg, #6366f1 0%, #818cf8 50%, #6366f1 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: background-position .4s ease;
    }

    .logo-text:hover {
        background-position: right center;
    }

    /* Separator line under nav */
    .nav-border {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, .15), transparent);
    }
</style>

<nav class="w-full relative z-40">
    <div class="flex items-center justify-between px-6 pt-3 pb-3
                bg-white dark:bg-gray-800
                border-b border-gray-100 dark:border-gray-700/60
                transition-colors duration-300">

        {{-- ── LEFT: Logo ── --}}
        <div class="flex items-center gap-5">
            <div class="nav-logo w-60 flex items-center gap-2.5">
                <div class="relative">
                    <img src="{{ asset('images/icons/logo.png') }}" class="w-9 h-9 rounded-xl object-cover
                                drop-shadow-[0_2px_8px_rgba(99,102,241,.3)]
                                transition-transform duration-300 hover:scale-110 hover:rotate-6">
                </div>
                <h1 class="logo-text text-lg font-bold tracking-tight">Darita Mart</h1>
            </div>
        </div>

        {{-- ── RIGHT: Actions ── --}}
        <div class="flex items-center gap-4">

            {{-- Dark / Light toggle --}}
            <div class="nav-item-1">
                <button type="button" onclick="toggleDarkMode()" class="theme-btn cursor-pointer p-2 rounded-xl
                           text-gray-400 dark:text-gray-300
                           hover:text-indigo-600 dark:hover:text-yellow-400
                           hover:bg-indigo-50 dark:hover:bg-gray-700
                           focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">

                    {{-- Moon (light mode) --}}
                    <svg class="w-5 h-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M9.37 5.51C9.19 6.15 9.1 6.82 9.1 7.5c0 4.08 3.32 7.4 7.4 7.4.68 0 1.35-.09 1.99-.27C17.45 17.19 14.93 19 12 19c-3.86 0-7-3.14-7-7 0-2.93 1.81-5.45 4.37-6.49ZM12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4C12.92 3.04 12.46 3 12 3Z" />
                    </svg>

                    {{-- Sun (dark mode) --}}
                    <svg class="w-5 h-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M6.76 4.84 5.34 3.42 3.92 4.84l1.42 1.42 1.42-1.42ZM1 13h3v-2H1v2Zm10 10h2v-3h-2v3Zm9.66-18.16-1.42-1.42-1.42 1.42 1.42 1.42 1.42-1.42ZM17.24 19.16l1.42 1.42 1.42-1.42-1.42-1.42-1.42 1.42ZM20 13h3v-2h-3v2ZM11 1v3h2V1h-2Zm0 6a5 5 0 1 0 0 10 5 5 0 0 0 0-10ZM4.84 17.24l-1.42 1.42 1.42 1.42 1.42-1.42-1.42-1.42Z" />
                    </svg>
                </button>
            </div>

            {{-- Notification bell --}}
            <div class="nav-item-2">
                <button onclick="toggleNotification()" class="bell-btn relative p-2 rounded-xl
                           text-gray-500 dark:text-gray-300
                           hover:bg-gray-100 dark:hover:bg-slate-700
                           focus:outline-none focus:ring-2 focus:ring-indigo-400
                           transition-all duration-200">

                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none"
                        stroke="currentColor">
                        <path
                            d="M427.68,351.43C402,320,383.87,304,383.87,217.35,383.87,138,343.35,109.73,310,96c-4.43-1.82-8.6-6-9.95-10.55C294.2,65.54,277.8,48,256,48S217.79,65.55,212,85.47c-1.35,4.6-5.52,8.71-9.95,10.53-33.39,13.75-73.87,41.92-73.87,121.35C128.13,304,110,320,84.32,351.43,73.68,364.45,83,384,101.61,384H410.49C429,384,438.26,364.39,427.68,351.43Z"
                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" />
                        <path d="M320,384v16a64,64,0,0,1-128,0V384"
                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px" />
                    </svg>

                    {{-- Badge --}}
                    <span id="notifCount" class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 text-[10px]
                                 bg-red-500 text-white rounded-full
                                 flex items-center justify-center hidden
                                 font-bold leading-none">0</span>
                </button>

                {{-- Notification Dropdown --}}
                <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80
                            bg-white dark:bg-[#1e293b]
                            border border-gray-200 dark:border-gray-700
                            rounded-2xl shadow-2xl overflow-hidden z-[65]">

                    <div class="px-4 py-3 flex items-center justify-between
                                border-b border-gray-100 dark:border-gray-700">
                        <span class="font-semibold text-sm text-gray-800 dark:text-white">Notifications</span>
                        <span id="notifBadgeHeader" class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                     bg-indigo-50 dark:bg-indigo-500/20
                                     text-indigo-600 dark:text-indigo-400 hidden">
                            new
                        </span>
                    </div>

                    <div id="notifList"
                        class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700/60">
                        {{-- Empty state --}}
                        {{-- <div id="notifEmpty"
                            class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-500 gap-2">
                            <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-xs">No notifications yet</p>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- Avatar / profile dropdown --}}
            <div class="nav-item-3 nav-avatar relative" x-data="{ open: false }">
                <img @click="open = !open" src="{{ asset('images/icons/profile.jpg') }}"
                    class="w-9 h-9 rounded-full cursor-pointer border-2 border-gray-200 dark:border-gray-600"
                    alt="Profile">

                <div x-cloak x-show="open" @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-6  top-full mt-3 w-56 z-50
            bg-white dark:bg-gray-900
            border border-gray-100 dark:border-gray-700
            rounded-2xl shadow-2xl p-3 space-y-1 z-[200]">

                    {{-- User info header --}}
                    <div class="flex items-center gap-3 px-3 py-2 mb-1">
                        <img src="{{ asset('images/icons/profile.jpg') }}"
                            class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-700">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">Admin</p>
                            <p class="text-[10px] text-gray-400 truncate">admin@daritamart.com</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700/60 mb-1"></div>

                    <a href="#" class="profile-link flex items-center gap-3 px-3 py-2 rounded-xl
                                      text-gray-600 dark:text-gray-300
                                      hover:bg-indigo-50 dark:hover:bg-gray-800
                                      hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm">My Profile</span>
                    </a>

                    <a href="#" class="profile-link flex items-center gap-3 px-3 py-2 rounded-xl
                                      text-gray-600 dark:text-gray-300
                                      hover:bg-indigo-50 dark:hover:bg-gray-800
                                      hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm">My Account</span>
                    </a>

                    <a href="#" class="profile-link flex items-center gap-3 px-3 py-2 rounded-xl
                                      text-gray-600 dark:text-gray-300
                                      hover:bg-indigo-50 dark:hover:bg-gray-800
                                      hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="text-sm">My Tasks</span>
                    </a>

                    <div class="border-t border-gray-100 dark:border-gray-700/60 pt-1">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="w-full py-2 rounded-xl text-sm font-semibold
                                       border border-red-200 dark:border-red-800/50
                                       text-red-500 dark:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/20
                                       transition-all duration-200
                                       hover:border-red-300 dark:hover:border-red-700
                                       active:scale-95">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Subtle gradient separator --}}
    <div class="nav-border"></div>
</nav>

<script src="https://js.pusher.com/8.2.0/pusher.min.js" defer></script>

<script defer>
    function initNavbar() {

        window.confirmLogout = function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will be logged out!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, logout'
            }).then(r => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
        };

        window.toggleDarkMode = function () {
            const h = document.documentElement;
            h.classList.toggle('dark');
            localStorage.setItem('theme', h.classList.contains('dark') ? 'dark' : 'light');
        };
        if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark');

        window.toggleNotification = function () {
            const dd = document.getElementById('notifDropdown');
            if (!dd) return;
            const opening = dd.classList.contains('hidden');
            dd.classList.toggle('hidden');
            if (opening) {
                dd.style.animation = 'none';
                dd.offsetHeight;
                dd.style.animation = '';
            }
        };

        document.addEventListener('click', function (e) {
            const dd = document.getElementById('notifDropdown');
            const btn = document.querySelector('[onclick="toggleNotification()"]');
            if (!dd || !btn) return;
            if (!dd.contains(e.target) && !btn.contains(e.target)) dd.classList.add('hidden');
        });

        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        if (typeof window.notifCount === 'undefined') window.notifCount = 0;

        // ── Shared item renderer ──────────────────────────────────────────────
        window.renderNotifItem = function (order, prepend = false) {
            const list = document.getElementById('notifList');
            const empty = document.getElementById('notifEmpty');
            if (!list) return;
            if (empty) empty.remove();

            const div = document.createElement('div');
            div.className = [
                'flex items-start gap-3 px-4 py-3 cursor-pointer',
                'transition-colors duration-150',
                'hover:bg-gray-50 dark:hover:bg-slate-700/50',
            ].join(' ');

            const total = Number(order.total ?? order.total_amount ?? 0)
                .toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const time = order.time ?? 'Just now';

            div.innerHTML = `
            <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center
                        bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707
                           1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                        Order #${order.id}
                    </p>
                    <span class="text-[10px] text-gray-400 whitespace-nowrap">${time}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Payment received successfully
                </p>
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                        $${total}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                 bg-emerald-50 dark:bg-emerald-500/10
                                 text-emerald-700 dark:text-emerald-400">
                        Paid
                    </span>
                </div>
            </div>
            <span class="w-2 h-2 bg-indigo-500 rounded-full mt-2 flex-shrink-0 animate-pulse"></span>
        `;
            div.onclick = () => (window.location.href = `/admin/orders/${order.id}`);
            prepend ? list.prepend(div) : list.appendChild(div);
        };

        // ── Pusher (init once) ────────────────────────────────────────────────
        if (!window._navbarPusherReady && typeof Pusher !== 'undefined') {
            window._navbarPusherReady = true;
            Pusher.logToConsole = false;

            window.pusher = new Pusher(
                "{{ config('broadcasting.connections.pusher.key') }}",
                { cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}" }
            );

            window.pusher.subscribe('orders').bind('new-order', function (data) {
                const order = data.order;

                window.notifCount++;
                _updateBadge(window.notifCount);

                const audio = document.getElementById('orderSound');
                if (audio) audio.play().catch(() => { });

                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('New Order', {
                        body: `Order #${order.id} • $${Number(order.total_amount).toFixed(2)}`,
                        icon: '/images/icons/logo.png'
                    });
                }

                // if (typeof Swal !== 'undefined') {
                //     Swal.fire({
                //         toast: true, position: 'top-end', icon: 'success',
                //         title: `New Order #${order.id}`,
                //         text: `$${Number(order.total_amount).toFixed(2)}`,
                //         showConfirmButton: false, timer: 3000, timerProgressBar: true
                //     });
                // }

                // Prepend item with normalised shape
                window.renderNotifItem({
                    id: order.id,
                    total: order.total_amount,
                    time: 'Just now'
                }, true);
            });
        }
    }

    // ── Badge helper ─────────────────────────────────────────────────────────
    function _updateBadge(n) {
        const badge = document.getElementById('notifCount');
        const hb = document.getElementById('notifBadgeHeader');
        if (!badge) return;
        const label = n > 99 ? '99+' : n;
        badge.innerText = label;
        badge.classList.remove('hidden');
        badge.style.animation = 'none';
        badge.offsetHeight;
        badge.style.animation = '';
        if (hb) { hb.textContent = label + ' new'; hb.classList.remove('hidden'); }
    }

    // ── Load existing notifications from API ─────────────────────────────────
    async function loadNotifications() {
        try {
            const res = await fetch('/admin/orders/notifications');
            const orders = await res.json();

            const list = document.getElementById('notifList');
            if (!list) return;
            list.innerHTML = '';

            if (!orders.length) {
                list.innerHTML = `
                <div id="notifEmpty"
                     class="flex flex-col items-center justify-center py-12
                            text-gray-400 dark:text-gray-500 gap-3">
                    <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159
                               c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm font-medium">No notifications yet</p>
                </div>`;
                return;
            }

            window.notifCount = orders.length;
            _updateBadge(window.notifCount);

            orders.forEach((o, i) => {
                // small stagger so items animate in sequentially
                setTimeout(() => window.renderNotifItem(o), i * 60);
            });

        } catch (err) {
            console.error('Notification load failed:', err);
        }
    }

    document.addEventListener('DOMContentLoaded', () => { initNavbar(); loadNotifications(); });
    document.addEventListener('livewire:navigated', initNavbar);
</script>