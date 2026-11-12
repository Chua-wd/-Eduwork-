@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    @php
        // The cart is stored in the session (one per visitor).
        // "Add to cart" will be built in a later task.
        $cart = session('cart', []);
        $total = 0;
    @endphp

    <p class="eyebrow">( cart )</p>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Your cart</h1>

    @if (count($cart) > 0)
        <table class="table">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            @foreach ($cart as $item)
                @php $total += $item['price'] * $item['quantity']; @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp {{ number_format($item['price']) }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['price'] * $item['quantity']) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3">Total</th>
                <th>Rp {{ number_format($total) }}</th>
            </tr>
        </table>
        <div style="margin-top: 20px; text-align: right;">
            <a href="{{ url('/checkout') }}" class="btn">Continue to checkout</a>
        </div>
    @else
        <div class="pill" style="padding: 48px 24px; text-align: center;">
            <div class="display display-md">Your cart is empty.</div>
            <p class="muted">Start with a cleanser, step one of your daily routine.</p>
            <a href="{{ url('/products') }}" class="btn" style="margin-top: 8px;">Shop now</a>
        </div>
    @endif
@endsection
