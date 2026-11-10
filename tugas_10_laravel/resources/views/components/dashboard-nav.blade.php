{{-- Dashboard navigation: <x-dashboard-nav /> on every dashboard page --}}
<nav class="toolbar" aria-label="Dashboard">
    <div class="tabs">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('dashboard.products.index') }}" class="{{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">Products</a>
        <a href="{{ route('dashboard.categories.index') }}" class="{{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">Product Categories</a>
    </div>
    <div class="cat-links">
        <a href="{{ route('products.index') }}">View shop &#8599;</a>
    </div>
</nav>
