<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Dashboard: manage product categories (list with product count, add, edit, delete).
 * Routes: /dashboard/categories/... (only for logged-in users, see routes/web.php)
 */
class CategoryController extends Controller
{
    // GET /dashboard/categories : category table with the number of products in each
    public function index()
    {
        // withCount('products') adds $category->products_count using the hasMany relation
        $categories = ProductCategory::withCount('products')->orderBy('id')->get();

        return view('dashboard.categories.index', compact('categories'));
    }

    // GET /dashboard/categories/create : add form
    public function create()
    {
        return view('dashboard.categories.create');
    }

    // POST /dashboard/categories : save a new category
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100|unique:categories,name',
        ]);

        ProductCategory::create($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category added.');
    }

    // GET /dashboard/categories/{category}/edit : edit form
    public function edit(ProductCategory $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    // PUT /dashboard/categories/{category} : save changes
    public function update(Request $request, ProductCategory $category)
    {
        $data = $request->validate([
            // unique, but ignore this category's own current name
            'name' => ['required', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
        ]);

        $category->update($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated.');
    }

    // DELETE /dashboard/categories/{category}
    // Its products are not deleted: the foreign key sets their category_id to NULL ("Uncategorized").
    public function destroy(ProductCategory $category)
    {
        $count = $category->products()->count();
        $category->delete();

        $message = $count > 0
            ? "Category deleted. {$count} product(s) are now uncategorized."
            : 'Category deleted.';

        return redirect()->route('dashboard.categories.index')->with('success', $message);
    }
}
