<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // GET /products -> tampilkan semua produk dari database
    public function index()
    {
        $products = Product::all();

        return view('products', compact('products'));
    }
}
