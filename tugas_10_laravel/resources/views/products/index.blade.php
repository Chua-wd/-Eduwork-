@extends('layouts.app')

@section('title', $selectedCategory ? $selectedCategory->name : 'Shop')

@section('content')
    @php
        // Link helpers: keep the active category / sort order when switching the other
        $categoryId = $selectedCategory?->id;
        $sortLink = fn ($value) => route('products.index', array_filter(['category' => $categoryId, 'sort' => $value === 'new' ? null : $value]));
        $catLink = fn ($id) => route('products.index', array_filter(['category' => $id, 'sort' => $sort === 'new' ? null : $sort]));
    @endphp

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: end; gap: 16px; flex-wrap: wrap;">
        <div>
            <p class="eyebrow">( {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }} )</p>
            <h1 class="display display-lg">{{ $selectedCategory ? $selectedCategory->name : 'All products' }}</h1>
        </div>
        @auth
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-ghost btn-sm">+ Add product</a>
        @endauth
    </div>

    {{-- Toolbar: sort order (left) + categories from ProductCategory (right) --}}
    <div class="toolbar">
        <div class="tabs">
            <a href="{{ $sortLink('new') }}" class="{{ $sort === 'new' ? 'active' : '' }}">Newest</a>
            <a href="{{ $sortLink('price_asc') }}" class="{{ $sort === 'price_asc' ? 'active' : '' }}">Price: low to high</a>
            <a href="{{ $sortLink('price_desc') }}" class="{{ $sort === 'price_desc' ? 'active' : '' }}">Price: high to low</a>
        </div>
        <div class="cat-links">
            <a href="{{ $catLink(null) }}" class="{{ $categoryId ? '' : 'active' }}">All</a>
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
                    There are no products on this page.
                    <a href="{{ $products->url(1) }}">Back to page 1</a>
                @else
                    No products yet.
                @endif
            </p>
        @endforelse
    </div>

    {{-- Pagination: Laravel builds the 1, 2, 3 ... links automatically --}}
    {{ $products->links() }}

    @if ($products->isNotEmpty())
        <p class="page-info">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
            of {{ $products->total() }} products · page {{ $products->currentPage() }} of {{ $products->lastPage() }}
        </p>
    @endif
@endsection
