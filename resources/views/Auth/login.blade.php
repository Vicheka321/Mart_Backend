<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">

    <!-- Card -->
    <div class="backdrop-blur-xl bg-white/20 border border-white/30 
                shadow-2xl rounded-3xl p-8 w-full max-w-md text-white">

        <!-- Title -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold">Welcome Back 👋</h2>
            <p class="text-sm text-white/70">Login to your admin dashboard</p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-500/20 text-red-200 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="text-sm text-white/80">Email</label>
                <input type="email" name="email" placeholder="Enter your email"
                    class="w-full mt-1 px-4 py-2.5 rounded-xl bg-white/20 
                           border border-white/30 placeholder-white/60
                           focus:outline-none focus:ring-2 focus:ring-white/60 text-white">
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm text-white/80">Password</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full mt-1 px-4 py-2.5 rounded-xl bg-white/20 
                           border border-white/30 placeholder-white/60
                           focus:outline-none focus:ring-2 focus:ring-white/60 text-white">
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full py-2.5 bg-white text-indigo-600 font-semibold rounded-xl 
                       hover:bg-gray-100 transition duration-200 shadow-md">
                Login
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="flex-1 h-px bg-white/30"></div>
            <span class="px-3 text-sm text-white/60">OR</span>
            <div class="flex-1 h-px bg-white/30"></div>
        </div>

        <!-- Google Button -->
        {{-- <button class="w-full py-2.5 bg-white/20 border border-white/30 rounded-xl 
                       hover:bg-white/30 transition text-sm flex items-center justify-center gap-2">
            🔵 Continue with Google
        </button> --}}

        <!-- Footer -->
        <p class="text-center text-xs text-white/60 mt-6">
            © 2026 Darita Mart
        </p>

    </div>

</body>
</html>