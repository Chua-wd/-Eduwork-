@extends('layouts.app')

@section('title', 'Beranda')

{{-- Navbar transparan di atas hero gelap --}}
@section('body_class', 'dark-hero')

@section('hero')
    <section class="hero">
        {{-- tekstur "grain" halus --}}
        <svg class="hero-grain" aria-hidden="true">
            <filter id="grain"><feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" stitchTiles="stitch"/></filter>
            <rect width="100%" height="100%" filter="url(#grain)"/>
        </svg>

        @if ($featured)
            <div class="hero-product">
                <x-product-visual :product="$featured" tone="dark" />
            </div>

            {{-- kartu produk mengambang --}}
            <a href="{{ route('products.show', $featured) }}" class="promo">
                <div class="eyebrow">( produk unggulan )</div>
                <div class="card-title" style="margin-top: 4px;">{{ $featured->name }}</div>
                <div style="display: flex; justify-content: space-between; align-items: end; margin-top: 18px;">
                    <div class="display display-md">Rp {{ number_format($featured->price, 0, ',', '.') }}</div>
                    <span class="arrow">&#8599;</span>
                </div>
            </a>
        @endif

        <h1 class="hero-title">Karena kulitmu layak dirawat.</h1>

        <div class="hero-bottom">
            <div class="container">
                <p>Perawatan wajah dan tubuh khusus pria. Ringan, tidak lengket, cukup tiga langkah setiap hari.</p>
                <div class="glass">
                    <span>Diuji dermatologis &reg;</span>
                    <span>&#9675;&#9675;&#9675; 3 langkah</span>
                    <span>Cocok iklim tropis</span>
                    <a href="/products" class="glass-btn">Belanja sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    {{-- Kategori --}}
    <section id="kategori">
        <p class="eyebrow">( belanja per kategori )</p>
        <div class="chips" style="margin-top: 10px;">
            @foreach ($categories as $category)
                <a class="chip" href="{{ route('products.index', ['category' => $category->id]) }}">
                    {{ $category->name }} · {{ $category->products_count }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- Produk terbaru --}}
    <section style="margin-top: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 18px;">
            <h2 class="display display-lg">Produk terbaru</h2>
            <a href="/products" class="muted" style="font-size: 13px;">Lihat semua &rarr;</a>
        </div>
        <div class="grid">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="muted">Belum ada produk.</p>
            @endforelse
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="grid" style="margin-top: 56px;">
        @foreach ([
            ['Diuji dermatologis', 'Aman untuk kulit sensitif dan dipakai setiap hari.'],
            ['Ringan & tidak lengket', 'Cepat meresap, cocok untuk iklim tropis yang lembap.'],
            ['Rutinitas 3 langkah', 'Bersihkan, rawat, lindungi. Selesai dalam 2 menit.'],
        ] as [$title, $text])
            <div class="pill" style="padding: 22px;">
                <div class="display display-md">{{ $title }}</div>
                <p class="muted" style="margin: 8px 0 0;">{{ $text }}</p>
            </div>
        @endforeach
    </section>
@endsection
