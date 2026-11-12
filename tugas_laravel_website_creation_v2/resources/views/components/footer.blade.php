{{-- Footer component: used on every page via <x-footer /> --}}
<footer class="site-footer">
    <div class="container">
        <span>&copy; {{ date('Y') }} Navir Men's Skincare · Built with Laravel</span>
        <nav>
            <a href="{{ url('/products') }}">Shop</a>
            <a href="{{ url('/about') }}">About</a>
            <a href="{{ url('/contact') }}">Contact</a>
        </nav>
    </div>
</footer>
