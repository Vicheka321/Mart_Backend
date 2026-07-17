<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex justify-center items-center">

<div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md">

    <h1 class="text-2xl font-bold mb-6">
        Reset Password
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">

        @csrf

        <input type="hidden"
               name="token"
               value="{{ $token }}">

        <input type="hidden"
               name="email"
               value="{{ $email }}">

        <div class="mb-4">

            <label>New Password</label>

            <input
                type="password"
                name="password"

                class="w-full border rounded-lg px-4 py-2">

        </div>

        <div class="mb-6">

            <label>Confirm Password</label>

            <input
                type="password"

                name="password_confirmation"

                class="w-full border rounded-lg px-4 py-2">

        </div>

        <button
            class="w-full bg-indigo-600 text-white py-3 rounded-lg">

            Reset Password

        </button>

    </form>

</div>

</body>
</html>