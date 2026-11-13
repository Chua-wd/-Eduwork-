<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * The shopping cart, kept in the visitor's session (works without logging in).
 * Stored as [product_id => quantity]; names and prices are always read fresh
 * from the database, so a price change in the dashboard shows up in the cart.
 */
class Cart
{
    private const KEY = 'cart';

    /** @return array<int, int> product_id => quantity */
    public static function quantities(): array
    {
        return array_filter(session(self::KEY, []), fn ($qty, $id) => is_int($id) && is_int($qty) && $qty > 0, ARRAY_FILTER_USE_BOTH);
    }

    // Add to what is already in the cart, never more than the stock
    public static function add(Product $product, int $quantity): void
    {
        $current = self::quantities()[$product->id] ?? 0;
        self::set($product, $current + $quantity);
    }

    // Set the exact quantity (0 removes the product)
    public static function set(Product $product, int $quantity): void
    {
        $cart = self::quantities();
        $quantity = min($quantity, $product->stock);

        if ($quantity > 0) {
            $cart[$product->id] = $quantity;
        } else {
            unset($cart[$product->id]);
        }

        session([self::KEY => $cart]);
    }

    public static function remove(Product $product): void
    {
        self::set($product, 0);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    // Number of items, for the "Cart 3" badge in the navbar
    public static function count(): int
    {
        return array_sum(self::quantities());
    }

    /**
     * Everything the cart and checkout pages show.
     *
     * @return array{lines: Collection, count: int, subtotal: int, shipping: int, total: int, freeShippingLeft: int}
     */
    public static function summary(): array
    {
        $quantities = self::quantities();
        $products = Product::with('category')->whereIn('id', array_keys($quantities))->get()->keyBy('id');

        $lines = collect($quantities)
            ->map(function ($quantity, $id) use ($products) {
                $product = $products->get($id);
                if (! $product || $product->stock < 1) {
                    return null; // deleted or sold out since it was added
                }
                $quantity = min($quantity, $product->stock);

                return ['product' => $product, 'quantity' => $quantity, 'total' => $product->price * $quantity];
            })
            ->filter()
            ->values();

        // Keep the session in line with what is shown (sold-out products dropped, quantities capped)
        session([self::KEY => $lines->mapWithKeys(fn ($line) => [$line['product']->id => $line['quantity']])->all()]);

        $subtotal = (int) $lines->sum('total');
        $shipping = self::shippingFor($subtotal);

        return [
            'lines' => $lines,
            'count' => (int) $lines->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
            'freeShippingLeft' => max(0, config('shop.free_shipping_from') - $subtotal),
        ];
    }

    public static function shippingFor(int $subtotal): int
    {
        return $subtotal === 0 || $subtotal >= config('shop.free_shipping_from') ? 0 : config('shop.shipping_fee');
    }
}
