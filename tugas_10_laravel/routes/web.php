<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman (PageController)
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/contact', [PageController::class, 'contact']);

// Produk (ProductController)
// Kelola produk (tambah/edit/hapus) hanya untuk pengguna yang sudah login.
// Didaftarkan lebih dulu agar /products/create tidak tertangkap oleh /products/{product}.
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});
// Melihat produk boleh untuk semua pengunjung.
Route::resource('products', ProductController::class)->only(['index', 'show']);

// Keranjang & checkout (dari Tugas 10)
Route::get('/cart', [OrderController::class, 'cart']);
Route::get('/checkout', [OrderController::class, 'checkout']);

// ===== Laravel Breeze =====
// Halaman akun setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Login, registrasi, lupa password, dll. (routes/auth.php dari Breeze)
require __DIR__.'/auth.php';
