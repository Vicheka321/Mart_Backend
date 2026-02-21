<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mart POS System</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    

</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-flex">
            <div class="logo">Mart POS</div>
{{-- 
            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="admin/dashboard" class="btn yellow">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn outline">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn white">Register</a>
                        @endif
                    @endauth
                @endif
            </div> --}}
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="container hero-flex">

            <div class="hero-text">
                <h1>Smart Mart POS & Ecommerce System</h1>
                <p>Manage Products, Orders, Customers and Promotions in one place.</p>

                <a href="{{ route('login') }}" class="btn white">Get Started</a>
                <a href="#features" class="btn outline">Learn More</a>
            </div>

            <div class="hero-image">
                <img src="https://cdn-icons-png.flaticon.com/512/2331/2331970.png">
            </div>

        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="features">
        <div class="container">

            <h2>System Features</h2>

            <div class="feature-grid">

                <div class="feature-card">
                    <h4>Product Management</h4>
                    <p>Add, edit and manage products easily.</p>
                </div>

                <div class="feature-card">
                    <h4>Order & POS</h4>
                    <p>Fast checkout and order tracking.</p>
                </div>

                <div class="feature-card">
                    <h4>Promotion System</h4>
                    <p>Create discounts and campaigns.</p>
                </div>

                <div class="feature-card">
                    <h4>Customers</h4>
                    <p>Store customer data & history.</p>
                </div>

                <div class="feature-card">
                    <h4>Reports</h4>
                    <p>Daily & Monthly sales reports.</p>
                </div>

                <div class="feature-card">
                    <h4>User Roles</h4>
                    <p>Admin, Staff and Customer roles.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <p>© {{ date('Y') }} Mart POS System. All rights reserved.</p>
    </footer>

</body>

</html>
