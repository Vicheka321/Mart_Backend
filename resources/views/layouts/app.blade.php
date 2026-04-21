<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <title>Mart Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="assets/public/css/app.css">




    <script>
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

<script>
let lastIds = [];

// ✅ REQUEST PERMISSION (run once)
if (Notification.permission !== "granted") {
    Notification.requestPermission();
}

// 🔔 DESKTOP NOTIFICATION (LIKE FACEBOOK)
function showDesktopNotification(order) {

    if (Notification.permission === "granted") {

        const notification = new Notification("🛒 Darita Mart", {
            body: `New Order #${order.id} • $${order.total}`,
            icon: "/logo.png"
        });

        // 👉 click open orders page
        notification.onclick = () => {
            window.open('/admin/orders', '_blank');
        };
    }
}

// 🔄 CHECK ORDER EVERY 5s
async function checkNewOrder() {
    try {
        const res = await fetch('/admin/orders/latest');
        const data = await res.json();

        if (!data.id) return;

        // first load (avoid spam)
        if (lastIds.length === 0) {
            lastIds = [data.id];
            return;
        }

        // new order detected 🔥
        if (!lastIds.includes(data.id)) {

            // 🔔 SHOW DESKTOP NOTIFICATION
            showDesktopNotification({
                id: data.id,
                total: data.total
            });

            // 🔊 SOUND
            const audio = document.getElementById('orderSound');
            if (audio) audio.play().catch(() => {});

            lastIds = [data.id];
        }

    } catch (e) {
        console.error("Error fetching order:", e);
    }
}

// 🚀 RUN
setInterval(checkNewOrder, 5000);
checkNewOrder();
</script>
</html>