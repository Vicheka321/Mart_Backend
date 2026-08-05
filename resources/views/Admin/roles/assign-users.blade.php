@extends('layouts.app')

@section('content')

    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-12px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        .s-header {
            animation: fadeSlideUp .4s ease both;
        }

        .s-table {
            animation: fadeSlideUp .5s .10s ease both;
        }

        .t-row {
            animation: rowSlideIn .35s ease both;
        }

        .t-row:nth-child(1) {
            animation-delay: .16s;
        }

        .t-row:nth-child(2) {
            animation-delay: .21s;
        }

        .t-row:nth-child(3) {
            animation-delay: .26s;
        }

        .t-row:nth-child(4) {
            animation-delay: .31s;
        }

        .t-row:nth-child(5) {
            animation-delay: .36s;
        }

        .t-row:nth-child(6) {
            animation-delay: .41s;
        }

        .t-row:nth-child(7) {
            animation-delay: .46s;
        }

        .t-row:nth-child(8) {
            animation-delay: .51s;
        }

        .t-row:nth-child(9) {
            animation-delay: .56s;
        }

        .t-row:nth-child(10) {
            animation-delay: .61s;
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

    <div x-data="assignRolePage()" x-init="init()" class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="s-header flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>

                <h1 class="text-lg font-bold text-gray-900 dark:text-white mt-0.5">Assign User Roles</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Assign a single role to each user and manage dashboard access.
                </p>
            </div>
            <button type="button" @click="openAddModal()"
                class="act inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                       bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/25 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add user
            </button>
        </div>

        {{-- ==================== TABLE ==================== --}}
        <div
            class="s-table bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- Toolbar --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div
                        class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">User role directory</h2>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Assign a single role to each user</p>
                    </div>
                </div>
                <div class="relative w-full sm:w-72">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                    </svg>
                    <input x-model="search" type="text" placeholder="Search name or email…"
                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700
                               pl-9 pr-3 text-sm text-gray-800 dark:text-white dark:placeholder:text-gray-500 outline-none
                               transition focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400">
                </div>
            </div>

            {{-- Desktop table --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr
                            class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Contact</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-center">Access</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($users as $user)
                            @php
                                $currentRole = $user->roles->pluck('name')->first();
                                $isSuperAdmin = $user->hasRole('Super Admin');
                                $userPayload = [
                                    'id' => $user->id,
                                    'full_name' => $user->full_name,
                                    'email' => $user->email,
                                    'phone' => $user->phone,
                                    'role' => $currentRole,
                                    'is_super_admin' => $isSuperAdmin,
                                ];
                                $roleDot = match ($currentRole) {
                                    'Super Admin' => 'bg-violet-500',
                                    'Admin' => 'bg-indigo-500',
                                    'Manager' => 'bg-blue-500',
                                    'Staff' => 'bg-amber-500',
                                    'Customer' => 'bg-emerald-500',
                                    default => 'bg-gray-400',
                                };
                                $roleBadge = match ($currentRole) {
                                    'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                                    'Admin' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                                    'Manager' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                    'Staff' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                    'Customer' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                };
                            @endphp
                            <tr x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                                class="t-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                                {{-- User --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->full_name }}"
                                                class="w-9 h-9 rounded-xl object-cover shadow-sm flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                                                flex items-center justify-center
                                                                text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $user->full_name ?? 'Unnamed user' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500">#{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact --}}
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $user->email ?: '—' }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $user->phone ?: 'No phone' }}</p>
                                </td>

                                {{-- Role --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-2 h-2 rounded-full flex-shrink-0 {{ $currentRole ? $roleDot : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         {{ $currentRole ? $roleBadge : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ $currentRole ?? 'No role' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Access --}}
                                <td class="px-6 py-4 text-center">
                                    @if ($user->can('access_admin_panel'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                             bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                            Allowed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium italic
                                                             bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500">
                                            No access
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($isSuperAdmin)
                                            <span class="act inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold
                                                                 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400
                                                                 border border-amber-200 dark:border-amber-900/40">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                                    <path d="M7 11V7a5 5 0 0110 0v4" />
                                                </svg>
                                                Protected
                                            </span>
                                        @else
                                            <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                                class="act inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold
                                                               bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                </svg>
                                                Assign role
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">No users found</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">There are no users to assign roles
                                            to yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div id="usersMobileList" class="lg:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($users as $user)
                    @php
                        $currentRole = $user->roles->pluck('name')->first();
                        $isSuperAdmin = $user->hasRole('Super Admin');
                        $userPayload = ['id' => $user->id, 'full_name' => $user->full_name, 'email' => $user->email, 'phone' => $user->phone, 'role' => $currentRole, 'is_super_admin' => $isSuperAdmin];
                        $roleDot = match ($currentRole) {
                            'Super Admin' => 'bg-violet-500',
                            'Admin' => 'bg-indigo-500',
                            'Manager' => 'bg-blue-500',
                            'Staff' => 'bg-amber-500',
                            'Customer' => 'bg-emerald-500',
                            default => 'bg-gray-400',
                        };
                        $roleBadge = match ($currentRole) {
                            'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                            'Admin' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                            'Manager' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                            'Staff' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                            'Customer' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                            default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                        };
                    @endphp
                    <div x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                        class="t-row p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                                flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $user->full_name ?? 'Unnamed user' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email ?: '—' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span
                                    class="w-2 h-2 rounded-full {{ $currentRole ? $roleDot : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                <span
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $currentRole ? $roleBadge : 'bg-gray-100 text-gray-400 dark:bg-gray-700' }}">
                                    {{ $currentRole ?? 'No role' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if ($isSuperAdmin)
                                <span
                                    class="flex h-9 items-center justify-center rounded-xl border border-amber-200 dark:border-amber-900/40
                                                     bg-amber-50 dark:bg-amber-500/10 text-xs font-semibold text-amber-600 dark:text-amber-400">
                                    Protected
                                </span>
                            @else
                                <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                    class="flex h-9 w-full items-center justify-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                                   text-xs font-semibold text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    Assign role
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-gray-400 dark:text-gray-500">No users found.</div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        bg-gray-50/50 dark:bg-gray-800/30">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($users->total())
                        Showing
                        <span
                            class="font-semibold text-gray-700 dark:text-gray-200">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($users->total()) }}</span>
                        users
                    @else
                        No records found
                    @endif
                </p>

                @if($users->hasPages())
                    @php
                        $current = $users->currentPage();
                        $last = $users->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    <nav class="flex items-center gap-1">
                        @if($users->onFirstPage())
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                              hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        {{-- First page + leading ellipsis --}}
                        @if($start > 1)
                            <a href="{{ $users->url(1) }}"
                                class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                              text-sm font-medium text-gray-500 dark:text-gray-400
                                              hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                1
                            </a>
                            @if($start > 2)
                                <span
                                    class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
                            @endif
                        @endif

                        {{-- Page window --}}
                        @foreach($users->getUrlRange($start, $end) as $page => $url)
                            @if($page == $current)
                                <span
                                    class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                                         bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                                      text-sm font-medium text-gray-500 dark:text-gray-400
                                                      hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Trailing ellipsis + last page --}}
                        @if($end < $last)
                            @if($end < $last - 1)
                                <span
                                    class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
                            @endif
                            <a href="{{ $users->url($last) }}"
                                class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                              text-sm font-medium text-gray-500 dark:text-gray-400
                                              hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ $last }}
                            </a>
                        @endif

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                              hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>


        {{-- ═══════════════════════════════════════════════════════════
        ASSIGN ROLE MODAL
        ════════════════════════════════════════════════════════════════ --}}
        <div x-show="showAssignModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8"
            x-cloak>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeAssignRoleModal()"></div>
            <div class="relative z-10 w-full max-w-lg" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden">

                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Assign role</h3>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">Replaces the user's current role</p>
                            </div>
                        </div>
                        <button type="button" @click="closeAssignRoleModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="assignFormAction" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4 p-5">

                            {{-- User card --}}
                            <div
                                class="flex items-center gap-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 p-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                            flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                    <span x-text="modalUserInitial"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                        x-text="selectedUser.full_name || '—'"></p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500"
                                        x-text="selectedUser.email || 'No email'"></p>
                                </div>
                                <template x-if="selectedUser.role">
                                    <span class="flex-shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400"
                                        x-text="selectedUser.role"></span>
                                </template>
                                <template x-if="!selectedUser.role">
                                    <span
                                        class="flex-shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400">No
                                        role</span>
                                </template>
                            </div>

                            {{-- Role select --}}
                            <div>
                                <label
                                    class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">New
                                    role</label>
                                <select name="role" x-model="selectedRole" required class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                           px-3 text-sm text-gray-900 dark:text-white outline-none transition
                                           focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30">
                                    <option value="">Choose a role…</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'Super Admin')
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Warning --}}
                            <div
                                class="flex gap-2.5 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-500/10 p-3">
                                <svg class="w-4 h-4 mt-0.5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                <p class="text-xs leading-relaxed text-amber-700 dark:text-amber-300">
                                    Roles without <span class="font-semibold">access_admin_panel</span> will lose dashboard
                                    access.
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="closeAssignRoleModal()" class="act h-9 inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-white dark:bg-gray-700 px-4 text-sm font-medium text-gray-600 dark:text-gray-300
                                       hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                Cancel
                            </button>
                            <button type="submit" class="act h-9 inline-flex items-center rounded-xl bg-indigo-600 hover:bg-indigo-700
                                       px-4 text-sm font-medium text-white shadow-sm shadow-indigo-500/20 transition">
                                Update role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ═══════════════════════════════════════════════════════════
        ADD USER MODAL (AJAX — Fetch API, no page reload)
        ════════════════════════════════════════════════════════════════ --}}
        <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8"
            x-cloak>
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeAddModal()"></div>
            <div class="relative z-10 w-full max-w-lg" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden">

                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Add new user</h3>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">Create an account and assign a role
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="closeAddModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- No plain HTML submit — handled entirely by submitAddUser() via Fetch API --}}
                    <form @submit.prevent="submitAddUser()" class="flex flex-col" novalidate>

                        <div class="max-h-[65vh] space-y-4 overflow-y-auto p-5">

                            {{-- Generic / non-field server error banner --}}
                            <template x-if="form.errors._general">
                                <div
                                    class="flex gap-2.5 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-500/10 p-3">
                                    <svg class="w-4 h-4 mt-0.5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    <p class="text-xs text-rose-700 dark:text-rose-300" x-text="form.errors._general"></p>
                                </div>
                            </template>

                            {{-- Full name --}}
                            <div>
                                <label
                                    class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Full
                                    name</label>
                                <input type="text" x-model="form.full_name" placeholder="e.g. Sophea Chan"
                                    class="h-9 w-full rounded-xl border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30
                                           dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 border-gray-200 dark:border-gray-600 focus:border-indigo-400">
                                <template x-if="form.errors.full_name">
                                    <p class="mt-1 text-xs text-rose-500" x-text="form.errors.full_name[0]"></p>
                                </template>
                            </div>

                            {{-- Email + Phone with live availability checks --}}
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Email --}}
                                <div>
                                    <label
                                        class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Email
                                        <span class="text-rose-400">*</span></label>
                                    <div class="relative">
                                        <input type="email" x-model="form.email" @input="checkEmailDebounced()"
                                            placeholder="user@example.com" required
                                            :class="form.errors.email ? 'border-rose-400 focus:border-rose-400' : 'border-gray-200 dark:border-gray-600 focus:border-indigo-400'"
                                            class="h-9 w-full rounded-xl border pl-3 pr-8 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">
                                        {{-- status icon: spinner / check / cross --}}
                                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                            <svg x-show="emailCheck.status === 'checking'"
                                                class="w-3.5 h-3.5 animate-spin text-gray-400" fill="none"
                                                viewBox="0 0 24 24" x-cloak>
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                            </svg>
                                            <svg x-show="emailCheck.status === 'available'" class="w-4 h-4 text-emerald-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                x-cloak>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg x-show="emailCheck.status === 'taken'" class="w-4 h-4 text-rose-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                x-cloak>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs"
                                        :class="emailCheck.status === 'taken' ? 'text-rose-500' : 'text-emerald-500'"
                                        x-show="emailCheck.status === 'available' || emailCheck.status === 'taken'"
                                        x-text="emailCheck.message"></p>
                                    <template x-if="form.errors.email">
                                        <p class="mt-1 text-xs text-rose-500" x-text="form.errors.email[0]"></p>
                                    </template>
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label
                                        class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Phone</label>
                                    <div class="relative">
                                        <input type="text" x-model="form.phone" @input="checkPhoneDebounced()"
                                            placeholder="+855 12 345 678"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 pl-3 pr-8 text-sm outline-none transition
                                                   focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white">
                                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2">
                                            <svg x-show="phoneCheck.status === 'checking'"
                                                class="w-3.5 h-3.5 animate-spin text-gray-400" fill="none"
                                                viewBox="0 0 24 24" x-cloak>
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                            </svg>
                                            <svg x-show="phoneCheck.status === 'available'" class="w-4 h-4 text-emerald-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                x-cloak>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg x-show="phoneCheck.status === 'taken'" class="w-4 h-4 text-rose-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                x-cloak>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs"
                                        :class="phoneCheck.status === 'taken' ? 'text-rose-500' : 'text-emerald-500'"
                                        x-show="phoneCheck.status === 'available' || phoneCheck.status === 'taken'"
                                        x-text="phoneCheck.message"></p>
                                    <template x-if="form.errors.phone">
                                        <p class="mt-1 text-xs text-rose-500" x-text="form.errors.phone[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div>
                                <label
                                    class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Role
                                    <span class="text-rose-400">*</span></label>
                                <select x-model="form.role" required
                                    :class="form.errors.role ? 'border-rose-400 focus:border-rose-400' : 'border-gray-200 dark:border-gray-600 focus:border-indigo-400'"
                                    class="h-9 w-full rounded-xl border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white">
                                    <option value="">Choose a role…</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'Super Admin')
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <template x-if="form.errors.role">
                                    <p class="mt-1 text-xs text-rose-500" x-text="form.errors.role[0]"></p>
                                </template>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700"></div>

                            {{-- Generate password --}}
                            <button type="button" @click="fillGeneratedPassword()"
                                class="act inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 dark:border-indigo-900/40
                                       bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1.5 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M12 3v3m0 12v3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1M3 12h3m12 0h3M5.6 18.4l2.1-2.1m8.6-8.6l2.1-2.1" />
                                </svg>
                                Generate password
                            </button>

                            {{-- Password --}}
                            <div x-data="{ show: false }">
                                <label
                                    class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Password
                                    <span class="text-rose-400">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" x-model="form.password"
                                        @input="evaluatePassword()" placeholder="Minimum 8 characters" required
                                        :class="form.errors.password ? 'border-rose-400 focus:border-rose-400' : 'border-gray-200 dark:border-gray-600 focus:border-indigo-400'"
                                        class="h-9 w-full rounded-xl border pl-3 pr-10 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">
                                    <button type="button" @click="show = !show" tabindex="-1"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak>
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                            <line x1="1" y1="1" x2="23" y2="23" />
                                        </svg>
                                    </button>
                                </div>
                                <template x-if="form.errors.password">
                                    <p class="mt-1 text-xs text-rose-500" x-text="form.errors.password[0]"></p>
                                </template>

                                {{-- Strength meter --}}
                                <div class="mt-2" x-show="form.password.length > 0">
                                    <div class="flex gap-1">
                                        <template x-for="i in 3" :key="i">
                                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                                :class="i <= strength.score ? strength.barColor : 'bg-gray-200 dark:bg-gray-600'">
                                            </div>
                                        </template>
                                    </div>
                                    <p class="mt-1 text-[11px] font-semibold" :class="strength.textColor"
                                        x-text="strength.label"></p>

                                    {{-- Checklist --}}
                                    <ul class="mt-2 grid grid-cols-2 gap-x-3 gap-y-1">
                                        <li class="flex items-center gap-1.5 text-[11px]"
                                            :class="strength.checks.length ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'">
                                            <span x-text="strength.checks.length ? '✓' : '✗'"></span> 8 characters
                                        </li>
                                        <li class="flex items-center gap-1.5 text-[11px]"
                                            :class="strength.checks.upper ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'">
                                            <span x-text="strength.checks.upper ? '✓' : '✗'"></span> Uppercase
                                        </li>
                                        <li class="flex items-center gap-1.5 text-[11px]"
                                            :class="strength.checks.lower ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'">
                                            <span x-text="strength.checks.lower ? '✓' : '✗'"></span> Lowercase
                                        </li>
                                        <li class="flex items-center gap-1.5 text-[11px]"
                                            :class="strength.checks.number ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'">
                                            <span x-text="strength.checks.number ? '✓' : '✗'"></span> Number
                                        </li>
                                        <li class="flex items-center gap-1.5 text-[11px]"
                                            :class="strength.checks.symbol ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'">
                                            <span x-text="strength.checks.symbol ? '✓' : '✗'"></span> Symbol
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Confirm password --}}
                            <div x-data="{ show: false }">
                                <label
                                    class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Confirm
                                    password <span class="text-rose-400">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" x-model="form.password_confirmation"
                                        @input="evaluatePassword()" placeholder="Repeat your password" required
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 pl-3 pr-10 text-sm outline-none transition
                                               focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">
                                    <button type="button" @click="show = !show" tabindex="-1"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak>
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                            <line x1="1" y1="1" x2="23" y2="23" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs" x-show="form.password_confirmation.length > 0"
                                    :class="strength.matches ? 'text-emerald-500' : 'text-rose-500'"
                                    x-text="strength.matches ? '✓ Passwords match' : '✗ Passwords do not match'"></p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="closeAddModal()" class="act h-9 inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                       bg-white dark:bg-gray-700 px-4 text-sm font-medium text-gray-600 dark:text-gray-300
                                       hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                Cancel
                            </button>
                            <button type="submit" :disabled="!canSubmit || addSubmitting"
                                class="act h-9 inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                       px-4 text-sm font-medium text-white shadow-sm shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                <svg x-show="addSubmitting" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"
                                    x-cloak>
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                </svg>
                                <span x-text="addSubmitting ? 'Creating…' : 'Create user'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ═══════════════════════════════════════════════════════════
        TOAST
        ════════════════════════════════════════════════════════════════ --}}
        <div x-data="toastManager()" x-init="init()"
            class="fixed right-5 top-5 z-[200] flex flex-col gap-2 pointer-events-none" aria-live="polite">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible" x-transition:enter="transition ease-out duration-250"
                    x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-3"
                    :class="toast.type === 'success' ? 'border-emerald-200 dark:border-emerald-800/50' : 'border-rose-200 dark:border-rose-800/50'"
                    class="pointer-events-auto flex w-72 items-start gap-3 rounded-xl border bg-white dark:bg-gray-800 px-4 py-3 shadow-lg shadow-black/5">
                    <div :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                        class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full">
                        <svg x-show="toast.type === 'success'" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <svg x-show="toast.type === 'error'" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="flex-1 text-xs font-medium text-gray-700 dark:text-gray-200" x-text="toast.message"></p>
                    <button @click="dismiss(toast.id)"
                        class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        /* ================================================================== *
         *  SHARED HELPERS
         * ================================================================== */

        // CSRF token used on every fetch() call (required for POST/PUT in Laravel).
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Generic debounce utility — reused by both the email and phone live checks.
        function debounce(fn, delay) {
            let timer;
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        // Role → color maps, kept in sync with the Blade @match() rules above,
        // so JS-injected rows look identical to server-rendered ones.
        const ROLE_DOT = {
            'Super Admin': 'bg-violet-500',
            'Admin': 'bg-indigo-500',
            'Manager': 'bg-blue-500',
            'Staff': 'bg-amber-500',
            'Customer': 'bg-emerald-500',
        };
        const ROLE_BADGE = {
            'Super Admin': 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
            'Admin': 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
            'Manager': 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
            'Staff': 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
            'Customer': 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
        };

        // Basic HTML-escaping helper so injected user data can never break markup / XSS.
        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        /* ================================================================== *
         *  ROW BUILDERS — inject a freshly created user into the table
         *  instantly, without reloading or refetching the (paginated) list.
         * ================================================================== */

        function buildDesktopRow(user) {
            const dot = ROLE_DOT[user.role] ?? 'bg-gray-400';
            const badge = ROLE_BADGE[user.role] ?? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
            const initial = (user.full_name || user.email || 'U').charAt(0).toUpperCase();

            const tr = document.createElement('tr');
            tr.className = 't-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150';
            tr.innerHTML = `
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">${initial}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">${escapeHtml(user.full_name || 'Unnamed user')}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">#${user.id}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-700 dark:text-gray-200">${escapeHtml(user.email || '—')}</p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500">${escapeHtml(user.phone || 'No phone')}</p>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full flex-shrink-0 ${dot}"></span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold ${badge}">${escapeHtml(user.role)}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium italic bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500">No access</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                    <button type="button" onclick='window.dispatchEvent(new CustomEvent("assign-role-request", { detail: ${JSON.stringify(user)} }))'
                        class="act inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition-colors">
                        Assign role
                    </button>
                </div>
            </td>`;
            return tr;
        }

        function buildMobileCard(user) {
            const dot = ROLE_DOT[user.role] ?? 'bg-gray-400';
            const badge = ROLE_BADGE[user.role] ?? 'bg-gray-100 text-gray-400 dark:bg-gray-700';
            const initial = (user.full_name || user.email || 'U').charAt(0).toUpperCase();

            const div = document.createElement('div');
            div.className = 't-row p-4';
            div.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">${initial}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">${escapeHtml(user.full_name || 'Unnamed user')}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">${escapeHtml(user.email || '—')}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="w-2 h-2 rounded-full ${dot}"></span>
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold ${badge}">${escapeHtml(user.role)}</span>
                </div>
            </div>
            <div class="mt-3">
                <button type="button" onclick='window.dispatchEvent(new CustomEvent("assign-role-request", { detail: ${JSON.stringify(user)} }))'
                    class="flex h-9 w-full items-center justify-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm shadow-indigo-500/20 transition-colors">
                    Assign role
                </button>
            </div>`;
            return div;
        }

        /* ================================================================== *
         *  TOAST COMPONENT (unchanged)
         * ================================================================== */
        function toastManager() {
            return {
                toasts: [], nextId: 1,
                init() {
                    window.addEventListener('show-toast', e => this.add(e.detail.type, e.detail.message));
                @if(session('success')) this.add('success', @js(session('success'))); @endif
                    @if(session('error'))   this.add('error', @js(session('error'))); @endif
            },
                add(type, message) {
                    const id = this.nextId++;
                    this.toasts.push({ id, type, message, visible: true });
                    setTimeout(() => this.dismiss(id), 5000);
                },
                dismiss(id) {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) t.visible = false;
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 250);
                },
            };
        }

        /* ================================================================== *
         *  MAIN PAGE COMPONENT
         * ================================================================== */
        function assignRolePage() {
            return {
                search: '',

                /* ---------------------------------------------------------- *
                 *  Assign role modal (server-rendered form, unchanged)
                 * ---------------------------------------------------------- */
                showAssignModal: false,
                assignFormAction: '',
                selectedRole: '',
                selectedUser: { id: null, full_name: '', email: '', phone: '', role: '', is_super_admin: false },

                get modalUserInitial() {
                    return (this.selectedUser?.full_name || this.selectedUser?.email || 'U').charAt(0).toUpperCase();
                },
                matchesUser(name, email) {
                    const q = (this.search || '').toLowerCase().trim();
                    return !q || name.includes(q) || email.includes(q);
                },
                openAssignRoleModal(user) {
                    if (user.is_super_admin) return;
                    this.selectedUser = user;
                    this.selectedRole = user.role ?? '';
                    this.assignFormAction = `/admin/assign-roles/${user.id}`;
                    this.showAssignModal = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeAssignRoleModal() {
                    this.showAssignModal = false;
                    this.selectedRole = '';
                    this.assignFormAction = '';
                    this.selectedUser = { id: null, full_name: '', email: '', phone: '', role: '', is_super_admin: false };
                    document.body.classList.remove('overflow-hidden');
                },

                /* ---------------------------------------------------------- *
                 *  Add user modal — fully AJAX via Fetch API
                 * ---------------------------------------------------------- */
                showAddModal: false,
                addSubmitting: false,

                form: {
                    full_name: '', email: '', phone: '', role: '',
                    password: '', password_confirmation: '',
                    errors: {}, // keyed by field name; ["message", ...] per Laravel's error bag shape
                },

                emailCheck: { status: 'idle', message: '' }, // idle | checking | available | taken
                phoneCheck: { status: 'idle', message: '' },

                strength: {
                    score: 0, label: '', barColor: '', textColor: '',
                    checks: { length: false, upper: false, lower: false, number: false, symbol: false },
                    matches: false,
                },

                init() {
                    // A JS-injected row dispatches this event instead of calling Alpine
                    // methods directly (it lives outside the Alpine-managed DOM tree).
                    window.addEventListener('assign-role-request', e => this.openAssignRoleModal(e.detail));
                },

                openAddModal() {
                    this.resetForm();
                    this.showAddModal = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeAddModal() {
                    this.showAddModal = false;
                    document.body.classList.remove('overflow-hidden');
                },
                resetForm() {
                    this.form = { full_name: '', email: '', phone: '', role: '', password: '', password_confirmation: '', errors: {} };
                    this.emailCheck = { status: 'idle', message: '' };
                    this.phoneCheck = { status: 'idle', message: '' };
                    this.strength = {
                        score: 0, label: '', barColor: '', textColor: '',
                        checks: { length: false, upper: false, lower: false, number: false, symbol: false },
                        matches: false,
                    };
                    this.addSubmitting = false;
                },

                /* ---- Live email availability check (debounced 500ms) ---- */
                checkEmailDebounced: debounce(function () { this._checkEmail(); }, 500),
                async _checkEmail() {
                    const email = this.form.email.trim();
                    if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
                        this.emailCheck = { status: 'idle', message: '' };
                        return;
                    }
                    this.emailCheck = { status: 'checking', message: '' };
                    try {
                        const res = await fetch(`{{ route('admin.customers.check-email') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({ email }),
                        });
                        const data = await res.json();
                        this.emailCheck = {
                            status: data.available ? 'available' : 'taken',
                            message: data.available ? '✓ Email available' : '✗ Email already exists',
                        };
                    } catch (e) {
                        this.emailCheck = { status: 'idle', message: '' };
                    }
                },

                /* ---- Live phone availability check (debounced 500ms) ---- */
                checkPhoneDebounced: debounce(function () { this._checkPhone(); }, 500),
                async _checkPhone() {
                    const phone = this.form.phone.trim();
                    if (!phone) {
                        this.phoneCheck = { status: 'idle', message: '' };
                        return;
                    }
                    this.phoneCheck = { status: 'checking', message: '' };
                    try {
                        const res = await fetch(`{{ route('admin.customers.check-phone') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({ phone }),
                        });
                        const data = await res.json();
                        this.phoneCheck = {
                            status: data.available ? 'available' : 'taken',
                            message: data.available ? '✓ Phone available' : '✗ Phone already exists',
                        };
                    } catch (e) {
                        this.phoneCheck = { status: 'idle', message: '' };
                    }
                },

                /* ---- Password strength evaluation ---- */
                evaluatePassword() {
                    const pw = this.form.password;
                    const checks = {
                        length: pw.length >= 8,
                        upper: /[A-Z]/.test(pw),
                        lower: /[a-z]/.test(pw),
                        number: /[0-9]/.test(pw),
                        symbol: /[^A-Za-z0-9]/.test(pw),
                    };
                    const passedCount = Object.values(checks).filter(Boolean).length;

                    let score = 0, label = '', barColor = '', textColor = '';
                    if (pw.length === 0) {
                        score = 0; label = '';
                    } else if (passedCount <= 2) {
                        score = 1; label = 'Weak password'; barColor = 'bg-rose-500'; textColor = 'text-rose-500';
                    } else if (passedCount <= 4) {
                        score = 2; label = 'Medium strength'; barColor = 'bg-amber-500'; textColor = 'text-amber-500';
                    } else {
                        score = 3; label = 'Strong password'; barColor = 'bg-emerald-500'; textColor = 'text-emerald-500';
                    }

                    this.strength = {
                        score, label, barColor, textColor, checks,
                        matches: this.form.password.length > 0 && this.form.password === this.form.password_confirmation,
                    };
                },

                /* ---- Generate a strong random password and fill both fields ---- */
                fillGeneratedPassword() {
                    const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // no I/O to avoid visual confusion
                    const lower = 'abcdefghijkmnopqrstuvwxyz'; // no l
                    const numbers = '23456789';                  // no 0/1
                    const symbols = '!@#$%^&*?';
                    const all = upper + lower + numbers + symbols;

                    const pick = (set) => set[Math.floor(Math.random() * set.length)];

                    // Guarantee one of each required category, then fill the rest randomly.
                    let pwChars = [pick(upper), pick(lower), pick(numbers), pick(symbols)];
                    for (let i = 0; i < 8; i++) pwChars.push(pick(all));

                    // Fisher–Yates shuffle so guaranteed chars aren't always in the same slot.
                    for (let i = pwChars.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [pwChars[i], pwChars[j]] = [pwChars[j], pwChars[i]];
                    }

                    const password = pwChars.join('');
                    this.form.password = password;
                    this.form.password_confirmation = password;
                    this.evaluatePassword();
                },

                /* ---- Gate that controls whether "Create user" is enabled ---- */
                get canSubmit() {
                    return this.form.email.trim() !== ''
                        && this.form.role !== ''
                        && this.strength.score >= 2          // Medium or Strong only
                        && this.strength.matches
                        && this.emailCheck.status !== 'taken'
                        && this.phoneCheck.status !== 'taken';
                },

                /* ---- AJAX submit via Fetch API ---- */
                async submitAddUser() {
                    if (this.addSubmitting || !this.canSubmit) return; // guards against double submit
                    this.addSubmitting = true;
                    this.form.errors = {};

                    try {
                        const res = await fetch(`{{ route('admin.customers.store') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({
                                full_name: this.form.full_name,
                                email: this.form.email,
                                phone: this.form.phone,
                                role: this.form.role,
                                password: this.form.password,
                                password_confirmation: this.form.password_confirmation,
                            }),
                        });

                        // Laravel validation failure → 422 with { errors: {...} }
                        if (res.status === 422) {
                            const data = await res.json();
                            this.form.errors = data.errors || {};
                            return;
                        }

                        if (!res.ok) {
                            this.form.errors = { _general: 'Something went wrong. Please try again.' };
                            return;
                        }

                        const data = await res.json();

                        // Inject the new row instantly into both table layouts — no reload, no refetch.
                        document.getElementById('usersTableBody')?.prepend(buildDesktopRow(data.user));
                        document.getElementById('usersMobileList')?.prepend(buildMobileCard(data.user));

                        window.dispatchEvent(new CustomEvent('show-toast', {
                            detail: { type: 'success', message: data.message || 'User created successfully.' },
                        }));

                        this.closeAddModal();
                    } catch (e) {
                        this.form.errors = { _general: 'Network error. Please check your connection and try again.' };
                    } finally {
                        this.addSubmitting = false;
                    }
                },
            };
        }
    </script>
@endpush