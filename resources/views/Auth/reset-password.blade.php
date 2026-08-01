<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password · Darita Mart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeSlideUp .5s ease both;
        }

        .logo {
            animation: fadeSlideUp .45s .05s ease both;
        }

        .field-1 {
            animation: fadeSlideUp .45s .15s ease both;
        }

        .field-2 {
            animation: fadeSlideUp .45s .20s ease both;
        }

        .field-3 {
            animation: fadeSlideUp .45s .25s ease both;
        }

        .act {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .act:hover {
            transform: translateY(-1px);
        }

        .act:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 px-4 py-6 overflow-hidden">

    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="card bg-white dark:bg-gray-900 shadow-xl shadow-gray-200/70 dark:shadow-black/40
                    rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-800
                    px-6 py-8 sm:px-10 sm:py-10">

            <!-- Logo + Brand -->
            <div class="logo text-center mb-6 sm:mb-8">
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-2xl border-2 border-indigo-500
                            flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4" />
                        <circle cx="12" cy="16.5" r="1.5" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Darita Mart
                </h1>
                <div class="flex items-center justify-center gap-3 mt-2">
                    <span class="h-px w-8 bg-gray-200 dark:bg-gray-700"></span>
                    <span class="text-xs sm:text-sm text-gray-400 dark:text-gray-500 tracking-wide">Admin
                        Dashboard</span>
                    <span class="h-px w-8 bg-gray-200 dark:bg-gray-700"></span>
                </div>
            </div>

            <!-- Heading -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Reset password</h2>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">Choose a new password for your account</p>
            </div>

            <!-- Error -->
            @if ($errors->any())
                <div
                    class="mb-5 flex items-start gap-2.5 p-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-900/40">
                    <svg class="w-4 h-4 mt-0.5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <p class="text-sm text-rose-600 dark:text-rose-400">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- New Password -->
                <div class="field-1">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                        <input id="password" type="password" name="password" required class="w-full h-12 sm:h-[52px] pl-12 pr-12 rounded-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700
                                   bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-500 outline-none transition
                                   focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/25"
                            placeholder="New password">
                        <button type="button" onclick="toggleField('password','eyeOpen1','eyeClosed1')" tabindex="-1"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg id="eyeOpen1" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg id="eyeClosed1" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                <line x1="1" y1="1" x2="23" y2="23" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="field-2">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full h-12 sm:h-[52px] pl-12 pr-12 rounded-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700
                                   bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white
                                   placeholder:text-gray-400 dark:placeholder:text-gray-500 outline-none transition
                                   focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/25"
                            placeholder="Confirm new password">
                        <button type="button" onclick="toggleField('password_confirmation','eyeOpen2','eyeClosed2')"
                            tabindex="-1"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg id="eyeOpen2" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg id="eyeClosed2" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                <line x1="1" y1="1" x2="23" y2="23" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Button -->
                <button type="submit" class="field-3 act w-full h-12 sm:h-[52px] rounded-xl sm:rounded-2xl
                           bg-indigo-600 hover:bg-indigo-700 text-white text-sm sm:text-base font-semibold
                           shadow-sm shadow-indigo-500/25 transition-all duration-200">
                    Reset Password
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 dark:text-gray-600 mt-6">
            © 2026 Darita Mart. All rights reserved.
        </p>

    </div>

    <script>
        function toggleField(inputId, openId, closedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openId);
            const eyeClosed = document.getElementById(closedId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        }
    </script>

</body>

</html>