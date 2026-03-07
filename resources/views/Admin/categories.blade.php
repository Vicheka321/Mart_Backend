@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2>Categories</h2>
    </div>

    <div class="card filter-bar">
        <input type="text" placeholder="Search category..." class="input">
        <button class="btn-primary add-btn" onclick="openModal()">+ Add Category</button>
    </div>

    <div class="card">

        <table class="table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($categories as $category)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if ($category->image)
                                <img src="{{ $category->image }}" class="category-image">
                            @else
                                <div class="no-image">N</div>
                            @endif
                        </td>

                        <td>{{ $category->name }}</td>

                        <td>

                            <a href="#" class="btn-sm edit"
                                onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->image }}')">
                                Edit
                            </a>

                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                class="delete-form" style="display:inline-block;">
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

    <div class="modal" id="categoryModal">

        <div class="modal-content fb-style">

            <div class="modal-header">
                <h3 id="modalTitle">Add Category</h3>
                <span class="close" onclick="closeModal()">×</span>
            </div>

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data"
                onsubmit="closeModal()">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="form-group">
                    <input type="text" name="name" id="categoryName" class="fb-input" placeholder="Category name..."
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
                    text: "Are you sure want to permanently delete this category?",
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

            document.getElementById('categoryModal').classList.add('show')

            document.getElementById("modalTitle").innerText = "Add Category"

            document.getElementById("categoryForm").action = "{{ route('categories.store') }}"

            document.getElementById("formMethod").value = "POST"

            document.getElementById("categoryName").value = ""

            imageInput.value = ""
            preview.src = ""
            wrapper.style.display = "none"
            placeholder.style.display = "block"

        }

        function closeModal() {
            document.getElementById('categoryModal').classList.remove('show')
        }



        /* ================= EDIT CATEGORY ================= */

        function editCategory(id, name, image) {

            openModal()

            document.getElementById("modalTitle").innerText = "Edit Category"

            document.getElementById("categoryName").value = name

            document.getElementById("categoryForm").action = "/admin/category/" + id

            document.getElementById("formMethod").value = "PUT"

            if (image) {
                preview.src = image
                wrapper.style.display = "block"
                placeholder.style.display = "none"
            }

        }



        /* ================= CLOSE MODAL CLICK OUTSIDE ================= */

        window.onclick = function(e) {

            const modal = document.getElementById('categoryModal')

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
