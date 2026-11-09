<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Halaman (PageController)
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/contact', [PageController::class, 'contact']);

// Produk (ProductController) - 1 baris = 7 route CRUD
Route::resource('products', ProductController::class);

// Keranjang & checkout (dari Tugas 10)
Route::get('/cart', [OrderController::class, 'cart']);
Route::get('/checkout', [OrderController::class, 'checkout']);
