{{-- Navbar component: used on every page via <x-navbar /> --}}
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
                <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">Shop</a>
                <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About</a>
                <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a>
            </span>
        </div>

        <div class="nav-group">
            <a href="/cart" class="{{ request()->is('cart') ? 'active' : '' }}">
                Cart <span class="count">{{ $cartCount }}</span>
            </a>

            {{-- Login state (Laravel Breeze) --}}
            @auth
                                @if (Auth::user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                        Dashboard · {{ \Illuminate\Support\Str::of(Auth::user()->name)->before(' ') }}
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
                        Profile · {{ \Illuminate\Support\Str::of(Auth::user()->name)->before(' ') }}
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Log out</a>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Log in</a>
                <a href="{{ route('register') }}" class="{{ request()->is('register') ? 'active' : '' }}">Sign up</a>
            @endauth
        </div>
    </nav>
</header>
