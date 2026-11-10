{{-- Kartu produk untuk grid katalog & beranda: <x-product-card :product="$product" /> --}}
@props(['product'])

<a href="{{ route('products.show', $product) }}" class="card">
    <div class="visual">
        <x-product-visual :product="$product" />
    </div>
    <div class="card-body">
        <div>
            <div class="eyebrow">{{ $product->category?->name ?? 'Tanpa kategori' }}</div>
            <h3 class="card-title">{{ $product->name }}</h3>
        </div>
        <div class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
    </div>
</a>
