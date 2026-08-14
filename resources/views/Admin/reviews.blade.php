@extends('layouts.app')

@section('content')
    @php
        $pageCollection = $reviews->getCollection();
        $totalReviews = $reviews->total();

        $avgRating = $pageCollection->count() ? round($pageCollection->avg('rating'), 1) : 0;

        $star5 = $pageCollection->where('rating', 5)->count();
        $star4 = $pageCollection->where('rating', 4)->count();
        $star3 = $pageCollection->where('rating', 3)->count();
        $star2 = $pageCollection->where('rating', 2)->count();
        $star1 = $pageCollection->where('rating', 1)->count();

        $ratedCount = max($pageCollection->count(), 1);
        $pct5 = round(($star5 / $ratedCount) * 100);
        $pct4 = round(($star4 / $ratedCount) * 100);
        $pct3 = round(($star3 / $ratedCount) * 100);
        $pct2 = round(($star2 / $ratedCount) * 100);
        $pct1 = round(($star1 / $ratedCount) * 100);

        $currentRating = request('rating', 'all');
        $currentSearch = request('search');
    @endphp

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

        @keyframes barFill {
            from {
                width: 0 !important;
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

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes overlayIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .stat-card {
            animation: fadeSlideUp .5s ease both;
        }

        .stat-card:nth-child(1) {
            animation-delay: .05s;
        }

        .stat-card:nth-child(2) {
            animation-delay: .1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: .15s;
        }

        .stat-card:nth-child(4) {
            animation-delay: .2s;
        }

        .stat-card:nth-child(5) {
            animation-delay: .25s;
        }

        .stat-card:nth-child(6) {
            animation-delay: .3s;
        }

        .stat-card:nth-child(7) {
            animation-delay: .35s;
        }

        .table-card {
            animation: fadeSlideUp .55s .3s ease both;
        }

        .review-row {
            animation: rowSlideIn .35s ease both;
        }

        .bar-fill {
            animation: barFill .9s .3s cubic-bezier(.4, 0, .2, 1) both;
        }

        #reviewModal.flex {
            animation: overlayIn .2s ease;
        }

        .modal-inner {
            animation: modalIn .25s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .action-btn {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="space-y-4" x-data="{ showFilters: false }">

        {{-- ==================== PAGE HEADER ==================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Reviews &amp; Ratings</h1>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">View customer feedback and product ratings.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                         ring-1 ring-indigo-200 dark:ring-indigo-800 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                {{ number_format($totalReviews) }} {{ Str::plural('review', $totalReviews) }}
            </span>
        </div>

        {{-- ==================== SUMMARY CARDS ==================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">

            {{-- Average Rating --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 shadow-sm p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20">
                </div>
                <div class="relative flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600
                                flex items-center justify-center shadow-md shadow-amber-500/25">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z" />
                        </svg>
                    </div>
                    <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Average</h4>
                </div>
                <h2 class="relative mt-2 pl-1 text-2xl font-bold tracking-tight
                           bg-gradient-to-r from-amber-600 to-yellow-600 bg-clip-text text-transparent leading-none">
                    {{ $avgRating }}<span class="text-sm text-gray-400 dark:text-gray-500 font-medium">/5</span>
                </h2>
            </div>

            {{-- Total Reviews --}}
            <div class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                        border border-gray-100 dark:border-gray-700 shadow-sm p-3">
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                            bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20">
                </div>
                <div class="relative flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600
                                flex items-center justify-center shadow-md shadow-indigo-500/25">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">Total</h4>
                </div>
                <h2 class="relative mt-2 pl-1 text-2xl font-bold tracking-tight
                           bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent leading-none">
                    {{ number_format($totalReviews) }}
                </h2>
            </div>

            {{-- 5 / 4 / 3 / 2 / 1 star cards --}}
            @php
                $starCards = [
                    5 => ['count' => $star5, 'from' => 'from-emerald-500', 'to' => 'to-green-600', 'bgFrom' => 'from-emerald-50', 'bgTo' => 'to-green-100', 'darkFrom' => 'dark:from-emerald-900/20', 'darkTo' => 'dark:to-green-900/20', 'text' => 'from-emerald-600 to-green-600'],
                    4 => ['count' => $star4, 'from' => 'from-lime-500', 'to' => 'to-green-500', 'bgFrom' => 'from-lime-50', 'bgTo' => 'to-green-100', 'darkFrom' => 'dark:from-lime-900/20', 'darkTo' => 'dark:to-green-900/20', 'text' => 'from-lime-600 to-green-600'],
                    3 => ['count' => $star3, 'from' => 'from-amber-500', 'to' => 'to-yellow-500', 'bgFrom' => 'from-amber-50', 'bgTo' => 'to-yellow-100', 'darkFrom' => 'dark:from-amber-900/20', 'darkTo' => 'dark:to-yellow-900/20', 'text' => 'from-amber-600 to-yellow-600'],
                    2 => ['count' => $star2, 'from' => 'from-orange-500', 'to' => 'to-amber-600', 'bgFrom' => 'from-orange-50', 'bgTo' => 'to-amber-100', 'darkFrom' => 'dark:from-orange-900/20', 'darkTo' => 'dark:to-amber-900/20', 'text' => 'from-orange-600 to-amber-600'],
                    1 => ['count' => $star1, 'from' => 'from-red-500', 'to' => 'to-rose-600', 'bgFrom' => 'from-red-50', 'bgTo' => 'to-rose-100', 'darkFrom' => 'dark:from-red-900/20', 'darkTo' => 'dark:to-rose-900/20', 'text' => 'from-red-600 to-rose-600'],
                ];
            @endphp

            @foreach ($starCards as $star => $card)
                <a href="{{ request()->fullUrlWithQuery(['rating' => $star]) }}" class="stat-card relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800
                            border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:-translate-y-0.5
                            transition-all duration-300 p-3">
                    <div
                        class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $card['bgFrom'] }} {{ $card['bgTo'] }} {{ $card['darkFrom'] }} {{ $card['darkTo'] }}">
                    </div>
                    <div class="relative flex items-center gap-2">
                        <div
                            class="w-7 h-7 rounded-xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-md">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">{{ $star }} Star</h4>
                    </div>
                    <h2 class="relative mt-2 pl-1 text-2xl font-bold tracking-tight
                               bg-gradient-to-r {{ $card['text'] }} bg-clip-text text-transparent leading-none">
                        {{ number_format($card['count']) }}
                    </h2>
                </a>
            @endforeach
        </div>

        {{-- ==================== RATING OVERVIEW ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 sm:p-5">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Rating Distribution</h2>
            <div class="space-y-2.5">
                @foreach ([5 => $pct5, 4 => $pct4, 3 => $pct3, 2 => $pct2, 1 => $pct1] as $star => $pct)
                    @php
                        $count = ${'star' . $star};
                        $barColor = match ($star) {
                            5 => 'from-emerald-500 to-green-600',
                            4 => 'from-lime-500 to-green-500',
                            3 => 'from-amber-500 to-yellow-500',
                            2 => 'from-orange-500 to-amber-600',
                            1 => 'from-red-500 to-rose-600',
                        };
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-10 flex-shrink-0 text-xs font-medium text-gray-600 dark:text-gray-300 flex items-center gap-0.5">
                            {{ $star }}
                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z" />
                            </svg>
                        </span>
                        <div class="flex-1 h-2.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="bar-fill h-full rounded-full bg-gradient-to-r {{ $barColor }}"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="w-10 flex-shrink-0 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">
                            {{ $count }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ==================== TABLE CARD ==================== --}}
        <div class="table-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER / SEARCH / FILTER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer Reviews</h2>

                <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-1 lg:flex-initial lg:w-auto">
                    <div class="relative flex-1 lg:w-72">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                        </svg>
                        <input type="text" name="search" value="{{ $currentSearch }}"
                            placeholder="Search customer, product, or review..."
                            class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                   placeholder:text-gray-400 dark:placeholder:text-gray-500
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400">
                    </div>

                    <select name="rating" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600
                               bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400">
                        <option value="all" {{ $currentRating == 'all' || !$currentRating ? 'selected' : '' }}>All Ratings</option>
                        <option value="5" {{ (string) $currentRating === '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ (string) $currentRating === '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ (string) $currentRating === '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ (string) $currentRating === '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ (string) $currentRating === '1' ? 'selected' : '' }}>1 Star</option>
                    </select>

                    <button type="submit"
                        class="action-btn inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium rounded-xl
                               bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-500/25 transition-all duration-200">
                        Search
                    </button>

                    @if($currentSearch || ($currentRating && $currentRating !== 'all'))
                        <a href="{{ request()->url() }}"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-xl
                                   border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400
                                   hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if($currentRating && $currentRating !== 'all')
                <div class="px-4 sm:px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20
                            flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by: <span class="font-semibold">{{ $currentRating }} Star{{ $currentRating == 1 ? '' : 's' }}</span>
                        &mdash; {{ number_format($reviews->total()) }} {{ Str::plural('result', $reviews->total()) }}
                    </p>
                </div>
            @endif

            {{-- ==================== REVIEW LIST ==================== --}}
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($reviews as $review)
                    @php
                        $fullName = $review->user->full_name ?? 'Deleted User';
                        $avatar = $review->user->avatar ?? null;
                        $initials = strtoupper(substr($fullName, 0, 1));

                        $productName = $review->product->name ?? 'Product removed';
                        $productImage = $review->product->firstImage->image_url ?? null;

                        $ratingVal = (int) ($review->rating ?? 0);
                        $reviewText = trim((string) $review->review);

                        $reviewData = [
                            'id' => $review->id,
                            'rating' => $ratingVal,
                            'review' => $reviewText,
                            'created_at' => optional($review->created_at)->format('M d, Y g:i A'),
                            'order_id' => $review->order_id,
                            'is_approved' => (bool) $review->is_approved,
                            'customer_name' => $fullName,
                            'customer_avatar' => $avatar,
                            'product_name' => $productName,
                            'product_image' => $productImage,
                        ];
                    @endphp

                    <div class="review-row p-4 sm:p-5 flex flex-col sm:flex-row gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-200">

                        {{-- CUSTOMER --}}
                        <div class="flex items-center gap-3 sm:w-48 flex-shrink-0">
                            @if($avatar)
                                <img src="{{ $avatar }}" alt="{{ $fullName }}"
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-100 dark:ring-gray-700">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600
                                            text-white flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                    {{ $initials ?: '?' }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $fullName }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ optional($review->created_at)->format('M d, Y') ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex-1 min-w-0 space-y-2">

                            <div class="flex flex-wrap items-center justify-between gap-2">
                                {{-- PRODUCT --}}
                                <div class="flex items-center gap-2 min-w-0">
                                    @if($productImage)
                                        <img src="{{ $productImage }}" alt="{{ $productName }}"
                                            class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-gray-100 dark:border-gray-700">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ $productName }}</span>
                                </div>

                                {{-- STATUS --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium flex-shrink-0
                                             {{ $review->is_approved ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>

                            {{-- STARS --}}
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $ratingVal ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z" />
                                    </svg>
                                @endfor
                            </div>

                            {{-- REVIEW TEXT --}}
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                @if($reviewText !== '')
                                    &ldquo;{{ $reviewText }}&rdquo;
                                @else
                                    <span class="italic text-gray-400 dark:text-gray-500">No written review</span>
                                @endif
                            </p>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex sm:flex-col items-start sm:items-end justify-between sm:justify-start gap-2 flex-shrink-0">
                            <button
                                type="button"
                                onclick='openReviewModal(@js($reviewData))'
                                class="action-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                                    border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700
                                    text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600
                                    transition-all duration-200"
                            >
                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                    />
                                </svg>

                                <span class="hidden sm:inline">
                                    Details
                                </span>
                            </button>
                        </div>
                    </div>
                @empty
                    {{-- ==================== EMPTY STATE ==================== --}}
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">No reviews yet</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-xs">
                            Customer reviews will appear here when users rate products.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($reviews->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700
                            flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                            bg-gray-50/50 dark:bg-gray-800/30">

                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Showing
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $reviews->firstItem() }}–{{ $reviews->lastItem() }}</span>
                        of
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ number_format($reviews->total()) }}</span>
                        results
                    </p>

                    @php
                        $reviews->appends(request()->query());
                        $currentPage = $reviews->currentPage();
                        $last = $reviews->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($last, $currentPage + 2);
                    @endphp
                    <nav class="flex items-center gap-1 overflow-x-auto">
                        @if($reviews->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $reviews->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                        hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                        transition-colors duration-150 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        @if($start > 1)
                            <a href="{{ $reviews->url(1) }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                    text-sm font-medium text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150 flex-shrink-0">1</a>
                            @if($start > 2)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none flex-shrink-0">…</span>
                            @endif
                        @endif

                        @foreach($reviews->getUrlRange($start, $end) as $page => $url)
                            @if($page == $currentPage)
                                <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                             bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-500/25 flex-shrink-0">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                        text-sm font-medium text-gray-500 dark:text-gray-400
                                        hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                        transition-colors duration-150 flex-shrink-0">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="w-8 h-8 flex items-center justify-center text-sm text-gray-300 dark:text-gray-600 select-none flex-shrink-0">…</span>
                            @endif
                            <a href="{{ $reviews->url($last) }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg
                                    text-sm font-medium text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150 flex-shrink-0">
                                {{ $last }}
                            </a>
                        @endif

                        @if($reviews->hasMorePages())
                            <a href="{{ $reviews->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400
                                    hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white
                                    transition-colors duration-150 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- ==================== REVIEW DETAIL MODAL ==================== --}}
    <div id="reviewModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-0 sm:p-4">
        <div class="modal-inner bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                    w-full h-full sm:h-auto sm:max-w-lg sm:rounded-2xl shadow-2xl flex flex-col sm:max-h-[90vh] overflow-hidden">

            <div class="bg-indigo-700 px-4 sm:px-6 pt-6 pb-12 flex-shrink-0">
                <div class="flex items-start justify-between">
                    <div>
                        <p id="modalReviewId" class="text-[11px] font-medium tracking-widest text-indigo-300 uppercase mb-1">Review #—</p>
                        <p id="modalOrderId" class="text-sm text-indigo-100">Order —</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="modalStatusBadge" class="px-3 py-1 rounded-full text-[11px] font-semibold"></span>
                        <button onclick="closeReviewModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-center -mt-9 mb-1 relative z-10 flex-shrink-0">
                <div class="relative inline-block">
                    <img id="modalAvatar" src="" alt="Customer"
                        class="w-[72px] h-[72px] rounded-[18px] object-cover border-[3px] border-white dark:border-gray-800 shadow-lg hidden">
                    <div id="modalAvatarFallback"
                        class="w-[72px] h-[72px] rounded-[18px] bg-gradient-to-br from-indigo-500 to-purple-600 text-white
                               flex items-center justify-center text-2xl font-semibold border-[3px] border-white dark:border-gray-800 shadow-lg">
                        ?
                    </div>
                </div>
            </div>

            <p id="modalCustomerName" class="text-center text-sm font-semibold text-gray-900 dark:text-white mt-2"></p>
            <p id="modalCustomerMeta" class="text-center text-xs text-gray-400 dark:text-gray-500 mb-4"></p>

            <div id="reviewContent" class="flex-1 overflow-y-auto px-4 sm:px-5 pb-5 space-y-3 text-sm text-gray-700 dark:text-gray-300"></div>
        </div>
    </div>

    @push('scripts')
        <script defer>
            function showModal(id) {
                const m = document.getElementById(id);
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
            function hideModal(id) {
                const m = document.getElementById(id);
                m.classList.add('hidden');
                m.classList.remove('flex');
            }

            document.getElementById('reviewModal').addEventListener('click', function (e) {
                if (e.target === this) hideModal('reviewModal');
            });

            function starRow(rating) {
                let html = '';
                for (let i = 1; i <= 5; i++) {
                    html += `<svg class="w-5 h-5 ${i <= rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600'}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.958a1 1 0 00-.363-1.118L2.9 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.958z"/>
                    </svg>`;
                }
                return html;
            }

            function openReviewModal(review) {
                document.getElementById('modalReviewId').textContent = 'Review #' + review.id;
                document.getElementById('modalOrderId').textContent = review.order_id ? ('Order #' + review.order_id) : 'No linked order';

                const statusBadge = document.getElementById('modalStatusBadge');
                if (review.is_approved) {
                    statusBadge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-400/20 text-emerald-200';
                    statusBadge.textContent = 'Approved';
                } else {
                    statusBadge.className = 'px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-400/20 text-amber-200';
                    statusBadge.textContent = 'Pending';
                }

                const avatarEl = document.getElementById('modalAvatar');
                const fallbackEl = document.getElementById('modalAvatarFallback');
                if (review.customer_avatar) {
                    avatarEl.src = review.customer_avatar;
                    avatarEl.classList.remove('hidden');
                    fallbackEl.classList.add('hidden');
                } else {
                    avatarEl.classList.add('hidden');
                    fallbackEl.classList.remove('hidden');
                    fallbackEl.textContent = (review.customer_name || '?').charAt(0).toUpperCase();
                }

                document.getElementById('modalCustomerName').textContent = review.customer_name || 'Customer';
                document.getElementById('modalCustomerMeta').textContent = review.created_at || '';

                const productImgTag = review.product_image
                    ? `<img src="${review.product_image}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 dark:border-gray-700 flex-shrink-0">`
                    : `<div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-400 text-[10px] font-semibold">IMG</div>`;

                const reviewTextHtml = review.review
                    ? `&ldquo;${review.review}&rdquo;`
                    : `<span class="italic text-gray-400 dark:text-gray-500">No written review</span>`;

                document.getElementById('reviewContent').innerHTML = `
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                        ${productImgTag}
                        <div class="min-w-0">
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Product</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${review.product_name || 'Product removed'}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Rating</p>
                        <div class="flex items-center gap-0.5">${starRow(review.rating)}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 rounded-xl p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Review</p>
                        <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">${reviewTextHtml}</p>
                    </div>
                `;

                showModal('reviewModal');
            }

            function closeReviewModal() {
                hideModal('reviewModal');
            }
        </script>
    @endpush

@endsection