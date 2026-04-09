@extends('layouts.app')

@section('content')

<div class="bg-white h-full rounded-xl shadow p-4 dark:bg-gray-800">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-[22px] font-semibold tracking-tight text-gray-900 dark:text-white">
            Products
        </h2>

        <div class="flex items-center gap-3">

            <!-- Export -->
            <button class="flex items-center gap-2 px-3 py-1.5 text-sm 
                text-gray-600 dark:text-gray-300 
                bg-white dark:bg-slate-800 
                border border-gray-200 dark:border-gray-700 
                rounded-md 
                hover:bg-gray-100 dark:hover:bg-slate-700 
                transition">
                Export
            </button>

        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

        <table class="w-full text-sm text-left">

            <thead class="bg-indigo-100 dark:bg-indigo-500/10 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-5 py-3 text-xs text-gray-500">No.</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Image</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Name</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Category</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Brand</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Price</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Stock</th>
                    <th class="px-5 py-3 text-xs text-gray-500">Status</th>
                    <th class="px-5 py-3 text-xs text-gray-500 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y dark:divide-gray-700">

                @foreach ($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

                    <td class="p-4">{{ $loop->iteration }}</td>

                    <!-- ✅ IMAGE HOVER PREVIEW -->
                    <td class="p-4">
                        <div class="relative group inline-block">

                            <img src="{{ $product['image'][0]['image_url'] ?? 'https://picsum.photos/200' }}"
                                class="w-10 h-10 rounded-lg object-cover border border-gray-100 dark:border-gray-600 cursor-pointer">

                            <div class="absolute left-full ml-3 top-1/2 -translate-y-1/2 z-50 
                                opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 
                                transition-all duration-200 pointer-events-none">

                                <img src="{{ $product['image'][0]['image_url'] ?? 'https://picsum.photos/200' }}"
                                    class="w-40 h-40 object-cover rounded-xl shadow-2xl 
                                    border border-gray-200 dark:border-gray-700 
                                    bg-white dark:bg-slate-800">
                            </div>

                        </div>
                    </td>

                    <td class="p-4 font-medium text-gray-800 dark:text-white">
                        {{ $product['name'] }}
                    </td>

                    <td class="p-4 text-gray-600 dark:text-gray-300">
                        {{ $product['category']['name'] }}
                    </td>

                    <td class="p-4 text-gray-600 dark:text-gray-300">
                        {{ $product['brand']['name'] }}
                    </td>

                    <td class="p-4 font-medium">
                        ${{ $product['sale_price'] }}
                    </td>

                    <td class="p-4">
                        {{ $product['quantity'] }}
                    </td>

                    <!-- ✅ FIXED STATUS -->
                    <td class="p-4">
                        @if ($product['status'])
                            <span class="px-2 py-1 text-xs rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-lg bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                Inactive
                            </span>
                        @endif
                    </td>

                    <!-- ACTION -->
                    <td class="px-5 py-3 text-right space-x-2">

                        <button class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded-lg 
                            hover:bg-indigo-100 transition 
                            dark:bg-indigo-900/30 dark:text-indigo-400">
                            Edit
                        </button>

                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="px-3 py-1 text-xs bg-red-50 text-red-500 rounded-lg 
                                hover:bg-red-100 transition 
                                dark:bg-red-900/20 dark:text-red-400">
                                Delete
                            </button>
                        </form>

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

<!-- DELETE CONFIRM -->
<script>
document.querySelectorAll(".delete-form").forEach(form => {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Delete product?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6366f1",
            cancelButtonColor: "#ef4444",
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

@endsection