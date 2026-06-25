@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;

        function permissionLabel($permission)
        {
            return str($permission)->replace('_', ' ')->title();
        }
    @endphp

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes numberPop {
            0%   { transform: scale(0.85); opacity: 0; }
            70%  { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }

        .stat-card { animation: fadeSlideUp .5s ease both; }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .12s; }
        .stat-card:nth-child(3) { animation-delay: .19s; }
        .stat-card:nth-child(4) { animation-delay: .26s; }

        .table-card { animation: fadeSlideUp .55s .28s ease both; }

        #rolesTableBody tr { animation: rowSlideIn .35s ease both; }
        #rolesTableBody tr:nth-child(1)  { animation-delay: .32s; }
        #rolesTableBody tr:nth-child(2)  { animation-delay: .37s; }
        #rolesTableBody tr:nth-child(3)  { animation-delay: .42s; }
        #rolesTableBody tr:nth-child(4)  { animation-delay: .47s; }
        #rolesTableBody tr:nth-child(5)  { animation-delay: .52s; }
        #rolesTableBody tr:nth-child(6)  { animation-delay: .57s; }
        #rolesTableBody tr:nth-child(7)  { animation-delay: .62s; }
        #rolesTableBody tr:nth-child(8)  { animation-delay: .67s; }

        .progress-bar { animation: progressFill .9s .65s cubic-bezier(.4,0,.2,1) both; }
        .count-done   { animation: numberPop .35s cubic-bezier(.34,1.56,.64,1) both; }

        #createRoleModal.flex, #editRoleModal.flex { animation: overlayIn .2s ease; }
        .modal-inner { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

        .action-btn { transition: transform .15s ease, box-shadow .15s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: translateY(0); }

        .role-table-row { transition: background .2s ease; }

        .perm-card { transition: border-color .2s ease, box-shadow .2s ease; }
        .perm-card:hover { box-shadow: 0 2px 12px rgba(99,102,241,.08); border-color: #c7d2fe; }
        .dark .perm-card:hover { border-color: rgba(99,102,241,.35); }

        .perm-scroll::-webkit-scrollbar { width: 5px; }
        .perm-scroll::-webkit-scrollbar-thumb { background: rgba(99,102,241,.25); border-radius: 999px; }
        .perm-scroll::-webkit-scrollbar-track { background: transparent; }

        .perm-label { transition: border-color .15s ease, background .15s ease; }
        .perm-label:hover { border-color: #a5b4fc; background: #eef2ff; }
        .dark .perm-label:hover { border-color: rgba(99,102,241,.4); background: rgba(99,102,241,.08); }
        .perm-label:has(input:checked) { border-color: #6366f1; background: #eef2ff; }
        .dark .perm-label:has(input:checked) { border-color: rgba(99,102,241,.5); background: rgba(99,102,241,.12); }
    </style>

    <div class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3"
             style="animation: fadeSlideUp .4s ease both;">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Manage roles, assign permissions, and control admin access.
                </p>
            </div>

            {{-- @can('create_roles')
                <button type="button" onclick="openCreateRoleModal()"
                    class="action-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                           bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                           shadow-lg shadow-indigo-500/25 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Role
                </button>
            @endcan --}}
        </div>

        {{-- ==================== FLASH ==================== --}}
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-500/20
                        bg-emerald-50 dark:bg-emerald-500/10
                        px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400 flex items-center gap-2"
                 style="animation: fadeSlideUp .4s ease both;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 dark:border-red-500/20
                        bg-red-50 dark:bg-red-500/10
                        px-4 py-3 text-sm text-red-700 dark:text-red-400 flex items-center gap-2"
                 style="animation: fadeSlideUp .4s ease both;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-500/20
                        bg-red-50 dark:bg-red-500/10 px-4 py-3"
                 style="animation: fadeSlideUp .4s ease both;">
                <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">Please fix the following errors:</p>
                <ul class="list-disc ml-5 text-xs text-red-600 dark:text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ==================== STAT CARDS ==================== --}}
        @php
            $totalRoles    = $roles->count();
            $totalPerms    = $permissions->count();
            $adminRoles    = $roles->whereIn('name', ['Super Admin','Admin','Manager','Editor','Staff'])->count();
            $customerRoles = $roles->where('name','Customer')->count();
        @endphp

        {{-- <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-indigo-50 to-violet-100
                            dark:from-indigo-900/20 dark:to-violet-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                    flex items-center justify-center shadow-md shadow-indigo-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total Roles</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">All defined roles</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400
                                 ring-1 ring-indigo-200 dark:ring-indigo-800 text-[10px] font-semibold">
                        100%
                    </span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight
                               bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $totalRoles }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All roles</span>
                        <span class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($totalRoles) }} total</span>
                    </div>
                </div>
            </div>

            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center shadow-md shadow-amber-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Permissions</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Total defined</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-800 text-[10px] font-semibold">
                        {{ $totalPerms }}
                    </span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-amber-600 to-yellow-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $totalPerms }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full w-full rounded-full bg-gradient-to-r from-amber-500 to-yellow-600"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">All permissions</span>
                        <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400">Across {{ $totalRoles }} roles</span>
                    </div>
                </div>
            </div>

           
            @php $adminPct = $totalRoles > 0 ? round(($adminRoles / $totalRoles) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shadow-blue-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Admin Roles</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Staff & above</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800 text-[10px] font-semibold">{{ $adminPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $adminRoles }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $adminPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $adminPct }}% of roles</span>
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ number_format($totalRoles) }} total</span>
                    </div>
                </div>
            </div>

           
            @php $customerPct = $totalRoles > 0 ? round(($customerRoles / $totalRoles) * 100) : 0; @endphp
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700
                        shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-md shadow-emerald-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Customer</h4>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">Customer role</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800 text-[10px] font-semibold">{{ $customerPct }}%</span>
                </div>
                <div class="relative mt-2 pl-2">
                    <h2 class="stat-number text-2xl font-bold tracking-tight bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent leading-none"
                        data-count="{{ $customerRoles }}">0</h2>
                </div>
                <div class="relative mt-2">
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600" style="width: {{ $customerPct }}%"></div>
                    </div>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $customerPct }}% of roles</span>
                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($totalRoles) }} total</span>
                    </div>
                </div>
            </div>

        </div> --}}

        {{-- ==================== ROLES TABLE ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Roles List</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Each role carries a custom set of permissions.</p>
                </div>
                @can('create_roles')
                    <button type="button" onclick="openCreateRoleModal()"
                        class="action-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                               bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Role
                    </button>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Users</th>
                            <th class="px-6 py-3">Permissions</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="rolesTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($roles as $role)
                            @php
                                $roleColor = match($role->name) {
                                    'Super Admin' => 'bg-purple-100 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400',
                                    'Admin'       => 'bg-indigo-100 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400',
                                    'Manager'     => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                    'Editor'      => 'bg-cyan-100 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400',
                                    'Staff'       => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'Customer'    => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                    default       => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                };
                                $dotColor = match($role->name) {
                                    'Super Admin' => 'bg-purple-500',
                                    'Admin'       => 'bg-indigo-500',
                                    'Manager'     => 'bg-blue-500',
                                    'Editor'      => 'bg-cyan-500',
                                    'Staff'       => 'bg-amber-500',
                                    'Customer'    => 'bg-emerald-500',
                                    default       => 'bg-gray-400',
                                };
                            @endphp

                            <tr class="role-table-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200">

                                {{-- ROLE --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $dotColor }}"></span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $roleColor }}">
                                            {{ $role->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- USERS --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 dark:text-gray-200">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $role->users_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- PERMISSIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5 max-w-lg">
                                        @forelse($role->permissions->take(5) as $permission)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-medium
                                                         bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ permissionLabel($permission->name) }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">No permissions</span>
                                        @endforelse
                                        @if($role->permissions->count() > 5)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold
                                                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                                +{{ $role->permissions->count() - 5 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- DATE --}}
                                <td class="px-6 py-4 text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $role->created_at?->format('d M Y') }}
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">

                                        @if($role->name === 'Super Admin')
                                            {{-- Protected badge: no edit / delete --}}
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold
                                                         bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500
                                                         ring-1 ring-gray-200 dark:ring-gray-600">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Protected
                                            </span>
                                        @else
                                            @can('edit_roles')
                                                <button type="button"
                                                    onclick='openEditRoleModal(@json(["id" => $role->id, "name" => $role->name, "permissions" => $role->permissions->pluck("name")->toArray()]))'
                                                    class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                           border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                           text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </button>
                                            @endcan

                                            @can('delete_roles')
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                                      onsubmit="return confirm('Delete the {{ $role->name }} role?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                               border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                               text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400
                                                               hover:border-red-200 dark:hover:border-red-500/30 transition-all duration-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
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
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">No roles found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /space-y-4 --}}


    {{-- ==================== CREATE ROLE MODAL ==================== --}}
    @can('create_roles')
    <div id="createRoleModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                flex items-center justify-center shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Create New Role</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Set a name and choose permissions by module.</p>
                    </div>
                </div>
                <button onclick="closeCreateRoleModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full
                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                           text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf

                <div class="p-6 space-y-5 flex-1 overflow-y-auto perm-scroll">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                            Role Name
                        </label>
                        <input type="text" name="name"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-white dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none
                                   transition-shadow duration-200 placeholder-gray-400 dark:placeholder-gray-500"
                            placeholder="e.g. Marketing Manager">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Permissions</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    Checking the main permission selects all sub-permissions. Unchecking subs does not remove view access.
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="toggleAllPermissions('createRoleModal', true)"
                                    class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Select all</button>
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <button type="button" onclick="toggleAllPermissions('createRoleModal', false)"
                                    class="text-xs font-medium text-red-500 hover:underline">Clear all</button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                @php
                                    $mainPermission = $groupPermissions[0] ?? null;
                                    $subPermissions = array_slice($groupPermissions, 1);
                                    $groupKey       = 'create_' . Str::slug($groupName, '_');
                                @endphp

                                <div class="perm-card rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/40 flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $groupName }}</h4>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ count($groupPermissions) }} permission{{ count($groupPermissions) > 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                        @if($mainPermission)
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $mainPermission }}"
                                                    class="permission-main w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                                    data-group="{{ $groupKey }}">
                                                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                                    {{ permissionLabel($mainPermission) }}
                                                </span>
                                            </label>
                                        @endif
                                    </div>

                                    @if(count($subPermissions))
                                        <div class="p-3 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2 bg-white dark:bg-gray-800">
                                            @foreach($subPermissions as $permission)
                                                <label class="perm-label flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700
                                                              px-3 py-2.5 cursor-pointer">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission }}"
                                                        class="permission-sub w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                                        data-group="{{ $groupKey }}">
                                                    <span class="text-xs text-gray-700 dark:text-gray-200">
                                                        {{ permissionLabel($permission) }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 flex-shrink-0 bg-gray-50/50 dark:bg-gray-800/50">
                    <button type="button" onclick="closeCreateRoleModal()"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                               text-sm font-medium text-gray-600 dark:text-gray-300
                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="action-btn px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                               text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                        Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endcan


    {{-- ==================== EDIT ROLE MODAL ==================== --}}
    @can('edit_roles')
    <div id="editRoleModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600
                                flex items-center justify-center shadow-md shadow-blue-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Edit Role</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Update name and permissions for this role.</p>
                    </div>
                </div>
                <button onclick="closeEditRoleModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full
                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                           text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editRoleForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-5 flex-1 overflow-y-auto perm-scroll">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                            Role Name
                        </label>
                        <input type="text" name="name" id="editRoleName"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-white dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none
                                   transition-shadow duration-200">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Permissions</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    Checking the main permission selects all sub-permissions. Unchecking subs does not remove view access.
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="toggleAllPermissions('editRoleModal', true)"
                                    class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Select all</button>
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <button type="button" onclick="toggleAllPermissions('editRoleModal', false)"
                                    class="text-xs font-medium text-red-500 hover:underline">Clear all</button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                @php
                                    $mainPermission = $groupPermissions[0] ?? null;
                                    $subPermissions = array_slice($groupPermissions, 1);
                                    $groupKey       = 'edit_' . Str::slug($groupName, '_');
                                @endphp

                                <div class="perm-card rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/40 flex items-center justify-between flex-wrap gap-2">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $groupName }}</h4>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ count($groupPermissions) }} permission{{ count($groupPermissions) > 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                        @if($mainPermission)
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $mainPermission }}"
                                                    class="permission-main edit-permission-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                                    data-group="{{ $groupKey }}">
                                                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                                    {{ permissionLabel($mainPermission) }}
                                                </span>
                                            </label>
                                        @endif
                                    </div>

                                    @if(count($subPermissions))
                                        <div class="p-3 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2 bg-white dark:bg-gray-800">
                                            @foreach($subPermissions as $permission)
                                                <label class="perm-label flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700
                                                              px-3 py-2.5 cursor-pointer">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission }}"
                                                        class="permission-sub edit-permission-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                                        data-group="{{ $groupKey }}">
                                                    <span class="text-xs text-gray-700 dark:text-gray-200">
                                                        {{ permissionLabel($permission) }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 flex-shrink-0 bg-gray-50/50 dark:bg-gray-800/50">
                    <button type="button" onclick="closeEditRoleModal()"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                               text-sm font-medium text-gray-600 dark:text-gray-300
                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="action-btn px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                               text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endcan


    @push('scripts')
    <script defer>
    // ══════════════════════════════════════════════════════
    //  ANIMATED COUNTER
    // ══════════════════════════════════════════════════════
    function animateCounter(el) {
        const target    = parseInt(el.dataset.count, 10) || 0;
        const duration  = 1000;
        const startTime = performance.now();
        function ease(t) { return 1 - Math.pow(1 - t, 3); }
        function tick(now) {
            const elapsed  = Math.max(0, now - startTime);
            const progress = Math.min(elapsed / duration, 1);
            el.textContent = Math.round(ease(progress) * target).toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
            else { el.textContent = target.toLocaleString(); el.classList.add('count-done'); }
        }
        requestAnimationFrame(tick);
    }
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => document.querySelectorAll('[data-count]').forEach(animateCounter), 320);
    });

    // ══════════════════════════════════════════════════════
    //  MODAL HELPERS
    // ══════════════════════════════════════════════════════
    function showModal(id) { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
    function hideModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

    ['createRoleModal', 'editRoleModal'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) { if (e.target === this) hideModal(id); });
    });

    // ══════════════════════════════════════════════════════
    //  CREATE MODAL
    // ══════════════════════════════════════════════════════
    function openCreateRoleModal() {
        const modal = document.getElementById('createRoleModal');
        if (!modal) return;
        showModal('createRoleModal');
        syncAllGroupStates(modal);
    }
    function closeCreateRoleModal() { hideModal('createRoleModal'); }

    // ══════════════════════════════════════════════════════
    //  EDIT MODAL
    // ══════════════════════════════════════════════════════
    function openEditRoleModal(role) {
        const modal = document.getElementById('editRoleModal');
        const form  = document.getElementById('editRoleForm');
        if (!modal || !form) return;

        form.action = `/admin/roles/${role.id}`;
        document.getElementById('editRoleName').value = role.name;

        modal.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
            cb.checked       = role.permissions.includes(cb.value);
            cb.indeterminate = false;
        });

        syncAllGroupStates(modal);
        showModal('editRoleModal');
    }
    function closeEditRoleModal() { hideModal('editRoleModal'); }

    // ══════════════════════════════════════════════════════
    //  TOGGLE ALL
    // ══════════════════════════════════════════════════════
    function toggleAllPermissions(modalId, checked) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
            cb.checked       = checked;
            cb.indeterminate = false;
        });
        // After clear-all, do NOT auto-recheck mains — leave them off.
        // After select-all, everything is on so no sync needed.
    }

    // ══════════════════════════════════════════════════════
    //  GROUP STATE SYNC
    //
    //  Rules:
    //    main ON  → all subs ON         (handled in change handler)
    //    main OFF → all subs OFF        (handled in change handler)
    //    sub ON   → force main ON       (Rule 3)
    //    sub OFF  → do NOT touch main   (Rule 4 — view-only is valid)
    // ══════════════════════════════════════════════════════
    function syncGroupState(modal, groupName) {
        const main = modal.querySelector(`.permission-main[data-group="${groupName}"]`);
        const subs = [...modal.querySelectorAll(`.permission-sub[data-group="${groupName}"]`)];

        if (!main) return;

        // Rule 3: any sub checked → main must be on
        const anyChecked = subs.some(cb => cb.checked);
        if (anyChecked) {
            main.checked       = true;
            main.indeterminate = false;
        }
        // Rule 4: all subs unchecked → intentionally do nothing.
        // Main stays however it already is (checked = view-only, unchecked = no access).
    }

    function syncAllGroupStates(modal) {
        const groups = new Set(
            [...modal.querySelectorAll('[data-group]')].map(el => el.dataset.group)
        );
        groups.forEach(g => syncGroupState(modal, g));
    }

    // ══════════════════════════════════════════════════════
    //  CHECKBOX CHANGE HANDLER
    // ══════════════════════════════════════════════════════
    document.addEventListener('change', function (e) {
        const target = e.target;
        if (!target.matches('input[name="permissions[]"]')) return;

        const modal = target.closest('#createRoleModal, #editRoleModal');
        if (!modal) return;

        const group = target.dataset.group;

        if (target.classList.contains('permission-main')) {
            // Rule 1 & 2: main drives all subs
            const subs = modal.querySelectorAll(`.permission-sub[data-group="${group}"]`);
            subs.forEach(cb => {
                cb.checked       = target.checked;
                cb.indeterminate = false;
            });
            target.indeterminate = false;
            return;
        }

        if (target.classList.contains('permission-sub')) {
            // Rule 3 & 4
            syncGroupState(modal, group);
        }
    });
    </script>
    @endpush

@endsection