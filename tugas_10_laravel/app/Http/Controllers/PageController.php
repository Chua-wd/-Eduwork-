<?php

namespace App\Http\Controllers;

use App\Models\Product;

class PageController extends Controller
{
    // Halaman utama: tampilkan 3 produk terbaru
    public function home()
    {
        $products = Product::latest()->take(3)->get();

        return view('pages.home', compact('products'));
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
