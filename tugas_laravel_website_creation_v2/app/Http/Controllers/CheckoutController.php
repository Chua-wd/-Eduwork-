<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Cart -> Checkout (shipping details + total) -> Payment form -> Done.
 * All pages need a logged-in customer (routes use the "auth" middleware).
 */
class CheckoutController extends Controller
{
    // GET /checkout
    public function create(Request $request)
    {
        $summary = Cart::summary();

        if ($summary['lines']->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('checkout', $summary + ['user' => $request->user()]);
    }

    // POST /checkout  ("Place order")
    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $quantities = Cart::quantities();
        if (! $quantities) {
            return redirect()->route('cart.index');
        }

        $checkout = DB::transaction(function () use ($data, $quantities, $request) {
            // Lock the products so two customers can't buy the last item at the same time
            $products = Product::whereIn('id', array_keys($quantities))->lockForUpdate()->get()->keyBy('id');

            foreach ($quantities as $id => $quantity) {
                $product = $products->get($id);
                if (! $product || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => ($product?->name ?? 'A product') . ' no longer has enough stock. Please check your cart.',
                    ]);
                }
            }

            $subtotal = collect($quantities)->sum(fn ($quantity, $id) => $products[$id]->price * $quantity);
            $shipping = Cart::shippingFor($subtotal);

            $checkout = Checkout::create($data + [
                'code' => $this->newCode(),
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
            ]);

            // One order row per product (the dashboard counts and charts these)
            foreach ($quantities as $id => $quantity) {
                $checkout->orders()->create([
                    'user_id' => $request->user()->id,
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'total' => $products[$id]->price * $quantity,
                    'status' => 'pending',
                ]);
                $products[$id]->decrement('stock', $quantity);
            }

            return $checkout;
        });

        Cart::clear();

        return redirect()->route('checkout.payment', $checkout);
    }

    // GET /checkout/{code}/payment  (bank details + form with the order text already in "Message")
    public function payment(Request $request, Checkout $checkout)
    {
        $this->ensureOwner($request, $checkout);
        $checkout->load('orders.product');

        return view('checkout-payment', [
            'checkout' => $checkout,
            'prefill' => $checkout->payment_message ?? $checkout->paymentMessage(),
        ]);
    }

    // POST /checkout/{code}/payment  ("Send confirmation")
    public function sendPayment(Request $request, Checkout $checkout)
    {
        $this->ensureOwner($request, $checkout);

        $data = $request->validate(['message' => ['required', 'string', 'max:5000']]);

        $checkout->update(['payment_message' => $data['message'], 'payment_sent_at' => now()]);

        return redirect()->route('checkout.done', $checkout);
    }

    // GET /checkout/{code}/done
    public function done(Request $request, Checkout $checkout)
    {
        $this->ensureOwner($request, $checkout);

        return view('checkout-done', ['checkout' => $checkout]);
    }

    // Customers can only open their own orders
    private function ensureOwner(Request $request, Checkout $checkout): void
    {
        abort_unless((int) $checkout->user_id === $request->user()->id, 404);
    }

    private function newCode(): string
    {
        do {
            $code = 'NVR-' . Str::upper(Str::random(8));
        } while (Checkout::where('code', $code)->exists());

        return $code;
    }
}
