<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
            Customers
        </h2>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Orders</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y dark:divide-gray-700">

                @foreach($customers as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

                    <!-- NAME -->
                    <td class="px-4 py-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <span class="text-gray-800 dark:text-white">
                            {{ $user->name }}
                        </span>
                    </td>

                    <!-- EMAIL -->
                    <td class="px-4 py-3 text-gray-500">
                        {{ $user->email }}
                    </td>

                    <!-- ORDERS -->
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-600 rounded">
                            {{ $user->orders_count }}
                        </span>
                    </td>

                    <!-- ACTIONS -->
                    <td class="px-4 py-3 text-right space-x-2">

                        <!-- VIEW -->
                        <button onclick="viewCustomer({{ $user->id }})"
                            class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-200">
                            👁
                        </button>

                        <!-- ORDERS -->
                        <a href="/admin/orders?user={{ $user->id }}"
                            class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                            Orders
                        </a>

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>

</div>