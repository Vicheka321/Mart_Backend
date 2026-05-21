@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════
           KEYFRAMES
        ══════════════════════════════════════════ */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideLeft {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes cardPop {
            from { opacity: 0; transform: scale(0.94) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes progressFill {
            from { width: 0 !important; }
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .4; transform: scale(1.7); }
        }
        @keyframes phoneFloat {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-5px); }
        }
        @keyframes notifBounce {
            0%   { opacity: 0; transform: translateY(-12px) scale(.9); }
            60%  { opacity: 1; transform: translateY(3px) scale(1.02); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes rowSlideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes statNumPop {
            0%   { opacity: 0; transform: scale(.7); }
            70%  { transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes sendPulse {
            0%   { box-shadow: 0 0 0 0 rgba(139,92,246,.55); }
            70%  { box-shadow: 0 0 0 10px rgba(139,92,246,0); }
            100% { box-shadow: 0 0 0 0 rgba(139,92,246,0); }
        }
        @keyframes shimmerSlide {
            from { background-position: -200% 0; }
            to   { background-position: 200% 0; }
        }
        @keyframes barFill {
            from { width: 0 !important; }
        }
        @keyframes iconSpin {
            from { transform: rotate(-15deg) scale(.8); opacity:0; }
            to   { transform: rotate(0deg)  scale(1);  opacity:1; }
        }

        /* ── Page sections ── */
        .notif-header   { animation: fadeSlideUp .45s .05s cubic-bezier(.22,1,.36,1) both; }
        .notif-compose  { animation: cardPop .55s .08s cubic-bezier(.22,1,.36,1) both; }
        .notif-recent   { animation: fadeSlideUp .5s .22s cubic-bezier(.22,1,.36,1) both; }
        .notif-preview  { animation: fadeSlideLeft .5s .14s cubic-bezier(.22,1,.36,1) both; }
        .notif-stats    { animation: fadeSlideLeft .5s .24s cubic-bezier(.22,1,.36,1) both; }
        .notif-delivery { animation: fadeSlideLeft .5s .34s cubic-bezier(.22,1,.36,1) both; }

        /* ── Stat micro-cards ── */
        .stat-mini { animation: cardPop .45s ease both; }
        .stat-mini:nth-child(1) { animation-delay: .26s; }
        .stat-mini:nth-child(2) { animation-delay: .32s; }
        .stat-mini:nth-child(3) { animation-delay: .38s; }
        .stat-mini:nth-child(4) { animation-delay: .44s; }

        .stat-mini h2 { animation: statNumPop .4s cubic-bezier(.34,1.56,.64,1) both; }
        .stat-mini:nth-child(1) h2 { animation-delay: .55s; }
        .stat-mini:nth-child(2) h2 { animation-delay: .61s; }
        .stat-mini:nth-child(3) h2 { animation-delay: .67s; }
        .stat-mini:nth-child(4) h2 { animation-delay: .73s; }

        /* ── Recent rows ── */
        .recent-row { animation: rowSlideIn .32s ease both; }
        .recent-row:nth-child(1) { animation-delay: .28s; }
        .recent-row:nth-child(2) { animation-delay: .34s; }
        .recent-row:nth-child(3) { animation-delay: .40s; }
        .recent-row:nth-child(4) { animation-delay: .46s; }

        /* ── Phone + notif preview ── */
        .phone-frame { animation: phoneFloat 4s 1s ease-in-out infinite; }
        .preview-toast { animation: notifBounce .5s .9s cubic-bezier(.34,1.3,.64,1) both; }

        /* ── Progress bars ── */
        .bar-anim { animation: barFill .9s .8s cubic-bezier(.4,0,.2,1) both; }

        /* ── Pulse dot ── */
        .pulse-dot { animation: pulseDot 2.2s ease-in-out infinite; }

        /* ── Send button pulse on hover ── */
        .send-btn:hover { animation: sendPulse .8s ease; }

        /* ── Input focus ring color ── */
        .vfocus:focus { box-shadow: 0 0 0 3px rgba(139,92,246,.18); }

        /* ── Hover cards lift ── */
        .hover-lift {
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
        }

        /* ── Action button ── */
        .action-btn { transition: transform .14s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: scale(.96); }

        /* ── Radio card checked animation ── */
        input[type="radio"].sr-only:checked + div {
            transition: border-color .15s ease, background-color .15s ease;
        }

        /* ── Icon entry ── */
        .icon-entry { animation: iconSpin .4s cubic-bezier(.34,1.56,.64,1) both; }

        /* ── Compose section fields stagger ── */
        .field-row { animation: fadeSlideUp .35s ease both; }
        .field-row:nth-child(1) { animation-delay: .18s; }
        .field-row:nth-child(2) { animation-delay: .24s; }
        .field-row:nth-child(3) { animation-delay: .30s; }
        .field-row:nth-child(4) { animation-delay: .36s; }
        .field-row:nth-child(5) { animation-delay: .42s; }
        .field-row:nth-child(6) { animation-delay: .48s; }
        .field-row:nth-child(7) { animation-delay: .54s; }

        /* ── Delivery rate bars ── */
        .delivery-row { animation: rowSlideIn .35s ease both; }
        .delivery-row:nth-child(1) { animation-delay: .40s; }
        .delivery-row:nth-child(2) { animation-delay: .50s; }
        .delivery-row:nth-child(3) { animation-delay: .60s; }

        /* ── Responsive tweaks ── */
        @media (max-width: 640px) {
            .stat-mini h2 { font-size: 1.15rem; }
        }
    </style>

    <div class="space-y-4">

        {{-- ══════════════ GRID ══════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ════════════ LEFT: Compose + Recent ════════════ --}}
            <div class="lg:col-span-2 flex flex-col gap-4">

                {{-- ── Compose Card ── --}}
                <div class="notif-compose relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm p-5">

                    {{-- Decorative blob --}}
                    <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full pointer-events-none
                                bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-100
                                dark:from-violet-900/20 dark:via-purple-900/20 dark:to-fuchsia-900/20"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 rounded-full pointer-events-none
                                bg-gradient-to-br from-indigo-50 to-violet-100 dark:from-indigo-900/10 dark:to-violet-900/10 opacity-60"></div>

                    {{-- Card title --}}
                    <div class="relative flex items-center gap-2.5 mb-5">
                        <div class="icon-entry w-9 h-9 rounded-xl
                                    bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500
                                    flex items-center justify-center shadow-md shadow-violet-500/30 shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">Compose Notification</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">Send to your app users</p>
                        </div>

                        {{-- FCM status badge --}}
                        <div class="ml-auto flex items-center gap-1.5 px-2.5 py-1 rounded-xl
                                    bg-emerald-50 dark:bg-emerald-900/20
                                    border border-emerald-100 dark:border-emerald-800/50">
                            <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 hidden sm:inline">FCM Live</span>
                        </div>
                    </div>

                    <form action="#" method="POST" class="space-y-4 relative">
                        @csrf

                        {{-- Target Audience --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Target Audience</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach([
                                        ['all', 'All Users', 'from-violet-500 to-fuchsia-500', 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z'],
                                        ['customers', 'Customers', 'from-blue-500 to-indigo-500', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                                        ['active', 'Active', 'from-emerald-500 to-teal-500', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        ['inactive', 'Inactive', 'from-amber-500 to-orange-500', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ] as [$val, $label, $grad, $path])
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="target" value="{{ $val }}" class="peer sr-only"
                                                   {{ $val === 'all' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-2 p-2.5 rounded-xl
                                                        border border-gray-100 dark:border-gray-700
                                                        bg-gray-50 dark:bg-gray-700/50
                                                        peer-checked:border-violet-300 dark:peer-checked:border-violet-600
                                                        peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20
                                                        group-hover:border-violet-200 dark:group-hover:border-violet-700
                                                        transition-all duration-150">
                                                <div class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $grad }}
                                                            flex items-center justify-center shrink-0 shadow-sm">
                                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                                                    </svg>
                                                </div>
                                                <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300
                                                             peer-checked:text-violet-600 dark:peer-checked:text-violet-400 leading-tight">
                                                    {{ $label }}
                                                </span>
                                            </div>
                                        </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Type --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Notification Type</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach([
                                        ['general', 'General', '🔔'],
                                        ['promo', 'Promotion', '🎁'],
                                        ['order', 'Order', '📦'],
                                        ['alert', 'Alert', '⚠️'],
                                    ] as [$val, $label, $emoji])
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="type" value="{{ $val }}" class="peer sr-only"
                                                   {{ $val === 'general' ? 'checked' : '' }}>
                                            <div class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl
                                                        border border-gray-100 dark:border-gray-700
                                                        bg-gray-50 dark:bg-gray-700/50
                                                        peer-checked:border-violet-300 dark:peer-checked:border-violet-600
                                                        peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20
                                                        group-hover:border-violet-200 dark:group-hover:border-violet-700
                                                        transition-all duration-150">
                                                <span class="text-sm leading-none">{{ $emoji }}</span>
                                                <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                            </div>
                                        </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Title</label>
                            <input type="text" name="title" id="notifTitle" placeholder="Notification title…"
                                   class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                          bg-gray-50 dark:bg-gray-700/50
                                          border border-gray-100 dark:border-gray-700
                                          text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600
                                          focus:outline-none transition-all" required>
                        </div>

                        {{-- Message --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Message</label>
                            <textarea name="message" id="notifMessage" rows="3"
                                      placeholder="Write your notification message…"
                                      class="vfocus w-full px-3 py-2.5 rounded-xl text-sm resize-none
                                             bg-gray-50 dark:bg-gray-700/50
                                             border border-gray-100 dark:border-gray-700
                                             text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600
                                             focus:outline-none transition-all" required></textarea>
                            <p class="mt-1 text-[10px] text-gray-300 dark:text-gray-600 text-right" id="charCount">0 / 200</p>
                        </div>

                        {{-- Image URL --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">
                                Image URL <span class="normal-case font-normal">(optional)</span>
                            </label>
                            <input type="url" name="image_url" placeholder="https://…"
                                   class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                          bg-gray-50 dark:bg-gray-700/50
                                          border border-gray-100 dark:border-gray-700
                                          text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600
                                          focus:outline-none transition-all">
                        </div>

                        {{-- Schedule --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Schedule</label>
                            <div class="flex gap-2">
                                <label class="relative cursor-pointer flex-1 group">
                                    <input type="radio" name="schedule" value="now" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl
                                                border border-gray-100 dark:border-gray-700
                                                bg-gray-50 dark:bg-gray-700/50
                                                peer-checked:border-violet-300 dark:peer-checked:border-violet-600
                                                peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20
                                                group-hover:border-violet-200 dark:group-hover:border-violet-700
                                                transition-all duration-150">
                                        <svg class="w-3 h-3 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300">Send Now</span>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer flex-1 group">
                                    <input type="radio" name="schedule" value="later" class="peer sr-only" id="scheduleLater">
                                    <div class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl
                                                border border-gray-100 dark:border-gray-700
                                                bg-gray-50 dark:bg-gray-700/50
                                                peer-checked:border-violet-300 dark:peer-checked:border-violet-600
                                                peer-checked:bg-violet-50 dark:peer-checked:bg-violet-900/20
                                                group-hover:border-violet-200 dark:group-hover:border-violet-700
                                                transition-all duration-150">
                                        <svg class="w-3 h-3 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300">Schedule</span>
                                    </div>
                                </label>
                            </div>

                            <div id="scheduleBox" class="hidden mt-2">
                                <input type="datetime-local" name="scheduled_at"
                                       class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                              bg-gray-50 dark:bg-gray-700/50
                                              border border-gray-100 dark:border-gray-700
                                              text-gray-800 dark:text-gray-200
                                              focus:outline-none transition-all">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="field-row flex items-center gap-2 pt-1">
                            <button type="submit"
                                class="send-btn action-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                                       bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500
                                       text-white text-sm font-semibold
                                       shadow-lg shadow-violet-500/25
                                       hover:shadow-xl hover:shadow-violet-500/35
                                       hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Notification
                            </button>
                            <button type="reset"
                                class="action-btn px-4 py-2.5 rounded-xl text-sm font-semibold
                                       bg-gray-50 dark:bg-gray-700/50
                                       border border-gray-100 dark:border-gray-700
                                       text-gray-500 dark:text-gray-400
                                       hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                Clear
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Recently Sent ── --}}
                <div class="notif-recent relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recently Sent</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">Last notifications dispatched</p>
                        </div>
                        <a href="#" class="text-[10px] font-semibold text-violet-600 dark:text-violet-400 hover:underline">View all →</a>
                    </div>

                    <div class="space-y-2">
                        @php
                            $recentNotifs = [
                                ['🎁', 'Flash Sale!', '50% off all items today only', 'All Users', '2 min ago', 'sent', 'from-amber-500 to-orange-500'],
                                ['📦', 'Order Shipped', 'Your order #1042 is on the way', 'Customers', '1 hr ago', 'sent', 'from-blue-500 to-indigo-500'],
                                ['⚠️', 'Low Stock Alert', 'Only 3 items left in stock', 'Active', '3 hrs ago', 'sent', 'from-red-500 to-rose-500'],
                                ['🔔', 'Welcome Back!', 'We missed you. Check new arrivals', 'Inactive', 'Tomorrow', 'scheduled', 'from-violet-500 to-fuchsia-500'],
                            ];
                        @endphp

                        @foreach($recentNotifs as [$emoji, $title, $msg, $target, $time, $status, $grad])
                            <div class="recent-row hover-lift flex items-center gap-3 p-2.5 rounded-xl
                                        bg-gray-50 dark:bg-gray-700/50
                                        hover:bg-gray-100 dark:hover:bg-gray-700/80
                                        border border-transparent hover:border-gray-100 dark:hover:border-gray-700
                                        transition-all duration-150 cursor-default">

                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $grad }}
                                            flex items-center justify-center text-base shrink-0 shadow-sm">
                                    {{ $emoji }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $title }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ $msg }}</p>
                                </div>

                                <div class="flex flex-col items-end gap-1 shrink-0">
                                    <span @class([
                                        'px-2 py-0.5 rounded-full text-[10px] font-semibold',
                                        'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800' => $status === 'sent',
                                        'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-800' => $status === 'scheduled',
                                    ])>{{ ucfirst($status) }}</span>
                                    <span class="text-[9px] text-gray-300 dark:text-gray-600">{{ $time }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ════════════ RIGHT: Preview + Stats + Delivery ════════════ --}}
            <div class="flex flex-col gap-4">

                {{-- ── Phone Preview ── --}}
                <div class="notif-preview hover-lift relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm p-4">

                    <div class="absolute -top-6 -right-6 w-20 h-20 rounded-full pointer-events-none
                                bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-700/30 dark:to-gray-600/20 opacity-70"></div>

                    <div class="relative flex items-center gap-2 mb-4">
                        <div class="icon-entry w-8 h-8 rounded-xl bg-gradient-to-br from-slate-500 to-gray-600
                                    flex items-center justify-center shadow-md shadow-slate-500/25">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-gray-900 dark:text-white">Live Preview</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">How it looks on device</p>
                        </div>
                    </div>

                    {{-- Phone frame --}}
                    <div class="phone-frame mx-auto w-44 bg-gray-900 rounded-[28px] p-1.5 shadow-2xl">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-[22px] overflow-hidden">

                            {{-- Status bar --}}
                            <div class="bg-gray-900 px-4 py-1.5 flex items-center justify-between">
                                <span class="text-[8px] text-gray-400 font-medium">9:41</span>
                                <div class="w-7 h-1.5 bg-gray-800 rounded-full"></div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-1.5 bg-gray-400 rounded-sm"></div>
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                </div>
                            </div>

                            {{-- Screen --}}
                            <div class="bg-gray-50 dark:bg-gray-700 p-2 min-h-28">
                                <div class="preview-toast bg-white dark:bg-gray-800 rounded-xl p-2.5 shadow-md border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-start gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-500 to-fuchsia-500
                                                    flex items-center justify-center shrink-0 text-sm">🔔</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[9px] font-bold text-gray-900 dark:text-white truncate" id="previewTitle">
                                                Notification Title
                                            </p>
                                            <p class="text-[8px] text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2" id="previewMessage">
                                                Your message will appear here...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-[7px] text-gray-300 dark:text-gray-600">now · Your App</span>
                                        <div class="flex gap-1">
                                            <span class="text-[7px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-400 font-medium">Dismiss</span>
                                            <span class="text-[7px] px-1.5 py-0.5 rounded bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 font-medium">Open</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Stat Mini Cards ── --}}
                <div class="notif-stats grid grid-cols-2 gap-3">

                    @foreach([
                            ['248', 'Total Sent', 'from-violet-500 to-fuchsia-500', 'from-violet-600 to-fuchsia-600', 'from-violet-50 to-purple-100', 'from-violet-900/20 to-purple-900/20', 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                            ['231', 'Delivered', 'from-emerald-500 to-teal-500', 'from-emerald-600 to-teal-600', 'from-emerald-50 to-green-100', 'from-emerald-900/20 to-green-900/20', 'M5 13l4 4L19 7'],
                            ['68%', 'Open Rate', 'from-blue-500 to-indigo-500', 'from-blue-600 to-indigo-600', 'from-blue-50 to-indigo-100', 'from-blue-900/20 to-indigo-900/20', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                            ['5', 'Scheduled', 'from-amber-500 to-orange-500', 'from-amber-600 to-orange-600', 'from-amber-50 to-orange-100', 'from-amber-900/20 to-orange-900/20', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ] as [$val, $label, $iconGrad, $textGrad, $blobLight, $blobDark, $path])
                            <div class="stat-mini hover-lift relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                                        border border-gray-100 dark:border-gray-700 shadow-sm p-3 flex flex-col gap-1.5">
                                <div class="absolute -top-4 -right-4 w-14 h-14 rounded-full pointer-events-none
                                            bg-gradient-to-br {{ $blobLight }} dark:{{ $blobDark }} opacity-60"></div>
                                <div class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $iconGrad }}
                                            flex items-center justify-center shadow-md shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold bg-gradient-to-r {{ $textGrad }} bg-clip-text text-transparent leading-none">
                                    {{ $val }}
                                </h2>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">{{ $label }}</p>
                            </div>
                    @endforeach

                </div>

                {{-- ── Delivery Rate ── --}}
                <div class="notif-delivery hover-lift relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm p-4 flex flex-col gap-3">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-900 dark:text-white">Delivery Rate</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Performance overview</p>
                    </div>

                    @foreach([
                            ['Delivered', 93, 'from-emerald-500 to-teal-500', 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400'],
                            ['Opened', 68, 'from-blue-500 to-indigo-500', 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'],
                            ['Failed', 7, 'from-red-400 to-rose-500', 'bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400'],
                        ] as [$label, $pct, $grad, $badge])
                            <div class="delivery-row flex flex-col gap-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $badge }}">{{ $pct }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    <div class="bar-anim h-full rounded-full bg-gradient-to-r {{ $grad }}"
                                         style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Schedule toggle ──
        document.querySelectorAll('input[name="schedule"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const box = document.getElementById('scheduleBox');
                if (this.value === 'later') {
                    box.classList.remove('hidden');
                    box.style.animation = 'fadeSlideUp .25s ease both';
                } else {
                    box.classList.add('hidden');
                }
            });
        });

        // ── Live preview ──
        const titleInput = document.getElementById('notifTitle');
        const msgInput   = document.getElementById('notifMessage');
        const charCount  = document.getElementById('charCount');

        if (titleInput) {
            titleInput.addEventListener('input', function () {
                const pTitle = document.getElementById('previewTitle');
                if (pTitle) pTitle.textContent = this.value || 'Notification Title';
            });
        }

        if (msgInput) {
            msgInput.addEventListener('input', function () {
                const pMsg = document.getElementById('previewMessage');
                if (pMsg) pMsg.textContent = this.value || 'Your message will appear here...';
                if (charCount) {
                    const len = this.value.length;
                    charCount.textContent = len + ' / 200';
                    charCount.style.color = len > 180 ? '#ef4444' : len > 150 ? '#f59e0b' : '';
                }
            });
        }

        // ── Reset char count on clear ──
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('reset', () => {
                setTimeout(() => {
                    if (charCount) charCount.textContent = '0 / 200';
                    const pTitle = document.getElementById('previewTitle');
                    const pMsg   = document.getElementById('previewMessage');
                    if (pTitle) pTitle.textContent = 'Notification Title';
                    if (pMsg)   pMsg.textContent   = 'Your message will appear here...';
                }, 10);
            });
        }
    </script>

@endsection