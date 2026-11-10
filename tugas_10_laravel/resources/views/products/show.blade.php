@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    <p><strong>Kategori:</strong> {{ $product->category?->name ?? '-' }}</p>
    <p>{{ $product->description }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    <p><strong>Stok:</strong> {{ $product->stock }}</p>

    <a href="{{ route('products.edit', $product) }}">Edit</a> |
    <a href="{{ route('products.index') }}">Kembali</a>
@endsection
