@extends('layouts.app')

@section('content')
    @php
        $user = $user ?? Auth::user();
        $role = $role ?? $user->getRoleNames()->first();
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

        .acct-card {
            animation: fadeSlideUp .4s ease both;
        }

        .acct-card:nth-child(1) {
            animation-delay: .04s;
        }

        .acct-card:nth-child(2) {
            animation-delay: .10s;
        }

        .btn-primary {
            transition: transform .14s ease, box-shadow .14s ease, background-color .14s ease;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: .65;
            cursor: not-allowed;
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

        .toast {
            animation: toastSlide .3s ease;
        }

        .toast.leaving {
            animation: toastOut .3s ease forwards;
        }

        .field-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        .password-toggle-btn svg.eye-off {
            display: none;
        }

        .password-toggle-btn.showing svg.eye {
            display: none;
        }

        .password-toggle-btn.showing svg.eye-off {
            display: block;
        }

        .bx-hidden {
            display: none !important;
        }
    </style>

    {{-- Toast container (used for JS-driven confirmations, e.g. copy actions) --}}
    <div class="fixed top-4 right-4 left-4 sm:left-auto sm:top-5 sm:right-5 z-[9999] flex flex-col gap-2"
        id="toastContainer"></div>

    <div class="space-y-4">

        {{-- ==================== PAGE HEADER ==================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">My Account</h1>
                <p class="text-sm text-gray-400 mt-0.5">Manage your profile, security, and account details.</p>
            </div>
        </div>

        {{-- ==================== FLASH MESSAGES ==================== --}}
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                <i class="ti ti-circle-check text-lg flex-shrink-0 mt-0.5"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
                <i class="ti ti-alert-circle text-lg flex-shrink-0 mt-0.5"></i>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
                <i class="ti ti-alert-triangle text-lg flex-shrink-0 mt-0.5"></i>
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

        {{-- ==================== PROFILE + ACCOUNT INFO (side by side on desktop) ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- ── PROFILE CARD ── --}}
            <div class="acct-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900">Profile</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Your public account details.</p>
                </div>

                <div class="p-4 sm:p-5 space-y-5">
                    <div class="flex items-center gap-4">
                        @if(!empty($user->avatar))
                            <img src="{{ $user->avatar }}" alt="{{ $user->full_name }}"
                                class="w-16 h-16 rounded-full object-cover border border-gray-200 flex-shrink-0">
                        @else
                            <div
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-700
                                                flex items-center justify-center text-white text-2xl font-bold shadow-md shadow-blue-500/25 flex-shrink-0">
                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-base font-semibold text-gray-900 truncate">{{ $user->full_name ?? '—' }}</p>
                            <p class="text-sm text-gray-400 truncate">{{ $user->email ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-phone text-gray-400 text-base mt-0.5"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wide">Phone</p>
                                <p class="text-gray-800 font-medium truncate">{{ $user->phone ?? '—' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-shield-check text-gray-400 text-base mt-0.5"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wide">Role</p>
                                <p class="text-gray-800 font-medium truncate">
                                    {{ $user->role->name ?? $user->role ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-activity text-gray-400 text-base mt-0.5"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wide">Status</p>
                                @if((int) ($user->status ?? 1) === 1)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-emerald-50 text-emerald-600 border border-emerald-100 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-gray-100 text-gray-500 border border-gray-200 mt-0.5">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <i class="ti ti-calendar text-gray-400 text-base mt-0.5"></i>
                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400 uppercase tracking-wide">Created At</p>
                                <p class="text-gray-800 font-medium truncate">
                                    {{ $user->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="editProfileToggleBtn" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                   bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/20">
                        <i class="ti ti-pencil text-base"></i>
                        Edit Profile
                    </button>
                </div>
            </div>

            {{-- ── ACCOUNT INFORMATION CARD ── --}}
            <div class="acct-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900">Account Information</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Read-only system details for this account.</p>
                </div>

                <div class="p-4 sm:p-5">
                    <dl class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3 first:pt-0">
                            <dt class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="ti ti-fingerprint text-gray-400 text-base"></i>
                                User ID
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">#{{ $user->id ?? '—' }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <dt class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="ti ti-shield-check text-gray-400 text-base"></i>
                                Role
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $user->role->name ?? $user->role ?? '—' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <dt class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="ti ti-mail-check text-gray-400 text-base"></i>
                                Email Verified
                            </dt>
                            <dd class="text-sm font-medium">
                                @if(!empty($user->email_verified_at))
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <i class="ti ti-check text-sm"></i> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-amber-50 text-amber-600 border border-amber-100">
                                        <i class="ti ti-clock text-sm"></i> Unverified
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <dt class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="ti ti-calendar-plus text-gray-400 text-base"></i>
                                Account Created
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $user->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3 last:pb-0">
                            <dt class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="ti ti-refresh text-gray-400 text-base"></i>
                                Updated At
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ $user->updated_at?->format('d M Y, h:i A') ?? '—' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ==================== EDIT PROFILE CARD (full width) ==================== --}}
        <div id="editProfileCard" class="acct-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Edit Profile</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Update your name, email, and phone number.</p>
                </div>
                <i class="ti ti-user-edit text-xl text-blue-500"></i>
            </div>

            <form action="{{ route('account.profile') }}" method="POST" class="p-4 sm:p-5 space-y-4" id="profileForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-xs font-medium text-gray-500 mb-1.5">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" required
                            value="{{ old('full_name', $user->full_name) }}"
                            class="field-input w-full px-3 py-2 text-sm rounded-xl border
                                       {{ $errors->has('full_name') ? 'border-red-300' : 'border-gray-200' }}
                                       bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        @error('full_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-500 mb-1.5">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" required value="{{ old('email', $user->email) }}"
                            class="field-input w-full px-3 py-2 text-sm rounded-xl border
                                       {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }}
                                       bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sm:w-1/2 sm:pr-2">
                    <label for="phone" class="block text-xs font-medium text-gray-500 mb-1.5">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="+855 12 345 678"
                        class="field-input w-full px-3 py-2 text-sm rounded-xl border
                                   {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-200' }}
                                   bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                    <button type="button" id="cancelProfileEditBtn" class="w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-xl border border-gray-200
                                   text-gray-600 hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="profileSubmitBtn" class="btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium
                                   bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-500/20">
                        <span id="profileSubmitSpinner" class="btn-spinner bx-hidden"></span>
                        <span id="profileSubmitLabel">Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ==================== CHANGE PASSWORD CARD (full width) ==================== --}}
        <div class="acct-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Change Password</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Choose a strong password you don't use elsewhere.</p>
                </div>
                <i class="ti ti-lock text-xl text-blue-500"></i>
            </div>

            <form action="{{ route('account.password') }}" method="POST" class="p-4 sm:p-5 space-y-4" id="passwordForm">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Current Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                            class="field-input w-full px-3 py-2 pr-11 text-sm rounded-xl border
                                       {{ $errors->has('current_password') ? 'border-red-300' : 'border-gray-200' }}
                                       bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <button type="button" data-toggle-for="current_password" class="password-toggle-btn absolute right-2.5 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center
                                       rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                            <svg class="eye w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303m3.13-1.279A10.45 10.45 0 0022.066 12c-1.292-4.057-5.065-7-9.542-7-.848 0-1.67.105-2.454.303" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-medium text-gray-500 mb-1.5">
                            New Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" required
                                class="field-input w-full px-3 py-2 pr-11 text-sm rounded-xl border
                                           {{ $errors->has('new_password') ? 'border-red-300' : 'border-gray-200' }}
                                           bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            <button type="button" data-toggle-for="new_password"
                                class="password-toggle-btn absolute right-2.5 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center
                                           rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="eye w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303m3.13-1.279A10.45 10.45 0 0022.066 12c-1.292-4.057-5.065-7-9.542-7-.848 0-1.67.105-2.454.303" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-medium text-gray-500 mb-1.5">
                            Confirm New Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                class="field-input w-full px-3 py-2 pr-11 text-sm rounded-xl border border-gray-200
                                           bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                            <button type="button" data-toggle-for="new_password_confirmation"
                                class="password-toggle-btn absolute right-2.5 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center
                                           rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="eye w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303m3.13-1.279A10.45 10.45 0 0022.066 12c-1.292-4.057-5.065-7-9.542-7-.848 0-1.67.105-2.454.303" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-400 flex items-center gap-1.5">
                    <i class="ti ti-info-circle text-sm"></i>
                    Use at least 8 characters, mixing letters and numbers.
                </p>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                    <button type="reset" class="w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-xl border border-gray-200
                                   text-gray-600 hover:bg-gray-50 transition-all">
                        Clear
                    </button>
                    <button type="submit" id="passwordSubmitBtn" class="btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-sm font-medium
                                   bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-500/20">
                        <span id="passwordSubmitSpinner" class="btn-spinner bx-hidden"></span>
                        <span id="passwordSubmitLabel">Update Password</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    @push('scripts')
        <script>
            (function () {
                // ══════════════════════════════════════════════════════
                //  PASSWORD SHOW / HIDE (vanilla JS)
                // ══════════════════════════════════════════════════════
                document.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var targetId = btn.getAttribute('data-toggle-for');
                        var input = document.getElementById(targetId);
                        if (!input) return;

                        var isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';
                        btn.classList.toggle('showing', isPassword);
                    });
                });

                // ══════════════════════════════════════════════════════
                //  EDIT PROFILE: scroll into view + focus first field
                // ══════════════════════════════════════════════════════
                var editProfileBtn = document.getElementById('editProfileToggleBtn');
                var editProfileCard = document.getElementById('editProfileCard');
                var cancelProfileEditBtn = document.getElementById('cancelProfileEditBtn');

                if (editProfileBtn && editProfileCard) {
                    editProfileBtn.addEventListener('click', function () {
                        editProfileCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        var firstField = document.getElementById('full_name');
                        if (firstField) setTimeout(function () { firstField.focus(); }, 350);
                    });
                }

                if (cancelProfileEditBtn) {
                    cancelProfileEditBtn.addEventListener('click', function () {
                        document.getElementById('profileForm').reset();
                    });
                }

                // ══════════════════════════════════════════════════════
                //  BUTTON LOADING STATE ON SUBMIT
                // ══════════════════════════════════════════════════════
                function bindLoadingState(formId, btnId, spinnerId, labelId, loadingText) {
                    var form = document.getElementById(formId);
                    if (!form) return;

                    form.addEventListener('submit', function () {
                        var btn = document.getElementById(btnId);
                        var spinner = document.getElementById(spinnerId);
                        var label = document.getElementById(labelId);
                        if (!btn) return;

                        btn.disabled = true;
                        if (spinner) spinner.classList.remove('bx-hidden');
                        if (label) label.textContent = loadingText;
                    });
                }

                bindLoadingState('profileForm', 'profileSubmitBtn', 'profileSubmitSpinner', 'profileSubmitLabel', 'Saving…');
                bindLoadingState('passwordForm', 'passwordSubmitBtn', 'passwordSubmitSpinner', 'passwordSubmitLabel', 'Updating…');

                // ══════════════════════════════════════════════════════
                //  If the page reloaded with validation errors for the
                //  password form, keep that card in view.
                // ══════════════════════════════════════════════════════
                @if($errors->has('current_password') || $errors->has('new_password'))
                    document.addEventListener('DOMContentLoaded', function () {
                        var pwForm = document.getElementById('passwordForm');
                        if (pwForm) pwForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                @endif

                // ══════════════════════════════════════════════════════
                //  TOAST (available for any future JS-driven feedback)
                // ══════════════════════════════════════════════════════
                window.showToast = function (message, type) {
                    type = type || 'success';
                    var colors = { success: '#2563eb', error: '#ef4444', info: '#2563eb', warning: '#f59e0b' };
                    var toast = document.createElement('div');
                    toast.className = 'toast flex items-center gap-2.5 px-4 py-3 rounded-2xl shadow-lg bg-white text-gray-800 text-sm font-medium min-w-0 sm:min-w-[220px] w-full sm:w-auto border border-gray-100';
                    toast.innerHTML = '<span class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + (colors[type] || colors.info) + '"></span><span>' + message + '</span>';
                    document.getElementById('toastContainer').appendChild(toast);
                    setTimeout(function () {
                        toast.classList.add('leaving');
                        toast.addEventListener('animationend', function () { toast.remove(); }, { once: true });
                    }, 3200);
                };
            })();
        </script>
    @endpush

@endsection