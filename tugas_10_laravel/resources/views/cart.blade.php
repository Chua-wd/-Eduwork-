@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1>Keranjang Belanja</h1>

    @php
        // Keranjang disimpan di session (per pengunjung).
        // Fitur "tambah ke keranjang" akan dibuat di tugas berikutnya.
        $cart = session('cart', []);
        $total = 0;
    @endphp

    @if (count($cart) > 0)
        <table>
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
        <p><a href="/checkout">Lanjut ke Checkout &rarr;</a></p>
    @else
        <p>Keranjang masih kosong.</p>
        <a href="/products">Belanja sekarang &rarr;</a>
    @endif
@endsection
