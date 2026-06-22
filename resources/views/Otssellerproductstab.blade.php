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
                    <tr>
                        <td class="ps-3">
                            <img src="https://via.placeholder.com/70" class="product-img-thum" alt="Squeaky Dog Bone">
                        </td>
                        <td class="fw-bold">Squeaky Dog Bone</td>
                        <td><span class="badge bg-secondary px-2.5 py-1.5"><i class="fa-solid fa-bone me-1"></i> DOG</span></td>
                        <td>50</td>
                        <td class="fw-bold">₱250.00</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="populateEditModal('Squeaky Dog Bone', 'A classic, durable bone-shaped toy with a soft squeaker, perfect for active pups.', 'DOG', 50, 250.00)">
                                <i class="fa-solid fa-pen"></i> EDIT
                            </button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> DELETE</button>
                        </td>
                    </tr>
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
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="image-upload-box text-center p-3">
                                    <i class="fa-solid fa-camera fa-2x mb-2" style="color: var(--ppp-red)"></i>
                                    <span class="fw-bold d-block mb-1" style="color: var(--ppp-red)">IMAGE UPLOAD</span>
                                    <small class="text-muted">Click or Drag to Upload</small>
                                    <input type="file" name="product_image" class="d-none" id="imgUploadInput">
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
                                    <select class="form-select" name="category" required>
                                        <option value="" selected disabled>Select Pet Category</option>
                                        <option value="DOG">Dog</option>
                                        <option value="CAT">Cat</option>
                                        <option value="ALL">All Pets</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Product Stocks</label>
                                        <input type="number" class="form-control" name="stocks" value="0" min="0">
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
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-5 text-center">
                                <div class="p-3 border bg-white rounded-3 mb-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <img src="https://via.placeholder.com/150" id="editPreviewImage" class="img-fluid max-h-100" alt="Preview">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-bold">CHANGE IMAGE</button>
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
                                    <select class="form-select" id="editCategory" name="category" required>
                                        <option value="DOG">DOG</option>
                                        <option value="CAT">CAT</option>
                                        <option value="ALL">ALL</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Product Stocks</label>
                                        <input type="number" class="form-control" id="editStocks" name="stocks">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Price (PHP)</label>
                                        <input type="number" class="form-control" id="editPrice" name="price" step="0.01">
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
    function populateEditModal(name, desc, category, stocks, price) {
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = desc;
        document.getElementById('editCategory').value = category;
        document.getElementById('editStocks').value = stocks;
        document.getElementById('editPrice').value = price;
    }
</script>
@endsection