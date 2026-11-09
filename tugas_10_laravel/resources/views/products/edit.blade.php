@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <h1>Edit Produk</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <p>Nama:<br><input type="text" name="name" value="{{ old('name', $product->name) }}"></p>
        <p>Deskripsi:<br><textarea name="description">{{ old('description', $product->description) }}</textarea></p>
        <p>Harga:<br><input type="number" name="price" value="{{ old('price', $product->price) }}"></p>
        <p>Stok:<br><input type="number" name="stock" value="{{ old('stock', $product->stock) }}"></p>
        <button type="submit">Update</button>
        <a href="{{ route('products.index') }}">Batal</a>
    </form>
@endsection
