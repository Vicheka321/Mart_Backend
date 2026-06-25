@extends('layouts.app')

@section('content')
    <div x-data="assignRolePage()" class="space-y-6">

        {{-- =========================
            Header
        ========================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Assign User Roles
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Manage which role each user has in the admin system.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('roles.index') }}"
                    class="inline-flex items-center gap-2 px-4 h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Back to Roles
                </a>
            </div>
        </div>

        {{-- =========================
            Alerts
        ========================== --}}
        @if (session('success'))
            <div
                class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-500/10 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-500/10 dark:text-red-300">
                <div class="font-medium mb-1">Please fix the following:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- =========================
            Top Card / Search / Summary
        ========================== --}}
        <div
            class="rounded-3xl border border-gray-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">User Role Directory</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Assign a single role to each user.
                        </p>
                    </div>

                    <div class="w-full lg:w-[320px] relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2" />
                            </svg>
                        </span>

                        <input x-model="search" type="text" placeholder="Search user by name / email..."
                            class="w-full h-11 rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 pl-11 pr-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </div>
            </div>

            {{-- =========================
                Desktop Table
            ========================== --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full min-w-[980px]">
                    <thead class="bg-gray-50/70 dark:bg-slate-900/40">
                        <tr class="text-left">
                            <th
                                class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                User
                            </th>
                            <th
                                class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                Contact
                            </th>
                            <th
                                class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                Current Role
                            </th>
                            <th
                                class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                Admin Access
                            </th>
                            <th
                                class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($users as $user)
                            @php
                                $currentRole = $user->roles->pluck('name')->first();
                                $isSuperAdmin = $user->hasRole('Super Admin');

                                $userPayload = [
                                    'id' => $user->id,
                                    'name' => $user->full_name,
                                    'email' => $user->email,
                                    'phone' => $user->phone,
                                    'role' => $currentRole,
                                    'is_super_admin' => $isSuperAdmin,
                                ];
                            @endphp

                            <tr x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                                class="hover:bg-gray-50/70 dark:hover:bg-slate-700/30 transition">
                                {{-- User --}}
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center text-sm font-semibold shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                                {{ $user->full_name ?? 'Unnamed User' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                User #{{ $user->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact --}}
                                <td class="px-6 py-4 align-middle">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-800 dark:text-gray-100">
                                            {{ $user->email ?: '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $user->phone ?: 'No phone' }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Current Role --}}
                                <td class="px-6 py-4 align-middle">
                                    @if ($currentRole)
                                        @php
                                            $roleBadgeClass = match ($currentRole) {
                                                'Super Admin'
                                                    => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-900/40',
                                                'Admin'
                                                    => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-900/40',
                                                'Manager'
                                                    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-900/40',
                                                'Staff'
                                                    => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-900/40',
                                                'Customer'
                                                    => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:border-slate-600',
                                                default
                                                    => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:border-violet-900/40',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-3 h-8 rounded-full border text-xs font-semibold {{ $roleBadgeClass }}">
                                            {{ $currentRole }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                            No Role
                                        </span>
                                    @endif
                                </td>

                                {{-- Admin Access --}}
                                <td class="px-6 py-4 align-middle">
                                    @if ($user->can('access_admin_panel'))
                                        <span
                                            class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-medium dark:border-emerald-900/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Allowed
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                            No access
                                        </span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-end">
                                        @if ($isSuperAdmin)
                                            <button type="button"
                                                class="px-4 h-10 rounded-2xl text-xs font-semibold border border-amber-200 bg-amber-50 text-amber-700 cursor-not-allowed dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300"
                                                disabled>
                                                Protected
                                            </button>
                                        @else
                                            <button type="button"
                                                @click='openAssignRoleModal(@json($userPayload))'
                                                class="inline-flex items-center gap-2 px-4 h-10 rounded-2xl text-sm font-medium border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:border-indigo-900/40 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20 transition">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" />
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
                                        <div
                                            class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                                                <path d="M17 21V19C17 17.8954 16.1046 17 15 17H5C3.89543 17 3 17.8954 3 19V21"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                                <circle cx="9" cy="7" r="4" stroke="currentColor"
                                                    stroke-width="2" />
                                                <path d="M23 21V19C23 18.0774 22.3729 17.2747 21.5 17.0645"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                                <path d="M16.5 3.06445C17.3729 3.27467 18 4.07741 18 5C18 5.92259 17.3729 6.72533 16.5 6.93555"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">
                                            No users found
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            There are no users to assign roles to yet.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- =========================
                Mobile Cards
            ========================== --}}
            <div class="lg:hidden p-4 space-y-4">
                @forelse ($users as $user)
                    @php
                        $currentRole = $user->roles->pluck('name')->first();
                        $isSuperAdmin = $user->hasRole('Super Admin');

                        $userPayload = [
                            'id' => $user->id,
                            'name' => $user->full_name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'role' => $currentRole,
                            'is_super_admin' => $isSuperAdmin,
                        ];
                    @endphp

                    <div x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                        class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center text-sm font-semibold shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $user->full_name ?? 'Unnamed User' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->email ?: '—' }}
                                    </div>
                                </div>
                            </div>

                            @if ($currentRole)
                                <span
                                    class="inline-flex items-center px-3 h-8 rounded-full border text-xs font-semibold border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-900/40 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    {{ $currentRole }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
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
                                    <span
                                        class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-medium dark:border-emerald-900/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Allowed
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 px-3 h-8 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-xs font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        No access
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            @if ($isSuperAdmin)
                                <button type="button"
                                    class="w-full h-11 rounded-2xl text-sm font-semibold border border-amber-200 bg-amber-50 text-amber-700 cursor-not-allowed dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300"
                                    disabled>
                                    Protected
                                </button>
                            @else
                                <button type="button" @click='openAssignRoleModal(@json($userPayload))'
                                    class="w-full h-11 rounded-2xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
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
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-white/70 dark:bg-slate-800/70">
                {{ $users->links() }}
            </div>
        </div>

        {{-- =========================
            Assign Role Modal
        ========================== --}}
        <div x-show="showAssignModal" x-transition.opacity class="fixed inset-0 z-[90] hidden"
            :class="showAssignModal ? '!block' : ''">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-[2px]" @click="closeAssignRoleModal()"></div>

            {{-- Modal --}}
            <div class="relative z-[91] flex items-center justify-center min-h-screen px-4 py-8">
                <div x-transition
                    class="w-full max-w-2xl rounded-[28px] border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl overflow-hidden">

                    {{-- Modal Header --}}
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>

                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Assign Role
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Select a role for this user. This will replace their current role.
                                    </p>
                                </div>
                            </div>

                            <button type="button" @click="closeAssignRoleModal()"
                                class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-gray-300 flex items-center justify-center transition">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <form :action="assignFormAction" method="POST" class="flex flex-col max-h-[85vh]">
                        @csrf
                        @method('PUT')

                        <div class="px-6 py-6 overflow-y-auto space-y-6">

                            {{-- User info --}}
                            <div
                                class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/40 p-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center text-lg font-semibold shadow-sm">
                                        <span x-text="modalUserInitial"></span>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-base font-semibold text-gray-900 dark:text-white"
                                            x-text="selectedUser.name || '-'"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                                            x-text="selectedUser.email || 'No email'"></div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1"
                                            x-text="selectedUser.phone || 'No phone'"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Current Role --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/20 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                        Current Role
                                    </div>
                                    <div class="mt-3">
                                        <template x-if="selectedUser.role">
                                            <span
                                                class="inline-flex items-center px-3 h-9 rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-medium dark:border-indigo-900/40 dark:bg-indigo-500/10 dark:text-indigo-300"
                                                x-text="selectedUser.role"></span>
                                        </template>

                                        <template x-if="!selectedUser.role">
                                            <span
                                                class="inline-flex items-center px-3 h-9 rounded-full border border-gray-200 bg-gray-100 text-gray-600 text-sm font-medium dark:border-slate-700 dark:bg-slate-700 dark:text-gray-300">
                                                No Role
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <div
                                    class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/20 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">
                                        Admin Panel Access
                                    </div>
                                    <div class="mt-3 text-sm text-gray-700 dark:text-gray-200">
                                        Access depends on whether the selected role has the
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                            access_admin_panel
                                        </span>
                                        permission.
                                    </div>
                                </div>
                            </div>

                            {{-- Role Select --}}
                            <div>
                                <label for="role"
                                    class="block text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                    Select Role
                                </label>

                                <div class="relative">
                                    <select name="role" x-model="selectedRole" id="role"
                                        class="w-full h-12 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 pr-10 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition"
                                        required>
                                        <option value="">Choose a role</option>
                                        @foreach ($roles as $role)
                                            @if ($role->name !== 'Super Admin')
                                                <option value="{{ $role->name }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <span
                                        class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Changing role here will replace the user’s current role.
                                </p>
                            </div>

                            {{-- Warning --}}
                            <div
                                class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-300">
                                <div class="font-medium mb-1">Important</div>
                                <p>
                                    If the selected role does not include
                                    <span class="font-semibold">access_admin_panel</span>,
                                    the user will not be able to log into the admin dashboard.
                                </p>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div
                            class="px-6 py-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-end gap-3">
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

    </div>
@endsection

@push('scripts')
    <script>
        function assignRolePage() {
            return {
                search: '',
                showAssignModal: false,
                assignFormAction: '',
                selectedRole: '',
                selectedUser: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    role: '',
                    is_super_admin: false,
                },

                get modalUserInitial() {
                    const name = this.selectedUser?.name || 'U';
                    return name.charAt(0).toUpperCase();
                },

                matchesUser(name, email) {
                    const q = (this.search || '').toLowerCase().trim();
                    if (!q) return true;
                    return name.includes(q) || email.includes(q);
                },

                openAssignRoleModal(user) {
                    if (user.is_super_admin) {
                        return;
                    }

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
                    this.selectedUser = {
                        id: null,
                        name: '',
                        email: '',
                        phone: '',
                        role: '',
                        is_super_admin: false,
                    };
                    document.body.classList.remove('overflow-hidden');
                }
            }
        }
    </script>
@endpush