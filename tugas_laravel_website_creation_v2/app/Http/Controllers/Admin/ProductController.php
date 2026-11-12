<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Dashboard: manage products (list as a table, add, edit, delete).
 * Routes: /dashboard/products/... (only for logged-in users, see routes/web.php)
 */
class ProductController extends Controller
{
    // Where uploaded product images are stored (inside /public, so the browser can load them)
    private const UPLOAD_DIR = 'uploads/products';

    private function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // max 2 MB
        ];
    }

    // GET /dashboard/products            : product table, 10 per page
    // GET /dashboard/products?q=serum     : search by name (sidebar search box)
    // GET /dashboard/products?stock=low   : only products with less than 10 in stock
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%' . $request->q . '%'))
            ->when($request->stock === 'low', fn ($query) => $query->where('stock', '<', 10))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.products.index', compact('products'));
    }

    // GET /dashboard/products/create : add form
    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('dashboard.products.create', compact('categories'));
    }

    // POST /dashboard/products : save a new product
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImage($request);
        }

        Product::create($data);

        return redirect()->route('dashboard.products.index')->with('success', 'Product added.');
    }

    // GET /dashboard/products/{product}/edit : edit form
    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    // PUT /dashboard/products/{product} : save changes
    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            // New image uploaded: delete the old file, save the new one
            $this->deleteImage($product);
            $data['image'] = $this->saveImage($request);
        } elseif ($request->boolean('remove_image')) {
            // "Remove image" ticked: the shop shows the "No photo yet" placeholder
            $this->deleteImage($product);
            $data['image'] = null;
        } else {
            unset($data['image']); // keep the current image
        }

        $product->update($data);

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated.');
    }

    // DELETE /dashboard/products/{product} : delete the product (and its image file)
    public function destroy(Product $product)
    {
        $this->deleteImage($product);
        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted.');
    }

    // Move the uploaded file to public/uploads/products and return its path for the database.
    // Uses PHP's move_uploaded_file() instead of $file->move(): on this OneDrive folder Windows
    // marks folders "ReadOnly", which makes Laravel's is_writable() check fail even though
    // writing works fine.
    private function saveImage(Request $request): string
    {
        $file = $request->file('image');
        $name = time() . '_' . Str::random(6) . '.' . $file->extension();
        $dir = public_path(self::UPLOAD_DIR);

        File::ensureDirectoryExists($dir);
        if (! move_uploaded_file($file->getPathname(), $dir . DIRECTORY_SEPARATOR . $name)) {
            throw new \RuntimeException('The uploaded image could not be saved.');
        }

        return self::UPLOAD_DIR . '/' . $name;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image) {
            File::delete(public_path($product->image));
        }
    }
}
