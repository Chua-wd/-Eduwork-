@extends('layouts.app')

@section('title', $selectedCategory ? $selectedCategory->name : 'Belanja')

@section('content')
    @php
        // Helper link: pertahankan filter kategori/urutan yang sedang aktif
        $categoryId = $selectedCategory?->id;
        $sortLink = fn ($value) => route('products.index', array_filter(['category' => $categoryId, 'sort' => $value === 'new' ? null : $value]));
        $catLink = fn ($id) => route('products.index', array_filter(['category' => $id, 'sort' => $sort === 'new' ? null : $sort]));
    @endphp

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: end; gap: 16px; flex-wrap: wrap;">
        <div>
            <p class="eyebrow">( {{ $products->total() }} produk )</p>
            <h1 class="display display-lg">{{ $selectedCategory ? $selectedCategory->name : 'Semua produk' }}</h1>
        </div>
        @auth
            <a href="{{ route('products.create') }}" class="btn btn-ghost btn-sm">+ Tambah produk</a>
        @endauth
    </div>

    {{-- Toolbar: urutan (kiri) + kategori dari ProductCategory (kanan) --}}
    <div class="toolbar">
        <div class="tabs">
            <a href="{{ $sortLink('new') }}" class="{{ $sort === 'new' ? 'active' : '' }}">Terbaru</a>
            <a href="{{ $sortLink('price_asc') }}" class="{{ $sort === 'price_asc' ? 'active' : '' }}">Harga terendah</a>
            <a href="{{ $sortLink('price_desc') }}" class="{{ $sort === 'price_desc' ? 'active' : '' }}">Harga tertinggi</a>
        </div>
        <div class="cat-links">
            <a href="{{ $catLink(null) }}" class="{{ $categoryId ? '' : 'active' }}">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ $catLink($category->id) }}" class="{{ $categoryId === $category->id ? 'active' : '' }}">
                    {{ $category->name }} <span class="muted">{{ $category->products_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <p class="muted">
                @if ($products->total() > 0)
                    Tidak ada produk di halaman ini.
                    <a href="{{ $products->url(1) }}">Kembali ke halaman 1</a>
                @else
                    Belum ada produk.
                @endif
            </p>
        @endforelse
    </div>

    {{-- Pagination: link halaman 1, 2, 3 ... dibuat otomatis oleh Laravel --}}
    {{ $products->links() }}

    @if ($products->isNotEmpty())
        <p class="page-info">
            Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }}
            dari {{ $products->total() }} produk · halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
        </p>
    @endif
@endsection
