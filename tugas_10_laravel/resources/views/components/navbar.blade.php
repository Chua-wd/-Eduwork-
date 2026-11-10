{{-- Komponen Navbar: dipakai di semua halaman lewat <x-navbar /> --}}
@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<header class="site-header">
    <nav class="container nav">
        <div class="nav-group">
            <a href="/" class="brand">
                <svg class="brand-mark" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2 L21 7 V17 L12 22 L3 17 V7 Z" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 7 V17 M7.5 9.5 L16.5 14.5" stroke="currentColor" stroke-width="2"/>
                </svg>
                NAVIR
            </a>
            <span class="nav-links" style="display: flex; gap: 2px;">
                <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">Belanja</a>
                <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">Tentang</a>
                <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Kontak</a>
            </span>
        </div>

        <div class="nav-group">
            <a href="/checkout" class="{{ request()->is('checkout') ? 'active' : '' }}">Checkout</a>
            <a href="/cart" class="{{ request()->is('cart') ? 'active' : '' }}">
                Keranjang <span class="count">{{ $cartCount }}</span>
            </a>
        </div>
    </nav>
</header>
