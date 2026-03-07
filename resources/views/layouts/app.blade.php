<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mart Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="app">

        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">

            <div class="brand">
                🛒 <span>Darita Mart</span>
            </div>

            <!-- DASHBOARD -->
            <a href="{{ route('admin.dashboard') }}"
                class="menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>

            <!-- PRODUCTS -->
            <div class="menu-item">

                <div class="menu toggle
                {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    🛍 Products
                    <span class="arrow">▾</span>
                </div>

                <div class="submenu {{ request()->routeIs('products.*') ? 'show' : '' }}">

                    <a href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                        All Products
                    </a>

                    <a href="{{ route('categories.index') }}"
                        class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                        Categories
                    </a>

                    <a href="{{ route('brands.index') }}"
                        class="{{ request()->routeIs('brands.index') ? 'active' : '' }}">
                        Brands
                    </a>

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

        </aside>

        <!-- ================= MAIN ================= -->
        <div class="main">
            {{-- 
            <header class="topbar">
                Welcome, {{ auth()->user()->name }}
            </header> --}}

            <main class="content">
                @yield('content')
            </main>

        </div>

    </div>

    <!-- ================= JS ================= -->
    <script>
        document.querySelectorAll(".toggle").forEach(menu => {

            menu.addEventListener("click", function() {

                const submenu = this.nextElementSibling;

                submenu.classList.toggle("show");

                // arrow rotation
                this.classList.toggle("active");

            });

        });


        // function openModal() {
        //     document.getElementById('categoryModal').classList.add('show');
        // }

        // function closeModal() {
        //     document.getElementById('categoryModal').classList.remove('show');
        // }

        // window.onclick = function(e) {
        //     const modal = document.getElementById('categoryModal');
        //     if (e.target === modal) {
        //         closeModal();
        //     }
        // }

        // document.getElementById("imageInput").addEventListener("change", function(event) {
        //     const file = event.target.files[0];
        //     const preview = document.getElementById("imagePreview");
        //     const container = document.getElementById("imagePreviewContainer");

        //     if (file) {
        //         preview.src = URL.createObjectURL(file);
        //         container.style.display = "block";
        //     }
        // });

        // const imageInput = document.getElementById("imageInput");
        // const preview = document.getElementById("imagePreview");
        // const wrapper = document.getElementById("imagePreviewWrapper");
        // const placeholder = document.getElementById("placeholderText");

        // imageInput.addEventListener("change", function() {
        //     const file = this.files[0];

        //     if (file) {
        //         preview.src = URL.createObjectURL(file);
        //         wrapper.style.display = "block";
        //         placeholder.style.display = "none";
        //     }
        // });

        // function removeImage(event) {
        //     event.stopPropagation(); // prevent re-opening file picker

        //     imageInput.value = "";
        //     preview.src = "";
        //     wrapper.style.display = "none";
        //     placeholder.style.display = "block";
        // }
    </script>




</body>

</html>
