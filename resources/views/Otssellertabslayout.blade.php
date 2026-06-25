<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerPuff Pets - Seller Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --ppp-red: #721c24;
            --ppp-bg-beige: #f4eee1;
            --ppp-card-white: #ffffff;
            --ppp-text-dark: #2b2b2b;
        }

        body {
            background-color: var(--ppp-bg-beige);
            color: var(--ppp-text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styling (Left Side Features) */
        .sidebar {
            background-color: var(--ppp-red);
            min-height: 100vh;
            color: white;
            padding-top: 1.5rem;
        }

        .brand-title {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.65rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            margin: 0.2rem 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: var(--ppp-red) !important;
            background-color: var(--ppp-bg-beige);
        }

        /* Shared Dashboard Components */
        .custom-card {
            background-color: var(--ppp-card-white);
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .table-header {
            background-color: #fcfaf6;
            color: var(--ppp-red);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .btn-ppp-red {
            background-color: var(--ppp-red);
            color: white;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            transition: background 0.2s;
        }

        .btn-ppp-red:hover {
            background-color: #56141a;
            color: white;
        }

        .product-img-thum {
            width: 1.6cm;
            height: 1.6cm;
            object-fit: cover;
            border: 1px solid #e2dcd0;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
        }

        .image-upload-box img,
        #addPreviewImage,
        #editPreviewImage {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
            border-radius: 10px;
        }

        #editPreviewImage {
            display: block;
        }

        .image-preview-box img,
        #addPreviewImage,
        #editPreviewImage {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 10px;
        }

        .image-upload-box img {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
        }

        .modal-content {
            border-radius: 16px;
            border: 3px solid var(--ppp-red);
            background-color: var(--ppp-bg-beige);
        }

        .image-upload-box {
            border: 2px dashed var(--ppp-red);
            background-color: rgba(114, 28, 36, 0.05);
            border-radius: 12px;
            height: 100%;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-0">
            <div class="position-sticky">
                <div class="px-4 text-center mb-4">
                    <h2 class="brand-title m-0 text-white">POWERPUFF<br><span style="color: #f4eee1;">PETS</span></h2>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('seller/products*') ? 'active' : '' }}" href="/seller/products">
                            <i class="fa-solid fa-box me-2"></i> PRODUCTS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('seller/orders*') ? 'active' : '' }}" href="/seller/orders">
                            <i class="fa-solid fa-list-check me-2"></i> ORDERS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('seller/earnings*') ? 'active' : '' }}" href="/seller/earnings">
                            <i class="fa-solid fa-wallet me-2"></i> EARNINGS
                        </a>
                    </li>

                    <li class="nav-item mt-4 border-top pt-3 mx-3" style="border-color: rgba(255,255,255,0.1) !important;">
                        <a class="nav-link text-white text-center rounded-pill" style="background-color: rgba(0,0,0,0.2);" href="{{ route('landing') }}">
                            <i class="fa-solid fa-store me-2"></i> BACK TO SHOPPING
                        </a>
                    </li>

                    <li class="nav-item mt-2 mx-3">
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="nav-link text-warning w-100 text-center border-0 bg-transparent fw-bold" style="cursor: pointer;">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> LOG OUT
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')

</body>
</html>