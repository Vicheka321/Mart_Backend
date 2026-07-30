<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mart Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js" defer></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>

    {{-- Dark mode --}}
    <script>
        window.toggleDarkMode = function () {
            const html = document.documentElement;

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        };

        (function () {
            const theme = localStorage.getItem('theme');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        // NOTE: 'livewire:navigated' removed — we are not using Livewire.
        // Dark mode is now only applied once on real page load (above),
        // which is correct because our SPA swap never touches <html> or <body>.
    </script>

    {{-- Toast animation --}}
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(100%); opacity: 0; }
        }

        .toast-enter { animation: slideIn 0.3s ease forwards; }
        .toast-exit  { animation: slideOut 0.3s ease forwards; }
    </style>
</head>

<body
    class="h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 dark:text-white font-[Inter] transition-colors duration-300">

    <div class="flex flex-col h-screen">

        {{-- Navbar — rendered ONCE, never reloaded, no @persist needed
             because our router only ever replaces #main-content below --}}
        <nav class="flex-shrink-0">
            @auth
                @can('access_admin_panel')
                    @include('Admin.navbar')
                @endcan
            @endauth
        </nav>

        {{-- Toast container --}}
        <div id="toastContainer" class="fixed top-5 right-5 space-y-3 z-50"></div>

        <div class="flex flex-1 min-h-0">

            {{-- Sidebar — rendered ONCE, never reloaded --}}
            <aside class="w-72 overflow-y-auto">
                @auth
                    @can('access_admin_panel')
                        @include('Admin.sidebar')
                    @endcan
                @endauth
            </aside>

            {{-- Main content — THE ONLY THING THE SPA ROUTER EVER TOUCHES --}}
            <main class="flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800">
                <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">
                    <div id="main-content"
                         data-page-url="{{ url()->current() }}"
                         class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6 overflow-visible">
                        @yield('content')
                    </div>
                </div>
            </main>

        </div>
    </div>

    {{-- Page-specific scripts (still works — pushed scripts render on
         first load; on subsequent SPA navigations we re-execute them
         manually, handled in Step 2) --}}
    @stack('scripts')

    {{-- Notification sound — plain include, no @persist needed since
         the whole layout (including this tag) is only ever rendered once --}}
    <audio id="orderSound" src="/sounds/notify.wav" preload="auto"></audio>

    {{-- ============================================================
         SPA ROUTER CORE
         This is the ONLY script that manages navigation between pages.
         It never needs to change when you add new pages later — every
         future page hooks into it via the 'spa:loaded' event.
    ============================================================ --}}
    <script>
        window.SPA = (function () {
            // Tracks the in-flight request so we can cancel it if the
            // user navigates again before it finishes (prevents stale
            // responses overwriting newer ones)
            let currentController = null;

            /**
             * Core navigation function.
             * @param {string} url        - URL to load
             * @param {boolean} pushState - Whether to push a new history entry
             */
            async function navigate(url, pushState = true) {
                const mainContent = document.getElementById('main-content');

                // 1. Cancel any previous in-flight request
                if (currentController) currentController.abort();
                currentController = new AbortController();

                // 2. Dim the content instead of blanking it, so the UI
                //    doesn't flash empty during the fetch
                mainContent.classList.add('opacity-50', 'pointer-events-none');

                try {
                    // 3. Tell Laravel this is an SPA/AJAX request so it
                    //    returns JSON instead of a full HTML page
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        signal: currentController.signal,
                    });

                    if (!response.ok) {
                        throw new Error('Navigation failed: ' + response.status);
                    }

                    // 4. Server responds with { html, title }
                    const data = await response.json();

                    // 5. Swap ONLY the main content
                    mainContent.innerHTML = data.html;
                    mainContent.dataset.pageUrl = url;

                    // 6. Update the browser tab title
                    if (data.title) document.title = data.title;

                    // 7. Push to browser history so Back/Forward works
                    //    and the URL bar stays accurate
                    if (pushState) {
                        history.pushState({ spaUrl: url }, '', url);
                    }

                    // 8. Highlight the active sidebar/navbar link
                    highlightActiveLink(url);

                    // 9. Let page-specific JS know new content has landed
                    document.dispatchEvent(new CustomEvent('spa:loaded', { detail: { url } }));

                } catch (err) {
                    if (err.name !== 'AbortError') {
                        console.error(err);
                        mainContent.innerHTML =
                            '<p class="p-6 text-red-500">Failed to load page. Please try again.</p>';
                    }
                } finally {
                    mainContent.classList.remove('opacity-50', 'pointer-events-none');
                }
            }

            function highlightActiveLink(url) {
                document.querySelectorAll('[data-spa-link]').forEach(link => {
                    link.classList.toggle('active-nav', link.href === url);
                });
            }

            // 10. Intercept clicks only on links explicitly marked
            //     data-spa-link — sidebar/navbar links you control.
            //     Regular links (logout, external, downloads) are untouched.
            document.addEventListener('click', function (e) {
                const link = e.target.closest('[data-spa-link]');
                if (!link) return;

                e.preventDefault();
                navigate(link.href);
            });

            // 11. Handle Back/Forward browser buttons
            window.addEventListener('popstate', function (e) {
                const url = (e.state && e.state.spaUrl) ? e.state.spaUrl : window.location.href;
                navigate(url, false); // false = don't re-push history
            });

            return { navigate };
        })();
    </script>

</body>

</html>