{{-- Komponen Navbar: dipakai di semua halaman lewat <x-navbar /> --}}
@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<header class="site-header">
    <nav class="container nav">
        <div class="nav-group">
            <a href="/" class="brand">
                <x-application-logo class="brand-mark" />
                NAVIR
            </a>
            <span class="nav-links" style="display: flex; gap: 2px;">
                <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">Belanja</a>
                <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">Tentang</a>
                <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Kontak</a>
            </span>
        </div>

        <div class="nav-group">
            <a href="/cart" class="{{ request()->is('cart') ? 'active' : '' }}">
                Keranjang <span class="count">{{ $cartCount }}</span>
            </a>

            {{-- Status login (Laravel Breeze) --}}
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard', 'profile') ? 'active' : '' }}">
                    Akun · {{ \Illuminate\Support\Str::of(Auth::user()->name)->before(' ') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</a>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Masuk</a>
                <a href="{{ route('register') }}" class="{{ request()->is('register') ? 'active' : '' }}">Daftar</a>
            @endauth
        </div>
    </nav>
</header>
