@extends('layouts.app')

@section('content')
    @php $s = $settings ?? []; @endphp

    <style>
        .bx-hidden { display: none !important; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.96) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes toastSlide {
            from { opacity: 0; transform: translateX(48px) scale(.95); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }

        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to { opacity: 0; transform: translateX(48px) scale(.95); }
        }

        .bx-header { animation: fadeSlideUp .4s ease both; }
        .bx-panel { animation: cardPop .3s ease both; }

        .action-btn { transition: transform .14s ease, box-shadow .14s ease; }
        .action-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, .15); }
        .action-btn:active { transform: translateY(0); }

        .bx-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .2rem .55rem;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        /* ── Sidebar nav items ── */
        .settings-nav-btn {
            transition: background .15s ease, color .15s ease;
        }
        .settings-nav-btn.active {
            background: rgb(238 242 255);
            color: rgb(79 70 229);
        }
        .dark .settings-nav-btn.active {
            background: rgba(99, 102, 241, .1);
            color: rgb(165 180 252);
        }
        .settings-nav-btn:not(.active):hover {
            background: rgb(249 250 251);
        }
        .dark .settings-nav-btn:not(.active):hover {
            background: rgb(55 65 81);
        }

        /* ── Style-choice cards (sidebar style / layout style) ── */
        .style-choice-btn {
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .style-choice-btn.active {
            border-color: rgb(129 140 248);
            box-shadow: 0 0 0 2px rgba(129, 140, 248, .2);
        }
        .style-choice-btn:not(.active):hover {
            border-color: rgb(203 213 225);
        }

        /* ── Toast (mirrors branches page) ── */
        .toast-wrap {
            position: fixed;
            top: 1rem;
            right: 1rem;
            left: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            pointer-events: none;
        }

        @media (min-width: 640px) {
            .toast-wrap { top: 1.25rem; right: 1.25rem; left: auto; }
        }

        .toast {
            pointer-events: all;
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .75rem 1rem;
            min-width: 0;
            width: 100%;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12);
            font-size: .8125rem;
            font-weight: 500;
            color: #111827;
            animation: toastSlide .3s cubic-bezier(.34, 1.3, .64, 1) both;
        }

        @media (min-width: 640px) {
            .toast { min-width: 240px; width: auto; }
        }

        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.leaving { animation: toastOut .28s ease forwards; }

        .toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    </style>

    <div id="settingsPage" class="space-y-4">

        {{-- Toast container (rendered entirely via JS) --}}
        <div class="toast-wrap" id="toastWrap"></div>

        {{-- ══════════════════ HEADER ══════════════════ --}}
        <div class="bx-header bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 sm:p-5
                    flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="text-gray-600 dark:text-gray-300">Settings</span>
                </div>
                <h1 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">Settings</h1>
            </div>

            @can('edit_settings')
                <div class="flex items-center gap-2">
                    <button type="button" id="headerResetBtn"
                        class="action-btn inline-flex h-9 items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                        </svg>
                        Reset
                    </button>
                    <button type="button" id="headerSaveBtn"
                        class="action-btn inline-flex h-9 items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 text-sm font-medium text-white shadow-md shadow-indigo-500/25 transition-all">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Save settings
                    </button>
                </div>
            @endcan
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 dark:border-emerald-800/40 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 dark:border-red-800/40 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                <p class="mb-2 font-medium">Please fix the following:</p>
                <ul class="list-disc space-y-1 pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="settingsForm" action="{{ route('settings.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-12 gap-4">

                {{-- ─── LEFT SIDEBAR ──────────────────────────────────────── --}}
                <aside class="col-span-12 lg:col-span-3">
                    <div class="sticky top-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Menu</p>
                        </div>

                        <nav class="max-h-[78vh] overflow-y-auto p-2" id="settingsNav">

                            @php
                                $navGroups = [
                                    'General' => [
                                        ['id' => 'general',      'label' => 'General',      'sub' => 'Store identity',   'icon' => '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" stroke="currentColor" stroke-width="1.8"/><path d="M12 16v-4M12 8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'],
                                        ['id' => 'design',       'label' => 'Design',       'sub' => 'Branding & theme', 'icon' => '<circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z" stroke="currentColor" stroke-width="1.8"/>'],
                                        ['id' => 'localization', 'label' => 'Localization', 'sub' => 'Language & formats','icon' => '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" stroke="currentColor" stroke-width="1.8"/>'],
                                    ],
                                    'Store' => [
                                        ['id' => 'payment',  'label' => 'Payment',  'sub' => 'COD, ABA, KHQR',    'icon' => '<rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M2 10h20" stroke="currentColor" stroke-width="1.8"/>'],
                                        ['id' => 'shipping', 'label' => 'Shipping', 'sub' => 'Fees & delivery',   'icon' => '<path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="5.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="1.8"/><circle cx="18.5" cy="18.5" r="2.5" stroke="currentColor" stroke-width="1.8"/>'],
                                        ['id' => 'orders',   'label' => 'Orders',   'sub' => 'Auto confirm & stock','icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.8"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
                                    ],
                                    'Communication' => [
                                        ['id' => 'email', 'label' => 'Email', 'sub' => 'Sender config',       'icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.8"/><path d="m22 6-10 7L2 6" stroke="currentColor" stroke-width="1.8"/>'],
                                        ['id' => 'sms',   'label' => 'SMS',   'sub' => 'OTP notifications',  'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.8"/>'],
                                        ['id' => 'push',  'label' => 'Push',  'sub' => 'App notifications',  'icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
                                    ],
                                    'System' => [
                                        ['id' => 'backup',      'label' => 'Backup',      'sub' => 'Export & import',   'icon' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
                                        ['id' => 'logs',        'label' => 'Logs',        'sub' => 'Activity timeline', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.8"/><path d="M14 2v6h6M16 13H8M16 17H8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'],
                                        ['id' => 'maintenance', 'label' => 'Maintenance', 'sub' => 'System mode',       'icon' => '<path d="m14.7 6.3-1 1L12 6l1-1a2 2 0 0 0-2.7 0l-7 7a2 2 0 0 0 0 2.7l1 1a2 2 0 0 0 2.7 0l7-7a2 2 0 0 0 0-2.7v-.4z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'],
                                    ],
                                ];
                            @endphp

                            @foreach($navGroups as $groupName => $items)
                                <div class="mb-1 mt-3 first:mt-1">
                                    <p class="mb-1 px-3 text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                        {{ $groupName }}
                                    </p>

                                    @foreach($items as $item)
                                        <button type="button" data-tab-btn="{{ $item['id'] }}"
                                            class="settings-nav-btn flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm text-gray-600 dark:text-gray-300">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">{!! $item['icon'] !!}</svg>
                                            </span>
                                            <span>
                                                <span class="block text-[13px] font-medium leading-tight">{{ $item['label'] }}</span>
                                                <span class="block text-[11px] text-gray-400 dark:text-gray-500">{{ $item['sub'] }}</span>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach

                        </nav>
                    </div>
                </aside>

                {{-- ─── CENTER CONTENT ────────────────────────────────────── --}}
                <main class="col-span-12 lg:col-span-6 space-y-4">

                    {{-- ── GENERAL ── --}}
                    <div data-tab-panel="general" class="bx-panel">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">General information</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Core store identity and contact details</p>
                            </div>
                            <div class="space-y-4 p-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Store name</label>
                                        <input type="text" name="store_name" id="input_store_name"
                                            value="{{ old('store_name', $s['store_name'] ?? '') }}"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Store phone</label>
                                        <input type="text" name="store_phone"
                                            value="{{ old('store_phone', $s['store_phone'] ?? '') }}"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Store email</label>
                                    <input type="email" name="store_email"
                                        value="{{ old('store_email', $s['store_email'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                                {{-- identity preview --}}
                                <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 p-4">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Identity preview</p>
                                        <span class="bx-badge bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">Live</span>
                                    </div>
                                    <div class="mt-3 flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-lg">🛒</div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white" id="preview_store_name"></p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Admin + storefront branding</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── DESIGN ── --}}
                    <div data-tab-panel="design" class="bx-panel space-y-4 bx-hidden">

                        {{-- Logo / Favicon --}}
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Logo & favicon</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Upload or replace your store branding assets</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 p-5">
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Store logo</label>
                                    <div class="flex h-24 cursor-pointer items-center justify-center rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40 transition hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5">
                                        <div class="text-center">
                                            <svg class="mx-auto h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                            <p class="mt-1.5 text-[11px] text-gray-400 dark:text-gray-500">400 × 120 PNG / SVG</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Favicon</label>
                                    <div class="flex h-24 cursor-pointer items-center justify-center rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/40 transition hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5">
                                        <div class="text-center">
                                            <svg class="mx-auto h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                            <p class="mt-1.5 text-[11px] text-gray-400 dark:text-gray-500">32 × 32 PNG / ICO</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Theme --}}
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Theme</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Colors, sidebar and layout style</p>
                            </div>
                            <div class="space-y-5 p-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Primary color</label>
                                        <div class="flex h-9 items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-2">
                                            <input type="color" name="primary_color" id="input_primary_color_picker"
                                                value="{{ old('primary_color', $s['primary_color'] ?? '#5B5CEB') }}"
                                                class="h-6 w-6 cursor-pointer rounded border-0 bg-transparent p-0">
                                            <input type="text" id="input_primary_color_text"
                                                value="{{ old('primary_color', $s['primary_color'] ?? '#5B5CEB') }}"
                                                class="flex-1 bg-transparent font-mono text-xs text-gray-700 dark:text-gray-300 outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Secondary color</label>
                                        <div class="flex h-9 items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-2">
                                            <input type="color" name="secondary_color" id="input_secondary_color_picker"
                                                value="{{ old('secondary_color', $s['secondary_color'] ?? '#16A34A') }}"
                                                class="h-6 w-6 cursor-pointer rounded border-0 bg-transparent p-0">
                                            <input type="text" id="input_secondary_color_text"
                                                value="{{ old('secondary_color', $s['secondary_color'] ?? '#16A34A') }}"
                                                class="flex-1 bg-transparent font-mono text-xs text-gray-700 dark:text-gray-300 outline-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400">Sidebar style</label>
                                        <input type="hidden" name="sidebar_style" id="input_sidebar_style"
                                            value="{{ old('sidebar_style', $s['sidebar_style'] ?? 'light') }}">
                                        <div class="flex gap-2" id="sidebarStyleChoices">
                                            @foreach([['light','Light'],['dark','Dark'],['transparent','Glass']] as [$val,$label])
                                                <button type="button" data-style-choice="sidebar_style" data-value="{{ $val }}"
                                                    class="style-choice-btn flex-1 rounded-xl border border-gray-200 dark:border-gray-600 p-2 text-center transition">
                                                    <div class="mb-1.5 h-8 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                                                        @if($val === 'light')
                                                            <div class="flex h-full gap-1 p-1"><div class="w-5 rounded bg-white"></div><div class="flex-1 rounded bg-gray-200 dark:bg-gray-700"></div></div>
                                                        @elseif($val === 'dark')
                                                            <div class="flex h-full gap-1 bg-gray-800 p-1"><div class="w-5 rounded bg-gray-600"></div><div class="flex-1 rounded bg-gray-700"></div></div>
                                                        @else
                                                            <div class="flex h-full gap-1 p-1"><div class="w-5 rounded border border-dashed border-gray-300 dark:border-gray-600"></div><div class="flex-1 rounded bg-gray-200 dark:bg-gray-700"></div></div>
                                                        @endif
                                                    </div>
                                                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400">Layout</label>
                                        <input type="hidden" name="layout_style" id="input_layout_style"
                                            value="{{ old('layout_style', $s['layout_style'] ?? 'default') }}">
                                        <div class="flex gap-2" id="layoutStyleChoices">
                                            @foreach([['default','Default'],['boxed','Boxed'],['wide','Wide']] as [$val,$label])
                                                <button type="button" data-style-choice="layout_style" data-value="{{ $val }}"
                                                    class="style-choice-btn flex-1 rounded-xl border border-gray-200 dark:border-gray-600 p-2 text-center transition">
                                                    <div class="mb-1.5 h-8 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800 p-1">
                                                        @if($val === 'default')
                                                            <div class="flex h-full gap-1"><div class="w-4 rounded bg-indigo-200 dark:bg-indigo-500/30"></div><div class="flex-1 rounded bg-white dark:bg-gray-700"></div></div>
                                                        @elseif($val === 'boxed')
                                                            <div class="flex h-full items-center justify-center"><div class="h-5 w-10 rounded bg-white shadow-sm dark:bg-gray-700"></div></div>
                                                        @else
                                                            <div class="h-full rounded bg-white dark:bg-gray-700"></div>
                                                        @endif
                                                    </div>
                                                    <span class="text-[11px] font-medium text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Typography --}}
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Typography</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Font controls for admin interface and storefront</p>
                            </div>
                            <div class="space-y-4 p-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Font family</label>
                                        <select name="font_family"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                            @foreach(['Inter','Poppins','Nunito','Roboto'] as $font)
                                                <option value="{{ $font }}" @selected(old('font_family', $s['font_family'] ?? 'Inter') === $font)>{{ $font }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Base font size</label>
                                        <select name="base_font_size"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                            @foreach(['13px','14px','15px','16px'] as $size)
                                                <option value="{{ $size }}" @selected(old('base_font_size', $s['base_font_size'] ?? '14px') === $size)>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-600 px-3 py-2.5 transition hover:bg-gray-50 dark:hover:bg-gray-700/60">
                                    <input type="checkbox" name="compact_sidebar" value="1"
                                        @checked(old('compact_sidebar', $s['compact_sidebar'] ?? false))
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-800 dark:text-gray-100">Compact sidebar</span>
                                        <span class="block text-xs text-gray-400 dark:text-gray-500">Denser layout for large admin menus</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- ── LOCALIZATION ── --}}
                    <div data-tab-panel="localization" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Localization</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Timezone, language, currency and display formats</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 p-5">
                                @php
                                    $localFields = [
                                        ['name' => 'timezone',         'label' => 'Timezone',        'options' => ['Asia/Phnom_Penh','UTC','Asia/Bangkok']],
                                        ['name' => 'currency',         'label' => 'Currency',        'options' => ['USD','KHR']],
                                        ['name' => 'language',         'label' => 'Language',        'options' => ['en' => 'English','km' => 'Khmer']],
                                        ['name' => 'date_format',      'label' => 'Date format',     'options' => ['d M Y','d/m/Y','Y-m-d']],
                                        ['name' => 'time_format',      'label' => 'Time format',     'options' => ['12h' => '12 Hour','24h' => '24 Hour']],
                                    ];
                                @endphp

                                @foreach($localFields as $field)
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">{{ $field['label'] }}</label>
                                        <select name="{{ $field['name'] }}"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                            @foreach($field['options'] as $optVal => $optLabel)
                                                @php
                                                    $v = is_string($optVal) ? $optVal : $optLabel;
                                                    $l = $optLabel;
                                                @endphp
                                                <option value="{{ $v }}" @selected(old($field['name'], $s[$field['name']] ?? '') === $v)>{{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Default province</label>
                                    <input type="text" name="default_province"
                                        value="{{ old('default_province', $s['default_province'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── PAYMENT ── --}}
                    <div data-tab-panel="payment" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment methods</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Enable or disable checkout payment options</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 p-5">
                                @php
                                    $payments = [
                                        ['name' => 'cod_enabled',    'label' => 'Cash on delivery', 'desc' => 'Pay when receiving the order'],
                                        ['name' => 'aba_enabled',    'label' => 'ABA Pay',          'desc' => 'ABA bank payment flow'],
                                        ['name' => 'khqr_enabled',   'label' => 'Bakong KHQR',      'desc' => 'Accept KHQR in Cambodia'],
                                        ['name' => 'paypal_enabled', 'label' => 'PayPal',           'desc' => 'International customers'],
                                    ];
                                @endphp

                                @foreach($payments as $item)
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 dark:border-gray-600 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/60">
                                        <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                            @checked(old($item['name'], $s[$item['name']] ?? false))
                                            class="mt-0.5 h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-800 dark:text-gray-100">{{ $item['label'] }}</span>
                                            <span class="block text-xs text-gray-400 dark:text-gray-500">{{ $item['desc'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ── SHIPPING ── --}}
                    <div data-tab-panel="shipping" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Shipping settings</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Delivery fees, thresholds and expected ETA</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 p-5">
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Free shipping threshold ($)</label>
                                    <input type="number" step="0.01" name="free_shipping_threshold"
                                        value="{{ old('free_shipping_threshold', $s['free_shipping_threshold'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Default shipping fee ($)</label>
                                    <input type="number" step="0.01" name="default_shipping_fee"
                                        value="{{ old('default_shipping_fee', $s['default_shipping_fee'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Estimated delivery time</label>
                                    <input type="text" name="estimated_delivery_days"
                                        value="{{ old('estimated_delivery_days', $s['estimated_delivery_days'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── ORDERS ── --}}
                    <div data-tab-panel="orders" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order settings</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Automation and stock-related order behavior</p>
                            </div>
                            <div class="space-y-4 p-5">
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 dark:border-gray-600 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/60">
                                    <input type="checkbox" name="auto_confirm_orders" value="1"
                                        @checked(old('auto_confirm_orders', $s['auto_confirm_orders'] ?? false))
                                        class="mt-0.5 h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-800 dark:text-gray-100">Auto confirm new orders</span>
                                        <span class="block text-xs text-gray-400 dark:text-gray-500">Automatically move new orders to processing after checkout success</span>
                                    </div>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Auto-complete after (days)</label>
                                        <input type="number" name="auto_complete_after_days"
                                            value="{{ old('auto_complete_after_days', $s['auto_complete_after_days'] ?? 0) }}"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Low stock threshold</label>
                                        <input type="number" name="low_stock_threshold"
                                            value="{{ old('low_stock_threshold', $s['low_stock_threshold'] ?? 10) }}"
                                            class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── EMAIL ── --}}
                    <div data-tab-panel="email" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Email settings</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Default mail sender configuration</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 p-5">
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">From name</label>
                                    <input type="text" name="mail_from_name"
                                        value="{{ old('mail_from_name', $s['mail_from_name'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">From email</label>
                                    <input type="email" name="mail_from_email"
                                        value="{{ old('mail_from_email', $s['mail_from_email'] ?? '') }}"
                                        class="h-9 w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── SMS ── --}}
                    <div data-tab-panel="sms" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">SMS settings</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Sender identity for OTP and notifications</p>
                            </div>
                            <div class="p-5">
                                <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">SMS sender name</label>
                                <input type="text" name="sms_sender"
                                    value="{{ old('sms_sender', $s['sms_sender'] ?? '') }}"
                                    class="h-9 w-full max-w-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- ── PUSH ── --}}
                    <div data-tab-panel="push" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Push notifications</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">App push for staff and admin</p>
                            </div>
                            <div class="space-y-2 p-5">
                                @php
                                    $pushItems = [
                                        ['name' => 'push_enabled',   'label' => 'Enable push notifications', 'desc' => 'Master switch for all app push'],
                                        ['name' => 'order_push',     'label' => 'Order push',                'desc' => 'New orders, status changes and payment updates'],
                                        ['name' => 'stock_push',     'label' => 'Stock push',                'desc' => 'Low stock and inventory alerts'],
                                        ['name' => 'marketing_push', 'label' => 'Marketing push',            'desc' => 'Promo and campaign notifications'],
                                    ];
                                @endphp

                                @foreach($pushItems as $item)
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 dark:border-gray-600 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/60">
                                        <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                            @checked(old($item['name'], $s[$item['name']] ?? false))
                                            class="mt-0.5 h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-800 dark:text-gray-100">{{ $item['label'] }}</span>
                                            <span class="block text-xs text-gray-400 dark:text-gray-500">{{ $item['desc'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ── BACKUP ── --}}
                    <div data-tab-panel="backup" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Backup & restore</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Manage configuration snapshots and recovery actions</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 p-5">
                                <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 p-4">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Export settings</p>
                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Download a JSON backup of current settings</p>
                                    <button type="button" id="exportBackupBtn"
                                        class="action-btn mt-4 inline-flex h-8 items-center gap-1.5 rounded-lg border border-indigo-200 dark:border-indigo-500/20 bg-indigo-50 dark:bg-indigo-500/10 px-3 text-xs font-medium text-indigo-600 dark:text-indigo-400 transition hover:bg-indigo-100">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                        Export backup
                                    </button>
                                </div>
                                <div class="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 p-4">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Import settings</p>
                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Restore a previous settings snapshot</p>
                                    <button type="button"
                                        class="action-btn mt-4 inline-flex h-8 items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 text-xs font-medium text-gray-600 dark:text-gray-300 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                        Import backup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── LOGS ── --}}
                    <div data-tab-panel="logs" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">System logs</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Activity summary and recent events</p>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                @php
                                    $logs = [
                                        ['title' => 'Payment gateway synced',             'time' => '2 minutes ago',   'type' => 'success'],
                                        ['title' => 'Low stock alert — Product #104',     'time' => '20 minutes ago',  'type' => 'warning'],
                                        ['title' => 'Admin updated role permissions',     'time' => '1 hour ago',      'type' => 'info'],
                                        ['title' => 'Nightly backup completed',           'time' => 'Today 02:00 AM',  'type' => 'success'],
                                    ];
                                    $logDotColors = ['success' => 'bg-emerald-500', 'warning' => 'bg-amber-400', 'info' => 'bg-indigo-500'];
                                    $logBadgeClasses = [
                                        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                        'info'    => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
                                    ];
                                @endphp

                                @foreach($logs as $log)
                                    <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <span class="h-2 w-2 shrink-0 rounded-full {{ $logDotColors[$log['type']] }}"></span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $log['title'] }}</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $log['time'] }}</p>
                                            </div>
                                        </div>
                                        <span class="bx-badge {{ $logBadgeClasses[$log['type']] }}">
                                            {{ ucfirst($log['type']) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ── MAINTENANCE ── --}}
                    <div data-tab-panel="maintenance" class="bx-panel bx-hidden">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-5 py-4">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Maintenance</h2>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">System mode and public-facing message</p>
                            </div>
                            <div class="space-y-4 p-5">
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50/60 dark:bg-red-900/10 p-4 transition hover:bg-red-50">
                                    <input type="checkbox" name="maintenance_mode" value="1"
                                        @checked(old('maintenance_mode', $s['maintenance_mode'] ?? false))
                                        class="mt-0.5 h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500">
                                    <div>
                                        <span class="block text-sm font-medium text-red-700 dark:text-red-300">Enable maintenance mode</span>
                                        <span class="block text-xs text-red-500 dark:text-red-400">Only admins can access the panel while the storefront is under maintenance</span>
                                    </div>
                                </label>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">Maintenance message</label>
                                    <textarea name="maintenance_message" rows="4"
                                        class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white outline-none transition focus:ring-2 focus:ring-indigo-500">{{ old('maintenance_message', $s['maintenance_message'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom save button --}}
                    @can('edit_settings')
                        <div class="flex justify-end">
                            <button type="submit" id="footerSaveBtn"
                                class="action-btn inline-flex h-9 items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 text-sm font-medium text-white shadow-md shadow-indigo-500/25 transition-all">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Save all settings
                            </button>
                        </div>
                    @endcan

                </main>

                {{-- ─── RIGHT PREVIEW ─────────────────────────────────────── --}}
                <aside class="col-span-12 lg:col-span-3">
                    <div class="sticky top-4 space-y-4">

                        {{-- Live preview --}}
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-4 py-3.5">
                                <div>
                                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Live preview</h2>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Design changes appear here</p>
                                </div>
                                <span class="bx-badge bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">Live</span>
                            </div>
                            <div class="p-4">
                                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-600">
                                    {{-- App bar --}}
                                    <div id="preview_appbar" class="flex items-center gap-2 px-3 py-2.5 text-white transition-colors">
                                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-white/15 text-sm">🛒</div>
                                        <div>
                                            <p class="text-xs font-semibold leading-none" id="preview_appbar_name"></p>
                                            <p class="mt-0.5 text-[10px] text-white/70">Admin panel</p>
                                        </div>
                                    </div>
                                    {{-- Body --}}
                                    <div class="grid grid-cols-12 bg-white dark:bg-gray-800">
                                        {{-- Sidebar --}}
                                        <div id="preview_sidebar" class="col-span-4 min-h-[160px] border-r border-gray-100 dark:border-gray-700 p-2.5 transition-colors">
                                            <div class="space-y-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <div class="h-2 rounded-full bg-gray-200 dark:bg-gray-700" style="width: {{ 60 + $i * 8 }}%"></div>
                                                @endfor
                                            </div>
                                        </div>
                                        {{-- Content --}}
                                        <div class="col-span-8 p-2.5">
                                            <p class="mb-2 text-[10px] font-semibold text-gray-500 dark:text-gray-400">Dashboard</p>
                                            <div class="mb-2 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-2">
                                                <p class="text-[9px] text-gray-400 dark:text-gray-500">Total sales</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">$14,580</p>
                                                <p class="text-[9px] font-semibold" id="preview_sales_delta">+12.5%</p>
                                            </div>
                                            <div class="space-y-1.5 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-2">
                                                <p class="text-[9px] text-gray-400 dark:text-gray-500">Recent orders</p>
                                                @for ($i = 1; $i <= 3; $i++)
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="preview-order-dot h-1.5 w-1.5 shrink-0 rounded-full"></span>
                                                        <span class="h-1.5 flex-1 rounded-full bg-gray-200 dark:bg-gray-700"></span>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Color chips --}}
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    <div class="rounded-xl bg-gray-50 dark:bg-gray-700/60 p-2.5">
                                        <p class="mb-1.5 text-[10px] text-gray-400 dark:text-gray-500">Primary</p>
                                        <div class="flex items-center gap-1.5">
                                            <span id="preview_primary_chip" class="h-4 w-4 rounded border border-white shadow"></span>
                                            <span class="font-mono text-[11px] font-medium text-gray-700 dark:text-gray-300" id="preview_primary_hex"></span>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-gray-50 dark:bg-gray-700/60 p-2.5">
                                        <p class="mb-1.5 text-[10px] text-gray-400 dark:text-gray-500">Secondary</p>
                                        <div class="flex items-center gap-1.5">
                                            <span id="preview_secondary_chip" class="h-4 w-4 rounded border border-white shadow"></span>
                                            <span class="font-mono text-[11px] font-medium text-gray-700 dark:text-gray-300" id="preview_secondary_hex"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick actions --}}
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                            <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3.5">
                                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Quick actions</h2>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div class="p-4">
                                    <p class="text-xs font-medium text-gray-800 dark:text-gray-100">Reset to default</p>
                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Restore the default Darita Mart theme</p>
                                    <button type="button" id="quickResetBtn"
                                        class="action-btn mt-3 inline-flex h-7 items-center gap-1.5 rounded-lg border border-red-200 dark:border-red-500/20 bg-red-50 dark:bg-red-500/10 px-2.5 text-xs font-medium text-red-600 dark:text-red-400 transition hover:bg-red-100">
                                        Reset all
                                    </button>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-medium text-gray-800 dark:text-gray-100">Clear cache</p>
                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Refresh cached design assets</p>
                                    <button type="button" id="clearCacheBtn"
                                        class="action-btn mt-3 inline-flex h-7 items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 text-xs font-medium text-gray-600 dark:text-gray-300 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Clear cache
                                    </button>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-medium text-gray-800 dark:text-gray-100">Export settings</p>
                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Download your current settings snapshot</p>
                                    <button type="button" id="quickExportBtn"
                                        class="action-btn mt-3 inline-flex h-7 items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-2.5 text-xs font-medium text-gray-600 dark:text-gray-300 transition hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- View-only notice --}}
                        @cannot('edit_settings')
                            <div class="rounded-2xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-900/10 p-4">
                                <p class="text-xs font-semibold text-amber-700 dark:text-amber-400">View only</p>
                                <p class="mt-1.5 text-xs leading-relaxed text-amber-600 dark:text-amber-400/80">
                                    You have <code class="font-mono">view_settings</code> but not <code class="font-mono">edit_settings</code>. Saving changes is restricted.
                                </p>
                            </div>
                        @endcannot

                    </div>
                </aside>

            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (function () {
                const DEFAULTS = {
                    storeName: 'Darita Mart',
                    primaryColor: '#5B5CEB',
                    secondaryColor: '#16A34A',
                    sidebarStyle: 'light',
                    layoutStyle: 'default',
                };

                function show(el) { if (el) el.classList.remove('bx-hidden'); }
                function hide(el) { if (el) el.classList.add('bx-hidden'); }

                const SettingsPage = {
                    activeTab: 'general',

                    init() {
                        const url = new URL(window.location.href);
                        const tab = url.searchParams.get('tab');
                        if (tab) this.activeTab = tab;

                        this.bindEvents();
                        this.setActiveTab(this.activeTab);
                        this.refreshPreview();
                    },

                    bindEvents() {
                        // Sidebar tab buttons
                        document.querySelectorAll('[data-tab-btn]').forEach(btn => {
                            btn.addEventListener('click', () => this.setActiveTab(btn.dataset.tabBtn));
                        });

                        // Live preview: store name
                        const storeNameInput = document.getElementById('input_store_name');
                        storeNameInput.addEventListener('input', () => this.refreshPreview());

                        // Live preview: color pickers <-> text inputs, two-way sync
                        this.bindColorPair('input_primary_color_picker', 'input_primary_color_text');
                        this.bindColorPair('input_secondary_color_picker', 'input_secondary_color_text');

                        // Style-choice buttons (sidebar style / layout style)
                        document.querySelectorAll('[data-style-choice]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const group = btn.dataset.styleChoice;
                                const value = btn.dataset.value;
                                document.getElementById(`input_${group}`).value = value;

                                document.querySelectorAll(`[data-style-choice="${group}"]`).forEach(b => {
                                    b.classList.toggle('active', b === btn);
                                });

                                this.refreshPreview();
                            });
                        });

                        // Header + quick-action reset buttons
                        const resetBtn = document.getElementById('headerResetBtn');
                        if (resetBtn) resetBtn.addEventListener('click', () => this.resetToDefaults());
                        const quickResetBtn = document.getElementById('quickResetBtn');
                        if (quickResetBtn) quickResetBtn.addEventListener('click', () => this.resetToDefaults());

                        // Header save button submits the form
                        const headerSaveBtn = document.getElementById('headerSaveBtn');
                        if (headerSaveBtn) headerSaveBtn.addEventListener('click', () => {
                            document.getElementById('settingsForm')?.submit();
                        });

                        // Placeholder actions (no backend endpoint wired up yet)
                        const clearCacheBtn = document.getElementById('clearCacheBtn');
                        if (clearCacheBtn) clearCacheBtn.addEventListener('click', () => this.toast('Cache cleared.'));
                        const quickExportBtn = document.getElementById('quickExportBtn');
                        if (quickExportBtn) quickExportBtn.addEventListener('click', () => this.toast('Export started.'));
                        const exportBackupBtn = document.getElementById('exportBackupBtn');
                        if (exportBackupBtn) exportBackupBtn.addEventListener('click', () => this.toast('Export started.'));
                    },

                    bindColorPair(pickerId, textId) {
                        const picker = document.getElementById(pickerId);
                        const text = document.getElementById(textId);
                        if (!picker || !text) return;

                        picker.addEventListener('input', () => {
                            text.value = picker.value;
                            this.refreshPreview();
                        });
                        text.addEventListener('input', () => {
                            if (/^#([0-9A-Fa-f]{3}){1,2}$/.test(text.value)) {
                                picker.value = text.value;
                            }
                            this.refreshPreview();
                        });
                    },

                    setActiveTab(id) {
                        this.activeTab = id;

                        document.querySelectorAll('[data-tab-panel]').forEach(panel => {
                            if (panel.dataset.tabPanel === id) {
                                show(panel);
                            } else {
                                hide(panel);
                            }
                        });

                        document.querySelectorAll('[data-tab-btn]').forEach(btn => {
                            btn.classList.toggle('active', btn.dataset.tabBtn === id);
                        });
                    },

                    refreshPreview() {
                        const storeName = document.getElementById('input_store_name').value || DEFAULTS.storeName;
                        const primaryColor = document.getElementById('input_primary_color_text').value || DEFAULTS.primaryColor;
                        const secondaryColor = document.getElementById('input_secondary_color_text').value || DEFAULTS.secondaryColor;
                        const sidebarStyle = document.getElementById('input_sidebar_style').value || DEFAULTS.sidebarStyle;

                        document.getElementById('preview_store_name').textContent = storeName;
                        document.getElementById('preview_appbar_name').textContent = storeName;
                        document.getElementById('preview_appbar').style.background = primaryColor;

                        document.getElementById('preview_sales_delta').style.color = secondaryColor;
                        document.querySelectorAll('.preview-order-dot').forEach(dot => {
                            dot.style.background = secondaryColor;
                        });

                        document.getElementById('preview_primary_chip').style.background = primaryColor;
                        document.getElementById('preview_primary_hex').textContent = primaryColor;
                        document.getElementById('preview_secondary_chip').style.background = secondaryColor;
                        document.getElementById('preview_secondary_hex').textContent = secondaryColor;

                        const sidebarPreview = document.getElementById('preview_sidebar');
                        sidebarPreview.classList.remove('bg-gray-900', 'bg-transparent', 'bg-white', 'dark:bg-gray-800');
                        if (sidebarStyle === 'dark') {
                            sidebarPreview.classList.add('bg-gray-900');
                        } else if (sidebarStyle === 'transparent') {
                            sidebarPreview.classList.add('bg-transparent');
                        } else {
                            sidebarPreview.classList.add('bg-white', 'dark:bg-gray-800');
                        }
                    },

                    resetToDefaults() {
                        document.getElementById('input_store_name').value = DEFAULTS.storeName;
                        document.getElementById('input_primary_color_picker').value = DEFAULTS.primaryColor;
                        document.getElementById('input_primary_color_text').value = DEFAULTS.primaryColor;
                        document.getElementById('input_secondary_color_picker').value = DEFAULTS.secondaryColor;
                        document.getElementById('input_secondary_color_text').value = DEFAULTS.secondaryColor;
                        document.getElementById('input_sidebar_style').value = DEFAULTS.sidebarStyle;
                        document.getElementById('input_layout_style').value = DEFAULTS.layoutStyle;

                        document.querySelectorAll('[data-style-choice="sidebar_style"]').forEach(b => {
                            b.classList.toggle('active', b.dataset.value === DEFAULTS.sidebarStyle);
                        });
                        document.querySelectorAll('[data-style-choice="layout_style"]').forEach(b => {
                            b.classList.toggle('active', b.dataset.value === DEFAULTS.layoutStyle);
                        });

                        this.refreshPreview();
                        this.toast('Preview reset to defaults.');
                    },

                    toast(message) {
                        const wrap = document.getElementById('toastWrap');
                        if (!wrap) return;
                        const el = document.createElement('div');
                        el.className = 'toast';
                        el.innerHTML = `<span class="toast-dot" style="background:#10b981"></span><span>${message}</span>`;
                        wrap.appendChild(el);
                        setTimeout(() => {
                            el.classList.add('leaving');
                            setTimeout(() => el.remove(), 280);
                        }, 3200);
                    },
                };

                window.SettingsPage = SettingsPage;

                document.addEventListener('DOMContentLoaded', () => SettingsPage.init());
            })();
        </script>
    @endpush
@endsection