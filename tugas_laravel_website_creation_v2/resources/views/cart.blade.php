@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    @if ($lines->isEmpty())
        <p class="eyebrow">( cart )</p>
        <h1 class="display display-lg" style="margin-bottom: 20px;">Your cart</h1>
        <div class="pill" style="padding: 48px 24px; text-align: center;">
            <div class="display display-md">Your cart is empty.</div>
            <p class="muted">Start with a cleanser, step one of your daily routine.</p>
            <a href="{{ url('/products') }}" class="btn" style="margin-top: 8px;">Shop now</a>
        </div>
    @else
        <x-checkout-steps :current="1" />
        <h1 class="display display-lg" style="margin-bottom: 20px;">Your cart</h1>

        @error('cart')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror

        <div class="split" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; align-items: start;">
            <div class="pill">
                @foreach ($lines as $line)
                    @php $product = $line['product']; @endphp
                    <div class="cart-item">
                        <a href="{{ route('products.show', $product) }}" class="visual cart-thumb"><x-product-visual :product="$product" /></a>

                        <div>
                            <div class="eyebrow">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                            <a href="{{ route('products.show', $product) }}" class="name">{{ $product->name }}</a>
                            <div class="hint">Rp {{ number_format($product->price) }} each</div>
                        </div>

                        {{-- − / + : each button sends the new quantity (0 removes the product) --}}
                        <form method="POST" action="{{ route('cart.update', $product) }}" class="qty sm">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="quantity" value="{{ $line['quantity'] - 1 }}" aria-label="One less {{ $product->name }}">−</button>
                            <span aria-label="Quantity">{{ $line['quantity'] }}</span>
                            <button type="submit" name="quantity" value="{{ $line['quantity'] + 1 }}" aria-label="One more {{ $product->name }}"
                                    @disabled($line['quantity'] >= $product->stock) @if ($line['quantity'] >= $product->stock) title="No more in stock" @endif>+</button>
                        </form>

                        <div class="line-total">Rp {{ number_format($line['total']) }}</div>

                        <form method="POST" action="{{ route('cart.destroy', $product) }}" class="remove">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn" aria-label="Remove {{ $product->name }}" title="Remove">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="pill summary" style="padding: 24px;">
                <p class="eyebrow">( order summary )</p>
                <div class="row"><span>Subtotal ({{ $count }} {{ Str::plural('item', $count) }})</span><span>Rp {{ number_format($subtotal) }}</span></div>
                <div class="row"><span>Shipping</span><span>{{ $shipping ? 'Rp ' . number_format($shipping) : 'Free' }}</span></div>
                @if ($freeShippingLeft > 0)
                    <div class="hint">Add Rp {{ number_format($freeShippingLeft) }} more for free shipping</div>
                    <div class="ship-bar"><i style="width: {{ round($subtotal / config('shop.free_shipping_from') * 100) }}%"></i></div>
                @else
                    <div class="hint">You get free shipping ✓</div>
                @endif
                <hr class="rule">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="display display-md">Total</span>
                    <span class="display display-md">Rp {{ number_format($total) }}</span>
                </div>
                <a href="{{ route('checkout.create') }}" class="btn" style="width: 100%; margin-top: 16px;">Continue to checkout</a>
                <a href="{{ url('/products') }}" class="btn btn-ghost" style="width: 100%; margin-top: 8px;">Continue shopping</a>
                @guest
                    <p class="hint" style="margin-top: 10px;">You'll log in or create an account at checkout. Your cart stays saved.</p>
                @endguest
            </div>
        </div>
    @endif
@endsection
