<?php

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
})->name('seller.earnings')

;
