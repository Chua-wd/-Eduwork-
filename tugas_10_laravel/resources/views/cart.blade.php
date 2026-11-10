@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    @php
        // Keranjang disimpan di session (per pengunjung).
        // Fitur "tambah ke keranjang" akan dibuat di tugas berikutnya.
        $cart = session('cart', []);
        $total = 0;
    @endphp

    <p class="eyebrow">( keranjang )</p>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Keranjang belanja</h1>

    @if (count($cart) > 0)
        <table class="table">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
            @foreach ($cart as $item)
                @php $total += $item['price'] * $item['quantity']; @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3">Total</th>
                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </table>
        <div style="margin-top: 20px; text-align: right;">
            <a href="/checkout" class="btn">Lanjut ke checkout</a>
        </div>
    @else
        <div class="pill" style="padding: 48px 24px; text-align: center;">
            <div class="display display-md">Keranjangmu masih kosong.</div>
            <p class="muted">Mulai dari pembersih wajah, langkah pertama rutinitas harianmu.</p>
            <a href="/products" class="btn" style="margin-top: 8px;">Belanja sekarang</a>
        </div>
    @endif
@endsection
