@extends('layouts.app')

@section('content')

<style>
@keyframes fadeSlideUp { from { opacity:0; transform:translateY(18px) } to { opacity:1; transform:translateY(0) } }
@keyframes rowSlideIn  { from { opacity:0; transform:translateX(-12px) } to { opacity:1; transform:translateX(0) } }

.s-header { animation: fadeSlideUp .4s ease both; }
.s-table  { animation: fadeSlideUp .5s .10s ease both; }

.t-row { animation: rowSlideIn .35s ease both; }
.t-row:nth-child(1) { animation-delay: .16s; }
.t-row:nth-child(2) { animation-delay: .21s; }
.t-row:nth-child(3) { animation-delay: .26s; }
.t-row:nth-child(4) { animation-delay: .31s; }
.t-row:nth-child(5) { animation-delay: .36s; }
.t-row:nth-child(6) { animation-delay: .41s; }
.t-row:nth-child(7) { animation-delay: .46s; }
.t-row:nth-child(8) { animation-delay: .51s; }
.t-row:nth-child(9) { animation-delay: .56s; }
.t-row:nth-child(10){ animation-delay: .61s; }

.act { transition: transform .15s ease, box-shadow .15s ease; }
.act:hover  { transform: translateY(-1px); }
.act:active { transform: translateY(0); }
</style>

<div x-data="assignRolePage()" class="space-y-4">

    {{-- ==================== HEADER ==================== --}}
    <div class="s-header flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                <span>Dashboard</span><span>/</span>
                <span class="text-gray-600 dark:text-gray-300">User Roles</span>
            </div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-white mt-0.5">Assign User Roles</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Assign a single role to each user and manage dashboard access.
            </p>
        </div>
        <button type="button" @click="openAddModal()"
            class="act inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                   bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/25 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Add user
        </button>
    </div>

    {{-- ==================== TABLE ==================== --}}
    <div class="s-table bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">User role directory</h2>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Assign a single role to each user</p>
                </div>
            </div>
            <div class="relative w-full sm:w-72">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
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
                    <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Contact</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-center">Access</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($users as $user)
                        @php
                            $currentRole  = $user->roles->pluck('name')->first();
                            $isSuperAdmin = $user->hasRole('Super Admin');
                            $userPayload  = [
                                'id'             => $user->id,
                                'full_name'      => $user->full_name,
                                'email'          => $user->email,
                                'phone'          => $user->phone,
                                'role'           => $currentRole,
                                'is_super_admin' => $isSuperAdmin,
                            ];
                            $roleDot = match ($currentRole) {
                                'Super Admin' => 'bg-violet-500',
                                'Admin'       => 'bg-indigo-500',
                                'Manager'     => 'bg-blue-500',
                                'Staff'       => 'bg-amber-500',
                                'Customer'    => 'bg-emerald-500',
                                default       => 'bg-gray-400',
                            };
                            $roleBadge = match ($currentRole) {
                                'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                                'Admin'       => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                                'Manager'     => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                'Staff'       => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                'Customer'    => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                default       => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                            };
                        @endphp
                        <tr x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                            class="t-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">

                            {{-- User --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                                flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->full_name ?? 'Unnamed user' }}</p>
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
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $currentRole ? $roleDot : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                 {{ $currentRole ? $roleBadge : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $currentRole ?? 'No role' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Access --}}
                            <td class="px-6 py-4 text-center">
                                @if ($user->can('access_admin_panel'))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold
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
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                                            </svg>
                                            Protected
                                        </span>
                                    @else
                                        <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                            class="act inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold
                                                   bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
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
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">No users found</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">There are no users to assign roles to yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="lg:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse ($users as $user)
                @php
                    $currentRole  = $user->roles->pluck('name')->first();
                    $isSuperAdmin = $user->hasRole('Super Admin');
                    $userPayload  = ['id' => $user->id, 'full_name' => $user->full_name, 'email' => $user->email, 'phone' => $user->phone, 'role' => $currentRole, 'is_super_admin' => $isSuperAdmin];
                    $roleDot = match ($currentRole) {
                        'Super Admin' => 'bg-violet-500',
                        'Admin'       => 'bg-indigo-500',
                        'Manager'     => 'bg-blue-500',
                        'Staff'       => 'bg-amber-500',
                        'Customer'    => 'bg-emerald-500',
                        default       => 'bg-gray-400',
                    };
                    $roleBadge = match ($currentRole) {
                        'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                        'Admin'       => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                        'Manager'     => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                        'Staff'       => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                        'Customer'    => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                        default       => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                    };
                @endphp
                <div x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                    class="t-row p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                        flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->full_name ?? 'Unnamed user' }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email ?: '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="w-2 h-2 rounded-full {{ $currentRole ? $roleDot : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $currentRole ? $roleBadge : 'bg-gray-100 text-gray-400 dark:bg-gray-700' }}">
                                {{ $currentRole ?? 'No role' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if ($isSuperAdmin)
                            <span class="flex h-9 items-center justify-center rounded-xl border border-amber-200 dark:border-amber-900/40
                                         bg-amber-50 dark:bg-amber-500/10 text-xs font-semibold text-amber-600 dark:text-amber-400">
                                Protected
                            </span>
                        @else
                            <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                class="flex h-9 w-full items-center justify-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                       text-xs font-semibold text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
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
                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
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
                    $last    = $users->lastPage();
                    $start   = max(1, $current - 2);
                    $end     = min($last, $current + 2);
                @endphp

                <nav class="flex items-center gap-1">
                    @if($users->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                  hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
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
                            <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
                        @endif
                    @endif

                    {{-- Page window --}}
                    @foreach($users->getUrlRange($start, $end) as $page => $url)
                        @if($page == $current)
                            <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
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
                            <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none">…</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
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
    <div x-show="showAssignModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeAssignRoleModal()"></div>
        <div class="relative z-10 w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Assign role</h3>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Replaces the user's current role</p>
                        </div>
                    </div>
                    <button type="button" @click="closeAssignRoleModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="assignFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 p-5">

                        {{-- User card --}}
                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 p-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                        flex items-center justify-center text-sm font-semibold text-white shadow-sm flex-shrink-0">
                                <span x-text="modalUserInitial"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="selectedUser.full_name || '—'"></p>
                                <p class="text-xs text-gray-400 dark:text-gray-500" x-text="selectedUser.email || 'No email'"></p>
                            </div>
                            <template x-if="selectedUser.role">
                                <span class="flex-shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-semibold
                                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400" x-text="selectedUser.role"></span>
                            </template>
                            <template x-if="!selectedUser.role">
                                <span class="flex-shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400">No role</span>
                            </template>
                        </div>

                        {{-- Role select --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">New role</label>
                            <select name="role" x-model="selectedRole" required
                                class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
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
                        <div class="flex gap-2.5 rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-500/10 p-3">
                            <svg class="w-4 h-4 mt-0.5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <p class="text-xs leading-relaxed text-amber-700 dark:text-amber-300">
                                Roles without <span class="font-semibold">access_admin_panel</span> will lose dashboard access.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="closeAssignRoleModal()"
                            class="act h-9 inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-white dark:bg-gray-700 px-4 text-sm font-medium text-gray-600 dark:text-gray-300
                                   hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="act h-9 inline-flex items-center rounded-xl bg-indigo-600 hover:bg-indigo-700
                                   px-4 text-sm font-medium text-white shadow-sm shadow-indigo-500/20 transition">
                            Update role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════
         ADD USER MODAL
    ════════════════════════════════════════════════════════════════ --}}
    <div x-show="showAddModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeAddModal()"></div>
        <div class="relative z-10 w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden">

                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Add new user</h3>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Create an account and assign a role</p>
                        </div>
                    </div>
                    <button type="button" @click="closeAddModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.customers.store') }}" method="POST" @submit="addSubmitting = true" class="flex flex-col">
                    @csrf
                    <div class="max-h-[65vh] space-y-4 overflow-y-auto p-5">

                        {{-- Validation errors --}}
                        @if ($errors->hasAny(['full_name','email','phone','password','password_confirmation','role']))
                            <div class="flex gap-2.5 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 dark:bg-rose-500/10 p-3">
                                <svg class="w-4 h-4 mt-0.5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <ul class="space-y-1">
                                    @foreach (['full_name','email','phone','password','password_confirmation','role'] as $f)
                                        @error($f)<li class="text-xs text-rose-700 dark:text-rose-300">{{ $message }}</li>@enderror
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Full name --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Full name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Sophea Chan"
                                class="h-9 w-full rounded-xl border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30
                                       dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500
                                       @error('full_name') border-rose-400 focus:border-rose-400 @else border-gray-200 dark:border-gray-600 focus:border-indigo-400 @enderror">
                            @error('full_name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email + Phone --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Email <span class="text-rose-400">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required
                                    class="h-9 w-full rounded-xl border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30
                                           dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500
                                           @error('email') border-rose-400 focus:border-rose-400 @else border-gray-200 dark:border-gray-600 focus:border-indigo-400 @enderror">
                                @error('email')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+855 12 345 678"
                                    class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 px-3 text-sm outline-none transition
                                           focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Role <span class="text-rose-400">*</span></label>
                            <select name="role" required
                                class="h-9 w-full rounded-xl border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30
                                       dark:bg-gray-700 dark:text-white
                                       @error('role') border-rose-400 focus:border-rose-400 @else border-gray-200 dark:border-gray-600 focus:border-indigo-400 @enderror">
                                <option value="">Choose a role…</option>
                                @foreach ($roles as $role)
                                    @if ($role->name !== 'Super Admin')
                                        <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-700"></div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Password <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="Minimum 8 characters" required
                                    class="h-9 w-full rounded-xl border pl-3 pr-10 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/30
                                           dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500
                                           @error('password') border-rose-400 focus:border-rose-400 @else border-gray-200 dark:border-gray-600 focus:border-indigo-400 @enderror">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Confirm password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Confirm password <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Repeat your password" required
                                    class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 pl-3 pr-10 text-sm outline-none transition
                                           focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/30 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="closeAddModal()"
                            class="act h-9 inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-white dark:bg-gray-700 px-4 text-sm font-medium text-gray-600 dark:text-gray-300
                                   hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="addSubmitting"
                            class="act h-9 inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                   px-4 text-sm font-medium text-white shadow-sm shadow-indigo-500/20 disabled:opacity-60 transition">
                            <svg x-show="addSubmitting" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
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
            <div x-show="toast.visible"
                x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-3"
                :class="toast.type === 'success' ? 'border-emerald-200 dark:border-emerald-800/50' : 'border-rose-200 dark:border-rose-800/50'"
                class="pointer-events-auto flex w-72 items-start gap-3 rounded-xl border bg-white dark:bg-gray-800 px-4 py-3 shadow-lg shadow-black/5">
                <div :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                    class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full">
                    <svg x-show="toast.type === 'success'" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <svg x-show="toast.type === 'error'" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </div>
                <p class="flex-1 text-xs font-medium text-gray-700 dark:text-gray-200" x-text="toast.message"></p>
                <button @click="dismiss(toast.id)" class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

</div>
@endsection


@push('scripts')
<script>
function toastManager() {
    return {
        toasts: [], nextId: 1,
        init() {
            window.addEventListener('show-toast', e => this.add(e.detail.type, e.detail.message));
            @if(session('success')) this.add('success', @js(session('success'))); @endif
            @if(session('error'))   this.add('error',   @js(session('error')));   @endif
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

function assignRolePage() {
    return {
        search: '',

        /* Assign role modal */
        showAssignModal:  false,
        assignFormAction: '',
        selectedRole:     '',
        selectedUser:     { id: null, full_name: '', email: '', phone: '', role: '', is_super_admin: false },

        get modalUserInitial() {
            return (this.selectedUser?.full_name || this.selectedUser?.email || 'U').charAt(0).toUpperCase();
        },

        matchesUser(name, email) {
            const q = (this.search || '').toLowerCase().trim();
            return !q || name.includes(q) || email.includes(q);
        },

        openAssignRoleModal(user) {
            if (user.is_super_admin) return;
            this.selectedUser     = user;
            this.selectedRole     = user.role ?? '';
            this.assignFormAction = `/admin/assign-roles/${user.id}`;
            this.showAssignModal  = true;
            document.body.classList.add('overflow-hidden');
        },

        closeAssignRoleModal() {
            this.showAssignModal  = false;
            this.selectedRole     = '';
            this.assignFormAction = '';
            this.selectedUser     = { id: null, full_name: '', email: '', phone: '', role: '', is_super_admin: false };
            document.body.classList.remove('overflow-hidden');
        },

        /* Add user modal */
        showAddModal:  {{ $errors->hasAny(['full_name','email','phone','password','password_confirmation','role']) ? 'true' : 'false' }},
        addSubmitting: false,

        openAddModal()  { this.showAddModal = true;  this.addSubmitting = false; document.body.classList.add('overflow-hidden'); },
        closeAddModal() { this.showAddModal = false; this.addSubmitting = false; document.body.classList.remove('overflow-hidden'); },
    };
}
</script>
@endpush