@extends('common.main')
@section('title', 'PPP')
@section('content')

<div class="container mt-4">
    <div class="row g-3 p-8"> 
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9">
                <img src="{{ asset('banners/main.png') }}" class="object-fit-cover" alt="Bubble Up the Fun">
            </div>
        </div>
        <div class="col-lg-4 d-flex flex-column gap-3">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9">
                <img src="{{ asset('banners/small1.png') }}" class="object-fit-cover" alt="We Care About Your Pet">
            </div>
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9 mt-auto">
                <img src="{{ asset('banners/small2.png') }}" class="object-fit-cover" alt="Pawsome Deals">
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0 text-uppercase" style="color: brown;">Pet Essentials</h3>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 text-center">
        @foreach(['Food'=>'pet1.png', 'Toys'=>'pet2.png', 'Accessories'=>'pet3.png', 'Grooming Products'=>'pet4.png', 'Medicine'=>'pet5.png'] as $cat => $img)
        <div class="col">
            <a href="{{ route('products.catalog', ['product_category' => $cat]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown;">
                    <img src="{{ asset('banners/'.$img) }}" alt="{{ $cat }}" class="img-fluid mx-auto mb-2" style="max-height: 80px; object-fit: contain;">
                    <h6 class="text-white fw-medium mb-0">{{ $cat }}</h6>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0 text-uppercase" style="color: brown;">Best Sellers</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm mb-3">{{ session('success') }}</div>
    @endif

    @if($bestSellers->isEmpty())
        <div class="text-center py-4 text-muted"> <p>No products available yet.</p> </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach($bestSellers as $loop_product)
                @php
                    $imgSrc = $loop_product->image 
                        ? (str_starts_with($loop_product->image, 'http') ? $loop_product->image : asset($loop_product->image))
                        : asset('images/pet3.png');
                @endphp
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card">
                        <img src="{{ $imgSrc }}" class="card-img-top p-4" alt="{{ $loop_product->name }}" style="object-fit: contain; height: 180px;">
                        <div class="card-body pt-0">
                            <small class="text-muted fw-medium">{{ $loop_product->seller->name ?? 'PowerPuff Pets' }}</small>
                            <a href="{{ route('product.show', $loop_product->slug) }}" class="text-decoration-none text-dark">
                                <h6 class="card-title fw-bold text-truncate-2 mb-1">{{ $loop_product->name }}</h6>
                            </a>
                            <div class="mb-3"><span class="fw-bold fs-5" style="color: brown;">₱{{ number_format($loop_product->price, 2) }}</span></div>
                            <div class="mt-auto">
                                @auth
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $loop_product->id }}">
                                        <button type="submit" class="btn btn-sm text-white rounded-pill w-100" style="background-color: brown;">Add to Cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm text-white rounded-pill w-100" style="background-color: brown;">Add to Cart</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="container mt-5 pt-4 mb-5">
    <div class="card border-0 rounded-4 shadow-lg overflow-hidden" style="background-color: brown;">
        <div class="row g-0 align-items-center">
            <div class="col-lg-7 p-4 p-md-5">
                <h2 class="fw-bold text-white mb-3">Grow Your Pet Business!</h2>
                <a href="{{ route('vendor.step1') }}" class="btn btn-warning rounded-pill px-5 py-3 fw-bold">Register as a Vendor</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="{{ asset('banners/apply.png') }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;" alt="Vendor">
            </div>
        </div>
    </div>
</div>

<style>
    .product-card { transition: transform 0.2s; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection