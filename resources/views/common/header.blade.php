<nav class="navbar navbar-expand-lg custom-nav px-4 py-2" data-bs-theme="dark">
  <div class="container-fluid p-0 d-flex align-items-center justify-content-between">

    <!-- 1. Left Side: Brand Logo & Categories -->
    <div class="d-flex align-items-center gap-3">
      <a class="navbar-brand fw-bold mb-0 text-white fs-4 d-flex align-items-center gap-2" href="{{ route('landing') }}">
        <i class="bi bi-shop"></i> PowerPuff Pets
      </a>

      <!-- Categories Dropdown -->
      <div class="dropdown d-none d-lg-block">
        <button class="btn btn-link text-white text-decoration-none dropdown-toggle fw-medium" type="button" data-bs-toggle="dropdown">
          Categories
        </button>
        <ul class="dropdown-menu shadow-sm mt-2">
          <li><a class="dropdown-item" href="#">Pet Food</a></li>
          <li><a class="dropdown-item" href="#">Toys & Accessories</a></li>
          <li><a class="dropdown-item" href="#">Clothing</a></li>
        </ul>
      </div>
    </div>

    <!-- 2. Center: Search Bar -->
    <form class="d-flex mx-auto w-50" role="search">
      <input class="form-control rounded-start-pill bg-white text-dark border-0 px-4 py-2" type="search" placeholder="Search for products, brands..." aria-label="Search">
      <button class="btn text-white rounded-end-pill px-4" type="button" style="background-color: #8b2323;">
        <i class="bi bi-search"></i>
      </button>
    </form>

    <!-- 3. Right Side: Cart & Sign Up (Updated!) -->
    <div class="d-flex align-items-center gap-4">

      <!-- Shopping Cart Icon -->
      <a href="#" class="text-white fs-5 text-decoration-none position-relative mt-1">
        <i class="bi bi-cart3"></i>
      </a>

      <!-- Sign Up Button -->
      <a href="{{ route('login') }}" class="btn bg-white fw-bold rounded-pill px-4 shadow-sm" style="color: #a52a2a;">
        Sign Up
      </a>

    </div>

  </div>
</nav>

<style>

    .custom-nav {
        background-color: #a52a2a;
        width: 100%; 
        border-radius: 0; 
    }
</style>