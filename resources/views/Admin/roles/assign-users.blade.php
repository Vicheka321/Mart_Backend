@extends('layouts.app')

@section('content')
<div x-data="assignRolePage()" class="space-y-6">

    {{-- ================================================================
         HEADER
    ================================================================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Assign User Roles</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Manage which role each user has in the admin system.
            </p>
        </div>
    </div>

    {{-- ================================================================
         TOP CARD
    ================================================================= --}}
    <div class="rounded-3xl border border-gray-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">User Role Directory</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assign a single role to each user.</p>
                </div>

                {{-- Search --}}
                <div class="w-full lg:w-[320px] relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    <input x-model="search" type="text" placeholder="Search user by name / email…"
                        class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900
                               pl-11 pr-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>

                {{-- Add User button --}}
                <button type="button" @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium
                           rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-200
                           shadow-md shadow-indigo-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    <span class="hidden sm:inline">Add User</span>
                </button>
            </div>
        </div>

        {{-- ============================================================
             DESKTOP TABLE
        ============================================================= --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full min-w-[980px]">
                <thead class="bg-gray-50/70 dark:bg-slate-900/40">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">User</th>
                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Contact</th>
                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Current Role</th>
                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Admin Access</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse ($users as $user)
                        @php
                            $currentRole  = $user->roles->pluck('name')->first();
                            $isSuperAdmin = $user->hasRole('Super Admin');
                            $userPayload  = [
                                'id'           => $user->id,
                                'full_name'    => $user->full_name,
                                'email'        => $user->email,
                                'phone'        => $user->phone,
                                'role'         => $currentRole,
                                'is_super_admin' => $isSuperAdmin,
                            ];
                            $roleBadgeClass = match ($currentRole) {
                                'Super Admin' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-900/40',
                                'Admin'       => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-900/40',
                                'Manager'     => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-900/40',
                                'Staff'       => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-900/40',
                                'Customer'    => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:border-slate-600',
                                default       => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:border-violet-900/40',
                            };
                        @endphp

                        <tr x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                            class="hover:bg-gray-50/70 dark:hover:bg-slate-700/30 transition">

                            {{-- User --}}
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500
                                                text-white flex items-center justify-center text-sm font-semibold shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $user->full_name ?? 'Unnamed User' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">User #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact --}}
                            <td class="px-6 py-4 align-middle">
                                <div class="text-sm text-gray-800 dark:text-gray-100">{{ $user->email ?: '—' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->phone ?: 'No phone' }}</div>
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4 align-middle">
                                @if ($currentRole)
                                    <span class="inline-flex items-center px-3 h-8 rounded-full border text-xs font-semibold {{ $roleBadgeClass }}">
                                        {{ $currentRole }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                        No Role
                                    </span>
                                @endif
                            </td>

                            {{-- Admin Access --}}
                            <td class="px-6 py-4 align-middle">
                                @if ($user->can('access_admin_panel'))
                                    <span class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-medium dark:border-emerald-900/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Allowed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span> No access
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-end items-center gap-2">
                                    @if ($isSuperAdmin)
                                        <span class="inline-flex items-center gap-1.5 px-3 h-9 rounded-2xl text-xs font-semibold
                                                     border border-amber-200 bg-amber-50 text-amber-700
                                                     dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Protected
                                        </span>
                                    @else
                                        {{-- Edit --}}
                                        <button type="button" @click="openEditModal(@js($userPayload))"
                                            class="inline-flex items-center gap-1.5 px-3 h-9 rounded-2xl text-sm font-medium transition
                                                   border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800
                                                   text-gray-600 dark:text-gray-300
                                                   hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600
                                                   dark:hover:bg-indigo-500/10 dark:hover:border-indigo-500/40 dark:hover:text-indigo-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>

                                        {{-- Assign Role --}}
                                        <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                            class="inline-flex items-center gap-1.5 px-3 h-9 rounded-2xl text-sm font-medium transition
                                                   border border-indigo-200 dark:border-indigo-900/40
                                                   bg-indigo-50 dark:bg-indigo-500/10
                                                   text-indigo-600 dark:text-indigo-300
                                                   hover:bg-indigo-100 dark:hover:bg-indigo-500/20">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Assign Role
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                                            <path d="M17 21V19C17 17.8954 16.1046 17 15 17H5C3.89543 17 3 17.8954 3 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">No users found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no users to assign roles to yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ============================================================
             MOBILE CARDS
        ============================================================= --}}
        <div class="lg:hidden p-4 space-y-4">
            @forelse ($users as $user)
                @php
                    $currentRole  = $user->roles->pluck('name')->first();
                    $isSuperAdmin = $user->hasRole('Super Admin');
                    $userPayload  = [
                        'id'           => $user->id,
                        'full_name'    => $user->full_name,
                        'email'        => $user->email,
                        'phone'        => $user->phone,
                        'role'         => $currentRole,
                        'is_super_admin' => $isSuperAdmin,
                    ];
                @endphp

                <div x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                    class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500
                                        text-white flex items-center justify-center text-sm font-semibold shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-gray-900 dark:text-white truncate">{{ $user->full_name ?? 'Unnamed User' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email ?: '—' }}</div>
                            </div>
                        </div>
                        @if ($currentRole)
                            <span class="inline-flex items-center px-3 h-8 rounded-full border text-xs font-semibold
                                         border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-900/40 dark:bg-indigo-500/10 dark:text-indigo-300 flex-shrink-0">
                                {{ $currentRole }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300 flex-shrink-0">
                                No Role
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Phone</div>
                            <div class="text-gray-800 dark:text-gray-100">{{ $user->phone ?: 'No phone' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Admin Access</div>
                            @if ($user->can('access_admin_panel'))
                                <span class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-medium dark:border-emerald-900/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Allowed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span> No access
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2">
                        @if ($isSuperAdmin)
                            <button type="button" disabled
                                class="w-full h-11 rounded-2xl text-sm font-semibold cursor-not-allowed
                                       border border-amber-200 bg-amber-50 text-amber-700
                                       dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300">
                                Protected
                            </button>
                        @else
                            <button type="button" @click="openEditModal(@js($userPayload))"
                                class="w-full h-11 rounded-2xl text-sm font-medium transition
                                       border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800
                                       text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700">
                                Edit User
                            </button>
                            <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                class="w-full h-11 rounded-2xl text-sm font-semibold transition
                                       bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20">
                                Assign Role
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-10 text-center">
                    <div class="text-gray-500 dark:text-gray-400">No users found.</div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70">
            {{ $users->links() }}
        </div>
    </div>


    {{-- ================================================================
         ASSIGN ROLE MODAL  (existing — unchanged logic)
    ================================================================= --}}
    <div x-show="showAssignModal"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[90]" x-cloak>
        <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-[2px]" @click="closeAssignRoleModal()"></div>
        <div class="relative z-[91] flex items-center justify-center min-h-screen px-4 py-8">
            <div x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="w-full max-w-2xl rounded-[28px] border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Assign Role</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select a role for this user. This will replace their current role.</p>
                            </div>
                        </div>
                        <button type="button" @click="closeAssignRoleModal()"
                            class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-gray-300 flex items-center justify-center transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form :action="assignFormAction" method="POST" class="flex flex-col max-h-[85vh]">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 overflow-y-auto space-y-6">
                        <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40 p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center text-lg font-semibold shadow-sm">
                                    <span x-text="modalUserInitial"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base font-semibold text-gray-900 dark:text-white" x-text="selectedUser.full_name || '-'"></div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="selectedUser.email || 'No email'"></div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="selectedUser.phone || 'No phone'"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/20 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Current Role</div>
                                <div class="mt-3">
                                    <template x-if="selectedUser.role">
                                        <span class="inline-flex items-center px-3 h-9 rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-medium dark:border-indigo-900/40 dark:bg-indigo-500/10 dark:text-indigo-300" x-text="selectedUser.role"></span>
                                    </template>
                                    <template x-if="!selectedUser.role">
                                        <span class="inline-flex items-center px-3 h-9 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-sm font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">No Role</span>
                                    </template>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/20 p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">Admin Panel Access</div>
                                <div class="mt-3 text-sm text-gray-700 dark:text-gray-200">
                                    Access depends on whether the selected role has the
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">access_admin_panel</span> permission.
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">Select Role</label>
                            <div class="relative">
                                <select name="role" x-model="selectedRole" required
                                    class="w-full h-12 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 pr-10 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                                    <option value="">Choose a role</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'Super Admin')
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Changing role here will replace the user's current role.</p>
                        </div>

                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300">
                            <div class="font-medium mb-1">Important</div>
                            <p>If the selected role does not include <span class="font-semibold">access_admin_panel</span>, the user will not be able to log into the admin dashboard.</p>
                        </div>
                    </div>

                    <div class="px-6 py-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-end gap-3">
                        <button type="button" @click="closeAssignRoleModal()"
                            class="px-5 h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 h-11 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                            Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================================================================
         ADD USER MODAL
    ================================================================= --}}
    <div x-show="showAddModal"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[90]" x-cloak>
        <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-[2px]" @click="closeAddModal()"></div>
        <div class="relative z-[91] flex items-center justify-center min-h-screen px-4 py-8">
            <div x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="w-full max-w-2xl rounded-[28px] border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/25 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New User</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create an account and assign a role.</p>
                            </div>
                        </div>
                        <button type="button" @click="closeAddModal()"
                            class="w-9 h-9 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-500 dark:text-gray-300 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.customers.store') }}" method="POST" @submit="addSubmitting = true" class="flex flex-col">
                    @csrf

                    <div class="px-6 py-6 space-y-5 overflow-y-auto max-h-[65vh]">

                        {{-- Validation banner --}}
                        @if ($errors->hasAny(['full_name','email','phone','password','password_confirmation','role']))
                            <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-900/40 dark:bg-red-500/10 px-4 py-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-red-700 dark:text-red-300">Please fix the following errors</p>
                                </div>
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach (['full_name','email','phone','password','password_confirmation','role'] as $f)
                                        @error($f)<li class="text-xs text-red-600 dark:text-red-400">{{ $message }}</li>@enderror
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Full Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Sophea Chan"
                                    class="w-full h-11 rounded-2xl border pl-10 pr-4 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400
                                           @error('full_name') border-red-400 dark:border-red-600 @else border-gray-200 dark:border-slate-700 @enderror">
                            </div>
                            @error('full_name')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email + Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required
                                        class="w-full h-11 rounded-2xl border pl-10 pr-4 text-sm transition
                                               bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400
                                               @error('email') border-red-400 dark:border-red-600 @else border-gray-200 dark:border-slate-700 @enderror">
                                </div>
                                @error('email')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Phone</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+855 12 345 678"
                                        class="w-full h-11 rounded-2xl border pl-10 pr-4 text-sm transition
                                               bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400
                                               @error('phone') border-red-400 dark:border-red-600 @else border-gray-200 dark:border-slate-700 @enderror">
                                </div>
                                @error('phone')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Role <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <select name="role" required
                                    class="w-full h-11 rounded-2xl border pl-10 pr-10 text-sm transition appearance-none
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400
                                           @error('role') border-red-400 dark:border-red-600 @else border-gray-200 dark:border-slate-700 @enderror">
                                    <option value="">Choose a role…</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'Super Admin')
                                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                    </svg>
                                </span>
                            </div>
                            @error('role')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="border-t border-gray-100 dark:border-slate-700"></div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="Minimum 8 characters" required
                                    class="w-full h-11 rounded-2xl border pl-10 pr-12 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400
                                           @error('password') border-red-400 dark:border-red-600 @else border-gray-200 dark:border-slate-700 @enderror">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Repeat your password" required
                                    class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-12 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-5 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end gap-3 bg-gray-50/50 dark:bg-slate-800/80">
                        <button type="button" @click="closeAddModal()"
                            class="px-5 h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800
                                   text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="addSubmitting"
                            class="inline-flex items-center gap-2 px-6 h-11 rounded-2xl
                                   bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed
                                   text-white text-sm font-semibold shadow-sm shadow-indigo-500/20 transition">
                            <svg x-show="addSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span x-text="addSubmitting ? 'Creating…' : 'Create User'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================================================================
         EDIT USER MODAL
    ================================================================= --}}
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[90]" x-cloak>
        <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-[2px]" @click="closeEditModal()"></div>
        <div class="relative z-[91] flex items-center justify-center min-h-screen px-4 py-8">
            <div x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="w-full max-w-2xl rounded-[28px] border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-base font-bold shadow-md shadow-indigo-500/25 flex-shrink-0"
                                 x-text="editUser.initial"></div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit User</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="editUser.email || 'Update user details'"></p>
                            </div>
                        </div>
                        <button type="button" @click="closeEditModal()"
                            class="w-9 h-9 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-500 dark:text-gray-300 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form :action="editFormAction" method="POST" @submit="editSubmitting = true" class="flex flex-col">
                    @csrf
                    @method('PATCH')

                    <div class="px-6 py-6 space-y-5 overflow-y-auto max-h-[65vh]">

                        {{-- Full Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Full Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" name="full_name" :value="editUser.full_name" placeholder="e.g. Sophea Chan" required
                                    class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-4 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                            </div>
                        </div>

                        {{-- Email + Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input type="email" name="email" :value="editUser.email" placeholder="user@example.com" required
                                        class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-4 text-sm transition
                                               bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Phone</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </span>
                                    <input type="text" name="phone" :value="editUser.phone" placeholder="+855 12 345 678"
                                        class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-4 text-sm transition
                                               bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                </div>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Role <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <select name="role" required x-ref="editRoleSelect"
                                    class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-10 text-sm transition appearance-none
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                    <option value="">Choose a role…</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'Super Admin')
                                            <option value="{{ $role->name }}" :selected="editUser.role === '{{ $role->name }}'">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 dark:border-slate-700 pt-1">
                            <p class="text-xs text-gray-400 dark:text-gray-500">Leave password fields blank to keep the current password.</p>
                        </div>

                        {{-- New Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">New Password</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="Leave blank to keep current"
                                    class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-12 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm New Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Repeat new password"
                                    class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 pl-10 pr-12 text-sm transition
                                           bg-white dark:bg-slate-900 text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-5 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end gap-3 bg-gray-50/50 dark:bg-slate-800/80">
                        <button type="button" @click="closeEditModal()"
                            class="px-5 h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800
                                   text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="editSubmitting"
                            class="inline-flex items-center gap-2 px-6 h-11 rounded-2xl
                                   bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed
                                   text-white text-sm font-semibold shadow-sm shadow-indigo-500/20 transition">
                            <svg x-show="editSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span x-text="editSubmitting ? 'Saving…' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================================================================
         TOAST NOTIFICATION
    ================================================================= --}}
    <div x-data="toastManager()" x-init="init()"
         class="fixed top-5 right-5 z-[200] flex flex-col gap-2 pointer-events-none" aria-live="polite">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-4"
                 :class="{
                    'border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/40': toast.type === 'success',
                    'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/40': toast.type === 'error',
                 }"
                 class="pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-lg shadow-black/5 w-80">
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="toast.type === 'success'">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <div class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="flex-1 min-w-0">
                    <p :class="{
                           'text-emerald-800 dark:text-emerald-300': toast.type === 'success',
                           'text-red-800 dark:text-red-300': toast.type === 'error',
                        }"
                       class="text-sm font-medium leading-snug" x-text="toast.message"></p>
                </div>
                <button @click="dismiss(toast.id)"
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition mt-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

</div>{{-- /x-data --}}
@endsection


@push('scripts')
<script>
/* ── Toast Manager ──────────────────────────────────────────────── */
function toastManager() {
    return {
        toasts: [],
        nextId: 1,
        init() {
            window.addEventListener('show-toast', (e) => this.add(e.detail.type, e.detail.message));

            @if(session('success'))
            this.add('success', @js(session('success')));
            @endif

            @if(session('error'))
            this.add('error', @js(session('error')));
            @endif
        },
        add(type, message) {
            const id = this.nextId++;
            this.toasts.push({ id, type, message, visible: true });
            setTimeout(() => this.dismiss(id), 5000);
        },
        dismiss(id) {
            const t = this.toasts.find(t => t.id === id);
            if (t) t.visible = false;
            setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
        },
    };
}

/* ── Main Page Alpine Component ─────────────────────────────────── */
function assignRolePage() {
    return {
        /* Search */
        search: '',

        /* Assign Role Modal */
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

        /* Add User Modal */
        showAddModal: {{ $errors->hasAny(['full_name','email','phone','password','password_confirmation','role']) ? 'true' : 'false' }},
        addSubmitting: false,

        openAddModal() {
            this.showAddModal   = true;
            this.addSubmitting  = false;
            document.body.classList.add('overflow-hidden');
        },

        closeAddModal() {
            this.showAddModal  = false;
            this.addSubmitting = false;
            document.body.classList.remove('overflow-hidden');
        },

        /* Edit User Modal */
        showEditModal: false,
        editSubmitting: false,
        editFormAction: '',
        editUser: { id: null, full_name: '', email: '', phone: '', role: '', initial: 'U' },

        openEditModal(payload) {
            if (payload.is_super_admin) return;
            this.editUser = {
                id:        payload.id,
                full_name: payload.full_name ?? '',
                email:     payload.email     ?? '',
                phone:     payload.phone     ?? '',
                role:      payload.role      ?? '',
                initial:   (payload.full_name || payload.email || 'U').charAt(0).toUpperCase(),
            };
            this.editFormAction  = `/admin/customers/${payload.id}`;
            this.editSubmitting  = false;
            this.showEditModal   = true;
            document.body.classList.add('overflow-hidden');
        },

        closeEditModal() {
            this.showEditModal  = false;
            this.editSubmitting = false;
            this.editFormAction = '';
            this.editUser       = { id: null, full_name: '', email: '', phone: '', role: '', initial: 'U' };
            document.body.classList.remove('overflow-hidden');
        },
    };
}

/* Global helper kept for backward-compat with any inline onclick="openModal()" */
function openModal() {
    document.querySelector('[x-data]').__x.$data.openAddModal();
}
</script>
@endpush