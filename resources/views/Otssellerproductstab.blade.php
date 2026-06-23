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
                        <tr>
                            <td class="ps-3">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/70' }}" class="product-img-thum" alt="{{ $product->name }}">
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-secondary px-2.5 py-1.5">
                                    <i class="fa-solid fa-paw me-1"></i> {{ $product->category_name ?? 'General' }}
                                </span>
                            </td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td class="fw-bold">₱{{ number_format($product->price, 2) }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="populateEditModal({{ $product->id }}, @json($product->name), @json($product->description), {{ $product->category_id }}, {{ $product->stock_quantity }}, {{ $product->price }}, @json($product->image))">
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
                                <div class="image-upload-box text-center p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('imgUploadInput').click();">
                                    <i class="fa-solid fa-camera fa-2x mb-2" style="color: var(--ppp-red)"></i>
                                    <span class="fw-bold d-block mb-1" style="color: var(--ppp-red)">IMAGE UPLOAD</span>
                                    <small class="text-muted">Click to Upload</small>
                                    <input type="file" name="product_image" class="d-none" id="imgUploadInput" onchange="previewImage(this, 'addPreviewImage')">
                                    <img id="addPreviewImage" class="img-fluid mt-2 d-none" style="max-height: 100px; object-fit: contain;">
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
                                <div class="p-3 border bg-white rounded-3 mb-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <img src="https://via.placeholder.com/150" id="editPreviewImage" class="img-fluid max-h-100" alt="Preview">
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
    function populateEditModal(id, name, desc, category, stocks, price, image) {
        // Dynamically updates form action routing targeted precisely to individual record IDs
        document.getElementById('editProductForm').action = '/seller/products/' + id;
        
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = desc;
        document.getElementById('editCategory').value = category;
        document.getElementById('editStocks').value = stocks;
        document.getElementById('editPrice').value = price;
        
        // Render current or placeholder storage image paths
        document.getElementById('editPreviewImage').src = image ? '{{ asset('storage') }}/' + image : 'https://via.placeholder.com/150';
    }

    // Helper previewing locally loaded file selections inside dialog structures immediately before uploading
    function previewImage(input, previewElementId) {
        const preview = document.getElementById(previewElementId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection