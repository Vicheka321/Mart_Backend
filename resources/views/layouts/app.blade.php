<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mart POS Admin</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    

</head>

<body>

    <div class="app">

        <!-- ========== SIDEBAR ========== -->
        <aside class="sidebar">

            <div class="brand">
                🛒 <span>Mart POS</span>
            </div>

            <p class="menu-title">MANAGEMENT</p>

            <a href="{{ route('admin.dashboard') }}" class="menu active">📊 Dashboard</a>

            <!-- PRODUCTS -->
            <div class="menu-item">
                <div class="menu toggle">
                    
                    🛍 Products
                    <span class="arrow">▾</span>
                </div>
                <div class="submenu">
                    <a href="#">All Products</a>
                    <a href="#">Categories</a>
                    <a href="#">Brands</a>
                </div>
            </div>

            <!-- ORDERS -->
            <div class="menu-item">
                <div class="menu toggle">
                    📦 Orders
                    <span class="arrow">▾</span>
                </div>
                <div class="submenu">
                    <a href="#">All Orders</a>
                    <a href="#">Pending</a>
                    <a href="#">Completed</a>
                </div>
            </div>

            <a href="#" class="menu">👥 Customers</a>
            <a href="#" class="menu">🎁 Promotions</a>

            <p class="menu-title">SYSTEM</p>

            <a href="#" class="menu">⚙ Settings</a>
{{-- 
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="menu logout">🚪 Logout</button>
            </form> --}}

        </aside>

        <!-- ========== MAIN AREA ========== -->
        <div class="main">

            <!-- TOP NAV -->
            <header class="topbar">
                <div>Welcome, {{ auth()->user()->name }}</div>
            </header>

            <!-- CONTENT -->
            <main class="content">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- JS -->
    <script>
        document.querySelectorAll(".toggle").forEach(item => {
            item.addEventListener("click", () => {
                item.nextElementSibling.classList.toggle("show");
            });
        });
    </script>

</body>

</html>
