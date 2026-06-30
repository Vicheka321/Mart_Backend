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
        @keyframes barFill {
            from { width: 0 !important; }
        }
        @keyframes iconSpin {
            from { transform: rotate(-15deg) scale(.8); opacity:0; }
            to   { transform: rotate(0deg)  scale(1);  opacity:1; }
        }
        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.92) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes toastSlide {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(40px); }
        }

        /* ── Page sections ── */
        .notif-header   { animation: fadeSlideUp .45s .05s cubic-bezier(.22,1,.36,1) both; }
        .notif-compose  { animation: cardPop .55s .08s cubic-bezier(.22,1,.36,1) both; }
        .notif-recent   { animation: fadeSlideUp .5s .22s cubic-bezier(.22,1,.36,1) both; }
        .notif-preview  { animation: fadeSlideLeft .5s .14s cubic-bezier(.22,1,.36,1) both; }
        .notif-stats    { animation: fadeSlideLeft .5s .24s cubic-bezier(.22,1,.36,1) both; }
        .notif-delivery { animation: fadeSlideLeft .5s .34s cubic-bezier(.22,1,.36,1) both; }

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

        .recent-row { animation: rowSlideIn .32s ease both; }
        .recent-row:nth-child(1) { animation-delay: .28s; }
        .recent-row:nth-child(2) { animation-delay: .34s; }
        .recent-row:nth-child(3) { animation-delay: .40s; }
        .recent-row:nth-child(4) { animation-delay: .46s; }

        .phone-frame   { animation: phoneFloat 4s 1s ease-in-out infinite; }
        .preview-toast { animation: notifBounce .5s .9s cubic-bezier(.34,1.3,.64,1) both; }

        .bar-anim  { animation: barFill .9s .8s cubic-bezier(.4,0,.2,1) both; }
        .pulse-dot { animation: pulseDot 2.2s ease-in-out infinite; }

        .send-btn:hover { animation: sendPulse .8s ease; }
        .vfocus:focus   { box-shadow: 0 0 0 3px rgba(139,92,246,.18); }

        .hover-lift { transition: transform .2s ease, box-shadow .2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }

        .action-btn { transition: transform .14s ease; }
        .action-btn:hover  { transform: translateY(-1px); }
        .action-btn:active { transform: scale(.96); }

        .icon-entry { animation: iconSpin .4s cubic-bezier(.34,1.56,.64,1) both; }

        .field-row { animation: fadeSlideUp .35s ease both; }
        .field-row:nth-child(1) { animation-delay: .18s; }
        .field-row:nth-child(2) { animation-delay: .24s; }
        .field-row:nth-child(3) { animation-delay: .30s; }
        .field-row:nth-child(4) { animation-delay: .36s; }
        .field-row:nth-child(5) { animation-delay: .42s; }
        .field-row:nth-child(6) { animation-delay: .48s; }
        .field-row:nth-child(7) { animation-delay: .54s; }

        .delivery-row { animation: rowSlideIn .35s ease both; }
        .delivery-row:nth-child(1) { animation-delay: .40s; }
        .delivery-row:nth-child(2) { animation-delay: .50s; }
        .delivery-row:nth-child(3) { animation-delay: .60s; }

        /* ── Modals ── */
        #historyModal.flex  { animation: overlayIn .2s ease; }
        #detailModal.flex   { animation: overlayIn .2s ease; }
        #editModal.flex     { animation: overlayIn .2s ease; }
        #deleteModal.flex   { animation: overlayIn .2s ease; }
        .modal-inner        { animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both; }

        /* ── Toast ── */
        .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; display: flex; flex-direction: column; gap: .5rem; }
        .toast {
            display: flex; align-items: center; gap: .625rem;
            padding: .75rem 1rem;
            background: white; border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,.12);
            font-size: .8125rem; font-weight: 500;
            animation: toastSlide .3s ease;
            min-width: 240px;
        }
        .dark .toast { background: #1f2937; color: #f3f4f6; }
        .toast.leaving { animation: toastOut .3s ease forwards; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* ── History table rows ── */
        #historyTableBody tr { animation: rowSlideIn .3s ease both; }
        #historyTableBody tr:nth-child(1) { animation-delay: .05s; }
        #historyTableBody tr:nth-child(2) { animation-delay: .10s; }
        #historyTableBody tr:nth-child(3) { animation-delay: .15s; }
        #historyTableBody tr:nth-child(4) { animation-delay: .20s; }
        #historyTableBody tr:nth-child(5) { animation-delay: .25s; }

        @media (max-width: 640px) {
            .stat-mini h2 { font-size: 1.15rem; }
        }
    </style>

    {{-- Toast container --}}
    <div class="toast-container" id="toastContainer"></div>

    <div class="space-y-4">

        {{-- ══════════════ GRID ══════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ════════════ LEFT: Compose + Recent ════════════ --}}
            <div class="lg:col-span-2 flex flex-col gap-4">

                {{-- ── Compose Card ── --}}
                <div class="notif-compose relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm p-5">

                    <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full pointer-events-none
                                bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-100
                                dark:from-violet-900/20 dark:via-purple-900/20 dark:to-fuchsia-900/20"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 rounded-full pointer-events-none
                                bg-gradient-to-br from-indigo-50 to-violet-100 dark:from-indigo-900/10 dark:to-violet-900/10 opacity-60"></div>

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
                        <div class="ml-auto flex items-center gap-1.5 px-2.5 py-1 rounded-xl
                                    bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50">
                            <span class="pulse-dot w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 hidden sm:inline">FCM Live</span>
                        </div>
                    </div>

                    <form action="{{ route('notifications.store') }}" method="POST" class="space-y-4 relative">
                        @csrf

                        {{-- Target Audience --}}
                        <div class="field-row">
                            <label class="block text-[10px] font-semibold uppercase tracking-widest
                                          text-gray-400 dark:text-gray-500 mb-2">Target Audience</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach([
                                    ['all', 'All Users', 'from-violet-500 to-fuchsia-500', 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z'],
                                    ['customers', 'Customers', 'from-blue-500 to-indigo-500', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
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
                                            <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300 leading-tight">
                                                {{ $label }}
                                            </span>
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
                                       text-white text-sm font-semibold shadow-lg shadow-violet-500/25
                                       hover:shadow-xl hover:shadow-violet-500/35 hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Notification
                            </button>
                            <button type="reset"
                                class="action-btn px-4 py-2.5 rounded-xl text-sm font-semibold
                                       bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
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
                        <button onclick="openHistoryModal()"
                                class="text-[10px] font-semibold text-violet-600 dark:text-violet-400 hover:underline">
                            View all →
                        </button>
                    </div>

                    <div class="space-y-2">
                        @foreach($recentNotifications as $notification)
                            @php
                                switch ($notification->target) {
                                    case 'customers': $emoji = '👤'; $grad = 'from-blue-500 to-indigo-500'; break;
                                    case 'active':    $emoji = '✅'; $grad = 'from-emerald-500 to-teal-500'; break;
                                    case 'inactive':  $emoji = '⚠️'; $grad = 'from-red-500 to-rose-500'; break;
                                    default:          $emoji = '📢'; $grad = 'from-violet-500 to-fuchsia-500'; break;
                                }
                            @endphp
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
                                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {{ $notification->title }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate">
                                        {{ \Illuminate\Support\Str::limit($notification->message, 50) }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-1 shrink-0">
                                    <span @class([
                                        'px-2 py-0.5 rounded-full text-[10px] font-semibold',
                                        'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800' => $notification->status === 'sent',
                                        'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-800' => $notification->status === 'scheduled',
                                        'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 ring-1 ring-red-200 dark:ring-red-800' => $notification->status === 'failed',
                                    ])>
                                        {{ ucfirst($notification->status) }}
                                    </span>
                                    <span class="text-[9px] text-gray-300 dark:text-gray-600">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
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
                    <div class="phone-frame mx-auto w-44 bg-gray-900 rounded-[28px] p-1.5 shadow-2xl">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-[22px] overflow-hidden">
                            <div class="bg-gray-900 px-4 py-1.5 flex items-center justify-between">
                                <span class="text-[8px] text-gray-400 font-medium">9:41</span>
                                <div class="w-7 h-1.5 bg-gray-800 rounded-full"></div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-1.5 bg-gray-400 rounded-sm"></div>
                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                </div>
                            </div>
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
                        [$stats['total'],         'Total',        'from-violet-500 to-fuchsia-500', 'from-violet-600 to-fuchsia-600', 'from-violet-50 to-purple-100',   'from-violet-900/20 to-purple-900/20',  'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                        [$stats['sent'],           'Sent',         'from-emerald-500 to-teal-500',   'from-emerald-600 to-teal-600',   'from-emerald-50 to-green-100',   'from-emerald-900/20 to-green-900/20',  'M5 13l4 4L19 7'],
                        [$stats['delivery_rate'].'%','Rate',       'from-blue-500 to-indigo-500',    'from-blue-600 to-indigo-600',    'from-blue-50 to-indigo-100',     'from-blue-900/20 to-indigo-900/20',    'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                        [$stats['scheduled'],      'Scheduled',   'from-amber-500 to-orange-500',   'from-amber-600 to-orange-600',   'from-amber-50 to-orange-100',    'from-amber-900/20 to-orange-900/20',   'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
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
                    @php
                        $delivery  = $stats['delivery_rate'];
                        $failed    = $stats['total'] > 0 ? round(($stats['failed']    / $stats['total']) * 100) : 0;
                        $scheduled = $stats['total'] > 0 ? round(($stats['scheduled'] / $stats['total']) * 100) : 0;
                    @endphp
                    @foreach([
                        ['Delivered', $delivery,  'from-emerald-500 to-teal-500',   'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400'],
                        ['Scheduled', $scheduled, 'from-blue-500 to-indigo-500',    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'],
                        ['Failed',    $failed,    'from-red-400 to-rose-500',       'bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400'],
                    ] as [$label, $pct, $grad, $badge])
                        <div class="delivery-row flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $badge }}">{{ $pct }}%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="bar-anim h-full rounded-full bg-gradient-to-r {{ $grad }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         HISTORY MODAL
    ══════════════════════════════════════════ --}}
    <div id="historyModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-500
                                flex items-center justify-center shadow-md shadow-violet-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Notification History</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">All sent, scheduled and failed notifications</p>
                    </div>
                </div>
                <button onclick="closeHistoryModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-full
                               bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                               text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Search bar --}}
            <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" id="historySearch" placeholder="Search by title or message…"
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-sm
                                  bg-gray-50 dark:bg-gray-700/50
                                  border border-gray-100 dark:border-gray-700
                                  text-gray-700 dark:text-gray-200
                                  placeholder-gray-400 dark:placeholder-gray-500
                                  focus:outline-none focus:ring-2 focus:ring-violet-400 transition">
                </div>
            </div>

            {{-- Table --}}
            <div class="flex-1 overflow-y-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40 sticky top-0 z-10">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Notification</th>
                            <th class="px-6 py-3">Target</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Scheduled</th>
                            <th class="px-6 py-3">Sent</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            @php
                                $badge = match($notification->status) {
                                    'sent'      => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                    'scheduled' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'failed'    => 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400',
                                    default     => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300',
                                };
                                switch ($notification->target) {
                                    case 'customers': $tGrad = 'from-blue-500 to-indigo-500'; $tEmoji = '👤'; break;
                                    default:          $tGrad = 'from-violet-500 to-fuchsia-500'; $tEmoji = '📢'; break;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150"
                                data-title="{{ strtolower($notification->title) }}"
                                data-message="{{ strtolower($notification->message) }}">

                                {{-- Notification --}}
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $tGrad }}
                                                    flex items-center justify-center text-base shrink-0 shadow-sm">
                                            {{ $tEmoji }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[180px]">
                                                {{ $notification->title }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate max-w-[180px]">
                                                {{ Str::limit($notification->message, 55) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Target --}}
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                                 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                        {{ ucfirst($notification->target) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $badge }}">
                                        {{ ucfirst($notification->status) }}
                                    </span>
                                </td>

                                {{-- Scheduled --}}
                                <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $notification->scheduled_at
                                        ? \Carbon\Carbon::parse($notification->scheduled_at)->format('d M Y H:i')
                                        : '—' }}
                                </td>

                                {{-- Sent --}}
                                <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">

                                        {{-- View --}}
                                        <button type="button"
                                                onclick='openDetailModal(@json($notification))'
                                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                       border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                                       text-gray-600 dark:text-gray-300
                                                       hover:bg-indigo-50 dark:hover:bg-indigo-500/10
                                                       hover:text-indigo-600 dark:hover:text-indigo-400
                                                       hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            View
                                        </button>

                                        {{-- Edit (scheduled only) --}}
                                        @if($notification->status === 'scheduled')
                                            <button type="button"
                                                    onclick='openEditModal(@json($notification))'
                                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                           border border-amber-200 dark:border-amber-500/30
                                                           bg-amber-50 dark:bg-amber-500/10
                                                           text-amber-600 dark:text-amber-400
                                                           hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-all">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </button>
                                        @endif

                                        {{-- Resend (sent/failed) --}}
                                        @if(in_array($notification->status, ['sent','failed']))
                                            <button type="button"
                                                    onclick="resendNotification({{ $notification->id }})"
                                                    class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                           border border-violet-200 dark:border-violet-500/30
                                                           bg-violet-50 dark:bg-violet-500/10
                                                           text-violet-600 dark:text-violet-400
                                                           hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                                Resend
                                            </button>
                                        @endif

                                        {{-- Delete --}}
                                        <button type="button"
                                                onclick="openDeleteModal({{ $notification->id }}, '{{ addslashes($notification->title) }}')"
                                                class="action-btn inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                       border border-red-200 dark:border-red-500/30
                                                       bg-red-50 dark:bg-red-500/10
                                                       text-red-600 dark:text-red-400
                                                       hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3.5 border-t border-gray-100 dark:border-gray-700 flex-shrink-0
                        bg-gray-50/50 dark:bg-gray-800/30 flex items-center justify-between">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $notifications->count() }} {{ Str::plural('notification', $notifications->count()) }}
                </p>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         DETAIL MODAL
    ══════════════════════════════════════════ --}}
    <div id="detailModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

            {{-- Gradient header --}}
            <div class="bg-gradient-to-br from-violet-600 to-fuchsia-600 px-6 pt-6 pb-10 relative">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-medium tracking-widest text-violet-200 uppercase mb-1" id="detailTarget">—</p>
                        <h3 class="text-xl font-semibold text-white leading-snug" id="detailTitle">—</h3>
                    </div>
                    <button onclick="closeDetailModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Status dot --}}
            <div class="flex justify-center -mt-5 mb-1 relative z-10">
                <span id="detailStatusBadge"
                      class="px-4 py-1.5 rounded-full text-xs font-semibold shadow-md"></span>
            </div>

            {{-- Content --}}
            <div class="px-6 pb-6 pt-4 space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1.5">Message</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed" id="detailMessage">—</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Scheduled</p>
                        <p class="text-xs font-medium text-gray-900 dark:text-white" id="detailScheduled">—</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Created</p>
                        <p class="text-xs font-medium text-gray-900 dark:text-white" id="detailCreated">—</p>
                    </div>
                </div>

                <div id="detailImageWrap" class="hidden bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-3">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Image</p>
                    <img id="detailImage" src="" alt="Notification image"
                         class="w-full h-28 object-cover rounded-lg border border-gray-100 dark:border-gray-700">
                </div>

                <button onclick="closeDetailModal()"
                        class="w-full py-2 text-sm font-medium rounded-xl
                               border border-gray-200 dark:border-gray-600
                               text-gray-500 dark:text-gray-400
                               hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Close
                </button>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         EDIT MODAL (scheduled only)
    ══════════════════════════════════════════ --}}
    <div id="editModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500
                                flex items-center justify-center shadow-md shadow-amber-500/25">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Edit Notification</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Only scheduled notifications can be edited</p>
                    </div>
                </div>
                <button onclick="closeEditModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-full
                               bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                               text-gray-500 dark:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editForm" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" id="editId">

                {{-- Title --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Title</label>
                    <input type="text" id="editTitle"
                           class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                  bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
                                  text-gray-800 dark:text-gray-200 focus:outline-none transition-all" required>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Message</label>
                    <textarea id="editMessage" rows="3"
                              class="vfocus w-full px-3 py-2.5 rounded-xl text-sm resize-none
                                     bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
                                     text-gray-800 dark:text-gray-200 focus:outline-none transition-all" required></textarea>
                </div>

                {{-- Target --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Target</label>
                    <select id="editTarget"
                            class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                   bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
                                   text-gray-800 dark:text-gray-200 focus:outline-none transition-all">
                        <option value="all">All Users</option>
                        <option value="customers">Customers</option>
                    </select>
                </div>

                {{-- Image URL --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">
                        Image URL <span class="normal-case font-normal">(optional)</span>
                    </label>
                    <input type="url" id="editImageUrl"
                           class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                  bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
                                  text-gray-800 dark:text-gray-200 focus:outline-none transition-all"
                           placeholder="https://…">
                </div>

                {{-- Scheduled At --}}
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1.5">Scheduled At</label>
                    <input type="datetime-local" id="editScheduledAt"
                           class="vfocus w-full px-3 py-2.5 rounded-xl text-sm
                                  bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700
                                  text-gray-800 dark:text-gray-200 focus:outline-none transition-all" required>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="submitEdit()"
                            class="action-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                                   bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold
                                   shadow-md shadow-amber-500/25 hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()"
                            class="action-btn px-4 py-2.5 rounded-xl text-sm font-medium
                                   border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                   text-gray-500 dark:text-gray-400
                                   hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         DELETE CONFIRM MODAL
    ══════════════════════════════════════════ --}}
    <div id="deleteModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[60] p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden">

            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Delete Notification</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Are you sure you want to delete
                </p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white mb-5" id="deleteTitle">"—"</p>

                <div class="flex gap-2">
                    <button type="button" onclick="submitDelete()"
                            class="action-btn flex-1 py-2.5 rounded-xl text-sm font-semibold
                                   bg-gradient-to-r from-red-500 to-rose-600 text-white
                                   shadow-md shadow-red-500/25 hover:-translate-y-0.5 transition-all">
                        Yes, Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                            class="action-btn flex-1 py-2.5 rounded-xl text-sm font-medium
                                   border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                   text-gray-500 dark:text-gray-400
                                   hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
    /* ════════════════════════════════════════════════════════════════
       NOTIFICATION PAGE — JS
    ════════════════════════════════════════════════════════════════ */
    const CSRF = '{{ csrf_token() }}';

    /* ── Toast ──────────────────────────────────────────────────── */
    const colors = { success:'#10b981', error:'#ef4444', info:'#8b5cf6', warning:'#f59e0b' };
    function showToast(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = 'toast';
        t.innerHTML = `<span class="toast-dot" style="background:${colors[type]||colors.info}"></span><span>${msg}</span>`;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => {
            t.classList.add('leaving');
            t.addEventListener('animationend', () => t.remove(), { once: true });
        }, 3500);
    }

    /* ── Modal helpers ──────────────────────────────────────────── */
    function showModal(id) {
        const m = document.getElementById(id);
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
    function hideModal(id) {
        const m = document.getElementById(id);
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    function hideAllModals() {
        ['historyModal','detailModal','editModal','deleteModal'].forEach(hideModal);
        document.body.classList.remove('overflow-hidden');
    }

    // Close on backdrop click
    ['historyModal','detailModal','editModal','deleteModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) hideAllModals();
        });
    });

    // ESC key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') hideAllModals();
    });

    /* ── History modal ──────────────────────────────────────────── */
    function openHistoryModal()  { showModal('historyModal'); }
    function closeHistoryModal() { hideModal('historyModal'); document.body.classList.remove('overflow-hidden'); }

    /* ── History search ─────────────────────────────────────────── */
    document.getElementById('historySearch').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#historyTableBody tr').forEach(row => {
            const title   = row.dataset.title   || '';
            const message = row.dataset.message || '';
            row.style.display = (!q || title.includes(q) || message.includes(q)) ? '' : 'none';
        });
    });

    /* ── Detail modal ───────────────────────────────────────────── */
    function openDetailModal(n) {
        document.getElementById('detailTarget').textContent    = ucfirst(n.target) + ' · Target';
        document.getElementById('detailTitle').textContent     = n.title;
        document.getElementById('detailMessage').textContent   = n.message;
        document.getElementById('detailScheduled').textContent = n.scheduled_at
            ? new Date(n.scheduled_at).toLocaleString('en-GB', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'})
            : '—';
        document.getElementById('detailCreated').textContent   = timeAgo(n.created_at);

        const badge = document.getElementById('detailStatusBadge');
        const statusStyles = {
            sent:      'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            scheduled: 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            failed:    'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
        };
        badge.className = 'px-4 py-1.5 rounded-full text-xs font-semibold shadow-md ' + (statusStyles[n.status] || 'bg-gray-100 text-gray-600');
        badge.textContent = ucfirst(n.status);

        const imgWrap = document.getElementById('detailImageWrap');
        const imgEl   = document.getElementById('detailImage');
        if (n.image_url) {
            imgEl.src = n.image_url;
            imgWrap.classList.remove('hidden');
        } else {
            imgWrap.classList.add('hidden');
        }

        showModal('detailModal');
    }
    function closeDetailModal() { hideModal('detailModal'); document.body.classList.remove('overflow-hidden'); }

    /* ── Edit modal ─────────────────────────────────────────────── */
    function openEditModal(n) {
        document.getElementById('editId').value          = n.id;
        document.getElementById('editTitle').value       = n.title;
        document.getElementById('editMessage').value     = n.message;
        document.getElementById('editTarget').value      = n.target;
        document.getElementById('editImageUrl').value    = n.image_url || '';

        if (n.scheduled_at) {
            // Convert to datetime-local format (YYYY-MM-DDTHH:mm)
            const d = new Date(n.scheduled_at);
            const pad = v => String(v).padStart(2, '0');
            document.getElementById('editScheduledAt').value =
                `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        }

        showModal('editModal');
    }
    function closeEditModal() { hideModal('editModal'); document.body.classList.remove('overflow-hidden'); }

    async function submitEdit() {
        const id  = document.getElementById('editId').value;
        const btn = document.querySelector('#editForm button[onclick="submitEdit()"]');

        const payload = {
            title:        document.getElementById('editTitle').value.trim(),
            message:      document.getElementById('editMessage').value.trim(),
            target:       document.getElementById('editTarget').value,
            image_url:    document.getElementById('editImageUrl').value.trim() || null,
            scheduled_at: document.getElementById('editScheduledAt').value,
            _method:      'PUT',
        };

        if (!payload.title || !payload.message || !payload.scheduled_at) {
            showToast('Please fill all required fields.', 'warning');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin inline-block"></span> Saving…';

        try {
            const res = await fetch(`/admin/notifitions/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify(payload),
            });
            const data = await res.json();

            if (data.success) {
                closeEditModal();
                showToast('Notification updated successfully.', 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message || 'Update failed.', 'error');
            }
        } catch {
            showToast('Something went wrong.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Save Changes';
        }
    }

    /* ── Delete modal ───────────────────────────────────────────── */
    let _deleteId = null;
    function openDeleteModal(id, title) {
        _deleteId = id;
        document.getElementById('deleteTitle').textContent = `"${title}"`;
        showModal('deleteModal');
    }
    function closeDeleteModal() { hideModal('deleteModal'); document.body.classList.remove('overflow-hidden'); }

    async function submitDelete() {
        if (!_deleteId) return;
        const btn = document.querySelector('#deleteModal button[onclick="submitDelete()"]');
        btn.disabled = true;
        btn.textContent = 'Deleting…';

        try {
            const res = await fetch(`/admin/notifitions/${_deleteId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ _method: 'DELETE' }),
            });
            const data = await res.json();

            if (data.success) {
                closeDeleteModal();
                showToast('Notification deleted.', 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message || 'Delete failed.', 'error');
            }
        } catch {
            showToast('Something went wrong.', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Yes, Delete';
        }
    }

    /* ── Resend ─────────────────────────────────────────────────── */
    async function resendNotification(id) {
        if (!confirm('Resend this notification?')) return;

        try {
            const res = await fetch(`/admin/notifitions/${id}/resend`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            });
            const data = await res.json();
            showToast(data.success ? 'Notification resent!' : data.message, data.success ? 'success' : 'error');
            if (data.success) setTimeout(() => location.reload(), 1200);
        } catch {
            showToast('Something went wrong.', 'error');
        }
    }

    /* ── Compose: schedule toggle ───────────────────────────────── */
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

    /* ── Live preview ────────────────────────────────────────────── */
    const titleInput = document.getElementById('notifTitle');
    const msgInput   = document.getElementById('notifMessage');
    const charCount  = document.getElementById('charCount');

    titleInput?.addEventListener('input', function () {
        const el = document.getElementById('previewTitle');
        if (el) el.textContent = this.value || 'Notification Title';
    });

    msgInput?.addEventListener('input', function () {
        const el = document.getElementById('previewMessage');
        if (el) el.textContent = this.value || 'Your message will appear here...';
        if (charCount) {
            const len = this.value.length;
            charCount.textContent = len + ' / 200';
            charCount.style.color = len > 180 ? '#ef4444' : len > 150 ? '#f59e0b' : '';
        }
    });

    document.querySelector('form')?.addEventListener('reset', () => {
        setTimeout(() => {
            if (charCount) charCount.textContent = '0 / 200';
            const pTitle = document.getElementById('previewTitle');
            const pMsg   = document.getElementById('previewMessage');
            if (pTitle) pTitle.textContent = 'Notification Title';
            if (pMsg)   pMsg.textContent   = 'Your message will appear here...';
        }, 10);
    });

    /* ── Helpers ─────────────────────────────────────────────────── */
    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    function timeAgo(dateStr) {
        if (!dateStr) return '—';
        const seconds = Math.floor((new Date() - new Date(dateStr)) / 1000);
        const intervals = [[31536000,'year'],[2592000,'month'],[86400,'day'],[3600,'hour'],[60,'minute'],[1,'second']];
        for (const [sec, label] of intervals) {
            const count = Math.floor(seconds / sec);
            if (count >= 1) return `${count} ${label}${count > 1 ? 's' : ''} ago`;
        }
        return 'just now';
    }
    </script>

@endsection