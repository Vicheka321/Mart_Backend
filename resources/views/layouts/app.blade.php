<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mart POS</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="wrapper">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h2>
                {{-- <img src="{{ asset('images/icons/store.png') }}" class="icon"> --}}
                Mart POS
            </h2>

            <a href="{{ route('admin.dashboard') }}" class="active">
                <img src="{{ asset('images/icons/dashboard.png') }}" class="icon">
                Dashboard
            </a>

            <div class="menu-item">
                <a href="javascript:void(0);" class="menu-toggle">
                    <img src="{{ asset('images/icons/products.png') }}" class="icon">
                    Products ▾
                </a>
            
                <div class="submenu">
                    <a href="#">All Products</a>
                    <a href="#">Brands</a>
                    <a href="#">Categories</a>
                    <a href="#">Add Product</a>
                </div>
            </div>
            

            <a href="#">
                <img src="{{ asset('images/icons/categories.png') }}" class="icon">
                Categories
            </a>

            <a href="#">
                <img src="{{ asset('images/icons/orders.png') }}" class="icon">
                Orders
            </a>

            <a href="#">
                <img src="{{ asset('images/icons/promo.png') }}" class="icon">
                Promotions
            </a>

            <a href="#">
                <img src="{{ asset('images/icons/logout.png') }}" class="icon">
                Logout
            </a>
        </div>


        <!-- MAIN -->
        <div class="main">

            <!-- NAVBAR -->
            <div class="navbar">
                Welcome, {{ auth()->user()->name ?? 'User' }}
            </div>

            <!-- CONTENT -->
            <div class="content">
                @yield('content')
            </div>

        </div>

    </div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggles = document.querySelectorAll(".menu-toggle");

        toggles.forEach(toggle => {
            toggle.addEventListener("click", function () {
                const submenu = this.nextElementSibling;
                submenu.classList.toggle("show");
            });
        });
    });
</script>



</html>
