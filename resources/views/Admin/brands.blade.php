
@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2>Brands</h2>
    </div>

    <div class="card filter-bar">
        <input type="text" placeholder="Search brand..." class="input">
        <button class="btn-primary add-btn" onclick="openModal()">+ Add Brand</button>
    </div>

    <div class="card">

        <table class="table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($brands as $brand)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if ($brand->image)
                                <img src="{{ $brand->image }}" class="category-image">
                            @else
                                <div class="no-image">N</div>
                            @endif
                        </td>

                        <td>{{ $brand->name }}</td>
                        <td>
                            @if ($brand->country)
                                {{ $brand->country }}
                            @else
                                <div class="no-image">N</div>
                            @endif
                        </td>

                        <td>

                            <a href="#" class="btn-sm edit"
                                onclick="editBrand({{ $brand->id }}, '{{ $brand->name }}', '{{ $brand->image }}')">
                                Edit
                            </a>

                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="delete-form"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-sm delete">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>


    <!-- ================= MODAL ================= -->

    <div class="modal" id="brandModal">

        <div class="modal-content fb-style">

            <div class="modal-header">
                <h3 id="modalTitle">Add Brand</h3>
                <span class="close" onclick="closeModal()">×</span>
            </div>

            <form id="brandForm" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data"
                onsubmit="closeModal()">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="form-group">
                    <input type="text" name="name" id="brandName" class="fb-input" placeholder="Brand name..."
                        required>
                </div>


                <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">

                    <input type="file" name="image" id="imageInput" hidden accept="image/*">

                    <div id="placeholderText">+</div>

                    <div id="imagePreviewWrapper">
                        <img id="imagePreview">
                        <span class="remove-image" onclick="removeImage(event)">×</span>
                    </div>

                </div>

                <div style="margin-top:20px;">
                    <button type="submit" class="fb-btn">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ================= DELETE ALERT ================= */

        document.querySelectorAll(".delete-form").forEach(function(form) {

            form.addEventListener("submit", function(e) {

                e.preventDefault()

                Swal.fire({
                    title: "Confirm",
                    text: "Are you sure want to permanently delete this brand?",
                    icon: "warning",
                    width: 380,
                    showCancelButton: true,
                    confirmButtonColor: "#2b7dbd",
                    cancelButtonColor: "#e74c3c",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "Cancel"
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit()
                    }

                })

            })

        })


        /* ================= MODAL ================= */

        function openModal() {

            document.getElementById('brandModal').classList.add('show')

            document.getElementById("modalTitle").innerText = "Add Brand"

            document.getElementById("brandForm").action = "{{ route('brands.store') }}"

            document.getElementById("formMethod").value = "POST"

            document.getElementById("brandName").value = ""

            imageInput.value = ""
            preview.src = ""
            wrapper.style.display = "none"
            placeholder.style.display = "block"

        }

        function closeModal() {
            document.getElementById('brandModal').classList.remove('show')
        }


        /* ================= EDIT BRAND ================= */

        function editBrand(id, name, image) {

            openModal()

            document.getElementById("modalTitle").innerText = "Edit Brand"

            document.getElementById("brandName").value = name

            document.getElementById("brandForm").action = "/admin/brands/" + id

            document.getElementById("formMethod").value = "PUT"

            if (image) {
                preview.src = image
                wrapper.style.display = "block"
                placeholder.style.display = "none"
            }

        }


        /* ================= CLOSE MODAL CLICK OUTSIDE ================= */

        window.onclick = function(e) {

            const modal = document.getElementById('brandModal')

            if (e.target === modal) {
                closeModal()
            }

        }


        /* ================= IMAGE PREVIEW ================= */

        const imageInput = document.getElementById("imageInput")
        const preview = document.getElementById("imagePreview")
        const wrapper = document.getElementById("imagePreviewWrapper")
        const placeholder = document.getElementById("placeholderText")

        imageInput.addEventListener("change", function() {

            const file = this.files[0]

            if (file) {

                preview.src = URL.createObjectURL(file)

                wrapper.style.display = "block"
                placeholder.style.display = "none"

            }

        })


        function removeImage(event) {

            event.stopPropagation()

            imageInput.value = ""
            preview.src = ""

            wrapper.style.display = "none"
            placeholder.style.display = "block"

        }
    </script>
@endsection
