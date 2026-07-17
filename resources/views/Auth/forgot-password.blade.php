<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md border border-gray-200">

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Forgot Password
        </h2>

        <p class="text-sm text-gray-500 mt-2">
            Enter your email and we'll send you a password reset link.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 text-red-600 px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label class="text-sm text-gray-600">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required

                class="w-full mt-1 px-4 py-2.5 rounded-lg border border-gray-300
                       focus:outline-none focus:ring-2 focus:ring-indigo-500"

                placeholder="admin@email.com">
        </div>

        <button
            type="submit"

            class="w-full py-2.5 rounded-lg bg-indigo-600 text-white
                   font-semibold hover:bg-indigo-700 transition">

            Send Reset Link

        </button>
    </form>

    <div class="mt-6 text-center">

        <a href="{{ route('login') }}"
           class="text-sm text-indigo-600 hover:underline">

            ← Back to Login

        </a>

    </div>

</div>

</body>
</html>