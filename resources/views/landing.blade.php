@extends('common.main')
@section('title', 'PPP')
@section('content')

<div class="container mt-4">
    <div class="row g-3 p-8"> 
        
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9">
                <img src="images/snoopy2.jpg" class="object-fit-cover" alt="Bubble Up the Fun">
            </div>
        </div>

        <div class="col-lg-4 d-flex flex-column gap-3">
            
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9">
                <img src="images/snoopy3.jpg" class="object-fit-cover" alt="We Care About Your Pet">
            </div>

            <div class="card border-0 rounded-4 overflow-hidden shadow-sm ratio ratio-16x9 mt-auto">
                <img src="images/snoopy5.jpg" class="object-fit-cover" alt="Pawsome Deals">
            </div>

        </div>

    </div>
</div>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0 text-uppercase" style="color: brown;">Pet Essentials</h3>
        <a href="#" class="text-decoration-none fw-medium" style="color: brown;">View All ></a>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 text-center">
        
        <div class="col">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown; transition: transform 0.2s;">
                    <img src="images/pet2.jpg" alt="Pet Food" class="img-fluid mx-auto mb-2" style="max-height: 80px;">
                    <h6 class="text-white fw-medium mb-0">Pet Food & Treats</h6>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown; transition: transform 0.2s;">
                    <img src="images/pet3.jpg" alt="Toys" class="img-fluid mx-auto mb-2" style="max-height: 80px;">
                    <h6 class="text-white fw-medium mb-0">Pet Toys</h6>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown; transition: transform 0.2s;">
                    <img src="images/pet5.jpg" alt="Accessories" class="img-fluid mx-auto mb-2" style="max-height: 80px;">
                    <h6 class="text-white fw-medium mb-0">Beds & Accessories</h6>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown; transition: transform 0.2s;">
                    <img src="images/pet1.jpg" alt="Grooming" class="img-fluid mx-auto mb-2" style="max-height: 80px;">
                    <h6 class="text-white fw-medium mb-0">Grooming Kits</h6>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background-color: brown; transition: transform 0.2s;">
                    <img src="images/pet4.jpg" alt="Health" class="img-fluid mx-auto mb-2" style="max-height: 80px;">
                    <h6 class="text-white fw-medium mb-0">Health & Vitamins</h6>
                </div>
            </a>
        </div>

    </div>
</div>


<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0 text-uppercase" style="color: brown;">Best Sellers</h3>
        <a href="#" class="text-decoration-none fw-medium" style="color: brown;">Shop More ></a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm mb-3">{{ session('success') }}</div>
    @endif

    @if($bestSellers->isEmpty())
        <div class="text-center py-4 text-muted">
            <i class="bi bi-box-seam d-block mb-2" style="font-size: 2.5rem;"></i>
            <p class="mb-0">No products available yet. Check back soon!</p>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach($bestSellers as $loop_product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden product-card">

                        <img src="{{ asset($loop_product->image ?? 'images/pet3.jpg') }}"
                             class="card-img-top p-4"
                             alt="{{ $loop_product->name }}"
                             style="object-fit: contain; height: 180px;"
                            >

                        <div class="card-body d-flex flex-column pt-0">

                            <small class="text-muted mb-1 fw-medium">
                                <i class="bi bi-shop me-1"></i>
                                {{ $loop_product->seller->name ?? 'PowerPuff Pets' }}
                            </small>

                            <a href="{{ route('product.show', $loop_product->slug) }}" class="text-decoration-none text-dark">
                                <h6 class="card-title fw-bold text-truncate-2 mb-1" style="font-size: 0.95rem;">
                                    {{ $loop_product->name }}
                                </h6>
                            </a>

                            <div class="mb-3">
                                <span class="fw-bold fs-5" style="color: brown;">
                                    ₱{{ number_format($loop_product->price, 2) }}
                                </span>
                            </div>

                            <div class="mt-auto d-flex align-items-center justify-content-between">
                                @auth
                                    <form action="{{ route('cart.add') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $loop_product->id }}">
                                        <button type="submit" class="btn btn-sm text-white rounded-pill px-3 fw-medium" style="background-color: brown;">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm text-white rounded-pill px-3 fw-medium" style="background-color: brown;">
                                        Add to Cart
                                    </a>
                                @endauth
                                <button class="btn btn-light rounded-circle text-muted d-flex align-items-center justify-content-center border" style="width: 35px; height: 35px;">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- "Become a Vendor" -->
<div class="container mt-5 pt-4 mb-5">
    <div class="card border-0 rounded-4 shadow-lg overflow-hidden" style="background-color: brown;">
        <div class="row g-0 align-items-center">
            

            <div class="col-lg-7 p-4 p-md-5 d-flex flex-column justify-content-center">
                <span class="badge bg-warning text-dark mb-3 align-self-start rounded-pill px-3 py-2 fw-bold">Partner With Us</span>
                <h2 class="fw-bold text-white mb-3" style="font-size: 2.5rem;">Grow Your Pet Business!</h2>
                <p class="text-white opacity-75 mb-4 fs-5" style="max-width: 500px;">
                    Join our growing community of local pet shops, veterinary clinics, groomers, and shelters. Reach thousands of pet parents and manage your bookings all in one place.
                </p>
                <div>
                    <!-- The bright yellow button draws the eye immediately -->
                    <a href="{{ route('vendor.step1') }}" class="btn btn-warning rounded-pill px-5 py-3 fw-bold shadow" style="color: brown; font-size: 1.1rem;">
                        Register as a Vendor <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block position-relative h-100">
                <img src="images/snoopy3.jpg" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;" alt="Become a Vendor">
                
                <div class="position-absolute top-0 start-0 h-100" style="width: 100px; background: linear-gradient(to right, brown 0%, transparent 100%);"></div>
            </div>

        </div>
    </div>
</div>


<style>
    .card:hover { transform: translateY(-5px); }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }

    .service-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .service-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>

@endsection