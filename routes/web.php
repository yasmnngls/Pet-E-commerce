<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CatalogController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\Seller\ApplicationController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CrudController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Misc\PageController;
use App\Http\Controllers\Misc\ContactController;
use App\Http\Controllers\Misc\BlogController;

/*
|--------------------------------------------------------------------------
| Guest Routes (no login required)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
});

/*
|--------------------------------------------------------------------------
| Public Pages (accessible to everyone)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'landing'])->name('landing');
Route::get('/pets/{type}', [CatalogController::class, 'byType'])->name('pets.type');
Route::get('/pets/{type}/{slug}', [CatalogController::class, 'detail'])->name('pets.detail');
Route::get('/product/{slug}', [CatalogController::class, 'productDetail'])->name('product.detail');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/filtered', [CatalogController::class, 'filtered'])->name('filtered');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update']);
Route::delete('/cart/remove', [CartController::class, 'remove']);

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send']);
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ---------- Onboarding (before the 'onboarded' check) ----------
    Route::get('/account/setup', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/account/setup', [OnboardingController::class, 'store'])->name('onboarding.store');

    // ---------- Routes that require completed onboarding ----------
    Route::middleware('onboarded')->group(function () {

        Route::get('/home', [HomeController::class, 'home'])->name('home');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/order/confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

        // Order tracking
        Route::get('/orders/status', [OrderTrackingController::class, 'status'])->name('orders.status');
        Route::get('/orders/{id}', [OrderTrackingController::class, 'detail'])->name('orders.detail');

        // Toggle between buyer / seller role (available to any logged-in, onboarded user)
        Route::post('/toggle-role', [AccountController::class, 'toggleRole'])->name('toggle.role');

        // ---------- Buyer-only routes ----------
        Route::middleware('role:buyer')->group(function () {
            Route::get('/account', [AccountController::class, 'dashboard'])->name('account.dashboard');
            Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
            Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
            Route::put('/account/profile', [AccountController::class, 'updateProfile']);

            // Addresses
            Route::get('/account/addresses', [AddressController::class, 'index'])->name('account.addresses');
            Route::post('/account/addresses', [AddressController::class, 'store']);
            Route::put('/account/addresses/{id}', [AddressController::class, 'update']);
            Route::delete('/account/addresses/{id}', [AddressController::class, 'destroy']);

            // Wishlist
            Route::get('/account/wishlist', [WishlistController::class, 'index'])->name('account.wishlist');
            Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);

            // Reviews
            Route::get('/account/reviews', [ReviewController::class, 'index'])->name('account.reviews');
            Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
            Route::put('/reviews/{id}', [ReviewController::class, 'update']);
            Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

            // Seller application
            Route::get('/seller/apply', [ApplicationController::class, 'show'])->name('seller.apply');
            Route::post('/seller/apply', [ApplicationController::class, 'store']);
            Route::get('/seller/apply/success', [ApplicationController::class, 'success'])->name('seller.apply.success');
        });

        // ---------- Seller-only routes ----------
        Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/listings', [ProductController::class, 'listings'])->name('listings');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/create/success', [ProductController::class, 'success'])->name('products.create.success');
            Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

            Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders');
            Route::post('/orders/{id}/ship', [SellerOrderController::class, 'ship'])->name('orders.ship');

            Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
            Route::put('/profile', [DashboardController::class, 'updateProfile']);
            Route::get('/earnings', [DashboardController::class, 'earnings'])->name('earnings');
            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (separate prefix, admin authentication)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Database CRUD
        Route::get('/database', [CrudController::class, 'index'])->name('database');
        Route::get('/database/{table}', [CrudController::class, 'showTable'])->name('database.table');

        // Approvals
        Route::get('/approvals/sellers', [ApprovalController::class, 'sellers'])->name('approvals.sellers');
        Route::post('/approvals/sellers/{id}/approve', [ApprovalController::class, 'approveSeller']);
        Route::post('/approvals/sellers/{id}/reject', [ApprovalController::class, 'rejectSeller']);
        Route::get('/approvals/products', [ApprovalController::class, 'products'])->name('approvals.products');
        Route::post('/approvals/products/{id}/approve', [ApprovalController::class, 'approveProduct']);
        Route::post('/approvals/products/{id}/reject', [ApprovalController::class, 'rejectProduct']);

        // Users & Orders
        Route::get('/users', [CrudController::class, 'users'])->name('users');
        Route::get('/orders', [CrudController::class, 'orders'])->name('orders');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update']);
    });
});