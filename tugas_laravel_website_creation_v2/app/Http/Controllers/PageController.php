<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class PageController extends Controller
{
    // Halaman utama: produk unggulan, kategori, dan 3 produk terbaru
    public function home()
    {
        $featured = Product::with('category')->where('name', 'like', '%Serum%')->first()
            ?? Product::with('category')->first();

        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        $products = Product::with('category')->latest('id')->take(3)->get();

        return view('pages.home', compact('featured', 'categories', 'products'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
