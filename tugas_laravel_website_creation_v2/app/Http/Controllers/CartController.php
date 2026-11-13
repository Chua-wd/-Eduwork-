<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET /cart
    public function index()
    {
        return view('cart', Cart::summary());
    }

    // POST /cart/{product}  ("Add to cart" on the product page)
    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        if ($product->stock < 1) {
            return back()->withErrors(['quantity' => 'Sorry, this product is out of stock.']);
        }

        // The cart never holds more than the stock, so count what was really added
        $before = Cart::quantities()[$product->id] ?? 0;
        Cart::add($product, $data['quantity']);
        $added = (Cart::quantities()[$product->id] ?? 0) - $before;

        if ($added === 0) {
            return back()->withErrors(['quantity' => "All {$product->stock} in stock are already in your cart."]);
        }

        // The product page shows an "Added to cart" popup
        return back()->with('added', ['name' => $product->name, 'quantity' => $added]);
    }

    // PATCH /cart/{product}  (the − / + buttons in the cart)
    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:99']]);

        Cart::set($product, $data['quantity']);

        return redirect()->route('cart.index');
    }

    // DELETE /cart/{product}  (the × button in the cart)
    public function destroy(Product $product)
    {
        Cart::remove($product);

        return redirect()->route('cart.index');
    }
}
