<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/seller', '/seller/products');

// Products Navigation Tab Route
Route::get('/seller/products', function () {
    return view('Otssellerproductstab');
})->name('seller.products');

// Orders Navigation Tab Route
Route::get('/seller/orders', function () {
    return view('Otssellerorderstab');
})->name('seller.orders');

// Earnings Navigation Tab Placeholder Route
Route::get('/seller/earnings', function () {
    return view('Otssellerearningstab');
})->name('seller.earnings');

Route::get('/', function () {
    return view('home');
});

//Landing Page
Route::get('/Home', [PageController::class, 'landing'])->name('landing');

//Login Page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

//Register Post
Route::get('/login/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login/register', [AuthController::class, 'register'])->name('register.submit');

//Logout Post
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');