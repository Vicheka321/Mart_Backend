@extends('layouts.app')

@section('content')

    <div class="bg-white rounded-xl shadow pt-3 pl-4 pr-4 pb-3 dark:bg-gray-800">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-[22px] font-semibold tracking-tight text-gray-900 dark:text-white">Products</h2>
            <div class="flex items-center gap-3">
                <button onclick="openExportModal()"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                    <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v12m0 0l4-4m-4 4l-4-4" stroke="currentColor" stroke-width="2" />
                        <path d="M5 21h14" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <span>Export</span>
                </button>
                <button onclick="openModal()"
                    class="h-9 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    Add New
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-100 dark:bg-indigo-500/10 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">No.</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Image</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Category</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Brand</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Price</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Stock</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500">Created at
                        </th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach ($products as $product)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-2 text-xs font-medium text-gray-300">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-0.5">
                                @php $img = optional($product->image->first())->image_url; @endphp
                                @if ($img)
                                    <img src="{{ $img }}" loading="lazy"
                                        class="w-10 h-10 rounded-xl object-cover border transition-transform duration-300 hover:scale-150 hover:z-10 relative">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-semibold text-sm">
                                        {{ strtoupper(substr($product->name ?? 'P', 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-2 font-medium text-gray-800 dark:text-white">{{ $product['name'] }}</td>
                            <td class="px-5 py-2 font-medium text-gray-800 dark:text-white">{{ $product['category']['name'] }}
                            </td>
                            <td class="px-5 py-2 font-medium text-gray-800 dark:text-white">{{ $product['brand']['name'] }}</td>
                            <td class="px-5 py-2 font-medium">${{ $product['sale_price'] }}</td>
                            <td class="px-5 py-2">{{ $product['quantity'] }}</td>
                            <td class="px-5 py-2 text-xs text-gray-400">{{ $product->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-2 text-right space-x-2">
                                <button onclick="editProduct({{ $product->id }})"
                                    class="px-3.5 py-1.5 text-xs font-medium bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3.5 py-1.5 text-xs font-medium bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex items-center justify-between text-gray-500 dark:text-gray-400">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium">{{ $products->firstItem() }}</span>
                to <span class="font-medium">{{ $products->lastItem() }}</span>
                of <span class="font-medium">{{ $products->total() }}</span> results
            </div>
            <div class="flex space-x-1">
                @if ($products->onFirstPage())
                    <span
                        class="px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-300 text-sm">Prev</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}"
                        class="px-3 py-1 rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 text-sm text-gray-700 dark:text-gray-300">Prev</a>
                @endif

                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="px-3 py-1 rounded-lg bg-indigo-600 dark:bg-indigo-500 text-white text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-1 rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 text-sm text-gray-700 dark:text-gray-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                        class="px-3 py-1 rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 text-sm text-gray-700 dark:text-gray-300">Next</a>
                @else
                    <span
                        class="px-3 py-1 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-300 text-sm">Next</span>
                @endif
            </div>
        </div>

        {{-- Export Modal --}}
        <div id="exportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
            <div onclick="event.stopPropagation()"
                class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-gray-700 rounded-2xl p-6 w-80 shadow-2xl">
                <h3 class="text-lg font-semibold mb-5 text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v12m0 0l4-4m-4 4l-4-4" stroke="currentColor" stroke-width="2" />
                        <path d="M5 21h14" stroke="currentColor" stroke-width="2" />
                    </svg>
                    Export Data
                </h3>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('products.export.csv') }}"
                        class="flex items-center justify-between px-4 py-2.5 rounded-lg bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 hover:bg-green-50 dark:hover:bg-green-500/10 hover:border-green-400 transition group">
                        <span
                            class="text-gray-700 dark:text-gray-300 group-hover:text-green-600 dark:group-hover:text-green-400">📄
                            CSV File</span>
                        <span
                            class="text-xs text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400">Download</span>
                    </a>
                    <a href="{{ route('products.export.pdf') }}"
                        class="flex items-center justify-between px-4 py-2.5 rounded-lg bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 hover:bg-red-50 dark:hover:bg-red-500/10 hover:border-red-400 transition group">
                        <span
                            class="text-gray-700 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-400">📑
                            PDF File</span>
                        <span
                            class="text-xs text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400">Download</span>
                    </a>
                </div>
                <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>
                <button onclick="closeExportModal()"
                    class="w-full py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition">Cancel</button>
            </div>
        </div>

        {{-- Add / Edit Product Modal --}}
        <div id="productModal"
            class="fixed inset-0 bg-black/50 hidden items-start justify-center z-50 px-4 py-8 overflow-y-auto">
            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-2xl w-full max-w-3xl mx-auto relative">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-5">
                    <h2 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Add Product</h2>
                    <button onclick="closeModal()"
                        class="text-gray-400 hover:text-red-500 transition text-xl font-bold leading-none">&times;</button>
                </div>

                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="grid grid-cols-2 gap-6">

                        {{-- LEFT: Fields --}}
                        <div class="space-y-4">

                            {{-- General Info --}}
                            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Product
                                        Name</label>
                                    <input type="text" id="productName" name="name" placeholder="e.g. Pepsi"
                                        class="w-full px-3 py-2 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                                        required>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                                    <textarea name="description" rows="3" placeholder="Enter product description..."
                                        class="w-full px-3 py-2 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition resize-none"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Category</label>
                                        <select name="categories_id"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:outline-none transition">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Brand</label>
                                        <select name="brand_id"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:outline-none transition">
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Pricing & Stock --}}
                            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm space-y-4">
                                <h3 class="font-semibold text-gray-700 dark:text-white text-sm">Pricing & Stock</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cost
                                            Price</label>
                                        <input type="number" step="0.01" name="cost_price" placeholder="0.50"
                                            class="w-full px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sale
                                            Price</label>
                                        <input type="number" step="0.01" name="sale_price" placeholder="1.00"
                                            class="w-full px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                                            required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Quantity</label>
                                        <input type="number" name="quantity" placeholder="100"
                                            class="w-full px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                                        <select name="status"
                                            class="w-full px-3 py-1 rounded-md border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none transition">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- RIGHT: Image Upload --}}
                        
                        <div class="flex flex-col bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm"
                            style="min-height: 100%;">

                            <p class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Upload Img</p>

                            {{-- Main upload box --}}
                            <div id="uploadBox" onclick="handleMainClick()" class="relative w-full h-52 rounded-2xl bg-gray-50 dark:bg-gray-700 border-2 border-dashed border-gray-200 dark:border-gray-600
                   flex items-center justify-center cursor-pointer overflow-hidden transition-colors
                   hover:border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">

                                <div id="uploadPlaceholder"
                                    class="flex flex-col items-center gap-2 pointer-events-none select-none">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none"
                                            stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Click to upload
                                        images</span>
                                    <span class="text-xs text-gray-400">PNG, JPG, WEBP up to 2MB</span>
                                </div>

                                <img id="mainPreview" class="hidden absolute inset-0 w-full h-full object-contain"
                                    alt="Preview">

                                <div id="editOverlay"
                                    class="hidden absolute inset-0 items-center justify-center pointer-events-none"
                                    style="transition: background 0.2s;">
                                    <span id="editLabel"
                                        class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-medium px-4 py-1.5 rounded-full opacity-0"
                                        style="transition: opacity 0.2s;">Change Photo</span>
                                </div>
                            </div>

                            <input type="file" id="imageInputNew" name="images[]" class="hidden" accept="image/*" multiple>
                            <input type="file" id="imageInputSwap" class="hidden" accept="image/*">

                            {{-- Thumbnails + count — these grow naturally, pushing save button down --}}
                            <div id="thumbGrid" class="grid grid-cols-4 gap-2 mt-3"></div>
                            <p id="imgCount" class="hidden mt-1.5 text-xs text-gray-400"></p>

                            {{-- Spacer pushes save button to bottom always --}}
                            <div class="flex-1"></div>

                            {{-- Save button — always at bottom --}}
                            <button type="submit"
                                class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 active:-translate-y-0.5 text-white text-sm font-medium py-3 rounded-xl transition-all">
                                Save Product
                            </button>

                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        .thumb-inner {
            border-radius: 11px;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        #uploadBox:hover #editOverlay {
            background: rgba(0, 0, 0, 0.28) !important;
        }

        #uploadBox:hover #editLabel {
            opacity: 1 !important;
        }
    </style>

    <script>
        // ─── Modal helpers ───────────────────────────────────────────────
        function openExportModal() {
            document.getElementById('exportModal').classList.replace('hidden', 'flex');
        }
        function closeExportModal() {
            document.getElementById('exportModal').classList.replace('flex', 'hidden');
        }
        function openModal() {
            resetModal();
            document.getElementById('productModal').classList.replace('hidden', 'flex');
        }
        function closeModal() {
            document.getElementById('productModal').classList.replace('flex', 'hidden');
        }
        function resetModal() {
            document.getElementById('productForm').action = "{{ route('products.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('productName').value = '';
            document.getElementById('modalTitle').innerText = 'Add Product';
            clearPreview();
        }

        // ─── Delete confirm ──────────────────────────────────────────────
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete product?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',
                }).then(r => { if (r.isConfirmed) form.submit(); });
            });
        });

        // ─── Image upload logic ──────────────────────────────────────────
        const inputNew = document.getElementById('imageInputNew');
        const inputSwap = document.getElementById('imageInputSwap');
        const mainPreview = document.getElementById('mainPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const uploadBox = document.getElementById('uploadBox');
        const editOverlay = document.getElementById('editOverlay');
        const thumbGrid = document.getElementById('thumbGrid');
        const imgCount = document.getElementById('imgCount');
        const MAX = 8;

        let images = []; // { url, file }
        let selectedIdx = 0;
        let swapTarget = null;

        function handleMainClick() {
            if (images.length > 0) { swapTarget = selectedIdx; inputSwap.click(); }
            else inputNew.click();
        }

        inputNew.addEventListener('change', () => {
            const slots = MAX - images.length;
            Array.from(inputNew.files).slice(0, slots).forEach(file => {
                if (file.size <= 2 * 1024 * 1024) images.push({ url: URL.createObjectURL(file), file });
            });
            if (images.length) render(images.length - 1);
            inputNew.value = '';
            syncInput();
        });

        inputSwap.addEventListener('change', () => {
            if (inputSwap.files.length && swapTarget !== null) {
                const file = inputSwap.files[0];
                if (file.size <= 2 * 1024 * 1024) {
                    images[swapTarget] = { url: URL.createObjectURL(file), file };
                    render(swapTarget);
                    syncInput();
                }
            }
            swapTarget = null;
            inputSwap.value = '';
        });

        function removeImage(idx, e) {
            e.stopPropagation();
            images.splice(idx, 1);
            if (!images.length) { clearPreview(); syncInput(); return; }
            render(Math.min(selectedIdx, images.length - 1));
            syncInput();
        }

        function clearPreview() {
            images = [];
            selectedIdx = 0;
            mainPreview.classList.add('hidden');
            mainPreview.src = '';
            placeholder.classList.remove('hidden');
            editOverlay.classList.add('hidden');
            editOverlay.classList.remove('flex');
            uploadBox.classList.remove('border-solid');
            uploadBox.classList.add('border-dashed');
            thumbGrid.innerHTML = '';
            imgCount.classList.add('hidden');
            syncInput();
        }

        function render(idx) {
            selectedIdx = idx;

            mainPreview.src = images[idx].url;
            mainPreview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            editOverlay.classList.remove('hidden');
            editOverlay.classList.add('flex');
            uploadBox.classList.add('border-solid');
            uploadBox.classList.remove('border-dashed');

            thumbGrid.innerHTML = '';

            images.forEach((img, i) => {
                const slot = document.createElement('div');
                slot.className = `relative aspect-square rounded-xl cursor-pointer transition-all ${i === idx ? 'ring-2 ring-indigo-500' : 'ring-1 ring-gray-200 dark:ring-gray-600'
                    }`;

                // ✕ Remove button
                const rmBtn = document.createElement('div');
                rmBtn.className = 'absolute -top-1.5 -right-1.5 z-10 w-[18px] h-[18px] bg-gray-900 hover:bg-red-500 border-2 border-white rounded-full flex items-center justify-center cursor-pointer transition-colors';
                rmBtn.innerHTML = `<svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>`;
                rmBtn.onclick = (e) => removeImage(i, e);

                // Thumbnail image
                const inner = document.createElement('div');
                inner.className = 'thumb-inner bg-gray-100 dark:bg-gray-700';
                inner.innerHTML = `<img src="${img.url}" class="w-full h-full object-cover" alt="">`;
                inner.onclick = () => render(i);

                slot.appendChild(rmBtn);
                slot.appendChild(inner);
                thumbGrid.appendChild(slot);
            });

            // ＋ Add slot — always last, hidden at max
            if (images.length < MAX) {
                const add = document.createElement('div');
                add.className = 'aspect-square rounded-xl ring-1 ring-gray-300 dark:ring-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors';
                add.style.borderStyle = 'dashed';
                add.innerHTML = `<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>`;
                add.onclick = (e) => { e.stopPropagation(); inputNew.click(); };
                thumbGrid.appendChild(add);
            }

            imgCount.textContent = `${images.length}/8 images selected`;
            imgCount.classList.remove('hidden');
        }

        // Keep real file input in sync so Laravel receives all files on submit
        function syncInput() {
            const dt = new DataTransfer();
            images.forEach(img => dt.items.add(img.file));
            inputNew.files = dt.files;
        }

        function editProduct(id) {
            fetch(`/products/${id}`)
                .then(res => res.json())
                .then(data => {
                    resetModal();
                    document.getElementById('productForm').action = `/products/${id}`;
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('productName').value = data.name;
                    document.getElementById('modalTitle').innerText = 'Edit Product';

                    // Preload existing images into preview (these won't be re-uploaded unless changed)
                    if (data.images.length) {
                        images = data.images.map(img => ({ url: img.image_url, file: null }));
                        render(0);
                    }


                });        }
                
    </script>

@endsection