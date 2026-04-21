@extends('layouts.app')

@section('content')

    <div class="bg-white rounded-xl shadow pt-3 pl-4 pr-4 pb-3 dark:bg-gray-800">


        <!-- HEADER -->
        <div class="flex items-center justify-between mb-3 ">

            <!-- Title -->
            <h2 class="text-[22px] font-semibold tracking-tight text-gray-900 dark:text-white">
                Brands
            </h2>

            <!-- Actions -->
            <div class="flex items-center gap-3">

                <!-- Export -->
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
                <!-- Add New -->
                <button onclick="openModal()"
                    class="h-9 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    Add New
                </button>

            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm text-left">

                <thead class="bg-indigo-100 dark:bg-indigo-500/10 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500">No.</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500">Image</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500">Name</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500">Country</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500">Created At</th>
                        <th class="px-5 py-2 text-[11px] font-semibold uppercase text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50 dark:divide-gray-700 h-full">

                    @forelse ($brands as $brand)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/40 transition-colors">

                            <td class="px-5 py-2 text-xs text-gray-400">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-5 py-0.5">
                                @if ($brand->image)
                                    <img src="{{ asset($brand->image) }}"
                                        class="w-8 h-8 rounded-lg object-cover border border-gray-100 dark:border-gray-600">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-semibold text-sm">
                                        {{ strtoupper(substr($brand->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-2 font-medium text-gray-800 dark:text-white">
                                {{ $brand->name }}
                            </td>

                            <td class="px-5 py-2 text-xs text-gray-400">
                                {{ $brand->country ?? 'N/A' }}
                            </td>

                            <td class="px-5 py-2 text-xs text-gray-400">
                                {{ $brand->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-2 text-right space-x-2">

                                <!-- EDIT -->
                                <button
                                    onclick="editBrand({{ $brand->id }}, '{{ $brand->name }}','{{ $brand->country ?? 'N/A' }}' ,'{{ $brand->image }}')"
                                    class="px-3.5 py-1.5 text-xs font-medium bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>

                                <!-- DELETE -->
                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3.5 py-1.5 text-xs font-medium bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">
                                No brands found
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-gray-500 dark:text-gray-400">

            <!-- Showing info -->
            <div class="text-sm text-gray-500">
                Showing
                <span class="font-medium">{{ $brands->firstItem() }}</span>
                to
                <span class="font-medium">{{ $brands->lastItem() }}</span>
                of
                <span class="font-medium">{{ $brands->total() }}</span>
                results
            </div>

            <!-- Pagination -->
            <div class="flex space-x-1">

                {{-- Previous --}}
                @if ($brands->onFirstPage())
                    <span
                        class="px-3 py-1 rounded-lg 
                                                                                                                    bg-gray-200 dark:bg-gray-700 
                                                                                                                    text-gray-400 dark:text-gray-300 
                                                                                                                    text-sm">Prev</span>
                @else
                    <a href="{{ $brands->previousPageUrl() }}"
                        class="px-3 py-1 rounded-lg 
                                                                                                                bg-white dark:bg-slate-800 
                                                                                                                border border-gray-200 dark:border-gray-700 
                                                                                                                hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                                                                                                                text-sm text-gray-700 dark:text-gray-300">Prev</a>
                @endif

                {{-- Pages --}}
                @foreach ($brands->getUrlRange(1, $brands->lastPage()) as $page => $url)
                    @if ($page == $brands->currentPage())
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
                @if ($brands->hasMorePages())
                    <a href="{{ $brands->nextPageUrl() }}"
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


    </div>

    <!-- MODAL -->
    <div id="brandModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl p-6 animate-scaleIn shadow-xl">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-5">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">
                    Add Brand
                </h3>

                <button onclick="closeModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 
                                                                                                           text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-lg leading-none">
                    &times;
                </button>
            </div>

            <!-- FORM -->
            <form id="brandForm" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- NAME -->
                <div class="flex gap-4 mb-4">

                    <!-- Brand Name -->
                    <div class="w-1/2">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1.5">
                            Brand Name
                        </label>

                        <input type="text" name="name" id="brandName" placeholder="e.g. Nike" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm 
                                                           focus:outline-none focus:ring-2 focus:ring-indigo-400 
                                                           dark:bg-gray-700 dark:text-white transition" required>
                    </div>

                    <!-- Country -->
                    <div class="w-1/2">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1.5">
                            Country
                        </label>

                        <input type="text" name="country" id="brandCountry" placeholder="e.g. USA" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm 
                                                           focus:outline-none focus:ring-2 focus:ring-indigo-400 
                                                           dark:bg-gray-700 dark:text-white transition" required>
                    </div>

                </div>

                <!-- IMAGE UPLOAD -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1.5">
                        Brand Image
                    </label>

                    <div id="uploadBox" onclick="document.getElementById('imageInput').click()"
                        class="relative w-full h-64 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-600 
                                                                                                               bg-gray-50 dark:bg-gray-700 flex flex-col items-center justify-center cursor-pointer 
                                                                                                               overflow-hidden transition hover:border-indigo-400 hover:bg-indigo-50 
                                                                                                               dark:hover:bg-gray-600/50">

                        <!-- Placeholder -->
                        <div id="uploadPlaceholder"
                            class="flex flex-col items-center gap-2 text-gray-400 pointer-events-none">

                            <div
                                class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>

                            <span class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                Click to upload image
                            </span>

                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                PNG, JPG, WEBP up to 2MB
                            </span>
                        </div>

                        <!-- Preview -->
                        <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-contain">

                        <!-- Hover overlay -->
                        <div id="editOverlay"
                            class="hidden absolute inset-0 bg-black/40 items-center justify-center pointer-events-none">
                            <div
                                class="flex items-center gap-2 bg-white/90 text-gray-800 text-sm font-medium px-4 py-2 rounded-xl shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                </svg>
                                Edit Photo
                            </div>
                        </div>

                        <!-- Remove button -->
                        <button type="button" id="removeImageBtn" onclick="removeImage(event)"
                            class="hidden absolute top-2 right-2 w-8 h-8 bg-gray-900/70 hover:bg-gray-900 text-white 
                                                                                                                   rounded-full flex items-center justify-center transition z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                    </div>

                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    Save
                </button>

            </form>
        </div>
    </div>

    {{-- Export Modal --}}
    <div id="exportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">

        <div onclick="event.stopPropagation()" class="bg-white dark:bg-[#1e293b] 
                           border border-gray-200 dark:border-gray-700
                           rounded-2xl p-6 w-80 shadow-2xl animate-scaleIn">

            <!-- Title -->
            <h3 class="text-lg font-semibold mb-5 
                               text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3v12m0 0l4-4m-4 4l-4-4" stroke="currentColor" stroke-width="2" />
                    <path d="M5 21h14" stroke="currentColor" stroke-width="2" />
                </svg>
                Export Data
            </h3>

            <!-- Options -->
            <div class="flex flex-col gap-3">

                <!-- CSV -->
                <a href="{{ route('brands.export.csv') }}" class="flex items-center justify-between px-4 py-2.5 rounded-lg 
                                   bg-gray-50 dark:bg-[#0f172a] 
                                   border border-gray-200 dark:border-gray-700 
                                   hover:bg-green-50 dark:hover:bg-green-500/10 
                                   hover:border-green-400 dark:hover:border-green-500
                                   transition group">

                    <span class="flex items-center gap-2 
                                         text-gray-700 dark:text-gray-300 
                                         group-hover:text-green-600 dark:group-hover:text-green-400">
                        📄 CSV File
                    </span>

                    <span class="text-xs 
                                         text-gray-400 dark:text-gray-500 
                                         group-hover:text-green-600 dark:group-hover:text-green-400">
                        Download
                    </span>
                </a>

                <!-- PDF -->
                <a href="{{ route('brands.export.pdf') }}" class="flex items-center justify-between px-4 py-2.5 rounded-lg 
                                   bg-gray-50 dark:bg-[#0f172a] 
                                   border border-gray-200 dark:border-gray-700 
                                   hover:bg-red-50 dark:hover:bg-red-500/10 
                                   hover:border-red-400 dark:hover:border-red-500
                                   transition group">

                    <span class="flex items-center gap-2 
                                         text-gray-700 dark:text-gray-300 
                                         group-hover:text-red-600 dark:group-hover:text-red-400">
                        📑 PDF File
                    </span>

                    <span class="text-xs 
                                         text-gray-400 dark:text-gray-500 
                                         group-hover:text-red-600 dark:group-hover:text-red-400">
                        Download
                    </span>
                </a>

            </div>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Close -->
            <button onclick="closeExportModal()" class="w-full py-2 text-sm 
                               text-gray-500 dark:text-gray-400 
                               hover:text-red-500 dark:hover:text-red-400 transition">
                Cancel
            </button>
        </div>
    </div>

    <script>
        function openExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
            document.getElementById('exportModal').classList.add('flex');
        }

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
            document.getElementById('exportModal').classList.remove('flex');
        }
    </script>


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

    <!-- SCRIPTS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    <script>
        /* ================= ELEMENTS ================= */
        const modal = document.getElementById('brandModal')
        const form = document.getElementById('brandForm')
        const modalTitle = document.getElementById('modalTitle')
        const nameInput = document.getElementById('brandName')
        const methodInput = document.getElementById('formMethod')
        const imageInput = document.getElementById('imageInput')
        const preview = document.getElementById('imagePreview')
        const placeholder = document.getElementById('uploadPlaceholder')
        const editOverlay = document.getElementById('editOverlay')
        const removeBtn = document.getElementById('removeImageBtn')
        const uploadBox = document.getElementById('uploadBox')

        /* ================= MODAL ================= */
        function openModal() {
            modal.classList.remove('hidden')
            modal.classList.add('flex')
            resetForm()
        }

        function closeModal() {
            modal.classList.add('hidden')
            modal.classList.remove('flex')
        }

        function resetForm() {
            form.action = "{{ route('brands.store') }}"
            methodInput.value = 'POST'
            nameInput.value = ''
            modalTitle.innerText = 'Add Brand'
            resetImagePreview()
        }

        /* ================= IMAGE PREVIEW ================= */
        function showImagePreview(src) {
            preview.src = src
            preview.classList.remove('hidden')
            placeholder.classList.add('hidden')
            removeBtn.classList.remove('hidden')
        }

        function resetImagePreview() {
            imageInput.value = ''
            preview.src = ''
            preview.classList.add('hidden')
            placeholder.classList.remove('hidden')
            editOverlay.classList.add('hidden')
            editOverlay.classList.remove('flex')
            removeBtn.classList.add('hidden')
        }

        function removeImage(e) {
            e.stopPropagation()
            resetImagePreview()
        }

        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0]
            if (!file) return
            showImagePreview(URL.createObjectURL(file))
        })

        // Hover: show/hide edit overlay when image is loaded
        uploadBox.addEventListener('mouseenter', () => {
            if (!preview.classList.contains('hidden')) {
                editOverlay.classList.remove('hidden')
                editOverlay.classList.add('flex')
            }
        })
        uploadBox.addEventListener('mouseleave', () => {
            editOverlay.classList.add('hidden')
            editOverlay.classList.remove('flex')
        })

        /* ================= EDIT ================= */
        function editBrand(id, name, country, image) {
            openModal()
            modalTitle.innerText = 'Edit Brand'
            nameInput.value = name
            document.getElementById('brandCountry').value = country
            form.action = '/admin/brand/' + id
            methodInput.value = 'PUT'

            if (image) {
                showImagePreview(image)
            } else {
                resetImagePreview()
            }
        }

        /* ================= DELETE ================= */
        document.querySelectorAll('.delete-form').forEach(f => {
            f.addEventListener('submit', function (e) {
                e.preventDefault()

                const isDark = document.documentElement.classList.contains('dark');

                Swal.fire({
                    title: 'Delete brand?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,

                    confirmButtonText: 'Yes, delete it',

                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',

                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#1f2937',

                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-lg font-semibold',
                        confirmButton: 'px-4 py-2 rounded-lg',
                        cancelButton: 'px-4 py-2 rounded-lg'
                    }
                }).then(result => {
                    if (result.isConfirmed) f.submit()
                })
            })
        })

        /* ================= CLOSE ON OUTSIDE CLICK ================= */
        form.addEventListener('submit', function (e) {
            e.preventDefault()

            closeModal()

            setTimeout(() => {
                this.submit() // ✅ use this (not form)
            }, 100)
        })
    </script>

@endsection