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

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <!-- Card -->
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md border border-gray-200">

        <!-- Title -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Admin Login</h2>
            <p class="text-sm text-gray-500">Sign in to continue</p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email" required class="w-full mt-1 px-4 py-2.5 rounded-lg border border-gray-300 
                           focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="admin@email.com">
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password" required class="w-full mt-1 px-4 py-2.5 rounded-lg border border-gray-300 
                           focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••">
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('password.request') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline">
                    Forgot Password?
                </a>
            </div>

            <!-- Button -->
            <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-semibold rounded-lg 
                       hover:bg-indigo-700 transition duration-200">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6">
            © 2026 Darita Mart
        </p>

    </div>

</body>

</html>