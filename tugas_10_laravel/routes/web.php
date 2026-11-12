<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ===== Public pages =====
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/contact', [PageController::class, 'contact']);

// Shop: anyone can browse the product list and product pages
Route::resource('products', ProductController::class)->only(['index', 'show']);

// Cart & checkout (from Tugas 10)
Route::get('/cart', [OrderController::class, 'cart']);
Route::get('/checkout', [OrderController::class, 'checkout']);

// ===== Dashboard (logged-in users only) =====
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Overview page: KPIs, charts and lists (?range=7|30|90)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // /dashboard/products and /dashboard/categories
    // Route names: dashboard.products.index, dashboard.categories.edit, ...
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('products', AdminProductController::class)->except('show');
        Route::resource('categories', AdminCategoryController::class)->except('show');
    });
});

// Profile (Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Log in, sign up, forgot password, ... (routes/auth.php from Laravel Breeze)
require __DIR__.'/auth.php';
