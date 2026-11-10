<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
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

    // Jumlah produk per halaman (6 = 2 baris di grid 3 kolom)
    private const PER_PAGE = 6;

    // GET /products            : semua produk, 6 per halaman
    // GET /products?category=2 : hanya produk di kategori 2
    // GET /products?page=2     : halaman ke-2
    public function index(Request $request)
    {
        // Untuk tombol filter: semua kategori + jumlah produknya (memakai relasi products())
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        $selectedCategory = null;
        if ($request->filled('category')) {
            $selectedCategory = ProductCategory::findOrFail($request->category);
            // Ambil produk lewat relasi: $kategori->products()
            $query = $selectedCategory->products();
        } else {
            $query = Product::query();
        }

        // Urutan: ?sort=new (default), ?sort=price_asc, ?sort=price_desc
        $sort = in_array($request->sort, ['price_asc', 'price_desc']) ? $request->sort : 'new';
        match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->latest('id'),
        };

        // with('category') = ambil kategori sekaligus (1 query, bukan 1 query per produk)
        // paginate() = ambil hanya 6 baris untuk halaman yang sedang dibuka
        // withQueryString() = link halaman berikutnya tetap membawa ?category=...&sort=...
        $products = $query->with('category')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('products.index', compact('products', 'categories', 'selectedCategory', 'sort'));
    }

    // GET /products/create : form tambah produk
    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

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
        $product->load('category');

        // Produk serupa: produk lain di kategori yang sama (lewat relasi)
        $related = $product->category
            ? $product->category->products()->with('category')->whereKeyNot($product->id)->take(3)->get()
            : collect();

        return view('products.show', compact('product', 'related'));
    }

    // GET /products/{product}/edit : form edit produk
    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();

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
