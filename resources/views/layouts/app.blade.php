{{--
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
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

    <!-- Dark Mode -->
    <script>

        window.toggleDarkMode = function () {
            const html = document.documentElement;

            if (html.classList.contains('dark')) {
                // Switch to Light Mode
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                // Switch to Dark Mode
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        };

        // ==========================
        // Apply Saved Theme on Load
        // ==========================
        (function () {
            const theme = localStorage.getItem('theme');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        // Re-apply theme after Livewire navigation
        document.addEventListener('livewire:navigated', function () {
            const theme = localStorage.getItem('theme');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    <!-- Toast Animation -->
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-enter {
            animation: slideIn 0.3s ease forwards;
        }

        .toast-exit {
            animation: slideOut 0.3s ease forwards;
        }
    </style>


</head>



<body
    class="h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 dark:text-white font-[Inter] transition-colors duration-300">

    <div class="flex flex-col h-screen">

        <!-- Navbar -->
        <nav class="flex-shrink-0">
            @persist('navbar')
            @auth
            @if(auth()->user()->role == 'admin')
            @include('Admin.navbar')
            @else
            @include('Staff.navbar')
            @endif
            @endauth
            @endpersist
        </nav>

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-5 right-5 space-y-3 z-50"></div>

        <div class="flex flex-1 min-h-0">

            <!-- Sidebar -->
            <aside class="w-72 overflow-y-auto">
                @auth
                @if(auth()->user()->role == 'admin')
                @include('Admin.sidebar')
                @else
                @include('Staff.sidebar')
                @endif
                @endauth
            </aside>

            <!-- Main Content -->
            <main class=" flex-1 min-h-0  flex flex-col bg-white dark:bg-gray-800">

                <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">

                    <div
                        class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6 overflow-visible">
                        @yield('content')
                    </div>

                </div>

            </main>

        </div>

    </div>

    @stack('scripts')


    @persist('order-sound')
    <audio id="orderSound" src="/sounds/notify.wav" preload="auto"></audio>
    @endpersist



    <script defer>
        (function () {

            if (window._wireNavigateFixed) return;
            window._wireNavigateFixed = true;

            document.addEventListener('livewire:navigated', function () {

                window._ordersRealtimeReady = false;
                window._productsRealtimeReady = false;
                window._dashboardReady = false;
            });
        })();
    </script>
</body>

</html> --}}



<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <title>Mart Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}

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

        document.addEventListener('livewire:navigated', function () {
            const theme = localStorage.getItem('theme');

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    {{-- Toast animation --}}
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-enter {
            animation: slideIn 0.3s ease forwards;
        }

        .toast-exit {
            animation: slideOut 0.3s ease forwards;
        }
    </style>
</head>

<body
    class="h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 dark:text-white font-[Inter] transition-colors duration-300">

    <div class="flex flex-col h-screen">

        {{-- Navbar --}}
        <nav class="flex-shrink-0">
            @persist('navbar')
            @auth
                {{--
                Admin layout is shown only for users allowed into admin panel.
                We no longer use auth()->user()->role == 'admin'
                --}}
                @can('access_admin_panel')
                    @include('Admin.navbar')
                @endcan
            @endauth
            @endpersist
        </nav>

        {{-- Toast container --}}
        <div id="toastContainer" class="fixed top-5 right-5 space-y-3 z-50"></div>

        <div class="flex flex-1 min-h-0">

            {{-- Sidebar --}}
            <aside class="w-72 overflow-y-auto">
                @auth
                    @can('access_admin_panel')
                        @include('Admin.sidebar')
                    @endcan
                @endauth
            </aside>

            {{-- Main content --}}
            <main class="flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800">
                <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">
                    <div
                        class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6 overflow-visible">
                        @yield('content')
                    </div>
                </div>
            </main>

        </div>
    </div>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    {{-- Notification sound --}}
    @persist('order-sound')
    <audio id="orderSound" src="/sounds/notify.wav" preload="auto"></audio>
    @endpersist

    <script defer>
        (function () {
            if (window._wireNavigateFixed) return;
            window._wireNavigateFixed = true;

            document.addEventListener('livewire:navigated', function () {
                window._ordersRealtimeReady = false;
                window._productsRealtimeReady = false;
                window._dashboardReady = false;
            });
        })();
    </script>

   



</body>

</html>