<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products : daftar produk
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    // GET /products/create : form tambah produk
    public function create()
    {
        return view('products.create');
    }

    // POST /products : simpan produk baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // GET /products/{product} : detail satu produk
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // GET /products/{product}/edit : form edit produk
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // PUT /products/{product} : simpan perubahan
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // DELETE /products/{product} : hapus produk
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
