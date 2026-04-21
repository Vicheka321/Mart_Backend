@extends('layouts.app')

@section('content')

    <!-- HEADER -->
    <div class="bg-white rounded-xl shadow pt-3 pl-4 pr-4 pb-3 pr-3 dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">

            <!-- LEFT -->
            <div class="flex flex-col gap-2">

                <!-- Title -->
                <h2 class="text-[22px] font-semibold tracking-tight text-gray-900 dark:text-white">
                    Orders
                </h2>

                <!-- FILTER -->
                <div class="flex gap-2 flex-wrap">

                    @php $current = request('status', 'all'); @endphp

                    @foreach(['all', 'pending', 'processing', 'completed', 'cancelled'] as $s)
                                <a href="?status={{ $s }}"
                                    class="px-3 py-1 text-xs rounded-md capitalize transition
                                                                                                                                                                                                                   {{ $current == $s
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    {{ $s }}
                                </a>
                    @endforeach

                </div>

            </div>

            <!-- RIGHT -->
            <div>
                <button onclick="openExportModal()" class="flex items-center gap-2 px-3 py-1.5 text-sm 
                                                                   text-gray-600 dark:text-gray-300 
                                                                   bg-white dark:bg-slate-800 
                                                                   border border-gray-200 dark:border-gray-700 
                                                                   rounded-md 
                                                                   hover:bg-gray-100 dark:hover:bg-slate-700 
                                                                   transition">

                    <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v12m0 0l4-4m-4 4l-4-4" stroke="currentColor" stroke-width="2" />
                        <path d="M5 21h14" stroke="currentColor" stroke-width="2" />
                    </svg>

                    <span>Export</span>
                </button>
            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden ">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 dark:bg-indigo-500/10 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">No.</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Phone</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Address</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Total</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Payment</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700 h-full">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/40 transition-colors">

                            <td class="px-5 py-2 text-xs text-gray-400">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-5 py-2 font-medium text-gray-800 dark:text-white">
                                {{ $order['user_name'] }}
                            </td>

                            <td class="px-5 py-2 text-gray-600">
                                {{ $order['phone'] }}
                            </td>

                            <td class="px-5 py-2 text-gray-600 max-w-[200px] truncate" title="{{ $order['address'] }}">
                                {{ $order['address'] }}
                            </td>

                            <td class="px-5 py-2 font-semibold">
                                ${{ number_format($order['total'], 2) }}
                            </td>

                            <td class="px-5 py-2 text-gray-600">
                                {{ $order['payment_method'] }}
                            </td>

                            <td class="px-5 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded
                                                                                                                                                                                                                                                                @if($order['status'] == 'pending') bg-yellow-100 text-yellow-800
                                                                                                                                                                                                                                                                @elseif($order['status'] == 'paid') bg-blue-100 text-blue-800
                                                                                                                                                                                                                                                                @elseif($order['status'] == 'completed') bg-green-100 text-green-800
                                                                                                                                                                                                                                                                @else bg-red-100 text-red-800
                                                                                                                                                                                                                                                                @endif">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>

                            <td class="px-5 py-2 text-sm text-gray-500">
                                {{ $order['created_at'] }}
                            </td>
                            <td class="px-5 py-2 text-right space-x-2">

                                <!-- View -->
                                <button onclick='openOrderModal(@json($order))'
                                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-xs hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                                        fill="currentColor">
                                        <defs></defs>
                                        <title>view</title>
                                        <path
                                            d="M30.94,15.66A16.69,16.69,0,0,0,16,5,16.69,16.69,0,0,0,1.06,15.66a1,1,0,0,0,0,.68A16.69,16.69,0,0,0,16,27,16.69,16.69,0,0,0,30.94,16.34,1,1,0,0,0,30.94,15.66ZM16,25c-5.3,0-10.9-3.93-12.93-9C5.1,10.93,10.7,7,16,7s10.9,3.93,12.93,9C26.9,21.07,21.3,25,16,25Z"
                                            transform="translate(0 0)"></path>
                                        <path d="M16,10a6,6,0,1,0,6,6A6,6,0,0,0,16,10Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,16,20Z"
                                            transform="translate(0 0)"></path>
                                        <rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;"
                                            class="cls-1" width="32" height="32" style="fill:none"></rect>
                                    </svg>
                                </button>


                                <!-- ACCEPT -->
                                @if($order['status'] == 'pending')
                                    <button onclick="confirmChange({{ $order['id'] }}, 'processing')"
                                        class="px-3 py-1 
                                                                                                                                                                                        bg-blue-100 dark:bg-blue-900/40 
                                                                                                                                                                                        text-blue-600 dark:text-blue-400 
                                                                                                                                                                                        rounded text-xs font-medium
                                                                                                                                                                                        hover:bg-blue-200 dark:hover:bg-blue-800/60
                                                                                                                                                                                        transition">
                                        <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M8.294 16.998c-.435 0-.847-.203-1.111-.553L3.61 11.724a1.392 1.392 0 0 1 .27-1.951 1.392 1.392 0 0 1 1.953.27l2.351 3.104 5.911-9.492a1.396 1.396 0 0 1 1.921-.445c.653.406.854 1.266.446 1.92L9.478 16.34a1.39 1.39 0 0 1-1.12.656c-.022.002-.042.002-.064.002z">
                                            </path>
                                        </svg>
                                    </button>
                                @endif

                                @if($order['status'] == 'processing')
                                    <button onclick="confirmChange({{ $order['id'] }}, 'completed')"
                                        class="px-3 py-1 
                                                                                                                                                                                        bg-green-100 dark:bg-green-900/40 
                                                                                                                                                                                        text-green-600 dark:text-green-400 
                                                                                                                                                                                        rounded text-xs font-medium
                                                                                                                                                                                        hover:bg-green-200 dark:hover:bg-green-800/60
                                                                                                                                                                                        transition">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="none"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M5 22h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2h-2a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1H5c-1.103 0-2 .897-2 2v15c0 1.103.897 2 2 2zM5 5h2v2h10V5h2v15H5V5z">
                                            </path>
                                            <path d="m11 13.586-1.793-1.793-1.414 1.414L11 16.414l5.207-5.207-1.414-1.414z"></path>
                                        </svg>
                                    </button>
                                @endif

                                <!-- Cancel -->
                                @if(!in_array($order['status'], ['completed', 'processing', 'cancelled']))
                                    <form action="{{ url('/admin/orders/' . $order['id'] . '/cancel') }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">
                                            <svg class="w-4 h-4" viewBox="0 0 428 480" fill="currentColor">
                                                <path
                                                    d="M90 390l120-120 130 120 30-30-130-120 130-120-30-30-130 120-120-120-30 30 120 120-120 120 30 30z">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="mt-4 flex items-center justify-between text-gray-500 dark:text-gray-400">

            <!-- Showing info -->
            <div class="text-sm text-gray-500">
                Showing
                <span class="font-medium">{{ $orders->firstItem() }}</span>
                to
                <span class="font-medium">{{ $orders->lastItem() }}</span>
                of
                <span class="font-medium">{{ $orders->total() }}</span>
                results
            </div>

            <!-- Pagination -->
            <div class="flex space-x-1">

                {{-- Previous --}}
                @if ($orders->onFirstPage())
                    <span
                        class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                                                    bg-gray-200 dark:bg-gray-700 
                                                                                                                                                                                                                                                                                                    text-gray-400 dark:text-gray-300 
                                                                                                                                                                                                                                                                                                    text-sm">Prev</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}"
                        class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                                                    bg-white dark:bg-slate-800 
                                                                                                                                                                                                                                                                                                    border border-gray-200 dark:border-gray-700 
                                                                                                                                                                                                                                                                                                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                                                                                                                                                                                                                                                                                                    text-sm text-gray-700 dark:text-gray-300">Prev</a>
                @endif

                {{-- Pages --}}
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page == $orders->currentPage())
                        <span
                            class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                                                                                                                                                                                    bg-indigo-600 dark:bg-indigo-500 
                                                                                                                                                                                                                                                                                                                                                                                                                                    text-white text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                                                                                                                                                                        bg-white dark:bg-slate-800 
                                                                                                                                                                                                                                                                                                                                                                                                                        border border-gray-200 dark:border-gray-700 
                                                                                                                                                                                                                                                                                                                                                                                                                        hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                                                                                                                                                                                                                                                                                                                                                                                                                        text-sm text-gray-700 dark:text-gray-300">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}"
                        class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                            bg-white dark:bg-slate-800 
                                                                                                                                                                                                                                                                            border border-gray-200 dark:border-gray-700 
                                                                                                                                                                                                                                                                            hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                                                                                                                                                                                                                                                                            text-sm text-gray-700 dark:text-gray-300">Next</a>
                @else
                    <span
                        class="px-3 py-1 rounded-lg 
                                                                                                                                                                                                                                                                    bg-gray-200 dark:bg-gray-700 
                                                                                                                                                                                                                                                                    text-gray-400 dark:text-gray-300 
                                                                                                                                                                                                                                                                    text-sm">Next</span>
                @endif

            </div>
        </div>

        <!-- ORDER DETAIL MODAL -->
        <div id="orderModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">

            <!-- MODAL BOX -->
            <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl
                                            border border-gray-100 dark:border-gray-700
                                            flex flex-col max-h-[90vh]">

                <!-- HEADER -->
                <div class="flex justify-between items-center p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Order Detail
                    </h3>

                    <button onclick="closeOrderModal()" class="w-8 h-8 flex items-center justify-center rounded-full
                                                   bg-gray-100 dark:bg-gray-700 hover:bg-gray-200">
                        ✕
                    </button>
                </div>

                <!-- CONTENT (SCROLLABLE) -->
                <div id="orderContent"
                    class="flex-1 overflow-y-auto p-5 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                    <!-- dynamic -->
                </div>

            </div>
        </div>
    </div>
    <!-- ANIMATION -->
    <style>
        @keyframes scaleIn {
            from {
                transform: scale(0.92);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .animate-scaleIn {
            animation: scaleIn 0.2s cubic-bezier(.34, 1.56, .64, 1);
        }
    </style>



    <script>

        const methodInput = document.getElementById('formMethod')




        function closeModal() {
            modal.classList.add('hidden')
            modal.classList.remove('flex')

        }

        const orderModal = document.getElementById('orderModal')
        const orderContent = document.getElementById('orderContent')

        function openOrderModal(order) {


            orderModal.classList.remove('hidden')
            orderModal.classList.add('flex')

            const getImage = (img) => {
                if (!img) return '/no-image.png'

                if (typeof img !== 'string') return '/no-image.png'
                if (img.startsWith('http')) return img
                return '/storage/' + img
            }
            const itemsHtml = order.items.map(item => `
                                        <div class="flex items-center gap-3 p-3 rounded-xl
                                            bg-gray-50 dark:bg-gray-700/40
                                            border border-gray-100 dark:border-gray-700">

                                            <!-- IMAGE -->
                                            <img src="${getImage(item.image)}"
                                                 onerror="this.src='/no-image.png'"
                                                 class="w-14 h-14 rounded-xl object-cover shadow-sm">

                                            <!-- INFO -->
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                                    ${item.name}
                                                </p>

                                                <!-- ✅ CATEGORY + BRAND -->
                                                <p class="text-[11px] text-gray-400 mt-1">
                                                    ${item.category ?? 'No category'} • ${item.brand ?? 'No brand'}
                                                </p>

                                                <p class="text-xs text-gray-400 mt-1">
                                                    Qty: ${item.qty} × $${item.price}
                                                </p>
                                            </div>

                                            <!-- PRICE -->
                                            <div class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                                $${(item.qty * item.price).toFixed(2)}
                                            </div>
                                        </div>
                                    `).join('')


            // 🧾 CONTENT
            orderContent.innerHTML = `

                                        <!-- HEADER -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <p class="text-xs text-gray-400">Order #${order.id}</p>
                                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                                    $${order.total}
                                                </p>
                                            </div>

                                            <span class="text-xs px-2 py-1 rounded-md font-medium
                                                ${order.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ''}
                                                ${order.status === 'processing' ? 'bg-blue-100 text-blue-700' : ''}
                                                ${order.status === 'completed' ? 'bg-green-100 text-green-700' : ''}
                                                ${order.status === 'cancelled' ? 'bg-red-100 text-red-700' : ''}
                                            ">
                                                ${order.status}
                                            </span>
                                        </div>

                                        <!-- CUSTOMER -->
                                        <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 space-y-1">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                                👤 ${order.user_name}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                📞 ${order.phone}
                                            </p>
                                        </div>

                                        <!-- ADDRESS -->
                                        <div class="text-xs text-gray-500 mt-2">
                                            📍 ${order.address ?? 'No address'}
                                        </div>

                                        <!-- PAYMENT + TOTAL -->
                                        <div class="mt-4 p-3 rounded-xl bg-gray-50 dark:bg-gray-700/40 space-y-2 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Payment</span>
                                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                                    ${order.payment_method}
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Total</span>
                                                <span class="font-bold text-gray-900 dark:text-white">
                                                    $${order.total}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- ITEMS -->
                                        <div class="mt-4">
                                            <p class="text-xs text-gray-400 mb-2 uppercase">Items</p>
                                            <div class="space-y-2">
                                                ${itemsHtml}
                                            </div>
                                        </div>

                                        <!-- ACTIONS -->
                                        <div class="flex gap-2 mt-5">

                                            ${order.status === 'pending' ? `
                                                <button onclick="confirmChange(${order.id}, 'processing')"
                                                    class="flex-1 py-2 text-xs rounded-lg bg-blue-500 text-white">
                                                    ✔ Accept
                                                </button>
                                            ` : ''}

                                            ${order.status === 'processing' ? `
                                                <button onclick="confirmChange(${order.id}, 'completed')"
                                                    class="flex-1 py-2 text-xs rounded-lg bg-green-500 text-white">
                                                    📦 Complete
                                                </button>
                                            ` : ''}

                                            ${(order.status === 'pending' || order.status === 'processing') ? `
                                                <button onclick="confirmCancel(${order.id})"
                                                    class="flex-1 py-2 text-xs rounded-lg bg-red-500 text-white">
                                                    ❌ Cancel
                                                </button>
                                            ` : ''}

                                        </div>

                                        <!-- DATE -->
                                        <p class="text-[11px] text-gray-400 text-right mt-4">
                                            ${order.created_at}
                                        </p>
                                    `
        }
        function closeOrderModal() {
            orderModal.classList.add('hidden')
            orderModal.classList.remove('flex')
        }

    </script>


    <script>
        function confirmChange(orderId, status) {

            let text = ''
            let color = ''

            if (status === 'processing') {
                text = 'Accept this order?'
                color = '#3b82f6'
            } else if (status === 'completed') {
                text = 'Mark as completed?'
                color = '#22c55e'
            } else if (status === 'cancelled') {
                text = 'Cancel this order?'
                color = '#ef4444'
            }

            // 🌙 detect dark mode
            const isDark = document.documentElement.classList.contains('dark')

            Swal.fire({
                title: 'Confirm',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes',

                background: isDark ? '#1f2937' : '#ffffff',
                color: isDark ? '#e5e7eb' : '#111827',
            }).then((result) => {

                if (result.isConfirmed) {

                    fetch(`/admin/orders/${orderId}/status`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ status: status })
                    })
                        .then(res => res.json())


                        .then(() => {
                            setTimeout(() => location.reload(), 800);
                        })

                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong'
                            });
                        });

                }

            });

        }
    </script>




@endsection