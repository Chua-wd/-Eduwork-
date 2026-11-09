@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <h1>Daftar Produk</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Belum ada produk.</td>
            </tr>
        @endforelse
    </table>
@endsection
