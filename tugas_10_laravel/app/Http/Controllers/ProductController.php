<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Aturan validasi yang sama untuk tambah & edit
    private array $rules = [
        'category_id' => 'nullable|exists:categories,id',
        'name'        => 'required|max:255',
        'description' => 'nullable',
        'price'       => 'required|integer|min:0',
        'stock'       => 'required|integer|min:0',
    ];

    // GET /products : daftar produk
    public function index()
    {
        // with('category') = ambil kategori sekaligus (1 query, bukan 1 query per produk)
        $products = Product::with('category')->latest('id')->get();

        return view('products.index', compact('products'));
    }

    // GET /products/create : form tambah produk
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // POST /products : simpan produk baru
    public function store(Request $request)
    {
        $data = $request->validate($this->rules);

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
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    // PUT /products/{product} : simpan perubahan
    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules);

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
