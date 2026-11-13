<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
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

// Cart: anyone can use it, no login needed (kept in the session)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// Checkout -> payment form -> done: customer must be logged in
// (guests are sent to the login page and come back here afterwards)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{checkout}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/{checkout}/payment', [CheckoutController::class, 'sendPayment'])->name('checkout.payment.send');
    Route::get('/checkout/{checkout}/done', [CheckoutController::class, 'done'])->name('checkout.done');
});

// ===== Dashboard (logged-in users only) =====
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Overview page: KPIs, charts and lists (?range=7|30|90)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // /dashboard/products and /dashboard/categories
    // Route names: dashboard.products.index, dashboard.categories.edit, ...
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('products', AdminProductController::class)->except(['show', 'store']);
        Route::resource('categories', AdminCategoryController::class)->except(['show', 'store']);
    });

    // Save new product / category (POST). Kept outside the "dashboard." name prefix
    // so the route names are exactly product.store and product-category.store
    Route::post('/dashboard/products', [AdminProductController::class, 'store'])->name('product.store');
    Route::post('/dashboard/categories', [AdminCategoryController::class, 'store'])->name('product-category.store');
});

// ===== User management (system admins only) =====
// dashboard.users.index (list), .update (change role), .destroy (delete)
Route::middleware(['auth', 'verified', 'system_admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('users', AdminUserController::class)->only(['index', 'update', 'destroy']);
});

// Profile (Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Log in, sign up, forgot password, ... (routes/auth.php from Laravel Breeze)
require __DIR__.'/auth.php';
