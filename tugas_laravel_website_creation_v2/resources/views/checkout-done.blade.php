@extends('layouts.app')

@section('title', 'Thank you')

@section('content')
    <div class="pill" style="padding: 48px 24px; text-align: center; max-width: 640px; margin: 0 auto;">
        <p class="eyebrow">( thank you )</p>
        <div class="display display-lg">We got your order.</div>
        <p class="muted">
            Order <b>{{ $checkout->code }}</b> &middot; Rp {{ number_format($checkout->total) }}.
            We'll check your transfer and reply within 1 business day.
        </p>
        <a href="{{ url('/products') }}" class="btn" style="margin-top: 8px;">Back to the shop</a>
    </div>
@endsection
