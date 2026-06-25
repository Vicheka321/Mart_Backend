@extends('layouts.app')

@section('content')
    @php $s = $settings ?? []; @endphp

    <div x-data="settingsPage()" x-init="init()" class="min-h-screen bg-slate-50 dark:bg-slate-950">

        {{-- Page Header --}}
        <div class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="mx-auto max-w-screen-xl px-6 py-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <span>Dashboard</span>
                            <span>/</span>
                            <span class="text-slate-600 dark:text-slate-300">Settings</span>
                        </div>
                        <h1 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">Settings</h1>
                    </div>

                    @can('edit_settings')
                        <div class="flex items-center gap-2">
                            <button type="button" @click="resetPreview()"
                                class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                                </svg>
                                Reset
                            </button>
                            <button type="button" @click="submitMainForm()"
                                class="inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white transition hover:bg-indigo-700">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Save settings
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-screen-xl px-6 py-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-800/40 dark:bg-rose-900/20 dark:text-rose-300">
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

                <div class="grid grid-cols-12 gap-5">

                    {{-- ─── LEFT SIDEBAR ──────────────────────────────────────── --}}
                    <aside class="col-span-12 lg:col-span-3">
                        <div class="sticky top-6 rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">

                            <div class="border-b border-slate-100 px-4 py-4 dark:border-slate-800">
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Menu</p>
                            </div>

                            <nav class="max-h-[78vh] overflow-y-auto p-2">

                                {{-- nav group helper --}}
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
                                        <p class="mb-1 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                                            {{ $groupName }}
                                        </p>

                                        @foreach($items as $item)
                                            <button type="button"
                                                @click="active = '{{ $item['id'] }}'"
                                                :class="active === '{{ $item['id'] }}'
                                                    ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400'
                                                    : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800'"
                                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm transition">
                                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">{!! $item['icon'] !!}</svg>
                                                </span>
                                                <span>
                                                    <span class="block text-[13px] font-medium leading-tight">{{ $item['label'] }}</span>
                                                    <span class="block text-[11px] text-slate-400 dark:text-slate-500">{{ $item['sub'] }}</span>
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
                        <div x-show="active === 'general'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">General information</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Core store identity and contact details</p>
                                </div>
                                <div class="space-y-4 p-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Store name</label>
                                            <input type="text" name="store_name"
                                                value="{{ old('store_name', $s['store_name'] ?? '') }}"
                                                x-model="preview.storeName"
                                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Store phone</label>
                                            <input type="text" name="store_phone"
                                                value="{{ old('store_phone', $s['store_phone'] ?? '') }}"
                                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Store email</label>
                                        <input type="email" name="store_email"
                                            value="{{ old('store_email', $s['store_email'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    {{-- identity preview --}}
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/50">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Identity preview</p>
                                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-400">Live</span>
                                        </div>
                                        <div class="mt-3 flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-800">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-lg">🛒</div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="preview.storeName"></p>
                                                <p class="text-xs text-slate-400">Admin + storefront branding</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── DESIGN ── --}}
                        <div x-show="active === 'design'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="space-y-4">

                                {{-- Logo / Favicon --}}
                                <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Logo & favicon</h2>
                                        <p class="mt-0.5 text-xs text-slate-400">Upload or replace your store branding assets</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 p-5">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Store logo</label>
                                            <div class="flex h-24 cursor-pointer items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 transition hover:border-indigo-400 hover:bg-indigo-50/30 dark:border-slate-700 dark:bg-slate-800/50">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                                    <p class="mt-1.5 text-[11px] text-slate-400">400 × 120 PNG / SVG</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Favicon</label>
                                            <div class="flex h-24 cursor-pointer items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 transition hover:border-indigo-400 hover:bg-indigo-50/30 dark:border-slate-700 dark:bg-slate-800/50">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                                    <p class="mt-1.5 text-[11px] text-slate-400">32 × 32 PNG / ICO</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Theme --}}
                                <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Theme</h2>
                                        <p class="mt-0.5 text-xs text-slate-400">Colors, sidebar and layout style</p>
                                    </div>
                                    <div class="space-y-5 p-5">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Primary color</label>
                                                <div class="flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-2 dark:border-slate-700 dark:bg-slate-800">
                                                    <input type="color" name="primary_color"
                                                        value="{{ old('primary_color', $s['primary_color'] ?? '#5B5CEB') }}"
                                                        x-model="preview.primaryColor"
                                                        class="h-6 w-6 cursor-pointer rounded border-0 bg-transparent p-0">
                                                    <input type="text" x-model="preview.primaryColor"
                                                        class="flex-1 bg-transparent font-mono text-xs text-slate-700 outline-none dark:text-slate-300">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Secondary color</label>
                                                <div class="flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-2 dark:border-slate-700 dark:bg-slate-800">
                                                    <input type="color" name="secondary_color"
                                                        value="{{ old('secondary_color', $s['secondary_color'] ?? '#16A34A') }}"
                                                        x-model="preview.secondaryColor"
                                                        class="h-6 w-6 cursor-pointer rounded border-0 bg-transparent p-0">
                                                    <input type="text" x-model="preview.secondaryColor"
                                                        class="flex-1 bg-transparent font-mono text-xs text-slate-700 outline-none dark:text-slate-300">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="mb-2 block text-xs font-medium text-slate-600 dark:text-slate-400">Sidebar style</label>
                                                <input type="hidden" name="sidebar_style" x-model="preview.sidebarStyle">
                                                <div class="flex gap-2">
                                                    @foreach([['light','Light'],['dark','Dark'],['transparent','Glass']] as [$val,$label])
                                                        <button type="button" @click="preview.sidebarStyle = '{{ $val }}'"
                                                            :class="preview.sidebarStyle === '{{ $val }}'
                                                                ? 'border-indigo-400 ring-2 ring-indigo-400/20'
                                                                : 'border-slate-200 dark:border-slate-700 hover:border-slate-300'"
                                                            class="flex-1 rounded-lg border p-2 text-center transition">
                                                            <div class="mb-1.5 h-8 overflow-hidden rounded bg-slate-100 dark:bg-slate-800">
                                                                @if($val === 'light')
                                                                    <div class="flex h-full gap-1 p-1"><div class="w-5 rounded bg-white"></div><div class="flex-1 rounded bg-slate-200 dark:bg-slate-700"></div></div>
                                                                @elseif($val === 'dark')
                                                                    <div class="flex h-full gap-1 bg-slate-800 p-1"><div class="w-5 rounded bg-slate-600"></div><div class="flex-1 rounded bg-slate-700"></div></div>
                                                                @else
                                                                    <div class="flex h-full gap-1 p-1"><div class="w-5 rounded border border-dashed border-slate-300 dark:border-slate-600"></div><div class="flex-1 rounded bg-slate-200 dark:bg-slate-700"></div></div>
                                                                @endif
                                                            </div>
                                                            <span class="text-[11px] font-medium text-slate-600 dark:text-slate-300">{{ $label }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-xs font-medium text-slate-600 dark:text-slate-400">Layout</label>
                                                <input type="hidden" name="layout_style" x-model="preview.layoutStyle">
                                                <div class="flex gap-2">
                                                    @foreach([['default','Default'],['boxed','Boxed'],['wide','Wide']] as [$val,$label])
                                                        <button type="button" @click="preview.layoutStyle = '{{ $val }}'"
                                                            :class="preview.layoutStyle === '{{ $val }}'
                                                                ? 'border-indigo-400 ring-2 ring-indigo-400/20'
                                                                : 'border-slate-200 dark:border-slate-700 hover:border-slate-300'"
                                                            class="flex-1 rounded-lg border p-2 text-center transition">
                                                            <div class="mb-1.5 h-8 overflow-hidden rounded bg-slate-100 p-1 dark:bg-slate-800">
                                                                @if($val === 'default')
                                                                    <div class="flex h-full gap-1"><div class="w-4 rounded bg-indigo-200 dark:bg-indigo-500/30"></div><div class="flex-1 rounded bg-white dark:bg-slate-700"></div></div>
                                                                @elseif($val === 'boxed')
                                                                    <div class="flex h-full items-center justify-center"><div class="h-5 w-10 rounded bg-white shadow-sm dark:bg-slate-700"></div></div>
                                                                @else
                                                                    <div class="h-full rounded bg-white dark:bg-slate-700"></div>
                                                                @endif
                                                            </div>
                                                            <span class="text-[11px] font-medium text-slate-600 dark:text-slate-300">{{ $label }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Typography --}}
                                <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Typography</h2>
                                        <p class="mt-0.5 text-xs text-slate-400">Font controls for admin interface and storefront</p>
                                    </div>
                                    <div class="space-y-4 p-5">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Font family</label>
                                                <select name="font_family" x-model="preview.fontFamily"
                                                    class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                                    @foreach(['Inter','Poppins','Nunito','Roboto'] as $font)
                                                        <option value="{{ $font }}" @selected(old('font_family', $s['font_family'] ?? 'Inter') === $font)>{{ $font }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Base font size</label>
                                                <select name="base_font_size" x-model="preview.fontSize"
                                                    class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                                    @foreach(['13px','14px','15px','16px'] as $size)
                                                        <option value="{{ $size }}" @selected(old('base_font_size', $s['base_font_size'] ?? '14px') === $size)>{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 px-3 py-2.5 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800">
                                            <input type="checkbox" name="compact_sidebar" value="1"
                                                @checked(old('compact_sidebar', $s['compact_sidebar'] ?? false))
                                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400">
                                            <div>
                                                <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">Compact sidebar</span>
                                                <span class="block text-xs text-slate-400">Denser layout for large admin menus</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ── LOCALIZATION ── --}}
                        <div x-show="active === 'localization'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Localization</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Timezone, language, currency and display formats</p>
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
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">{{ $field['label'] }}</label>
                                            <select name="{{ $field['name'] }}"
                                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                                @foreach($field['options'] as $optVal => $optLabel)
                                                    @php
                                                        $v = is_string($optVal) ? $optVal : $optLabel;
                                                        $l = is_string($optVal) ? $optLabel : $optLabel;
                                                    @endphp
                                                    <option value="{{ $v }}" @selected(old($field['name'], $s[$field['name']] ?? '') === $v)>{{ $l }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach

                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Default province</label>
                                        <input type="text" name="default_province"
                                            value="{{ old('default_province', $s['default_province'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PAYMENT ── --}}
                        <div x-show="active === 'payment'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Payment methods</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Enable or disable checkout payment options</p>
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
                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60">
                                            <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                                @checked(old($item['name'], $s[$item['name']] ?? false))
                                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400">
                                            <div>
                                                <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">{{ $item['label'] }}</span>
                                                <span class="block text-xs text-slate-400">{{ $item['desc'] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── SHIPPING ── --}}
                        <div x-show="active === 'shipping'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Shipping settings</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Delivery fees, thresholds and expected ETA</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 p-5">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Free shipping threshold ($)</label>
                                        <input type="number" step="0.01" name="free_shipping_threshold"
                                            value="{{ old('free_shipping_threshold', $s['free_shipping_threshold'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Default shipping fee ($)</label>
                                        <input type="number" step="0.01" name="default_shipping_fee"
                                            value="{{ old('default_shipping_fee', $s['default_shipping_fee'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Estimated delivery time</label>
                                        <input type="text" name="estimated_delivery_days"
                                            value="{{ old('estimated_delivery_days', $s['estimated_delivery_days'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── ORDERS ── --}}
                        <div x-show="active === 'orders'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Order settings</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Automation and stock-related order behavior</p>
                                </div>
                                <div class="space-y-4 p-5">
                                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60">
                                        <input type="checkbox" name="auto_confirm_orders" value="1"
                                            @checked(old('auto_confirm_orders', $s['auto_confirm_orders'] ?? false))
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400">
                                        <div>
                                            <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">Auto confirm new orders</span>
                                            <span class="block text-xs text-slate-400">Automatically move new orders to processing after checkout success</span>
                                        </div>
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Auto-complete after (days)</label>
                                            <input type="number" name="auto_complete_after_days"
                                                value="{{ old('auto_complete_after_days', $s['auto_complete_after_days'] ?? 0) }}"
                                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Low stock threshold</label>
                                            <input type="number" name="low_stock_threshold"
                                                value="{{ old('low_stock_threshold', $s['low_stock_threshold'] ?? 10) }}"
                                                class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── EMAIL ── --}}
                        <div x-show="active === 'email'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Email settings</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Default mail sender configuration</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 p-5">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">From name</label>
                                        <input type="text" name="mail_from_name"
                                            value="{{ old('mail_from_name', $s['mail_from_name'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">From email</label>
                                        <input type="email" name="mail_from_email"
                                            value="{{ old('mail_from_email', $s['mail_from_email'] ?? '') }}"
                                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── SMS ── --}}
                        <div x-show="active === 'sms'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">SMS settings</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Sender identity for OTP and notifications</p>
                                </div>
                                <div class="p-5">
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">SMS sender name</label>
                                    <input type="text" name="sms_sender"
                                        value="{{ old('sms_sender', $s['sms_sender'] ?? '') }}"
                                        class="h-9 w-full max-w-sm rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                </div>
                            </div>
                        </div>

                        {{-- ── PUSH ── --}}
                        <div x-show="active === 'push'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Push notifications</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">App push for staff and admin</p>
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
                                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800/60">
                                            <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                                @checked(old($item['name'], $s[$item['name']] ?? false))
                                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400">
                                            <div>
                                                <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">{{ $item['label'] }}</span>
                                                <span class="block text-xs text-slate-400">{{ $item['desc'] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── BACKUP ── --}}
                        <div x-show="active === 'backup'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Backup & restore</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Manage configuration snapshots and recovery actions</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 p-5">
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/50">
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Export settings</p>
                                        <p class="mt-1 text-xs text-slate-400">Download a JSON backup of current settings</p>
                                        <button type="button"
                                            class="mt-4 inline-flex h-8 items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 text-xs font-medium text-indigo-600 transition hover:bg-indigo-100 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-400">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                            Export backup
                                        </button>
                                    </div>
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-800/50">
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Import settings</p>
                                        <p class="mt-1 text-xs text-slate-400">Restore a previous settings snapshot</p>
                                        <button type="button"
                                            class="mt-4 inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                            Import backup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── LOGS ── --}}
                        <div x-show="active === 'logs'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">System logs</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">Activity summary and recent events</p>
                                </div>
                                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @php
                                        $logs = [
                                            ['title' => 'Payment gateway synced',             'time' => '2 minutes ago',   'type' => 'success'],
                                            ['title' => 'Low stock alert — Product #104',     'time' => '20 minutes ago',  'type' => 'warning'],
                                            ['title' => 'Admin updated role permissions',     'time' => '1 hour ago',      'type' => 'info'],
                                            ['title' => 'Nightly backup completed',           'time' => 'Today 02:00 AM',  'type' => 'success'],
                                        ];
                                        $logColors = ['success' => 'bg-emerald-500', 'warning' => 'bg-amber-400', 'info' => 'bg-indigo-500'];
                                        $logBadges = ['success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400', 'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400', 'info' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400'];
                                    @endphp

                                    @foreach($logs as $log)
                                        <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <span class="h-2 w-2 shrink-0 rounded-full {{ $logColors[$log['type']] }}"></span>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $log['title'] }}</p>
                                                    <p class="text-xs text-slate-400">{{ $log['time'] }}</p>
                                                </div>
                                            </div>
                                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-medium {{ $logBadges[$log['type']] }}">
                                                {{ ucfirst($log['type']) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── MAINTENANCE ── --}}
                        <div x-show="active === 'maintenance'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Maintenance</h2>
                                    <p class="mt-0.5 text-xs text-slate-400">System mode and public-facing message</p>
                                </div>
                                <div class="space-y-4 p-5">
                                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-rose-200 bg-rose-50/60 p-4 transition hover:bg-rose-50 dark:border-rose-900/40 dark:bg-rose-900/10">
                                        <input type="checkbox" name="maintenance_mode" value="1"
                                            @checked(old('maintenance_mode', $s['maintenance_mode'] ?? false))
                                            class="mt-0.5 h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-400">
                                        <div>
                                            <span class="block text-sm font-medium text-rose-700 dark:text-rose-300">Enable maintenance mode</span>
                                            <span class="block text-xs text-rose-500 dark:text-rose-400">Only admins can access the panel while the storefront is under maintenance</span>
                                        </div>
                                    </label>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-400">Maintenance message</label>
                                        <textarea name="maintenance_message" rows="4"
                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('maintenance_message', $s['maintenance_message'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bottom save button --}}
                        @can('edit_settings')
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    Save all settings
                                </button>
                            </div>
                        @endcan

                    </main>

                    {{-- ─── RIGHT PREVIEW ─────────────────────────────────────── --}}
                    <aside class="col-span-12 lg:col-span-3">
                        <div class="sticky top-6 space-y-4">

                            {{-- Live preview --}}
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3.5 dark:border-slate-800">
                                    <div>
                                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Live preview</h2>
                                        <p class="text-xs text-slate-400">Design changes appear here</p>
                                    </div>
                                    <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-400">Live</span>
                                </div>
                                <div class="p-4">
                                    <div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
                                        {{-- App bar --}}
                                        <div class="flex items-center gap-2 px-3 py-2.5 text-white transition-colors" :style="`background: ${preview.primaryColor}`">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-white/15 text-sm">🛒</div>
                                            <div>
                                                <p class="text-xs font-semibold leading-none" x-text="preview.storeName"></p>
                                                <p class="mt-0.5 text-[10px] text-white/70">Admin panel</p>
                                            </div>
                                        </div>
                                        {{-- Body --}}
                                        <div class="grid grid-cols-12 bg-white dark:bg-slate-900">
                                            {{-- Sidebar --}}
                                            <div class="col-span-4 min-h-[160px] border-r border-slate-100 p-2.5 transition-colors dark:border-slate-800"
                                                :class="{
                                                    'bg-slate-900': preview.sidebarStyle === 'dark',
                                                    'bg-transparent': preview.sidebarStyle === 'transparent',
                                                    'bg-white dark:bg-slate-900': preview.sidebarStyle === 'light'
                                                }">
                                                <div class="space-y-2">
                                                    <template x-for="i in 5" :key="i">
                                                        <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-700"
                                                            :style="`width: ${60 + i * 8}%`"></div>
                                                    </template>
                                                </div>
                                            </div>
                                            {{-- Content --}}
                                            <div class="col-span-8 p-2.5">
                                                <p class="mb-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400">Dashboard</p>
                                                <div class="mb-2 rounded-md border border-slate-100 bg-slate-50 p-2 dark:border-slate-800 dark:bg-slate-800">
                                                    <p class="text-[9px] text-slate-400">Total sales</p>
                                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">$14,580</p>
                                                    <p class="text-[9px] font-semibold" :style="`color: ${preview.secondaryColor}`">+12.5%</p>
                                                </div>
                                                <div class="space-y-1.5 rounded-md border border-slate-100 bg-slate-50 p-2 dark:border-slate-800 dark:bg-slate-800">
                                                    <p class="text-[9px] text-slate-400">Recent orders</p>
                                                    <template x-for="i in 3" :key="i">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="h-1.5 w-1.5 shrink-0 rounded-full" :style="`background: ${preview.secondaryColor}`"></span>
                                                            <span class="h-1.5 flex-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Color chips --}}
                                    <div class="mt-3 grid grid-cols-2 gap-2">
                                        <div class="rounded-lg bg-slate-50 p-2.5 dark:bg-slate-800/60">
                                            <p class="mb-1.5 text-[10px] text-slate-400">Primary</p>
                                            <div class="flex items-center gap-1.5">
                                                <span class="h-4 w-4 rounded border border-white shadow" :style="`background: ${preview.primaryColor}`"></span>
                                                <span class="font-mono text-[11px] font-medium text-slate-700 dark:text-slate-300" x-text="preview.primaryColor"></span>
                                            </div>
                                        </div>
                                        <div class="rounded-lg bg-slate-50 p-2.5 dark:bg-slate-800/60">
                                            <p class="mb-1.5 text-[10px] text-slate-400">Secondary</p>
                                            <div class="flex items-center gap-1.5">
                                                <span class="h-4 w-4 rounded border border-white shadow" :style="`background: ${preview.secondaryColor}`"></span>
                                                <span class="font-mono text-[11px] font-medium text-slate-700 dark:text-slate-300" x-text="preview.secondaryColor"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Quick actions --}}
                            <div class="rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-4 py-3.5 dark:border-slate-800">
                                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Quick actions</h2>
                                </div>
                                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <div class="p-4">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-100">Reset to default</p>
                                        <p class="mt-0.5 text-xs text-slate-400">Restore the default Darita Mart theme</p>
                                        <button type="button" @click="resetPreview()"
                                            class="mt-3 inline-flex h-7 items-center gap-1.5 rounded-md border border-rose-200 bg-rose-50 px-2.5 text-xs font-medium text-rose-600 transition hover:bg-rose-100 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400">
                                            Reset all
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-100">Clear cache</p>
                                        <p class="mt-0.5 text-xs text-slate-400">Refresh cached design assets</p>
                                        <button type="button"
                                            class="mt-3 inline-flex h-7 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            Clear cache
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-100">Export settings</p>
                                        <p class="mt-0.5 text-xs text-slate-400">Download your current settings snapshot</p>
                                        <button type="button"
                                            class="mt-3 inline-flex h-7 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            Export
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- View-only notice --}}
                            @cannot('edit_settings')
                                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/40 dark:bg-amber-900/10">
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
    </div>
@endsection

@push('scripts')
<script>
function settingsPage() {
    return {
        active: 'general',

        preview: {
            storeName:      @json(old('store_name',     $s['store_name']     ?? 'Darita Mart')),
            primaryColor:   @json(old('primary_color',  $s['primary_color']  ?? '#5B5CEB')),
            secondaryColor: @json(old('secondary_color',$s['secondary_color'] ?? '#16A34A')),
            sidebarStyle:   @json(old('sidebar_style',  $s['sidebar_style']  ?? 'light')),
            layoutStyle:    @json(old('layout_style',   $s['layout_style']   ?? 'default')),
            fontFamily:     @json(old('font_family',    $s['font_family']    ?? 'Inter')),
            fontSize:       @json(old('base_font_size', $s['base_font_size'] ?? '14px')),
        },

        defaults: {
            storeName:      'Darita Mart',
            primaryColor:   '#5B5CEB',
            secondaryColor: '#16A34A',
            sidebarStyle:   'light',
            layoutStyle:    'default',
            fontFamily:     'Inter',
            fontSize:       '14px',
        },

        init() {
            const tab = new URL(window.location.href).searchParams.get('tab');
            if (tab) this.active = tab;
        },

        submitMainForm() {
            document.getElementById('settingsForm')?.submit();
        },

        resetPreview() {
            this.preview = { ...this.defaults };
        },
    }
}
</script>
@endpush