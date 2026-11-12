@extends('layouts.app')

@section('title', 'Home')

{{-- Transparent navbar on top of the dark hero --}}
@section('body_class', 'dark-hero')

@section('hero')
    <section class="hero">
        {{-- subtle grain texture --}}
        <svg class="hero-grain" aria-hidden="true">
            <filter id="grain"><feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" stitchTiles="stitch"/></filter>
            <rect width="100%" height="100%" filter="url(#grain)"/>
        </svg>

        @if ($featured)
            <div class="hero-product">
                <x-product-visual :product="$featured" tone="dark" />
            </div>

            {{-- floating product card --}}
            <a href="{{ route('products.show', $featured) }}" class="promo">
                <div class="eyebrow">( featured )</div>
                <div class="card-title" style="margin-top: 4px;">{{ $featured->name }}</div>
                <div style="display: flex; justify-content: space-between; align-items: end; margin-top: 18px;">
                    <div class="display display-md">Rp {{ number_format($featured->price) }}</div>
                    <span class="arrow">&#8599;</span>
                </div>
            </a>
        @endif

        <h1 class="hero-title">Because your skin deserves better.</h1>

        <div class="hero-bottom">
            <div class="container">
                <p>Face and body care made for men. Lightweight, non-greasy, and just three steps a day.</p>
                <div class="glass">
                    <span>Dermatologist tested &reg;</span>
                    <span>&#9675;&#9675;&#9675; 3-step routine</span>
                    <span>Made for humid climates</span>
                    <a href="{{ url('/products') }}" class="glass-btn">Shop now</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    {{-- Categories --}}
    <section id="categories">
        <p class="eyebrow">( shop by category )</p>
        <div class="chips" style="margin-top: 10px;">
            @foreach ($categories as $category)
                <a class="chip" href="{{ route('products.index', ['category' => $category->id]) }}">
                    {{ $category->name }} · {{ $category->products_count }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- Latest products --}}
    <section style="margin-top: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 18px;">
            <h2 class="display display-lg">New arrivals</h2>
            <a href="{{ url('/products') }}" class="muted" style="font-size: 13px;">View all &rarr;</a>
        </div>
        <div class="grid">
            @forelse ($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="muted">No products yet.</p>
            @endforelse
        </div>
    </section>

    {{-- Why Navir --}}
    <section class="grid" style="margin-top: 56px;">
        @foreach ([
            ['Dermatologist tested', 'Gentle enough for sensitive skin and everyday use.'],
            ['Light & non-greasy', 'Absorbs fast and stays comfortable in hot, humid weather.'],
            ['3-step routine', 'Cleanse, treat, protect. Done in two minutes.'],
        ] as [$title, $text])
            <div class="pill" style="padding: 22px;">
                <div class="display display-md">{{ $title }}</div>
                <p class="muted" style="margin: 8px 0 0;">{{ $text }}</p>
            </div>
        @endforeach
    </section>
@endsection
