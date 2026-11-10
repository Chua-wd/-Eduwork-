@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <p class="eyebrow">( kelola produk )</p>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Edit produk</h1>

    <div class="split" style="display: grid; grid-template-columns: 640px 1fr; gap: 20px; align-items: start;">
        <form action="{{ route('products.update', $product) }}" method="POST" class="form-card">
            @csrf
            @method('PUT')
            @include('products._form', ['product' => $product])

            <div style="display: flex; gap: 8px; margin-top: 8px;">
                <button type="submit" class="btn">Simpan perubahan</button>
                <a href="{{ route('products.show', $product) }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>

        <div class="visual" style="max-width: 360px;">
            <x-product-visual :product="$product" />
        </div>
    </div>
@endsection
