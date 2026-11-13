@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <x-checkout-steps :current="2" />
    <h1 class="display display-lg" style="margin-bottom: 20px;">Checkout</h1>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" class="split" style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 20px; align-items: start;">
        @csrf

        <div class="form-card" style="max-width: none;">
            <p class="eyebrow" style="margin-bottom: 12px;">( shipping details )</p>
            <div class="row-2">
                <div class="field">
                    <label for="recipient_name">Recipient name</label>
                    <input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required maxlength="100" autocomplete="name">
                </div>
                <div class="field">
                    <label for="phone">Phone / WhatsApp</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel" placeholder="0812 3456 7890">
                </div>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
            </div>
            <div class="field">
                <label for="address">Full address</label>
                <textarea id="address" name="address" required maxlength="1000" autocomplete="street-address" placeholder="Street, number, district, city, postal code">{{ old('address') }}</textarea>
            </div>
            <div class="field">
                <label for="note">Note for us (optional)</label>
                <input id="note" name="note" value="{{ old('note') }}" maxlength="255" placeholder="e.g. leave it with the security guard">
            </div>
        </div>

        <div class="pill summary" style="padding: 24px;">
            <p class="eyebrow">( your order )</p>
            @foreach ($lines as $line)
                <div class="mini-item">
                    <div class="visual"><x-product-visual :product="$line['product']" /></div>
                    <span class="grow">{{ $line['product']->name }}<br><span class="hint">&times; {{ $line['quantity'] }}</span></span>
                    <span>Rp {{ number_format($line['total']) }}</span>
                </div>
            @endforeach
            <hr class="rule">
            <div class="row"><span>Subtotal</span><span>Rp {{ number_format($subtotal) }}</span></div>
            <div class="row"><span>Shipping</span><span>{{ $shipping ? 'Rp ' . number_format($shipping) : 'Free' }}</span></div>
            <hr class="rule">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="display display-md">Total</span>
                <span class="display display-md">Rp {{ number_format($total) }}</span>
            </div>
            <button type="submit" class="btn" style="width: 100%; margin-top: 16px;">Place order</button>
            <a href="{{ route('cart.index') }}" class="btn btn-ghost" style="width: 100%; margin-top: 8px;">Back to cart</a>
            <p class="hint" style="margin-top: 10px;">No online payment yet. Next you'll see the bank transfer details.</p>
        </div>
    </form>
@endsection
