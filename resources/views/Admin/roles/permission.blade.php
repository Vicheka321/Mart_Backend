@extends('layouts.app')

@section('content')

    <style>
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

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-12px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .table-card {
            animation: fadeSlideUp .5s ease both;
        }

        .header-block {
            animation: fadeSlideUp .4s ease both;
        }

        .action-btn {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @php
        // The store() controller accepts `resource` (string) + `actions` (array of
        // already-generated permission names). We re-open the Create modal after a
        // failed validation if either of those failed.
        $createHasErrors = $errors->has('resource')
            || $errors->has('actions')
            || collect($errors->keys())->contains(fn($k) => str_starts_with($k, 'actions.'));
    @endphp

    <div x-data="permissionManager()" x-cloak @keydown.escape.window="closeModals()" class="space-y-4">

        {{-- ==================== HEADER ==================== --}}
        <div class="header-block flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Permissions</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Manage permissions and control access to system resources.
                </p>
            </div>

            <button type="button" @click="openCreateModal()" class="action-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                                                   bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                                                   shadow-lg shadow-indigo-500/25 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Create Permission
            </button>
        </div>

        {{-- ==================== SUCCESS ALERT ==================== --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="rounded-2xl border border-emerald-200 dark:border-emerald-500/20
                                           bg-emerald-50 dark:bg-emerald-500/10
                                           px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400 flex items-center justify-between gap-2"
                style="animation: fadeSlideUp .4s ease both;">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ==================== ERROR ALERT (delete blocked, etc.) ==================== --}}
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="rounded-2xl border border-red-200 dark:border-red-500/20
                                           bg-red-50 dark:bg-red-500/10
                                           px-4 py-3 text-sm text-red-700 dark:text-red-400 flex items-center justify-between gap-2"
                style="animation: fadeSlideUp .4s ease both;">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 dark:hover:text-red-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ==================== VALIDATION ERRORS ==================== --}}
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

        {{-- ==================== RESOURCES TABLE ==================== --}}
        <div
            class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            <div
                class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Resources</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        <span x-text="filteredResourceList.length"></span> of <span x-text="resourceList.length"></span>
                        resource<span x-text="resourceList.length === 1 ? '' : 's'"></span>
                    </p>
                </div>

                <div class="relative w-full sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Search resources..." class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-white
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none
                                           transition-shadow duration-200">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr
                            class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3 w-14">#</th>
                            <th class="px-6 py-3">Resource</th>
                            <th class="px-6 py-3">Permissions</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(r, idx) in filteredResourceList" :key="r.name">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200"
                                style="animation: rowSlideIn .3s ease both;">
                                <td class="px-6 py-4 text-xs font-medium text-gray-400 dark:text-gray-500" x-text="idx + 1">
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                                             bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300"
                                        x-text="r.name"></span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    <span x-text="r.count"></span>
                                    <span x-text="r.count === 1 ? ' permission' : ' permissions'"></span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <button type="button" @click="openResourceModal(r.name)"
                                            class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                               border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                               text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <button type="button" @click="activeResource = r.name; openDeleteResourceModal()"
                                            class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                                               border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10
                                                               text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        {{-- Empty state: no resources at all --}}
                        <tr x-show="resourceList.length === 0">
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">No resources found.</p>
                                    <button type="button" @click="openCreateModal()"
                                        class="action-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-xl
                                                           bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create Permission
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Empty state: search matched nothing --}}
                        <tr x-show="resourceList.length > 0 && filteredResourceList.length === 0">
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">No resources found.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ==================== CREATE PERMISSION MODAL ==================== --}}
        <div x-show="createModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            @click.self="closeModals()">
            <div x-show="createModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md shadow-indigo-500/25">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Create Permission</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Pick a resource, then tick every action
                                that applies.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeModals()" class="w-8 h-8 flex items-center justify-center rounded-full
                                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                           text-gray-500 dark:text-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{--
                The store() controller expects:
                resource : string
                actions : array of strings (final permission names)

                So this form submits ONE "resource" field, plus one hidden
                "actions[]" input per ticked/generated permission. Everything
                ships in a single native POST — no fetch/AJAX needed.
                --}}
                <form method="POST" action="{{ route('permissions.store') }}"
                    @submit="if (!canSave) $event.preventDefault()">
                    @csrf

                    <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label for="resource"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                Resource
                            </label>
                            <input type="text" name="resource" id="resource" x-model="resourceInput"
                                placeholder="e.g. Products"
                                class="w-full rounded-xl border {{ $errors->has('resource') ? 'border-red-300 dark:border-red-500/40' : 'border-gray-200 dark:border-gray-600' }}
                                                   bg-white dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white font-mono
                                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none
                                                   transition-shadow duration-200 placeholder-gray-400 dark:placeholder-gray-500">
                            @error('resource')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                Permissions / Actions
                            </label>

                            <div class="space-y-2">
                                <template x-for="(action, idx) in actionsList" :key="idx + '-' + action.label">
                                    <label
                                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-100 dark:border-gray-700
                                                       bg-gray-50/60 dark:bg-gray-700/30 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors">
                                        <input type="checkbox" x-model="action.checked"
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-200 flex-1"
                                            x-text="action.label"></span>
                                        <button type="button" x-show="action.custom"
                                            @click.stop.prevent="removeActionEntirely(idx)"
                                            class="text-gray-400 hover:text-red-500 text-sm leading-none">&times;</button>
                                    </label>
                                </template>
                            </div>

                            <div class="mt-2">
                                <button type="button" x-show="!showAddActionInput"
                                    @click="showAddActionInput = true; customActionInput = ''; customActionError = ''"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Action
                                </button>

                                <div x-show="showAddActionInput" class="flex items-center gap-2 mt-2">
                                    <input type="text" x-model="customActionInput"
                                        @keydown.enter.prevent="addCustomAction()" placeholder="e.g. Approve"
                                        class="flex-1 rounded-lg border border-gray-200 dark:border-gray-600
                                                           bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white
                                                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                                    <button type="button" @click="addCustomAction()"
                                        class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold whitespace-nowrap">
                                        Add
                                    </button>
                                    <button type="button" @click="showAddActionInput = false; customActionInput = ''"
                                        class="px-3 py-2 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 whitespace-nowrap">
                                        Cancel
                                    </button>
                                </div>

                                <p x-show="customActionError" x-text="customActionError"
                                    class="mt-1 text-[11px] text-amber-600 dark:text-amber-400"></p>
                            </div>

                            @error('actions')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                                Generated Permissions
                            </label>
                            <div
                                class="rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">
                                <template x-for="perm in generatedPermissions" :key="perm.name">
                                    <div
                                        class="flex items-center justify-between px-3 py-2 bg-gray-50/60 dark:bg-gray-700/30">
                                        <span class="text-xs font-mono text-gray-700 dark:text-gray-200"
                                            x-text="perm.name"></span>
                                        <button type="button" @click="perm.action.checked = false"
                                            class="text-gray-400 hover:text-red-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <p x-show="generatedPermissions.length === 0"
                                    class="text-xs text-gray-400 dark:text-gray-500 px-3 py-4 text-center">
                                    No permissions selected yet.
                                </p>
                            </div>
                        </div>

                        {{-- Actual fields submitted to Laravel: one actions[] per generated permission --}}
                        <template x-for="perm in generatedPermissions" :key="'field-' + perm.name">
                            <input type="hidden" name="actions[]" :value="perm.name">
                        </template>
                    </div>

                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50/50 dark:bg-gray-800/50">
                        <button type="button" @click="closeModals()" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                                               text-sm font-medium text-gray-600 dark:text-gray-300
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="!canSave" :class="canSave
                                    ? 'bg-indigo-600 hover:bg-indigo-700 cursor-pointer'
                                    : 'bg-indigo-300 dark:bg-indigo-800/60 cursor-not-allowed'"
                            class="action-btn px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                            Save Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== EDIT RESOURCE MODAL ==================== --}}
        <div x-show="resourceModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
            @click.self="closeModals()">
            <div x-show="resourceModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Edit Resource: <span x-text="activeResource"></span>
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Manage the permissions that belong to this
                            resource.</p>
                    </div>
                    <button type="button" @click="closeModals()" class="w-8 h-8 flex items-center justify-center rounded-full
                                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                           text-gray-500 dark:text-gray-300 transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-2 max-h-96 overflow-y-auto">
                    <template x-for="perm in activePermissions" :key="perm.id">
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl border border-gray-100 dark:border-gray-700
                                                bg-gray-50/60 dark:bg-gray-700/30">
                            <span class="text-sm font-mono text-gray-800 dark:text-gray-200" x-text="perm.name"></span>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" @click="openEditPermissionModal(perm.id, perm.name)"
                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg
                                                       border border-blue-200 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10
                                                       text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all duration-200">
                                    Edit
                                </button>
                                <button type="button" @click="openDeletePermissionModal(perm.id, perm.name)"
                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg
                                                       border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10
                                                       text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all duration-200">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>

                    <p class="text-xs text-gray-400 dark:text-gray-500 py-4 text-center"
                        x-show="activePermissions.length === 0">
                        No permissions left under this resource.
                    </p>

                    {{--
                    IMPORTANT: not changed from the original behaviour on purpose
                    (see instructions — the update UI stays as-is). This still
                    posts to permissions.store, but now sends "actions[]" (an
                    array with one entry) to match the real store() signature,
                    instead of the old singular "action" field.
                    --}}
                    <div class="pt-3">
                        <button type="button" x-show="!addPermissionOpen" @click="addPermissionOpen = true; newAction = ''"
                            class="action-btn inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg
                                               border border-indigo-200 dark:border-indigo-500/30 bg-indigo-50 dark:bg-indigo-500/10
                                               text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Permission
                        </button>

                        <form x-show="addPermissionOpen" method="POST" action="{{ route('permissions.store') }}"
                            class="mt-2 p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-700/30 space-y-3">
                            @csrf
                            <input type="hidden" name="resource" :value="activeResource">

                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Resource</label>
                                <div class="text-sm font-mono text-gray-700 dark:text-gray-300" x-text="activeResource">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Permission</label>
                                <input type="text" name="actions[]" x-model="newAction" placeholder="e.g. approve_products"
                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-600
                                                       bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white font-mono
                                                       focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="addPermissionOpen = false" class="px-3.5 py-2 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400
                                                       hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="action-btn px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700
                                                       text-xs font-semibold text-white shadow-sm shadow-indigo-500/20 transition-colors">
                                    Save Permission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div
                    class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <button type="button" @click="openDeleteResourceModal()"
                        class="action-btn inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg
                                           border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10
                                           text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Resource
                    </button>

                    <button type="button" @click="closeModals()" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                                           text-sm font-medium text-gray-600 dark:text-gray-300
                                           hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- ==================== EDIT PERMISSION MODAL ==================== --}}
        <div x-show="editPermissionModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[60] p-4"
            @click.self="editPermissionModal = false">
            <div x-show="editPermissionModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Edit Permission</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Update this permission's name.</p>
                    </div>
                    <button type="button" @click="editPermissionModal = false" class="w-8 h-8 flex items-center justify-center rounded-full
                                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                           text-gray-500 dark:text-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{--
                FIX: the update() controller requires BOTH "resource" and
                "action". The original form only sent "name" and never sent
                "resource" at all, so this modal would have failed validation
                every time it was submitted. Now sends both correctly.
                --}}
                <form method="POST" :action="editAction">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="resource" :value="editResourceName">

                    <div class="p-6 space-y-4">
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Resource</label>
                            <div class="text-sm font-mono text-gray-700 dark:text-gray-300" x-text="editResourceName"></div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Permission</label>
                            <input type="text" name="action" x-model="editName" class="w-full rounded-xl border border-gray-200 dark:border-gray-600
                                                   bg-white dark:bg-gray-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white font-mono
                                                   focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none
                                                   transition-shadow duration-200">
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50/50 dark:bg-gray-800/50">
                        <button type="button" @click="editPermissionModal = false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                                               text-sm font-medium text-gray-600 dark:text-gray-300
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="action-btn px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700
                                               text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== DELETE PERMISSION CONFIRMATION MODAL ==================== --}}
        <div x-show="deletePermissionModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[60] p-4"
            @click.self="deletePermissionModal = false">
            <div x-show="deletePermissionModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Delete Permission?</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Are you sure you want to delete this permission
                                (<span class="font-mono font-semibold text-gray-700 dark:text-gray-200"
                                    x-text="selectedPermissionName"></span>)?
                                This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" :action="deletePermissionAction">
                    @csrf
                    @method('DELETE')

                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50/50 dark:bg-gray-800/50">
                        <button type="button" @click="deletePermissionModal = false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                                               text-sm font-medium text-gray-600 dark:text-gray-300
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="action-btn px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700
                                               text-sm font-semibold text-white shadow-lg shadow-red-500/20 transition-colors">
                            Delete Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== DELETE RESOURCE CONFIRMATION MODAL ==================== --}}
        <div x-show="deleteResourceModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[60] p-4"
            @click.self="deleteResourceModal = false">
            <div x-show="deleteResourceModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                Delete <span x-text="activeResource"></span> resource?
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                This will delete all permissions belonging to <span x-text="activeResource"
                                    class="font-semibold"></span>.
                                If any of them are assigned to a role, nothing will be deleted and you'll see an error
                                instead.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" :action="deleteResourceAction">
                    @csrf
                    @method('DELETE')

                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 bg-gray-50/50 dark:bg-gray-800/50">
                        <button type="button" @click="deleteResourceModal = false" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600
                                               text-sm font-medium text-gray-600 dark:text-gray-300
                                               hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="action-btn px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700
                                               text-sm font-semibold text-white shadow-lg shadow-red-500/20 transition-colors">
                            Delete Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- /x-data --}}

    @push('scripts')
        <script>
            // ------------------------------------------------------------------
            // Helpers (plain functions, not part of the Alpine component object,
            // so there is no risk of colliding with Alpine's own reactive props)
            // ------------------------------------------------------------------

            function normalizeSlug(str) {
                return (str || '')
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_+|_+$/g, '');
            }

            function defaultActionsList() {
                return [
                    { label: 'View', checked: false, custom: false, forcedName: null },
                    { label: 'Create', checked: false, custom: false, forcedName: null },
                    { label: 'Edit', checked: false, custom: false, forcedName: null },
                    { label: 'Delete', checked: false, custom: false, forcedName: null },
                    { label: 'Export', checked: false, custom: false, forcedName: null },
                ];
            }

            function permissionManager() {
                return {
                    search: '',

                    // { "Products": [{id, name}, ...], "Orders": [...] }
                    // Built entirely from the DB — nothing hardcoded.
                    resources: @json($resources),

                    createModal: @json($createHasErrors),
                    resourceModal: false,
                    editPermissionModal: false,
                    deletePermissionModal: false,
                    deleteResourceModal: false,

                    activeResource: null,
                    addPermissionOpen: false,
                    newAction: '',

                    editAction: '',
                    editName: '',
                    editResourceName: '',

                    deletePermissionAction: '',
                    selectedPermissionName: '',

                    deleteResourceAction: '',

                    // -------- Create Permission modal state --------
                    resourceInput: @json(old('resource', '')),
                    actionsList: defaultActionsList(),
                    customActionInput: '',
                    showAddActionInput: false,
                    customActionError: '',
                    // Re-hydrate previously chosen actions after a failed validation
                    // (old('actions') is an array of already-generated permission names).
                    oldActions: @json(old('actions', [])),

                    // URL templates, generated by Laravel's route() helper so the
                    // admin route prefix is never hard-coded on the JS side.
                    updateUrlTemplate: @json(route('permissions.update', ['permission' => '__ID__'])),
                    destroyUrlTemplate: @json(route('permissions.destroy', ['permission' => '__ID__'])),
                    destroyResourceUrlTemplate: @json(route('permissions.destroyResource', ['resource' => '__RESOURCE__'])),

                    init() {
                        if (Array.isArray(this.oldActions) && this.oldActions.length) {
                            this.oldActions.forEach(name => {
                                this.actionsList.push({
                                    label: name,
                                    checked: true,
                                    custom: true,
                                    forcedName: name,
                                });
                            });
                        }
                    },

                    get resourceList() {
                        return Object.keys(this.resources).map(name => ({
                            name,
                            count: this.resources[name].length,
                        }));
                    },

                    get filteredResourceList() {
                        if (!this.search.trim()) return this.resourceList;
                        const q = this.search.toLowerCase();
                        return this.resourceList.filter(r => r.name.toLowerCase().includes(q));
                    },

                    get activePermissions() {
                        if (!this.activeResource) return [];
                        return this.resources[this.activeResource] || [];
                    },

                    // -------- Create Permission modal getters --------

                    get generatedPermissions() {
                        const resourceSlug = normalizeSlug(this.resourceInput);
                        const seen = new Set();
                        const list = [];

                        this.actionsList.forEach(action => {
                            if (!action.checked) return;

                            const name = action.forcedName
                                ? action.forcedName
                                : (resourceSlug ? normalizeSlug(action.label) + '_' + resourceSlug : '');

                            if (!name || seen.has(name)) return;

                            seen.add(name);
                            list.push({ name, action });
                        });

                        return list;
                    },

                    get canSave() {
                        return this.resourceInput.trim() !== '' && this.generatedPermissions.length > 0;
                    },

                    // -------- Create Permission modal methods --------

                    addCustomAction() {
                        const label = this.customActionInput.trim();
                        if (!label) return;

                        const slug = normalizeSlug(label);
                        const duplicate = this.actionsList.find(a => normalizeSlug(a.label) === slug);

                        if (duplicate) {
                            duplicate.checked = true;
                            this.customActionError = 'That action already exists — it has been checked for you.';
                        } else {
                            this.actionsList.push({ label, checked: true, custom: true, forcedName: null });
                            this.customActionError = '';
                        }

                        this.customActionInput = '';
                        this.showAddActionInput = false;
                    },

                    removeActionEntirely(idx) {
                        if (this.actionsList[idx] && this.actionsList[idx].custom) {
                            this.actionsList.splice(idx, 1);
                        }
                    },

                    resetCreateForm() {
                        this.resourceInput = '';
                        this.actionsList = defaultActionsList();
                        this.customActionInput = '';
                        this.showAddActionInput = false;
                        this.customActionError = '';
                    },

                    // -------- Modal open/close --------

                    openCreateModal() {
                        this.resetCreateForm();
                        this.createModal = true;
                    },

                    openResourceModal(resourceName) {
                        this.activeResource = resourceName;
                        this.addPermissionOpen = false;
                        this.newAction = '';
                        this.resourceModal = true;
                    },

                    openEditPermissionModal(id, name) {
                        this.editAction = this.updateUrlTemplate.replace('__ID__', id);
                        this.editName = name;
                        this.editResourceName = this.activeResource;
                        this.editPermissionModal = true;
                    },

                    openDeletePermissionModal(id, name) {
                        this.deletePermissionAction = this.destroyUrlTemplate.replace('__ID__', id);
                        this.selectedPermissionName = name;
                        this.deletePermissionModal = true;
                    },

                    openDeleteResourceModal() {
                        this.deleteResourceAction = this.destroyResourceUrlTemplate
                            .replace('__RESOURCE__', encodeURIComponent(this.activeResource));
                        this.deleteResourceModal = true;
                    },

                    closeModals() {
                        this.createModal = false;
                        this.resourceModal = false;
                        this.editPermissionModal = false;
                        this.deletePermissionModal = false;
                        this.deleteResourceModal = false;
                    },
                }
            }
        </script>
    @endpush

@endsection