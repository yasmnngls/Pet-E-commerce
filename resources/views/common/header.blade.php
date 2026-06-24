<nav class="navbar navbar-expand-lg custom-nav px-4 py-2" data-bs-theme="dark">
  <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
    
    <div class="d-flex align-items-center gap-3">
      <a class="navbar-brand fw-bold mb-0 text-white fs-4 d-flex align-items-center gap-2" href="{{ route('landing') }}">
        <i class="bi bi-shop"></i> PowerPuff Pets
      </a>

      <div class="dropdown d-none d-lg-block">
        <button class="btn btn-link text-white text-decoration-none dropdown-toggle fw-medium" type="button" data-bs-toggle="dropdown">
          Categories
        </button>
        <ul class="dropdown-menu shadow-sm mt-2">
          <li><h6 class="dropdown-header">Pet Categories</h6></li>
          @php
            $petCategories = \App\Models\Category::where('type', 'pet')->get();
          @endphp
          @foreach($petCategories as $petCategory)
            <li><a class="dropdown-item" href="#">{{ $petCategory->name }}</a></li>
          @endforeach
          <li><hr class="dropdown-divider"></li>
          <li><h6 class="dropdown-header">Product Categories</h6></li>
          @php
            $productCategories = \App\Models\Category::where('type', 'product')->get();
          @endphp
          @foreach($productCategories as $productCategory)
            <li><a class="dropdown-item" href="#">{{ $productCategory->name }}</a></li>
          @endforeach
        </ul>
      </div>
    </div>

    <form class="d-flex mx-auto w-50" role="search">
      <input class="form-control rounded-start-pill bg-white text-dark border-0 px-4 py-2" type="search" placeholder="Search for products, brands..." aria-label="Search">
      <button class="btn text-white rounded-end-pill px-4" type="button" style="background-color: #8b2323;">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <div class="d-flex align-items-center gap-4">
      <a href="{{ route('cart.index') }}" class="text-white fs-5 text-decoration-none position-relative mt-1">
        <i class="bi bi-cart3"></i>
        @auth
          @php
            $cartCount = \App\Http\Controllers\CartController::cartCount();
          @endphp
          @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem;">
              {{ $cartCount }}
            </span>
          @endif
        @endauth
      </a>

      <div class="vr text-white opacity-75" style="min-height: 35px; width: 2px;"></div>

      <a href="#profileOffcanvas" data-bs-toggle="offcanvas" role="button" aria-controls="profileOffcanvas" style="outline: none;">
        <img src="{{ Auth::check() && Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('storage/banners/pet1.png') }}" 
             alt="Profile" width="45" height="45" class="rounded-circle border border-2 border-light shadow-sm" style="object-fit: cover;">
      </a>
    </div>

  </div>
</nav>

<div class="offcanvas offcanvas-end shadow-lg border-0" tabindex="-1" id="profileOffcanvas" aria-labelledby="profileOffcanvasLabel">
  
  <div class="offcanvas-header text-white" style="background-color: #a52a2a;">
    <h5 class="offcanvas-title fw-bold" id="profileOffcanvasLabel">
      <i class="bi bi-person-circle me-2"></i> My Account
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column p-0">
    
    @auth
    <div class="p-4" style="background-color: #F5EFE6;">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : asset('storage/banners/pet1.png') }}" 
                 alt="Profile" width="60" height="60" class="rounded-circle border border-2 border-white shadow-sm" style="object-fit: cover;">
            <div>
                <h5 class="fw-bold m-0 text-dark">{{ auth()->user()->name }}</h5>
                <span class="small text-muted">{{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>

    <div class="list-group list-group-flush mt-2">
        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action py-3 border-0">
            <i class="bi bi-box-seam me-3 text-muted"></i> My Orders
        </a>
        <a href="#" class="list-group-item list-group-item-action py-3 border-0">
            <i class="bi bi-heart me-3 text-muted"></i> Wishlist
        </a>
        <a href="{{ route('account.settings') }}" class="list-group-item list-group-item-action py-3 border-0">
            <i class="bi bi-gear me-3 text-muted"></i> Account Settings
        </a>
        
        @if(auth()->user()->role === 'seller')
            <div class="p-3 mt-2">
                <a href="{{ route('seller.products') }}" class="btn text-white w-100 fw-bold py-2 shadow-sm rounded-pill" style="background-color: #a52a2a;">
                    <i class="bi bi-shop me-2"></i> Go to Seller Centre
                </a>
            </div>
        @elseif(auth()->user()->role === 'admin')
            <div class="p-3 mt-2">
                <a href="{{ route('admin.dashboard') }}" class="btn text-white w-100 fw-bold py-2 shadow-sm rounded-pill" style="background-color: #343a40;">
                    <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                </a>
            </div>
        @else
            <div class="p-3 mt-2">
                <div class="card border-0 shadow-sm" style="background-color: #fff3f3; border-left: 4px solid #a52a2a !important;">
                    <div class="card-body py-3">
                        <h6 class="fw-bold" style="color: #a52a2a;">Start Selling!</h6>
                        <p class="small text-muted mb-2">Turn your pet passion into profit.</p>
                        <a href="{{ route('vendor.step1') }}" class="btn btn-sm text-white w-100 fw-bold" style="background-color: #a52a2a;">Apply as a Vendor</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-auto p-3">
        <form id="secure-logout-form" method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-light border w-100 fw-bold text-danger py-2">
                <i class="bi bi-box-arrow-right me-2"></i> Log Out
            </button>
        </form>
    </div>
    @endauth

  </div>
</div>

<style>
    .custom-nav {
        background-color: brown;
    }
</style>