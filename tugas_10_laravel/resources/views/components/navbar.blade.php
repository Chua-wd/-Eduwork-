{{-- Komponen Navbar: dipakai di semua halaman lewat <x-navbar /> --}}
<nav class="navbar">
    <a href="/" class="brand">Toko Online</a>

    <div class="links">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
        <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">Produk</a>
        <a href="/cart" class="{{ request()->is('cart') ? 'active' : '' }}">Keranjang</a>
        <a href="/checkout" class="{{ request()->is('checkout') ? 'active' : '' }}">Checkout</a>
        <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">Tentang</a>
        <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Kontak</a>
    </div>
</nav>
