@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <p class="eyebrow">( checkout )</p>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Checkout</h1>

    <div class="split" style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 20px; align-items: start;">
        <div class="form-card" style="max-width: none;">
            <p class="eyebrow">( shipping address )</p>
            <p class="muted">The address and payment form will be built in a later task.</p>
            <div class="row-2">
                <div class="field"><label>Recipient name</label><input type="text" disabled></div>
                <div class="field"><label>Phone number</label><input type="text" disabled></div>
            </div>
            <div class="field"><label>Full address</label><textarea disabled></textarea></div>
        </div>

        <div class="pill" style="padding: 24px;">
            <p class="eyebrow">( order summary )</p>
            <dl class="spec" style="margin-top: 8px;">
                <dt>Subtotal</dt><dd>Rp 0</dd>
                <dt>Shipping</dt><dd>Free on orders over Rp 200,000</dd>
            </dl>
            <hr class="rule">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="display display-md">Total</span>
                <span class="display display-md">Rp 0</span>
            </div>
            <a href="{{ url('/products') }}" class="btn btn-ghost" style="width: 100%; margin-top: 16px;">Continue shopping</a>
        </div>
    </div>
@endsection
