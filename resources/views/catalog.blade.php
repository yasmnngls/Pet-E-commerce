@extends('common.main')
@section('title', 'Shop')
@section('content')

<div class="container mt-5 mb-5">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-uppercase mb-0" style="color: brown;">
            @if($query)
                Search results for "{{ $query }}"
            @elseif($catName)
                {{ $catName }}
            @else
                All Products
            @endif
        </h3>
        <small class="text-muted">{{ $products->total() }} product(s) found</small>
    </div>

    <div class="row g-4">

        {{-- LEFT: Filters --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4">

                {{-- Search --}}
                <h6 class="fw-bold mb-3" style="color: brown;">Search</h6>
                <form method="GET" action="{{ route('products.catalog') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control rounded-start-3"
                               value="{{ $query }}" placeholder="Search products...">
                        <button class="btn text-white rounded-end-3" type="submit" style="background-color: brown;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                {{-- Pet Category --}}
                <h6 class="fw-bold mb-3" style="color: brown;">By Pet</h6>
                <div class="d-flex flex-column gap-2 mb-4">
                    <a href="{{ route('products.catalog') }}{{ $query ? '?q='.urlencode($query) : '' }}"
                       class="btn btn-sm rounded-pill text-start {{ !$petCat && !$petType ? 'text-white' : 'btn-light border' }}"
                       style="{{ !$petCat && !$petType ? 'background-color: brown;' : '' }}">
                        All Pets
                    </a>
                    @foreach($petCategories as $cat)
                        <a href="{{ route('products.catalog') }}?pet_category={{ $cat->id }}{{ $query ? '&q='.$query : '' }}"
                           class="btn btn-sm rounded-pill text-start {{ $petCat == $cat->id ? 'text-white' : 'btn-light border' }}"
                           style="{{ $petCat == $cat->id ? 'background-color: brown;' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Product Category --}}
                <h6 class="fw-bold mb-3" style="color: brown;">By Type</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('products.catalog') }}{{ $query ? '?q='.urlencode($query) : '' }}{{ $petCat ? ($query ? '&' : '?').'pet_category='.$petCat : '' }}"
                       class="btn btn-sm rounded-pill text-start {{ !$prodCat ? 'text-white' : 'btn-light border' }}"
                       style="{{ !$prodCat ? 'background-color: brown;' : '' }}">
                        All Types
                    </a>
                    @foreach($productCategories as $cat)
                        <a href="{{ route('products.catalog') }}?product_category={{ urlencode($cat->name) }}{{ $query ? '&q='.urlencode($query) : '' }}{{ $petCat ? '&pet_category='.$petCat : '' }}"
                           class="btn btn-sm rounded-pill text-start {{ $prodCat == $cat->name ? 'text-white' : 'btn-light border' }}"
                           style="{{ $prodCat == $cat->name ? 'background-color: brown;' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- RIGHT: Products Grid --}}
        <div class="col-lg-9">

            @if(session('success'))
                <div class="alert alert-success rounded-3 shadow-sm mb-3">{{ session('success') }}</div>
            @endif

            @if($products->isEmpty())
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                    <i class="bi bi-search text-muted d-block mb-2" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark mb-1">No products found</h5>
                    <p class="text-muted small mb-3">Try a different search or category.</p>
                    <a href="{{ route('products.catalog') }}" class="btn btn-sm text-white rounded-pill px-4 fw-bold" style="background-color: brown;">
                        View All Products
                    </a>
                </div>
            @else
                <div class="row row-cols-2 row-cols-md-3 g-4">
                    @foreach($products as $product)
                        @php
                            // THE SUPABASE FIX: 
                            // Safely checks the database column and creates the right URL
                            $imgSrc = asset('images/pet3.png'); // Default fallback
                            
                            // Check 'image' first, fallback to 'image_url' if needed
                            $dbImage = $product->image ?? $product->image_url;

                            if (!empty($dbImage)) {
                                if (str_starts_with($dbImage, 'http')) {
                                    $imgSrc = $dbImage;
                                } else {
                                    $imgSrc = 'https://qrftqykinnwvxqyclcfl.supabase.co/storage/v1/object/public/' . $dbImage;
                                }
                            }
                        @endphp
                    
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">

                                  <img src="{{ $imgSrc }}"
                                       class="card-img-top p-3"
                                       alt="{{ $product->name }}"
                                       style="object-fit: contain; height: 160px;">

                                <div class="card-body d-flex flex-column pt-0">
                                    <small class="text-muted mb-1 fw-medium">
                                        <i class="bi bi-shop me-1"></i>
                                        {{ $product->seller->name ?? 'PowerPuff Pets' }}
                                    </small>

                                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $product->name }}</h6>
                                    </a>

                                    @if($product->category)
                                        <small class="text-muted mb-2">{{ $product->category->name }}</small>
                                    @endif

                                    <div class="mt-auto">
                                        <span class="fw-bold" style="color: brown; font-size: 1.1rem;">
                                            ₱{{ number_format($product->price, 2) }}
                                        </span>
                                    </div>

                                    <div class="mt-2">
                                        @auth
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit"
                                                        class="btn btn-sm text-white rounded-pill px-3 fw-medium w-100"
                                                        style="background-color: brown;">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="btn btn-sm text-white rounded-pill px-3 fw-medium w-100"
                                               style="background-color: brown;">
                                                Add to Cart
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .product-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
</style>

@endsection