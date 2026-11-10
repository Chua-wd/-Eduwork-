@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <p class="eyebrow">( kelola produk )</p>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Tambah produk</h1>

    <form action="{{ route('products.store') }}" method="POST" class="form-card">
        @csrf
        @include('products._form')

        <div style="display: flex; gap: 8px; margin-top: 8px;">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('products.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
@endsection
