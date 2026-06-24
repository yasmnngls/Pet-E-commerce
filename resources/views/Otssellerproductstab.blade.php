@extends('Otssellertabslayout')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap pb-3 mb-4 border-bottom">
        <h1 class="h3 font-weight-bold">Seller Product Management</h1>
        <div class="d-flex gap-3 align-items-center">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search products...">
            </div>
            <button class="btn btn-ppp-red py-2 px-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa-solid fa-plus me-1"></i> ADD NEW PRODUCT
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="custom-card p-4">
        <h5 class="mb-4 font-weight-bold">Your Product Listings</h5>
        
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-header">
                    <tr>
                        <th scope="col" class="ps-3">Product Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Pet Category</th>
                        <th scope="col">Stocks</th>
                        <th scope="col">Price (PHP)</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            if ($product->image && file_exists(public_path($product->image))) {
                                $productImageUrl = asset($product->image);
                            } elseif ($product->image && file_exists(storage_path('app/public/' . str_replace('storage/', '', $product->image)))) {
                                $productImageUrl = asset('storage/' . str_replace('storage/', '', $product->image));
                            } else {
                                $productImageUrl = asset('images/default-product.png'); 
                            }

                            $categoryIcon = 'fa-paw';
                            switch(strtolower($product->category_name ?? '')) {
                                case 'cat':
                                case 'cats':
                                    $categoryIcon = 'fa-cat';
                                    break;
                                case 'fish':
                                case 'fishes':
                                    $categoryIcon = 'fa-fish';
                                    break;
                                case 'bird':
                                case 'birds':
                                    $categoryIcon = 'fa-dove';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <button type="button" class="btn p-0 border-0 bg-transparent" onclick="showProductImageModal(@json($productImageUrl), @json($product->name))" data-bs-toggle="modal" data-bs-target="#productImageModal">
                                    <img src="{{ $productImageUrl }}" class="product-img-thum" alt="{{ $product->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
                                </button>
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-secondary px-2.5 py-1.5">
                                    <i class="fa-solid {{ $categoryIcon }} me-1"></i> {{ $product->category_name ?? 'General' }}
                                </span>
                            </td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td class="fw-bold">₱{{ number_format($product->price, 2) }}</td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary me-1 edit-product-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProductModal"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-description="{{ $product->description }}"
                                        data-category="{{ $product->category_id }}"
                                        data-stocks="{{ $product->stock_quantity }}"
                                        data-price="{{ $product->price }}"
                                        data-image="{{ $productImageUrl }}">
                                    <i class="fa-solid fa-pen"></i> EDIT
                                </button>
                                <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> DELETE</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted fw-bold">No products found yet. Add your first item with the button above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="productImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none position-relative">
                <div class="modal-body p-0 d-flex justify-content-center align-items-center">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.8));"></button>
                    <img id="productImageModalImg" src="" alt="Product Preview" class="img-fluid rounded" style="max-height: 85vh; max-width: 100%; object-fit: contain; box-shadow: 0 10px 30px rgba(0,0,0,0.5); background-color: #fff; border: 4px solid #fff;">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title mx-auto fw-bold" style="color: var(--ppp-red)">Add New Product</h4>
                </div>
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="image-upload-box text-center p-3 border rounded d-flex flex-column justify-content-center align-items-center" style="cursor: pointer; min-height: 200px; background-color: #f8f9fa;" onclick="document.getElementById('imgUploadInput').click();">
                                    <div id="addUploadPlaceholder">
                                        <i class="fa-solid fa-camera fa-2x mb-2" style="color: var(--ppp-red)"></i>
                                        <span class="fw-bold d-block mb-1" style="color: var(--ppp-red)">IMAGE UPLOAD</span>
                                        <small class="text-muted">Click to Upload</small>
                                    </div>
                                    <input type="file" name="product_image" class="d-none" id="imgUploadInput" onchange="previewImage(this, 'addPreviewImage', 'addUploadPlaceholder')">
                                    <img id="addPreviewImage" class="img-fluid d-none" style="width: 100%; max-height: 190px; object-fit: contain; border-radius: 6px;">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter new product name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="A detailed description of your new product"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pet Category</label>
                                    <select class="form-select" name="category_id" required>
                                        <option value="" selected disabled>Select Pet Category</option>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @empty
                                            <option value="1">Dog</option>
                                            <option value="2">Cat</option>
                                            <option value="3">Fish</option>
                                            <option value="4">Bird</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Product Stocks</label>
                                        <input type="number" class="form-control" name="stock_quantity" value="0" min="0" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Price (PHP)</label>
                                        <input type="number" class="form-control" name="price" step="0.01" placeholder="₱0.00 enter amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-3 pt-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-ppp-red px-4 py-2">ADD PRODUCT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title mx-auto fw-bold" style="color: var(--ppp-red)">Edit Product</h4>
                </div>
                <form id="editProductForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-5 text-center">
                                <div class="border bg-light rounded-3 mb-3 d-flex align-items-center justify-content-center overflow-hidden" style="height: 200px; width: 100%; border: 1px solid #dee2e6;">
                                    <img src="" id="editPreviewImage" class="w-100 h-100" alt="Product Image Preview" style="object-fit: contain; background-color: #f8f9fa;">
                                </div>
                                <label class="btn btn-sm btn-outline-secondary w-100 fw-bold mb-0" for="editProductImage">CHANGE IMAGE</label>
                                <input type="file" name="product_image" id="editProductImage" class="d-none" onchange="previewImage(this, 'editPreviewImage')">
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pet Category</label>
                                    <select class="form-select" id="editCategory" name="category_id" required>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @empty
                                            <option value="1">Dog</option>
                                            <option value="2">Cat</option>
                                            <option value="3">Fish</option>
                                            <option value="4">Bird</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Product Stocks</label>
                                        <input type="number" class="form-control" id="editStocks" name="stock_quantity" min="0" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Price (PHP)</label>
                                        <input type="number" class="form-control" id="editPrice" name="price" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-3 pt-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-ppp-red px-4 py-2">SAVE CHANGES</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Robust Data-Attribute Event Binding Setup
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-product-btn');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Read clean dataset blocks directly out of DOM target parameters
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const desc = this.getAttribute('data-description');
                const category = this.getAttribute('data-category');
                const stocks = this.getAttribute('data-stocks');
                const price = this.getAttribute('data-price');
                const imageUrl = this.getAttribute('data-image');

                // Fixed: Explicitly targets the full resource endpoint /seller/products/{id}
                document.getElementById('editProductForm').action = '/seller/products/' + id;
                
                // Form assignment statements
                document.getElementById('editName').value = name;
                document.getElementById('editDescription').value = desc;
                document.getElementById('editCategory').value = category;
                document.getElementById('editStocks').value = stocks;
                document.getElementById('editPrice').value = price;
                
                // Dynamic Image Preview Assignment
                const previewElement = document.getElementById('editPreviewImage');
                previewElement.src = imageUrl;
            });
        });
    });

    function showProductImageModal(imageUrl, imageAlt) {
        const previewImg = document.getElementById('productImageModalImg');
        previewImg.src = imageUrl;
        previewImg.alt = imageAlt;
    }

    function previewImage(input, previewElementId, placeholderId = null) {
        const preview = document.getElementById(previewElementId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholderId) {
                    document.getElementById(placeholderId).classList.add('d-none');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection