@extends('layouts.app')

@section('content')
    @php
        $totalCount = $customers->total();
        $vipCount = $customers->getCollection()->filter(fn($c) => $c->total_spent > 1000)->count();
        $activeCount = $totalCount - $vipCount;

        $activePct = $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0;
        $vipPct = $totalCount > 0 ? round(($vipCount / $totalCount) * 100) : 0;
    @endphp

    <div class="space-y-4">

        {{-- PAGE HEADER --}}
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Customers</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage and monitor all customer accounts.
            </p>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Total Customers --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Total Customers
                </p>
                <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($totalCount) }}
                </p>
                <div class="mt-4 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    <div class="h-full w-full bg-gray-400"></div>
                </div>
            </div>

            {{-- Active Customers --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Active Customers
                </p>
                <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($activeCount) }}
                </p>
                <div class="mt-4 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    <div class="h-full bg-blue-500" style="width: {{ $activePct }}%"></div>
                </div>
            </div>

            {{-- VIP Members --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    VIP Members
                </p>
                <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($vipCount) }}
                </p>
                <div class="mt-4 h-1 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ $vipPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Customer List
                </h2>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">

                    {{-- ROLE FILTER PILLS --}}
                    <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 p-1 gap-1">
                        @foreach(['all' => 'All', 'customer' => 'Customers', 'staff' => 'Staff'] as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['role' => $value, 'page' => 1]) }}"
                               class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200
                                   {{ ($roleFilter ?? 'all') === $value
                                       ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm'
                                       : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- SEARCH --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>

                        <input type="text" id="customerSearch" placeholder="Search customers..." oninput="filterCustomers()"
                            autocomplete="off"
                            class="w-full sm:w-64 pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- EXPORT --}}
                    <button type="button" onclick="exportCustomers()"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3v12" />
                            <path d="m7 10 5 5 5-5" />
                            <path d="M4 21h16" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- ACTIVE FILTER BADGE --}}
            @if(($roleFilter ?? 'all') !== 'all')
                <div class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border-b border-indigo-100 dark:border-indigo-500/20 flex items-center justify-between">
                    <p class="text-xs text-indigo-600 dark:text-indigo-400">
                        Filtering by:
                        <span class="font-semibold capitalize">{{ $roleFilter }}</span>
                        &mdash; {{ number_format($customers->total()) }} {{ Str::plural('result', $customers->total()) }}
                    </p>
                    <a href="{{ request()->fullUrlWithQuery(['role' => 'all', 'page' => 1]) }}"
                       class="text-xs text-indigo-500 dark:text-indigo-400 hover:underline">
                        Clear filter
                    </a>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Joined</th>
                            <th class="px-6 py-3 text-center">Orders</th>
                            <th class="px-6 py-3">Spent</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody id="customersTable" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($customers as $user)
                            @php
                                $isVip = $user->total_spent > 1000;
                                $words = preg_split('/\s+/', trim($user->name));
                                $initials = strtoupper(
                                    substr($words[0] ?? '', 0, 1) .
                                    substr($words[1] ?? '', 0, 1)
                                );
                            @endphp

                            <tr class="customer-row hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-all duration-200"
                                data-id="{{ $user->id }}"
                                data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}"
                                data-role="{{ $user->role ?? 'customer' }}">

                                {{-- CUSTOMER --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xs font-semibold">
                                            {{ $initials ?: strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                Customer #{{ $user->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </td>

                                {{-- JOINED --}}
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>

                                {{-- ORDERS --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $user->orders_count }}
                                    </span>
                                </td>

                                {{-- SPENT --}}
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    ${{ number_format($user->total_spent, 2) }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    @if($user->role === 'staff')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 dark:bg-violet-500/10 text-violet-700 dark:text-violet-400">
                                            Staff
                                        </span>
                                    @elseif($isVip)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                            VIP
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400">
                                            Active
                                        </span>
                                    @endif
                                </td>

                                {{-- ROLE --}}
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.customers.change-role', $user->id) }}"
                                        id="role-form-{{ $user->id }}">
                                        @csrf
                                        @method('PATCH')

                                        <select name="role"
                                            onchange="document.getElementById('role-form-{{ $user->id }}').submit()"
                                            class="text-xs rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="customer" {{ ($user->role ?? 'customer') === 'customer' ? 'selected' : '' }}>
                                                Customer
                                            </option>
                                            <option value="staff" {{ ($user->role ?? '') === 'staff' ? 'selected' : '' }}>
                                                Staff
                                            </option>
                                        </select>
                                    </form>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <button type="button" onclick="viewCustomer({{ $user->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            View
                                        </button>

                                        <a href="/admin/orders?user={{ $user->id }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all duration-200">
                                            Orders
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="searchEmpty" class="hidden px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                    No customers match your search.
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    @if($customers->total())
                        Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of
                        {{ number_format($customers->total()) }}
                    @else
                        No customers found
                    @endif
                </p>

                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <script>
        function filterCustomers() {
            const query = document.getElementById('customerSearch').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.customer-row');
            const empty = document.getElementById('searchEmpty');
            let visible = 0;

            rows.forEach(row => {
                const name  = row.dataset.name  || '';
                const email = row.dataset.email || '';
                const id    = row.dataset.id    || '';

                const match =
                    name.includes(query) ||
                    email.includes(query) ||
                    id.includes(query);

                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            empty.classList.toggle('hidden', !(query !== '' && visible === 0));
        }

        function viewCustomer(id) {
            window.location.href = '/admin/customers/' + id;
        }

        function exportCustomers() {
            const rows = document.querySelectorAll('.customer-row');
            const csv  = ['ID,Name,Email,Role'];

            rows.forEach(row => {
                if (row.style.display === 'none') return;

                const id    = row.dataset.id;
                const name  = row.querySelector('td .font-medium')?.textContent.trim() || '';
                const email = row.dataset.email;
                const role  = row.dataset.role;

                csv.push(`${id},"${name.replace(/"/g, '""')}",${email},${role}`);
            });

            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const url  = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href     = url;
            link.download = 'customers.csv';
            link.click();
            URL.revokeObjectURL(url);
        }
    </script>
@endsection