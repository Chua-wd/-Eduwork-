<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( dashboard )</p>
        <h1 class="display display-lg">Hello, {{ \Illuminate\Support\Str::of(Auth::user()->name)->before(' ') }}.</h1>
        <p class="muted" style="margin: 6px 0 0;">You're logged in. Manage your products, categories and profile from here.</p>
        <x-dashboard-nav />
    </x-slot>

    @php
        $productCount = \App\Models\Product::count();
        $categoryCount = \App\Models\ProductCategory::count();
        $lowStock = \App\Models\Product::where('stock', '<', 10)->count();
    @endphp

    <div class="grid">
        <a href="{{ route('dashboard.products.index') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Products</div>
            <div class="display display-lg">{{ $productCount }}</div>
            <p class="muted" style="margin: 6px 0 0;">
                {{ $lowStock ? $lowStock . ' low on stock (under 10) · ' : '' }}Manage products &rarr;
            </p>
        </a>
        <a href="{{ route('dashboard.categories.index') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Product categories</div>
            <div class="display display-lg">{{ $categoryCount }}</div>
            <p class="muted" style="margin: 6px 0 0;">Manage categories &rarr;</p>
        </a>
        <a href="{{ route('profile.edit') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Profile</div>
            <div class="display display-md" style="margin-top: 6px;">{{ Auth::user()->email }}</div>
            <p class="muted" style="margin: 6px 0 0;">Edit name, email or password &rarr;</p>
        </a>
    </div>
</x-app-layout>
