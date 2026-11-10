@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <h1>Tambah Produk</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <p>Kategori:<br>
            <select name="category_id">
                <option value="">-- Pilih kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </p>
        <p>Nama:<br><input type="text" name="name" value="{{ old('name') }}"></p>
        <p>Deskripsi:<br><textarea name="description">{{ old('description') }}</textarea></p>
        <p>Harga:<br><input type="number" name="price" value="{{ old('price') }}"></p>
        <p>Stok:<br><input type="number" name="stock" value="{{ old('stock', 0) }}"></p>
        <button type="submit">Simpan</button>
        <a href="{{ route('products.index') }}">Batal</a>
    </form>
@endsection
