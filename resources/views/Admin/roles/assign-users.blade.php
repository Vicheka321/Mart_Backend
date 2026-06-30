@extends('layouts.app')

@section('content')

<style>
@keyframes fadeUp   { from { opacity:0; transform:translateY(14px) } to { opacity:1; transform:translateY(0) } }
@keyframes cardPop  { from { opacity:0; transform:scale(.96) translateY(10px) } to { opacity:1; transform:scale(1) translateY(0) } }
@keyframes rowIn    { from { opacity:0; transform:translateX(-8px) } to { opacity:1; transform:translateX(0) } }

.s-header  { animation: fadeUp   .4s .04s cubic-bezier(.22,1,.36,1) both }
.s-table   { animation: cardPop  .5s .10s cubic-bezier(.22,1,.36,1) both }

.t-row { animation: rowIn .28s ease both }
.t-row:nth-child(1){animation-delay:.18s}.t-row:nth-child(2){animation-delay:.23s}
.t-row:nth-child(3){animation-delay:.28s}.t-row:nth-child(4){animation-delay:.33s}
.t-row:nth-child(5){animation-delay:.38s}.t-row:nth-child(6){animation-delay:.43s}
.t-row:nth-child(7){animation-delay:.48s}.t-row:nth-child(8){animation-delay:.53s}
.t-row:nth-child(9){animation-delay:.58s}.t-row:nth-child(10){animation-delay:.63s}

.act { transition: transform .12s ease }
.act:hover  { transform: translateY(-1px) }
.act:active { transform: scale(.96) }
</style>

<div x-data="assignRolePage()" class="min-h-screen bg-slate-50 dark:bg-slate-950">

    {{-- ── Page header ── --}}
    {{-- <div class="s-header border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-screen-xl px-6 py-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <span>Dashboard</span><span>/</span>
                        <span class="text-slate-600 dark:text-slate-300">User Roles</span>
                    </div>
                    <h1 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">Assign User Roles</h1>
                </div>
                <button type="button" @click="openAddModal()"
                    class="act inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-sm shadow-indigo-500/25">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Add user
                </button>
            </div>
        </div>
    </div> --}}

    <div class="mx-auto max-w-screen-xl px-6 py-6">

        {{-- ═══════════════════════════════
             Table (full width — no sidebar)
        ════════════════════════════════ --}}
        <div class="s-table rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 overflow-hidden">

            {{-- Toolbar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 dark:border-slate-800 px-5 py-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">User role directory</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Assign a single role to each user</p>
                </div>
                <div class="relative w-full sm:w-72">
                    <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="6"/><path d="M21 21L16.65 16.65"/>
                    </svg>
                    <input x-model="search" type="text" placeholder="Search name or email…"
                        class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-800 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800/60 dark:text-white dark:placeholder:text-slate-500">
                </div>
            </div>

            {{-- Desktop table --}}
            <div class="hidden overflow-x-auto lg:block">
                <table class="w-full min-w-[820px]">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40">
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-slate-400">User</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-slate-400">Contact</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-slate-400">Role</th>
                            <th class="px-5 py-3 text-left text-[10px] font-semibold uppercase tracking-widest text-slate-400">Access</th>
                            <th class="px-5 py-3 text-right text-[10px] font-semibold uppercase tracking-widest text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
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
                                // Dot + text-only badge styles (matches Roles & Permissions page)
                                $roleDot = match ($currentRole) {
                                    'Super Admin' => 'bg-violet-500',
                                    'Admin'       => 'bg-indigo-500',
                                    'Manager'     => 'bg-blue-500',
                                    'Staff'       => 'bg-amber-500',
                                    'Customer'    => 'bg-emerald-500',
                                    default       => 'bg-slate-400',
                                };
                                $roleBadge = match ($currentRole) {
                                    'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                                    'Admin'       => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                                    'Manager'     => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                    'Staff'       => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                    'Customer'    => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    default       => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                                };
                            @endphp
                            <tr x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                                class="t-row transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">

                                {{-- User --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-semibold text-white shadow-sm">
                                            {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->full_name ?? 'Unnamed user' }}</p>
                                            <p class="text-[11px] text-slate-400">#{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact --}}
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-slate-700 dark:text-slate-200">{{ $user->email ?: '—' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $user->phone ?: 'No phone' }}</p>
                                </td>

                                {{-- Role: dot + pill (matches Roles & Permissions list) --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 shrink-0 rounded-full {{ $currentRole ? $roleDot : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $currentRole ? $roleBadge : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                            {{ $currentRole ?? 'No role' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Access: plain pill tag (matches permission tag style) --}}
                                <td class="px-5 py-3.5">
                                    @if ($user->can('access_admin_panel'))
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            Allowed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1.5 text-xs italic text-slate-400 dark:bg-slate-800/50 dark:text-slate-500">
                                            No access
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($isSuperAdmin)
                                            <span class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 text-[11px] font-medium text-amber-600 dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-400">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                Protected
                                            </span>
                                        @else
                                            <button type="button" @click="openEditModal(@js($userPayload))"
                                                class="act inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-[11px] font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit
                                            </button>
                                            <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                                class="act inline-flex h-8 items-center gap-1.5 rounded-lg bg-indigo-600 px-3 text-[11px] font-medium text-white transition hover:bg-indigo-700 shadow-sm shadow-indigo-500/20">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                Assign role
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    </div>
                                    <p class="mt-3 text-sm font-medium text-slate-700 dark:text-slate-200">No users found</p>
                                    <p class="mt-1 text-xs text-slate-400">There are no users to assign roles to yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="divide-y divide-slate-100 dark:divide-slate-800 lg:hidden">
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
                            default       => 'bg-slate-400',
                        };
                        $roleBadge = match ($currentRole) {
                            'Super Admin' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
                            'Admin'       => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                            'Manager'     => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                            'Staff'       => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                            'Customer'    => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                            default       => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                        };
                    @endphp
                    <div x-show="matchesUser(@js(strtolower($user->full_name ?? '')), @js(strtolower($user->email ?? '')))"
                        class="t-row p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-semibold text-white">
                                    {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->full_name ?? 'Unnamed user' }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email ?: '—' }}</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ $currentRole ? $roleDot : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $currentRole ? $roleBadge : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                                    {{ $currentRole ?? 'No role' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            @if ($isSuperAdmin)
                                <span class="inline-flex h-9 flex-1 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-xs font-medium text-amber-600 dark:border-amber-900/40 dark:bg-amber-500/10 dark:text-amber-400">Protected</span>
                            @else
                                <button type="button" @click="openEditModal(@js($userPayload))"
                                    class="inline-flex h-9 flex-1 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    Edit
                                </button>
                                <button type="button" @click="openAssignRoleModal(@js($userPayload))"
                                    class="inline-flex h-9 flex-1 items-center justify-center rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700">
                                    Assign role
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-slate-400">No users found.</div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════
         ASSIGN ROLE MODAL
    ════════════════════════════════════════════════════════════════ --}}
    <div x-show="showAssignModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeAssignRoleModal()"></div>
        <div class="relative z-10 w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-black/10 dark:border-slate-700 dark:bg-slate-900">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Assign role</h3>
                        <p class="mt-0.5 text-xs text-slate-400">Replaces the user's current role</p>
                    </div>
                    <button type="button" @click="closeAssignRoleModal()"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="assignFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 p-5">

                        {{-- User card --}}
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-800/60">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-semibold text-white shadow-sm">
                                <span x-text="modalUserInitial"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-900 dark:text-white" x-text="selectedUser.full_name || '—'"></p>
                                <p class="text-xs text-slate-400" x-text="selectedUser.email || 'No email'"></p>
                            </div>
                            <template x-if="selectedUser.role">
                                <span class="shrink-0 rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-600 ring-1 ring-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:ring-indigo-900/40" x-text="selectedUser.role"></span>
                            </template>
                            <template x-if="!selectedUser.role">
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] text-slate-400 dark:bg-slate-800">No role</span>
                            </template>
                        </div>

                        {{-- Role select --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">New role</label>
                            <select name="role" x-model="selectedRole" required
                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Choose a role…</option>
                                @foreach ($roles as $role)
                                    @if ($role->name !== 'Super Admin')
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Warning --}}
                        <div class="flex gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-3 dark:border-amber-900/40 dark:bg-amber-500/10">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <p class="text-xs leading-relaxed text-amber-700 dark:text-amber-300">
                                Roles without <span class="font-semibold">access_admin_panel</span> will lose dashboard access.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                        <button type="button" @click="closeAssignRoleModal()"
                            class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="act inline-flex h-9 items-center rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-sm shadow-indigo-500/20">
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
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeAddModal()"></div>
        <div class="relative z-10 w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-black/10 dark:border-slate-700 dark:bg-slate-900">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Add new user</h3>
                        <p class="mt-0.5 text-xs text-slate-400">Create an account and assign a role</p>
                    </div>
                    <button type="button" @click="closeAddModal()"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.customers.store') }}" method="POST" @submit="addSubmitting = true" class="flex flex-col">
                    @csrf
                    <div class="max-h-[65vh] space-y-4 overflow-y-auto p-5">

                        {{-- Validation errors --}}
                        @if ($errors->hasAny(['full_name','email','phone','password','password_confirmation','role']))
                            <div class="flex gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3 dark:border-rose-900/40 dark:bg-rose-500/10">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <ul class="space-y-1">
                                    @foreach (['full_name','email','phone','password','password_confirmation','role'] as $f)
                                        @error($f)<li class="text-xs text-rose-700 dark:text-rose-300">{{ $message }}</li>@enderror
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Full name --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Full name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Sophea Chan"
                                class="h-9 w-full rounded-lg border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/20 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 @error('full_name') border-rose-400 focus:border-rose-400 @else border-slate-200 focus:border-indigo-400 dark:border-slate-700 @enderror">
                            @error('full_name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email + Phone --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Email <span class="text-rose-400">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required
                                    class="h-9 w-full rounded-lg border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/20 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 @error('email') border-rose-400 focus:border-rose-400 @else border-slate-200 focus:border-indigo-400 dark:border-slate-700 @enderror">
                                @error('email')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+855 12 345 678"
                                    class="h-9 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Role <span class="text-rose-400">*</span></label>
                            <select name="role" required
                                class="h-9 w-full rounded-lg border px-3 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/20 dark:bg-slate-800 dark:text-white @error('role') border-rose-400 focus:border-rose-400 @else border-slate-200 focus:border-indigo-400 dark:border-slate-700 @enderror">
                                <option value="">Choose a role…</option>
                                @foreach ($roles as $role)
                                    @if ($role->name !== 'Super Admin')
                                        <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-800"></div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Password <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="Minimum 8 characters" required
                                    class="h-9 w-full rounded-lg border pl-3 pr-10 text-sm outline-none transition focus:ring-2 focus:ring-indigo-400/20 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 @error('password') border-rose-400 focus:border-rose-400 @else border-slate-200 focus:border-indigo-400 dark:border-slate-700 @enderror">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Confirm password --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Confirm password <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Repeat your password" required
                                    class="h-9 w-full rounded-lg border border-slate-200 pl-3 pr-10 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                        <button type="button" @click="closeAddModal()"
                            class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            Cancel
                        </button>
                        <button type="submit" :disabled="addSubmitting"
                            class="act inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:opacity-60 shadow-sm shadow-indigo-500/20">
                            <svg x-show="addSubmitting" class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none" x-cloak>
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
         EDIT USER MODAL
    ════════════════════════════════════════════════════════════════ --}}
    <div x-show="showEditModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[90] flex items-center justify-center px-4 py-8" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeEditModal()"></div>
        <div class="relative z-10 w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-black/10 dark:border-slate-700 dark:bg-slate-900">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-semibold text-white" x-text="editUser.initial"></div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Edit user</h3>
                            <p class="text-xs text-slate-400" x-text="editUser.email || 'Update user details'"></p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditModal()"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editFormAction" method="POST" @submit="editSubmitting = true" class="flex flex-col">
                    @csrf
                    @method('PATCH')

                    <div class="max-h-[65vh] space-y-4 overflow-y-auto p-5">

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Full name <span class="text-rose-400">*</span></label>
                            <input type="text" name="full_name" :value="editUser.full_name" placeholder="e.g. Sophea Chan" required
                                class="h-9 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Email <span class="text-rose-400">*</span></label>
                                <input type="email" name="email" :value="editUser.email" placeholder="user@example.com" required
                                    class="h-9 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Phone</label>
                                <input type="text" name="phone" :value="editUser.phone" placeholder="+855 12 345 678"
                                    class="h-9 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Role <span class="text-rose-400">*</span></label>
                            <select name="role" required x-ref="editRoleSelect"
                                class="h-9 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="">Choose a role…</option>
                                @foreach ($roles as $role)
                                    @if ($role->name !== 'Super Admin')
                                        <option value="{{ $role->name }}" :selected="editUser.role === '{{ $role->name }}'">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 dark:border-slate-800 dark:bg-slate-800/60">
                            <p class="text-xs text-slate-400">Leave password fields blank to keep the current password.</p>
                        </div>

                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">New password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="Leave blank to keep current"
                                    class="h-9 w-full rounded-lg border border-slate-200 pl-3 pr-10 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Confirm new password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Repeat new password"
                                    class="h-9 w-full rounded-lg border border-slate-200 pl-3 pr-10 text-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" x-cloak><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                        <button type="button" @click="closeEditModal()"
                            class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            Cancel
                        </button>
                        <button type="submit" :disabled="editSubmitting"
                            class="act inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:opacity-60 shadow-sm shadow-indigo-500/20">
                            <svg x-show="editSubmitting" class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span x-text="editSubmitting ? 'Saving…' : 'Save changes'"></span>
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
                class="pointer-events-auto flex w-72 items-start gap-3 rounded-xl border bg-white px-4 py-3 shadow-lg shadow-black/5 dark:bg-slate-900">
                <div :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"
                    class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full">
                    <svg x-show="toast.type === 'success'" class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <svg x-show="toast.type === 'error'" class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </div>
                <p class="flex-1 text-xs font-medium text-slate-700 dark:text-slate-200" x-text="toast.message"></p>
                <button @click="dismiss(toast.id)" class="text-slate-300 transition hover:text-slate-500 dark:text-slate-600 dark:hover:text-slate-400">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
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

        /* Edit user modal */
        showEditModal:  false,
        editSubmitting: false,
        editFormAction: '',
        editUser:       { id: null, full_name: '', email: '', phone: '', role: '', initial: 'U' },

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
            this.editFormAction = `/admin/customers/${payload.id}`;
            this.editSubmitting = false;
            this.showEditModal  = true;
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
</script>
@endpush