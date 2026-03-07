@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2>All Products</h2>
    </div>

    <div class="card filter-bar">
        <input type="text" placeholder="Search product..." class="input">
        <button class="btn-primary add-btn" onclick="openModal()">+ Add Product</button>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>No .</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Cost Price</th>
                    <th>Sale Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $product)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if (!empty($product['image']) && count($product['image']) > 0)
                                <img src="{{ $product['image'][0]['image_url'] }}" class="product-img"
                                    onerror="this.src='https://picsum.photos/200';">
                            @else
                                <img src="https://picsum.photos/200" class="product-img">
                            @endif
                        </td>

                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['category']['name'] }}</td>
                        <td>{{ $product['brand']['name'] }}</td>
                        <td>${{ $product['cost_price'] }}</td>
                        <td>${{ $product['sale_price'] }}</td>
                        <td>{{ $product['quantity'] }}</td>

                        <td>
                            @if ($product['status'])
                                <span class="badge success">Active</span>
                            @else
                                <span class="badge danger">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <a href="#" class="btn-sm edit">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form"
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



    {{-- PRODUCT MODAL --}}
    <div class="modal" id="ProductModal">

        <div class="modal-dialog">

            <div class="modal-header">
                <h3>Add Product</h3>
                <button class="close-btn" onclick="closeModal()">✕</button>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name">
                        </div>

                        <div class="form-group">
                            <label>Product Code</label>
                            <input type="text" name="product_code">
                        </div>

                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label>Category</label>
                            <select name="categories_id">
                                <option>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Brand</label>
                            <select name="brand_id">
                                <option>Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label>Cost Price</label>
                            <input type="number" name="cost_price">
                        </div>

                        <div class="form-group">
                            <label>Sale Price</label>
                            <input type="number" name="sale_price">
                        </div>

                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="quantity">
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>


                    <div class="form-row">

                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" name="unit">
                        </div>

                        <div class="form-group ">
                            <label>Description</label>
                            <input type="text" name="unit">
                        </div>


                    </div>

                    <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">

                        <input type="file" name="image" id="imageInput" hidden accept="image/*">

                        <div id="placeholderText">+</div>

                        <div id="imagePreviewWrapper">
                            <img id="imagePreview">
                            <span class="remove-image" onclick="removeImage(event)">×</span>
                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn-cancel" onclick="closeModal()">
                        Cancel
                    </button>

                    <button class="btn-save">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll(".delete-form").forEach(function(form) {

            form.addEventListener("submit", function(e) {

                e.preventDefault()

                Swal.fire({
                    title: "Confirm",
                    text: "Are you sure want to permanently delete this product?",
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
        const imageInput = document.getElementById("imageInput")
        const preview = document.getElementById("imagePreview")
        const wrapper = document.getElementById("imagePreviewWrapper")
        const placeholder = document.getElementById("placeholderText")



        function openModal() {

            document.getElementById('ProductModal').classList.add('show')

            document.getElementById("modalTitle").innerText = "Add Product"

            document.getElementById("ProductForm").action = "{{ route('products.store') }}"

            document.getElementById("formMethod").value = "POST"

            document.getElementById("productName").value = ""

            imageInput.value = ""
            preview.src = ""

            wrapper.style.display = "none"
            placeholder.style.display = "block"
        }



        function closeModal() {
            document.getElementById('ProductModal').classList.remove('show')
        }



        window.onclick = function(e) {

            const modal = document.getElementById('ProductModal')

            if (e.target === modal) {
                closeModal()
            }

        }



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
