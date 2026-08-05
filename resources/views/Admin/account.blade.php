@extends('layouts.app')

@section('content')
    @php
        $user = $user ?? Auth::user();
        $role = $role ?? ($user->getRoleNames()->first() ?? $user->role->name ?? $user->role ?? null);

        // ── Best-effort "current session" details for the read-only card.
        //    Adjust the property names below to match your schema — anything
        //    missing safely falls back to "—". ──
        $registrationMethod = $user->provider ?? $user->registration_method ?? 'Email';
        $facebookConnected = !empty($user->facebook_id);
        $googleConnected = !empty($user->google_id);
        $twoFactorEnabled = !empty($user->two_factor_enabled ?? $user->two_factor_secret ?? null);
        $lastLoginAt = $user->last_login_at ?? null;
        $lastIp = $user->last_login_ip ?? request()->ip();

        // Lightweight UA parse for "current" browser/device — no package required.
        $ua = request()->userAgent() ?? '';
        $currentBrowser = match (true) {
            str_contains($ua, 'Edg/') => 'Microsoft Edge',
            str_contains($ua, 'Chrome/') && !str_contains($ua, 'Edg/') => 'Chrome',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/') => 'Safari',
            default => null,
        };
        $currentDevice = match (true) {
            str_contains($ua, 'iPhone') => 'iPhone',
            str_contains($ua, 'iPad') => 'iPad',
            str_contains($ua, 'Android') && str_contains($ua, 'Mobile') => 'Android Phone',
            str_contains($ua, 'Android') => 'Android Tablet',
            str_contains($ua, 'Windows') => 'Windows PC',
            str_contains($ua, 'Macintosh') => 'Mac',
            str_contains($ua, 'Linux') => 'Linux PC',
            default => null,
        };

        // ══════════════════════════════════════════════════════════════
        //  Reusable render helpers (kept in this one file on purpose —
        //  avoids duplicated HTML without needing separate component files)
        //  All of them ship dark: variants so the page follows the app's
        //  global dark mode (Tailwind class strategy, toggled elsewhere).
        // ══════════════════════════════════════════════════════════════

        // Small status/connection badge chip.
        $badge = function (string $label, string $color = 'gray', ?string $icon = null) {
            $palette = [
                'green' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
                'gray' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-600',
                'amber' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-800',
                'red' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800',
                'blue' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-800',
            ];
            $classes = $palette[$color] ?? $palette['gray'];
            $iconHtml = $icon ? '<i class="ti ti-' . $icon . ' text-sm" aria-hidden="true"></i>' : '';
            return '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border ' . $classes . '">'
                . $iconHtml . e($label) . '</span>';
        };

        // One read-only row inside the Account Information card.
        // $valueHtml may be plain escaped text or pre-rendered badge HTML.
        $infoRow = function (string $icon, string $label, ?string $valueHtml) {
            $display = ($valueHtml === null || $valueHtml === '') ? '<span class="text-gray-400 dark:text-gray-500">—</span>' : $valueHtml;
            return '<div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">'
                . '<dt class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">'
                . '<i class="ti ti-' . $icon . ' text-gray-400 dark:text-gray-500 text-base" aria-hidden="true"></i>' . e($label) . '</dt>'
                . '<dd class="text-sm font-medium text-gray-900 dark:text-white text-right">' . $display . '</dd>'
                . '</div>';
        };

        // Floating-label text/email/tel input with an icon (pure CSS floating label).
        $field = function (string $name, string $label, string $type = 'text', ?string $icon = null, $value = null, bool $required = false) use ($errors) {
            $fieldValue = old($name, $value);
            $hasError = $errors->has($name);
            $iconHtml = $icon
                ? '<i class="ti ti-' . $icon . ' pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base ' . ($hasError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500') . '"></i>'
                : '';
            $labelLeft = $icon ? 'left-10' : 'left-3.5';
            $inputPad = $icon ? 'pl-10' : 'pl-3.5';
            $borderClass = $hasError ? 'border-red-300 dark:border-red-500/60 field-error' : 'border-gray-200 dark:border-gray-600';
            $floated = ($fieldValue !== null && $fieldValue !== '') ? 'top-2.5 !translate-y-0 text-[10px]' : '';
            $requiredAttr = $required ? 'required aria-required="true"' : '';
            $requiredMark = $required ? ' <span class="text-red-400">*</span>' : '';
            $errorHtml = '';
            if ($hasError) {
                $errorHtml = '<p id="' . $name . '-error" class="mt-1 text-xs text-red-500 dark:text-red-400 field-error-text">' . e($errors->first($name)) . '</p>';
            }

            return '<div class="relative">'
                . '<div class="relative">'
                . $iconHtml
                . '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . e($fieldValue) . '" ' . $requiredAttr . ' placeholder=" " '
                . 'data-field="' . $name . '" aria-invalid="' . ($hasError ? 'true' : 'false') . '" '
                . ($hasError ? 'aria-describedby="' . $name . '-error"' : '')
                . ' class="field-input peer w-full ' . $inputPad . ' pr-3.5 pt-8 pb-2 text-sm rounded-xl border bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white '
                . 'placeholder-transparent focus:outline-none focus:bg-white dark:focus:bg-gray-700 transition-all duration-150 ' . $borderClass . '">'
                . '<label for="' . $name . '" class="pointer-events-none absolute ' . $labelLeft . ' top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500 transition-all duration-150 '
                . 'peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-sm '
                . 'peer-focus:top-2.5 peer-focus:-translate-y-0 peer-focus:text-[10px] peer-focus:text-blue-600 dark:peer-focus:text-blue-400 ' . $floated . '">'
                . e($label) . $requiredMark . '</label>'
                . '<i class="ti ti-circle-check field-success-icon hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-emerald-500 text-base"></i>'
                . '</div>'
                . $errorHtml
                . '</div>';
        };

        // Password input with show/hide toggle, optional strength meter / match hint containers.
        $passwordField = function (string $name, string $label, bool $required = true, bool $withStrength = false, bool $withMatch = false) use ($errors) {
            $hasError = $errors->has($name);
            $borderClass = $hasError ? 'border-red-300 dark:border-red-500/60 field-error' : 'border-gray-200 dark:border-gray-600';
            $requiredAttr = $required ? 'required aria-required="true"' : '';
            $requiredMark = $required ? ' <span class="text-red-400">*</span>' : '';
            $errorHtml = '';
            if ($hasError) {
                $errorHtml = '<p id="' . $name . '-error" class="mt-1 text-xs text-red-500 dark:text-red-400 field-error-text">' . e($errors->first($name)) . '</p>';
            }

            $strengthHtml = '';
            if ($withStrength) {
                $strengthHtml = '<div id="passwordStrength" class="mt-2 hidden" aria-live="polite">'
                    . '<div class="flex gap-1">'
                    . '<span class="strength-bar h-1 flex-1 rounded-full bg-gray-100 dark:bg-gray-600 transition-colors duration-200"></span>'
                    . '<span class="strength-bar h-1 flex-1 rounded-full bg-gray-100 dark:bg-gray-600 transition-colors duration-200"></span>'
                    . '<span class="strength-bar h-1 flex-1 rounded-full bg-gray-100 dark:bg-gray-600 transition-colors duration-200"></span>'
                    . '</div><p id="passwordStrengthLabel" class="mt-1 text-[11px] font-medium text-gray-400 dark:text-gray-500"></p></div>';
            }
            $matchHtml = $withMatch ? '<p id="passwordMatchHint" class="mt-1 text-xs hidden" aria-live="polite"></p>' : '';

            return '<div class="relative">'
                . '<div class="relative">'
                . '<i class="ti ti-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base ' . ($hasError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500') . '"></i>'
                . '<input type="password" id="' . $name . '" name="' . $name . '" ' . $requiredAttr . ' placeholder=" " data-field="' . $name . '" '
                . 'aria-invalid="' . ($hasError ? 'true' : 'false') . '" ' . ($hasError ? 'aria-describedby="' . $name . '-error"' : '')
                . ' class="field-input peer w-full pl-10 pr-11 pt-8 pb-2 text-sm rounded-xl border bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white '
                . 'placeholder-transparent focus:outline-none focus:bg-white dark:focus:bg-gray-700 transition-all duration-150 ' . $borderClass . '">'
                . '<label for="' . $name . '" class="pointer-events-none absolute left-10 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-gray-500 transition-all duration-150 '
                . 'peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-sm '
                . 'peer-focus:top-2.5 peer-focus:-translate-y-0 peer-focus:text-[10px] peer-focus:text-blue-600 dark:peer-focus:text-blue-400">'
                . e($label) . $requiredMark . '</label>'
                . '<button type="button" data-toggle-for="' . $name . '" aria-label="Show password" aria-pressed="false" '
                . 'class="password-toggle-btn absolute right-2.5 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">'
                . '<svg class="eye w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" /><circle cx="12" cy="12" r="3" /></svg>'
                . '<svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303m3.13-1.279A10.45 10.45 0 0022.066 12c-1.292-4.057-5.065-7-9.542-7-.848 0-1.67.105-2.454.303" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" /></svg>'
                . '</button>'
                . '</div>'
                . $errorHtml . $strengthHtml . $matchHtml
                . '</div>';
        };
    @endphp

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

        @keyframes spinPulse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes successPop {
            0% {
                transform: scale(.6);
                opacity: 0;
            }

            60% {
                transform: scale(1.08);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes rippleOut {
            to {
                transform: scale(3);
                opacity: 0;
            }
        }

        .acct-card {
            animation: fadeSlideUp .4s ease both;
            transition: box-shadow .2s ease, transform .2s ease, background-color .2s ease, border-color .2s ease;
        }

        .acct-card:hover {
            box-shadow: 0 8px 28px rgba(15, 23, 42, .06);
        }

        .dark .acct-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, .35);
        }

        .acct-card:nth-child(1) {
            animation-delay: .04s;
        }

        .acct-card:nth-child(2) {
            animation-delay: .10s;
        }

        /* ── Buttons ── */
        .btn {
            transition: transform .14s ease, box-shadow .14s ease, background-color .14s ease, border-color .14s ease, color .14s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .2);
        }

        .btn-primary:hover:not(:disabled) {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, .28);
        }

        .dark .btn-primary {
            box-shadow: 0 4px 14px rgba(37, 99, 235, .35);
        }

        .btn-secondary {
            background: #fff;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .btn-secondary:hover:not(:disabled) {
            background: #f9fafb;
        }

        .dark .btn-secondary {
            background: #1f2937;
            color: #d1d5db;
            border-color: #374151;
        }

        .dark .btn-secondary:hover:not(:disabled) {
            background: #273244;
        }

        .btn-danger {
            background: #fff;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .btn-danger:hover:not(:disabled) {
            background: #fef2f2;
        }

        .dark .btn-danger {
            background: #1f2937;
            color: #f87171;
            border-color: rgba(248, 113, 113, .35);
        }

        .dark .btn-danger:hover:not(:disabled) {
            background: rgba(248, 113, 113, .1);
        }

        .btn-ripple {
            position: absolute;
            border-radius: 9999px;
            background: rgba(255, 255, 255, .55);
            transform: scale(0);
            pointer-events: none;
            animation: rippleOut .5s ease-out forwards;
        }

        .btn-secondary .btn-ripple,
        .btn-danger .btn-ripple {
            background: rgba(37, 99, 235, .12);
        }

        .dark .btn-secondary .btn-ripple,
        .dark .btn-danger .btn-ripple {
            background: rgba(96, 165, 250, .18);
        }

        .btn-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinPulse .6s linear infinite;
        }

        /* ── Toast ── */
        .toast {
            animation: toastSlide .3s ease;
        }

        .toast.leaving {
            animation: toastOut .3s ease forwards;
        }

        /* ── Inputs ── */
        .field-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
            border-color: #93c5fd;
        }

        .dark .field-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
            border-color: #3b82f6;
        }

        .field-input.field-error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .15);
        }

        .field-input.field-success {
            border-color: #6ee7b7 !important;
        }

        .dark .field-input.field-success {
            border-color: #10b981 !important;
        }

        .field-input.field-success~.field-success-icon {
            display: block;
        }

        /* ── Password toggle ── */
        .password-toggle-btn svg.eye-off {
            display: none;
        }

        .password-toggle-btn.showing svg.eye {
            display: none;
        }

        .password-toggle-btn.showing svg.eye-off {
            display: block;
        }

        /* ── Password strength ── */
        .strength-bar.filled-weak {
            background: #ef4444;
        }

        .strength-bar.filled-medium {
            background: #f59e0b;
        }

        .strength-bar.filled-strong {
            background: #10b981;
        }

        /* ── Avatar dropzone ── */
        .avatar-dropzone {
            transition: border-color .15s ease, background-color .15s ease;
        }

        .avatar-dropzone.dragover {
            border-color: #2563eb;
            background-color: #eff6ff;
        }

        .dark .avatar-dropzone.dragover {
            border-color: #3b82f6;
            background-color: rgba(59, 130, 246, .12);
        }

        .avatar-progress-track {
            height: 4px;
            border-radius: 999px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .dark .avatar-progress-track {
            background: #374151;
        }

        .avatar-progress-bar {
            height: 100%;
            width: 0%;
            background: #2563eb;
            transition: width .15s ease;
        }

        /* ── Profile hero (glass style) ── */
        .profile-hero {
            background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%);
            position: relative;
            overflow: hidden;
        }

        .dark .profile-hero {
            background: linear-gradient(135deg, rgba(37, 99, 235, .16) 0%, rgba(124, 58, 237, .14) 100%);
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 85% -10%, rgba(37, 99, 235, .14), transparent 55%);
        }

        .dark .profile-hero::before {
            background: radial-gradient(circle at 85% -10%, rgba(59, 130, 246, .22), transparent 55%);
        }

        .glass-panel {
            background: rgba(255, 255, 255, .72);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .dark .glass-panel {
            background: rgba(31, 41, 55, .68);
        }

        .bx-hidden {
            display: none !important;
        }

        .alert-box {
            animation: fadeSlideUp .35s ease both;
            transition: opacity .3s ease, max-height .3s ease;
        }

        .success-pop {
            animation: successPop .35s cubic-bezier(.34, 1.56, .64, 1) both;
        }
    </style>

    {{-- Toast container --}}
    <div class="fixed top-4 right-4 left-4 sm:left-auto sm:top-5 sm:right-5 z-[9999] flex flex-col gap-2"
        id="toastContainer" aria-live="polite"></div>

    <div class="space-y-4">

        {{-- ==================== PAGE HEADER ==================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight">My Account</h1>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">Manage your profile, security, and account
                    details.</p>
            </div>
        </div>

        {{-- ==================== FLASH MESSAGES (auto-hide) ==================== --}}
        <div id="flashMessages" class="space-y-2">
            @if(session('success'))
                <div class="alert-box flex items-start gap-3 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400"
                    role="status">
                    <i class="ti ti-circle-check text-lg flex-shrink-0 mt-0.5" aria-hidden="true"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box flex items-start gap-3 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400"
                    role="alert">
                    <i class="ti ti-alert-circle text-lg flex-shrink-0 mt-0.5" aria-hidden="true"></i>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box flex items-start gap-3 p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400"
                    role="alert">
                    <i class="ti ti-alert-triangle text-lg flex-shrink-0 mt-0.5" aria-hidden="true"></i>
                    <div class="text-sm">
                        <p class="font-semibold mb-1">Please fix the following:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- ==================== PROFILE + ACCOUNT INFO ====================
        Desktop & tablet: 2 columns. Phone: 1 column. --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- ── PROFILE CARD (glass hero style) ── --}}
            <div
                class="acct-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="profile-hero p-4 sm:p-5">
                    <div
                        class="glass-panel relative rounded-2xl p-4 flex items-center gap-4 border border-white/60 dark:border-gray-600/40">
                        @if(!empty($user->avatar))
                            <img id="profileHeroAvatar" src="{{ $user->avatar }}" alt="{{ $user->full_name }}"
                                class="w-16 h-16 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm flex-shrink-0">
                        @else
                            <div id="profileHeroAvatar"
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 border-2 border-white dark:border-gray-600
                                               flex items-center justify-center text-white text-2xl font-bold shadow-md shadow-blue-500/25 flex-shrink-0">
                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                {{ $user->full_name ?? '—' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $role ?? '—' }}</p>
                            <div class="mt-1.5">
                                @if((int) ($user->status ?? 1) === 1)
                                    {!! $badge('Active', 'green') !!}
                                @else
                                    {!! $badge('Inactive', 'gray') !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-mail text-gray-400 dark:text-gray-500 text-base mt-0.5" aria-hidden="true"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Email</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium truncate">{{ $user->email ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-phone text-gray-400 dark:text-gray-500 text-base mt-0.5" aria-hidden="true"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Phone</p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium truncate">{{ $user->phone ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-calendar-plus text-gray-400 dark:text-gray-500 text-base mt-0.5"
                                aria-hidden="true"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Member Since
                                </p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium truncate">
                                    {{ $user->created_at?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-refresh text-gray-400 dark:text-gray-500 text-base mt-0.5"
                                aria-hidden="true"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Last Updated
                                </p>
                                <p class="text-gray-800 dark:text-gray-200 font-medium truncate">
                                    {{ $user->updated_at?->diffForHumans() ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="editProfileToggleBtn"
                        class="btn btn-primary inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl">
                        <i class="ti ti-pencil text-base" aria-hidden="true"></i>
                        Edit Profile
                    </button>
                </div>
            </div>

            {{-- ── ACCOUNT INFORMATION CARD ── --}}
            <div
                class="acct-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Account Information</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Read-only system &amp; session details for
                        this account.</p>
                </div>

                <div class="p-4 sm:p-5">
                    <dl>
                        {!! $infoRow('fingerprint', 'User ID', $user->id ? '#' . $user->id : null) !!}
                        {!! $infoRow('shield-check', 'Role', $role ? e($role) : null) !!}
                        {!! $infoRow('mail-check', 'Email Verification', !empty($user->email_verified_at) ? $badge('Verified', 'green', 'check') : $badge('Unverified', 'amber', 'clock')) !!}
                        {!! $infoRow('calendar-plus', 'Created At', $user->created_at?->format('d M Y, h:i A')) !!}
                        {!! $infoRow('refresh', 'Updated At', $user->updated_at?->format('d M Y, h:i A')) !!}
                        {!! $infoRow('login', 'Registration Method', e(ucfirst($registrationMethod))) !!}
                        {!! $infoRow('brand-facebook', 'Facebook Connected', $facebookConnected ? $badge('Connected', 'blue', 'check') : $badge('Not Connected', 'gray')) !!}
                        {!! $infoRow('brand-google', 'Google Connected', $googleConnected ? $badge('Connected', 'blue', 'check') : $badge('Not Connected', 'gray')) !!}
                        {!! $infoRow('shield-lock', 'Two Factor', $twoFactorEnabled ? $badge('Enabled', 'green', 'check') : $badge('Disabled', 'gray')) !!}
                        {!! $infoRow('clock-hour-4', 'Last Login', $lastLoginAt?->format('d M Y, h:i A')) !!}
                        {!! $infoRow('map-pin', 'Last IP', $lastIp ? e($lastIp) : null) !!}
                        {!! $infoRow('browser', 'Current Browser', $currentBrowser ? e($currentBrowser) : null) !!}
                        {!! $infoRow('device-desktop', 'Current Device', $currentDevice ? e($currentDevice) : null) !!}
                    </dl>
                </div>
            </div>
        </div>

        {{-- ==================== EDIT PROFILE CARD (full width) ==================== --}}
        <div id="editProfileCard"
            class="acct-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Edit Profile</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Update your photo, name, email, and phone
                        number.</p>
                </div>
                <i class="ti ti-user-edit text-xl text-blue-500 dark:text-blue-400" aria-hidden="true"></i>
            </div>

            <form action="{{ route('account.profile') }}" method="POST" enctype="multipart/form-data"
                class="p-4 sm:p-5 space-y-5" id="profileForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">

                {{-- ── Avatar upload ── --}}
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2" id="avatarLabel">Profile
                        Photo</span>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div id="avatarDropzone" tabindex="0" role="button" aria-labelledby="avatarLabel"
                            aria-describedby="avatarHelp"
                            class="avatar-dropzone relative w-24 h-24 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600
                                       flex items-center justify-center cursor-pointer overflow-hidden flex-shrink-0 bg-gray-50 dark:bg-gray-700">

                            <img id="avatarPreviewImg" src="{{ $user->avatar ?? '' }}" alt="Avatar preview"
                                class="w-full h-full object-cover {{ empty($user->avatar) ? 'hidden' : '' }}">

                            <span id="avatarPreviewInitial"
                                class="{{ !empty($user->avatar) ? 'hidden' : '' }} text-2xl font-bold text-gray-400 dark:text-gray-500">
                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                            </span>

                            <span class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/30
                                             text-white opacity-0 hover:opacity-100 transition-all duration-150">
                                <i class="ti ti-camera text-lg" aria-hidden="true"></i>
                            </span>

                            <input type="file" id="avatarInput" name="avatar"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only"
                                aria-hidden="true">
                        </div>

                        <div class="flex-1 min-w-0">
                            <p id="avatarHelp" class="text-xs text-gray-500 dark:text-gray-400">
                                Click or drag &amp; drop a photo. <span class="font-medium">JPG, JPEG, PNG or WEBP</span>,
                                up to <span class="font-medium">2MB</span>.
                            </p>
                            <p id="avatarFileName" class="text-xs text-gray-400 dark:text-gray-500 mt-1 truncate"></p>

                            <div id="avatarProgressWrap" class="avatar-progress-track mt-2 hidden" aria-hidden="true">
                                <div id="avatarProgressBar" class="avatar-progress-bar"></div>
                            </div>

                            <p id="avatarError" class="text-xs text-red-500 dark:text-red-400 mt-1 hidden" role="alert"></p>

                            <div class="flex items-center gap-2 mt-2">
                                <button type="button" id="chooseAvatarBtn"
                                    class="btn btn-secondary px-3 py-1.5 text-xs font-medium rounded-lg">
                                    <i class="ti ti-upload text-sm mr-1" aria-hidden="true"></i>Choose Photo
                                </button>
                                <button type="button" id="removeAvatarBtn"
                                    class="btn btn-danger px-3 py-1.5 text-xs font-medium rounded-lg {{ empty($user->avatar) ? 'hidden' : '' }}">
                                    <i class="ti ti-trash text-sm mr-1" aria-hidden="true"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {!! $field('full_name', 'Full Name', 'text', 'user', $user->full_name, true) !!}
                    {!! $field('email', 'Email', 'email', 'mail', $user->email, true) !!}
                </div>

                <div class="sm:w-1/2 sm:pr-2">
                    {!! $field('phone', 'Phone', 'tel', 'phone', $user->phone, false) !!}
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                    <button type="button" id="cancelProfileEditBtn"
                        class="btn btn-secondary w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-xl">
                        Cancel
                    </button>
                    <button type="submit" id="profileSubmitBtn" data-default-label="Save Changes"
                        class="btn btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium rounded-xl">
                        <span id="profileSubmitSpinner" class="btn-spinner bx-hidden"></span>
                        <i id="profileSubmitCheck" class="ti ti-check bx-hidden" aria-hidden="true"></i>
                        <span id="profileSubmitLabel">Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ==================== CHANGE PASSWORD CARD (full width) ==================== --}}
        <div
            class="acct-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Change Password</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Choose a strong password you don't use
                        elsewhere.</p>
                </div>
                <i class="ti ti-lock text-xl text-blue-500 dark:text-blue-400" aria-hidden="true"></i>
            </div>

            <form action="{{ route('account.password') }}" method="POST" class="p-4 sm:p-5 space-y-5" id="passwordForm"
                novalidate>
                @csrf
                @method('PUT')

                {!! $passwordField('current_password', 'Current Password') !!}

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {!! $passwordField('new_password', 'New Password', true, true, false) !!}
                    {!! $passwordField('new_password_confirmation', 'Confirm New Password', true, false, true) !!}
                </div>

                <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                    <i class="ti ti-info-circle text-sm" aria-hidden="true"></i>
                    Use at least 8 characters, mixing upper/lowercase letters, numbers, and a symbol.
                </p>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                    <button type="reset" id="clearPasswordBtn"
                        class="btn btn-secondary w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-xl">
                        Clear
                    </button>
                    <button type="submit" id="passwordSubmitBtn" data-default-label="Update Password"
                        class="btn btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium rounded-xl">
                        <span id="passwordSubmitSpinner" class="btn-spinner bx-hidden"></span>
                        <i id="passwordSubmitCheck" class="ti ti-check bx-hidden" aria-hidden="true"></i>
                        <span id="passwordSubmitLabel">Update Password</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            (function () {
                'use strict';

                // ══════════════════════════════════════════════════════
                //  SHARED HELPERS (cached lookups, reused everywhere)
                // ══════════════════════════════════════════════════════
                var $ = function (id) { return document.getElementById(id); };
                var MAX_AVATAR_BYTES = 2 * 1024 * 1024; // 2MB
                var ALLOWED_AVATAR_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

                function toggleHidden(el, hidden) {
                    if (!el) return;
                    el.classList.toggle('bx-hidden', hidden);
                }

                // ══════════════════════════════════════════════════════
                //  TOAST (dark-mode aware surface)
                // ══════════════════════════════════════════════════════
                var toastContainer = $('toastContainer');
                window.showToast = function (message, type) {
                    type = type || 'success';
                    var colors = { success: '#2563eb', error: '#ef4444', info: '#2563eb', warning: '#f59e0b' };
                    var toast = document.createElement('div');
                    toast.className = 'toast flex items-center gap-2.5 px-4 py-3 rounded-2xl shadow-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 text-sm font-medium min-w-0 sm:min-w-[220px] w-full sm:w-auto border border-gray-100 dark:border-gray-700';
                    toast.setAttribute('role', 'status');
                    toast.innerHTML = '<span class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + (colors[type] || colors.info) + '"></span><span>' + message + '</span>';
                    toastContainer.appendChild(toast);
                    setTimeout(function () {
                        toast.classList.add('leaving');
                        toast.addEventListener('animationend', function () { toast.remove(); }, { once: true });
                    }, 3200);
                };

                // ══════════════════════════════════════════════════════
                //  AUTO-HIDE FLASH ALERTS
                // ══════════════════════════════════════════════════════
                document.querySelectorAll('#flashMessages .alert-box[role="status"]').forEach(function (box) {
                    setTimeout(function () {
                        box.style.maxHeight = box.offsetHeight + 'px';
                        requestAnimationFrame(function () {
                            box.style.opacity = '0';
                            box.style.maxHeight = '0px';
                            box.style.marginBottom = '0px';
                            box.style.overflow = 'hidden';
                        });
                        setTimeout(function () { box.remove(); }, 320);
                    }, 4000);
                });

                // ══════════════════════════════════════════════════════
                //  BUTTON RIPPLE (event delegation, single listener)
                // ══════════════════════════════════════════════════════
                document.addEventListener('click', function (e) {
                    var btn = e.target.closest('.btn');
                    if (!btn || btn.disabled) return;

                    var rect = btn.getBoundingClientRect();
                    var size = Math.max(rect.width, rect.height);
                    var ripple = document.createElement('span');
                    ripple.className = 'btn-ripple';
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                    btn.appendChild(ripple);
                    ripple.addEventListener('animationend', function () { ripple.remove(); });
                });

                // ══════════════════════════════════════════════════════
                //  PASSWORD SHOW / HIDE
                // ══════════════════════════════════════════════════════
                document.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var targetId = btn.getAttribute('data-toggle-for');
                        var input = $(targetId);
                        if (!input) return;

                        var isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';
                        btn.classList.toggle('showing', isPassword);
                        btn.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
                        btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    });
                });

                // ══════════════════════════════════════════════════════
                //  LIVE FIELD VALIDATION STYLING (green / red borders)
                // ══════════════════════════════════════════════════════
                function markFieldValid(input, isValid) {
                    input.classList.toggle('field-success', !!isValid);
                    input.classList.toggle('field-error', !isValid && input.value.trim() !== '');
                }

                document.querySelectorAll('[data-field]').forEach(function (input) {
                    input.addEventListener('input', function () {
                        if (input.hasAttribute('required')) {
                            markFieldValid(input, input.checkValidity() && input.value.trim() !== '');
                        } else if (input.type === 'email') {
                            markFieldValid(input, input.value === '' || input.checkValidity());
                        }
                    });
                });

                // ══════════════════════════════════════════════════════
                //  EDIT PROFILE: scroll into view + focus first field
                // ══════════════════════════════════════════════════════
                var editProfileBtn = $('editProfileToggleBtn');
                var editProfileCard = $('editProfileCard');
                var cancelProfileEditBtn = $('cancelProfileEditBtn');

                if (editProfileBtn && editProfileCard) {
                    editProfileBtn.addEventListener('click', function () {
                        editProfileCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        var firstField = $('full_name');
                        if (firstField) setTimeout(function () { firstField.focus(); }, 350);
                    });
                }

                if (cancelProfileEditBtn) {
                    cancelProfileEditBtn.addEventListener('click', function () {
                        $('profileForm').reset();
                        resetAvatarPreviewToServerState();
                        document.querySelectorAll('#profileForm [data-field]').forEach(function (el) {
                            el.classList.remove('field-success', 'field-error');
                        });
                    });
                }

                // ══════════════════════════════════════════════════════
                //  AVATAR UPLOAD: click, drag & drop, preview, remove
                // ══════════════════════════════════════════════════════
                var dropzone = $('avatarDropzone');
                var avatarInput = $('avatarInput');
                var previewImg = $('avatarPreviewImg');
                var previewInitial = $('avatarPreviewInitial');
                var chooseAvatarBtn = $('chooseAvatarBtn');
                var removeAvatarBtn = $('removeAvatarBtn');
                var removeAvatarFlag = $('removeAvatarFlag');
                var avatarFileName = $('avatarFileName');
                var avatarError = $('avatarError');
                var heroAvatar = $('profileHeroAvatar');
                var serverAvatarSrc = previewImg ? previewImg.getAttribute('src') : '';
                var serverHasAvatar = !!serverAvatarSrc;

                function showAvatarError(message) {
                    avatarError.textContent = message;
                    toggleHidden(avatarError, false);
                }
                function clearAvatarError() {
                    avatarError.textContent = '';
                    toggleHidden(avatarError, true);
                }

                function setAvatarPreview(src) {
                    if (src) {
                        previewImg.src = src;
                        toggleHidden(previewImg, false);
                        toggleHidden(previewInitial, true);
                    } else {
                        previewImg.src = '';
                        toggleHidden(previewImg, true);
                        toggleHidden(previewInitial, false);
                    }
                    if (heroAvatar && heroAvatar.tagName === 'IMG') {
                        heroAvatar.src = src || '';
                    }
                }

                function resetAvatarPreviewToServerState() {
                    removeAvatarFlag.value = '0';
                    avatarInput.value = '';
                    avatarFileName.textContent = '';
                    clearAvatarError();
                    setAvatarPreview(serverHasAvatar ? serverAvatarSrc : null);
                    toggleHidden(removeAvatarBtn, !serverHasAvatar);
                }

                function handleAvatarFile(file) {
                    clearAvatarError();
                    if (!file) return;

                    if (ALLOWED_AVATAR_TYPES.indexOf(file.type) === -1) {
                        showAvatarError('Please choose a JPG, JPEG, PNG, or WEBP image.');
                        return;
                    }
                    if (file.size > MAX_AVATAR_BYTES) {
                        showAvatarError('Image must be 2MB or smaller.');
                        return;
                    }

                    removeAvatarFlag.value = '0';
                    avatarFileName.textContent = file.name;

                    var reader = new FileReader();
                    reader.onload = function (e) { setAvatarPreview(e.target.result); };
                    reader.readAsDataURL(file);

                    toggleHidden(removeAvatarBtn, false);
                }

                if (chooseAvatarBtn) chooseAvatarBtn.addEventListener('click', function () { avatarInput.click(); });
                if (dropzone) {
                    dropzone.addEventListener('click', function () { avatarInput.click(); });
                    dropzone.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); avatarInput.click(); }
                    });

                    ['dragenter', 'dragover'].forEach(function (evt) {
                        dropzone.addEventListener(evt, function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.add('dragover');
                        });
                    });
                    ['dragleave', 'drop'].forEach(function (evt) {
                        dropzone.addEventListener(evt, function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            dropzone.classList.remove('dragover');
                        });
                    });
                    dropzone.addEventListener('drop', function (e) {
                        var file = e.dataTransfer.files && e.dataTransfer.files[0];
                        if (file) {
                            // Keep the native input in sync so a normal (non-JS) submit would still carry the file.
                            try {
                                var dt = new DataTransfer();
                                dt.items.add(file);
                                avatarInput.files = dt.files;
                            } catch (err) { /* older browsers: preview still works via handleAvatarFile */ }
                            handleAvatarFile(file);
                        }
                    });
                }

                if (avatarInput) {
                    avatarInput.addEventListener('change', function () {
                        handleAvatarFile(avatarInput.files && avatarInput.files[0]);
                    });
                }

                if (removeAvatarBtn) {
                    removeAvatarBtn.addEventListener('click', function () {
                        removeAvatarFlag.value = '1';
                        avatarInput.value = '';
                        avatarFileName.textContent = '';
                        clearAvatarError();
                        setAvatarPreview(null);
                        toggleHidden(removeAvatarBtn, true);
                    });
                }

                // ══════════════════════════════════════════════════════
                //  PASSWORD STRENGTH METER
                // ══════════════════════════════════════════════════════
                var newPasswordInput = $('new_password');
                var strengthWrap = $('passwordStrength');
                var strengthLabel = $('passwordStrengthLabel');
                var strengthBars = strengthWrap ? strengthWrap.querySelectorAll('.strength-bar') : [];

                function scorePassword(value) {
                    var score = 0;
                    if (value.length >= 8) score++;
                    if (value.length >= 12) score++;
                    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
                    if (/\d/.test(value)) score++;
                    if (/[^A-Za-z0-9]/.test(value)) score++;
                    return score; // 0–5
                }

                function renderStrength(value) {
                    if (!strengthWrap) return;

                    if (!value) {
                        toggleHidden(strengthWrap, true);
                        return;
                    }
                    toggleHidden(strengthWrap, false);

                    var score = scorePassword(value);
                    var level = score <= 2 ? 'weak' : (score <= 3 ? 'medium' : 'strong');
                    var filledCount = level === 'weak' ? 1 : (level === 'medium' ? 2 : 3);
                    var labels = { weak: 'Weak password', medium: 'Medium strength', strong: 'Strong password' };
                    var labelColors = { weak: '#ef4444', medium: '#f59e0b', strong: '#10b981' };

                    strengthBars.forEach(function (bar, i) {
                        bar.classList.remove('filled-weak', 'filled-medium', 'filled-strong');
                        if (i < filledCount) bar.classList.add('filled-' + level);
                    });

                    strengthLabel.textContent = labels[level];
                    strengthLabel.style.color = labelColors[level];
                }

                if (newPasswordInput) {
                    newPasswordInput.addEventListener('input', function () {
                        renderStrength(newPasswordInput.value);
                        checkPasswordMatch();
                    });
                }

                // ══════════════════════════════════════════════════════
                //  CONFIRM PASSWORD MATCH
                // ══════════════════════════════════════════════════════
                var confirmInput = $('new_password_confirmation');
                var matchHint = $('passwordMatchHint');

                function checkPasswordMatch() {
                    if (!confirmInput || !matchHint || !newPasswordInput) return;

                    var confirmValue = confirmInput.value;
                    if (!confirmValue) {
                        toggleHidden(matchHint, true);
                        confirmInput.classList.remove('field-success', 'field-error');
                        return;
                    }

                    var matches = confirmValue === newPasswordInput.value;
                    toggleHidden(matchHint, false);
                    matchHint.textContent = matches ? 'Passwords match.' : 'Passwords do not match.';
                    matchHint.className = 'mt-1 text-xs ' + (matches ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400');
                    confirmInput.classList.toggle('field-success', matches);
                    confirmInput.classList.toggle('field-error', !matches);
                }

                if (confirmInput) confirmInput.addEventListener('input', checkPasswordMatch);

                // ══════════════════════════════════════════════════════
                //  BUTTON LOADING STATE (shared by both forms)
                // ══════════════════════════════════════════════════════
                function setButtonLoading(btnId, spinnerId, labelId, checkId, loadingText) {
                    var btn = $(btnId), spinner = $(spinnerId), label = $(labelId), check = $(checkId);
                    if (!btn) return;
                    btn.disabled = true;
                    toggleHidden(spinner, false);
                    toggleHidden(check, true);
                    if (label) label.textContent = loadingText;
                }

                function setButtonSuccess(btnId, spinnerId, labelId, checkId, successText, resetAfterMs) {
                    var btn = $(btnId), spinner = $(spinnerId), label = $(labelId), check = $(checkId);
                    if (!btn) return;
                    toggleHidden(spinner, true);
                    toggleHidden(check, false);
                    check.classList.add('success-pop');
                    if (label) label.textContent = successText;

                    setTimeout(function () {
                        btn.disabled = false;
                        toggleHidden(check, true);
                        check.classList.remove('success-pop');
                        if (label) label.textContent = btn.dataset.defaultLabel || label.textContent;
                    }, resetAfterMs || 1600);
                }

                function resetButton(btnId, spinnerId, labelId, checkId, defaultText) {
                    var btn = $(btnId), spinner = $(spinnerId), label = $(labelId), check = $(checkId);
                    if (!btn) return;
                    btn.disabled = false;
                    toggleHidden(spinner, true);
                    toggleHidden(check, true);
                    if (label) label.textContent = defaultText;
                }

                // ══════════════════════════════════════════════════════
                //  PROFILE FORM SUBMIT (AJAX so we can show upload
                //  progress + a success state without a hard reload)
                // ══════════════════════════════════════════════════════
                var profileForm = $('profileForm');
                if (profileForm) {
                    profileForm.addEventListener('submit', function (e) {
                        e.preventDefault();

                        document.querySelectorAll('#profileForm .field-error-text').forEach(function (el) { el.remove(); });
                        document.querySelectorAll('#profileForm [data-field]').forEach(function (el) { el.classList.remove('field-error'); });

                        setButtonLoading('profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'profileSubmitCheck', 'Saving…');

                        var formData = new FormData(profileForm);
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', profileForm.action, true);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.setRequestHeader('Accept', 'application/json');

                        var progressWrap = $('avatarProgressWrap');
                        var progressBar = $('avatarProgressBar');
                        var hasFile = avatarInput.files && avatarInput.files.length > 0;
                        if (hasFile) {
                            toggleHidden(progressWrap, false);
                            xhr.upload.addEventListener('progress', function (evt) {
                                if (!evt.lengthComputable) return;
                                progressBar.style.width = Math.round((evt.loaded / evt.total) * 100) + '%';
                            });
                        }

                        xhr.onload = function () {
                            toggleHidden(progressWrap, true);
                            progressBar.style.width = '0%';

                            var data = {};
                            try { data = JSON.parse(xhr.responseText); } catch (err) { /* non-JSON response */ }

                            if (xhr.status === 200 && data.success !== false) {
                                setButtonSuccess('profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'profileSubmitCheck', 'Saved!');
                                showToast(data.message || 'Profile updated successfully.', 'success');
                                serverHasAvatar = !!(data.user && data.user.avatar) || (!hasFile && removeAvatarFlag.value !== '1' && serverHasAvatar);
                                serverAvatarSrc = (data.user && data.user.avatar) || (removeAvatarFlag.value === '1' ? '' : serverAvatarSrc);
                                removeAvatarFlag.value = '0';
                                avatarFileName.textContent = '';
                            } else if (xhr.status === 422 && data.errors) {
                                resetButton('profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'profileSubmitCheck', 'Save Changes');
                                Object.keys(data.errors).forEach(function (field) {
                                    var input = profileForm.querySelector('[data-field="' + field + '"]');
                                    var message = Array.isArray(data.errors[field]) ? data.errors[field][0] : data.errors[field];
                                    if (input) {
                                        input.classList.add('field-error');
                                        input.classList.remove('field-success');
                                        var p = document.createElement('p');
                                        p.className = 'mt-1 text-xs text-red-500 dark:text-red-400 field-error-text';
                                        p.textContent = message;
                                        input.closest('.relative').parentElement.appendChild(p);
                                    } else if (field === 'avatar') {
                                        showAvatarError(message);
                                    }
                                });
                                showToast('Please fix the highlighted fields.', 'error');
                            } else {
                                resetButton('profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'profileSubmitCheck', 'Save Changes');
                                showToast((data && data.message) || 'Something went wrong. Please try again.', 'error');
                            }
                        };

                        xhr.onerror = function () {
                            toggleHidden(progressWrap, true);
                            resetButton('profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'profileSubmitCheck', 'Save Changes');
                            showToast('Network error. Please check your connection and try again.', 'error');
                        };

                        xhr.send(formData);
                    });
                }

                // ══════════════════════════════════════════════════════
                //  PASSWORD FORM SUBMIT (AJAX, success animation, then
                //  clears the form — nothing else on the page changes)
                // ══════════════════════════════════════════════════════
                var passwordForm = $('passwordForm');
                if (passwordForm) {
                    passwordForm.addEventListener('submit', function (e) {
                        e.preventDefault();

                        document.querySelectorAll('#passwordForm .field-error-text').forEach(function (el) { el.remove(); });
                        document.querySelectorAll('#passwordForm [data-field]').forEach(function (el) { el.classList.remove('field-error'); });

                        setButtonLoading('passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'passwordSubmitCheck', 'Updating…');

                        var formData = new FormData(passwordForm);

                        fetch(passwordForm.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: formData,
                        })
                            .then(function (response) {
                                return response.json().then(function (data) { return { status: response.status, data: data }; });
                            })
                            .then(function (result) {
                                if (result.status === 200 && result.data.success !== false) {
                                    setButtonSuccess('passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'passwordSubmitCheck', 'Updated!');
                                    showToast(result.data.message || 'Password updated successfully.', 'success');
                                    passwordForm.reset();
                                    toggleHidden(strengthWrap, true);
                                    toggleHidden(matchHint, true);
                                    document.querySelectorAll('#passwordForm [data-field]').forEach(function (el) {
                                        el.classList.remove('field-success', 'field-error');
                                    });
                                } else if (result.status === 422 && result.data.errors) {
                                    resetButton('passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'passwordSubmitCheck', 'Update Password');
                                    Object.keys(result.data.errors).forEach(function (field) {
                                        var input = passwordForm.querySelector('[data-field="' + field + '"]');
                                        var message = Array.isArray(result.data.errors[field]) ? result.data.errors[field][0] : result.data.errors[field];
                                        if (input) {
                                            input.classList.add('field-error');
                                            var p = document.createElement('p');
                                            p.className = 'mt-1 text-xs text-red-500 dark:text-red-400 field-error-text';
                                            p.textContent = message;
                                            input.closest('.relative').parentElement.appendChild(p);
                                        }
                                    });
                                    showToast('Please fix the highlighted fields.', 'error');
                                } else {
                                    resetButton('passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'passwordSubmitCheck', 'Update Password');
                                    showToast((result.data && result.data.message) || 'Something went wrong. Please try again.', 'error');
                                }
                            })
                            .catch(function () {
                                resetButton('passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'passwordSubmitCheck', 'Update Password');
                                showToast('Network error. Please check your connection and try again.', 'error');
                            });
                    });
                }

                if ($('clearPasswordBtn')) {
                    $('clearPasswordBtn').addEventListener('click', function () {
                        toggleHidden(strengthWrap, true);
                        toggleHidden(matchHint, true);
                        document.querySelectorAll('#passwordForm [data-field]').forEach(function (el) {
                            el.classList.remove('field-success', 'field-error');
                        });
                    });
                }

                // ══════════════════════════════════════════════════════
                //  Keep the password card in view if the page loaded
                //  with server-side validation errors on it.
                // ══════════════════════════════════════════════════════
                @if($errors->has('current_password') || $errors->has('new_password'))
                    document.addEventListener('DOMContentLoaded', function () {
                        var pwForm = $('passwordForm');
                        if (pwForm) pwForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                @endif
                    })();
        </script>
    @endpush

@endsection