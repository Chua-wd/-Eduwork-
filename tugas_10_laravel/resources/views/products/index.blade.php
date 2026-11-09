@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <h1>Daftar Produk</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('products.create') }}">+ Tambah Produk</a>

    <table style="margin-top: 12px;">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;"
                          onsubmit="return confirm('Yakin hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Belum ada produk.</td>
            </tr>
        @endforelse
    </table>
@endsection
