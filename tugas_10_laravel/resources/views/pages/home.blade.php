@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1>Selamat datang di Toko Online</h1>
    <p>Temukan produk terbaik dengan harga terjangkau.</p>

    <h2>Produk Terbaru</h2>
    <div class="grid">
        @forelse ($products as $product)
            <div class="card">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="{{ route('products.show', $product) }}">Lihat detail</a>
            </div>
        @empty
            <p>Belum ada produk.</p>
        @endforelse
    </div>

    <p style="margin-top: 20px;"><a href="/products">Lihat semua produk &rarr;</a></p>
@endsection
