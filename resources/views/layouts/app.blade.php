{{--
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <title>Mart Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/public/css/app.css">
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script defer>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <script defer>
        // Dark mode (before render)
        const theme = localStorage.getItem('theme');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        // Run after DOM ready
        document.addEventListener("DOMContentLoaded", () => {

            // Fade in
            document.body.classList.remove("opacity-0");

            // Fade out on navigation
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function () {
                    if (this.href && this.target !== '_blank') {
                        document.body.classList.add('opacity-0');
                    }
                });
            });

        });
    </script>

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


    <script defer>

        Pusher.logToConsole = false;

        var pusher = new Pusher('ac672e0dfc7b9aa3c37d', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('orders');

        channel.bind('NewOrderCreated', function (data) {

            // 🔔 Desktop Notification
            showDesktopNotification({
                id: data.order.id,
                total: data.order.total
            });

            // 🔊 Sound
            const audio = document.getElementById('orderSound');

            if (audio) {
                audio.play().catch(() => { });
            }

            console.log(data);

        });

    </script>

    <script defer>


        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

    </script>


</head>

<body
    class="transition-colors duration-300 h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 dark:text-white font-[Inter] transition-colors duration-300 opacity-0 transition-opacity duration-200 ease-in-out">

    <div class="flex flex-col h-screen">

        <nav class="flex-shrink-0">
            @auth
            @if(auth()->user()->role == 'admin')
            @include('Admin.navbar')
            @else
            @include('Staff.navbar')
            @endif
            @endauth
        </nav>
        <div id="toastContainer" class="fixed top-5 right-5 space-y-3 z-50"></div>

        <div class="flex flex-1 min-h-0">

            <aside class="w-72 overflow-y-auto">
                @auth
                @if(auth()->user()->role == 'admin')
                @include('Admin.sidebar')
                @else
                @include('Staff.sidebar')
                @endif
                @endauth
            </aside>

            <main class="flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800">

                <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">

                    <div class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6">
                        @yield('content')
                    </div>

                </div>

            </main>

        </div>

    </div>

</body>


<audio id="orderSound" src="/sounds/notify.wav" preload="auto"></audio>



</html> --}}



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
        // ==========================
        // Dark Mode Toggle (Global)
        // ==========================
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

    <!-- Pusher -->
    {{-- <script defer>
        document.addEventListener('DOMContentLoaded', function () {

            // Turn off debug logs
            Pusher.logToConsole = false;

            // Request browser notification permission once
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }

            // Prevent duplicate initialization
            if (!window._pusherReady) {
                window._pusherReady = true;

                window.pusher = new Pusher(
                    "{{ config('broadcasting.connections.pusher.key') }}",
                    {
                        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
                    }
                );

                // Subscribe to orders channel
                const channel = window.pusher.subscribe('orders');

                // Listen to event name from broadcastAs()
                channel.bind('new-order', function (data) {

                    const order = data.order;

                    // Play sound
                    const audio = document.getElementById('orderSound');
                    if (audio) {
                        audio.play().catch(() => { });
                    }

                    // Desktop notification
                    if (Notification.permission === "granted") {
                        new Notification("🛒 New Order", {
                            body: `Order #${order.id} • $${order.total_amount}`,
                            icon: "public/images/icons/logo.png"
                        });
                    }

                    // Custom functions if available
                    if (typeof addNotification === 'function') {
                        addNotification(order);
                    }

                    if (typeof showToast === 'function') {
                        showToast(order);
                    }
                });
            }
        });
    </script> --}}


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
            <main class="flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800">

                <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">

                    <div class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6">
                        @yield('content')
                    </div>

                </div>

            </main>

        </div>

    </div>

    {{-- <body
        class="h-screen overflow-hidden bg-gray-100 dark:bg-gray-900 dark:text-white font-[Inter] transition-colors duration-300">

        <div class="flex flex-col h-screen">

         
            <nav class="flex-shrink-0 relative z-[100] isolate">
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

         
            <div id="toastContainer" class="fixed top-5 right-5 space-y-3 z-[300]"></div>

        
            <div class="flex flex-1 min-h-0 relative z-0 isolate">

           
                <aside class="w-72 overflow-y-auto relative z-0 isolate">
                    @auth
                        @if(auth()->user()->role == 'admin')
                            @include('Admin.sidebar')
                        @else
                            @include('Staff.sidebar')
                        @endif
                    @endauth
                </aside>

              
                <main class="flex-1 min-h-0 flex flex-col bg-white dark:bg-gray-800 relative z-0 isolate">
                    <div class="flex-1 overflow-y-auto pl-0 pt-3 pr-2 pb-0">
                        <div class="max-w-7xl mx-auto bg-gray-100 dark:bg-slate-700 rounded-3xl pl-6 pr-6 py-6">
                            @yield('content')
                        </div>
                    </div>
                </main>

            </div>
        </div> --}}

        <!-- Page-specific scripts -->
        @stack('scripts')

        <!-- Notification Sound -->
        @persist('order-sound')
        <audio id="orderSound" src="/sounds/notify.wav" preload="auto"></audio>
        @endpersist



        <script defer>
            (function () {

                if (window._wireNavigateFixed) return;
                window._wireNavigateFixed = true;

                document.addEventListener('livewire:navigated', function () {
                    // Reset page-specific flags only
                    window._ordersRealtimeReady = false;
                    window._productsRealtimeReady = false;
                    window._dashboardReady = false;
                });
            })();
        </script>




    </body>



</html>