{{--
    Admin layout with a sidebar (in the style of the "Harbor" dashboard reference).
    Used by every dashboard page:
        <x-admin-layout>
            <x-slot name="title">Products</x-slot>   (browser tab title)
            ...page content...
        </x-admin-layout>
--}}
@php
    $user = Auth::user();
    $initials = collect(explode(' ', $user->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
    $productCount = \App\Models\Product::count();
    $categoryCount = \App\Models\ProductCategory::count();
    $lowStock = \App\Models\Product::where('stock', '<', 10)->count();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} · Navir Admin</title>

    @include('layouts.partials.styles')
</head>
<body>
    <div class="admin">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="brand" style="padding: 4px 10px; text-decoration: none; color: var(--ink);">
                <x-application-logo class="brand-mark" />
                NAVIR <span class="muted" style="font-weight: 400; letter-spacing: 0;">Admin</span>
            </a>

            {{-- Search products (goes to the product table) --}}
            <form action="{{ route('dashboard.products.index') }}" method="GET" class="side-search" role="search">
                <x-icon name="search" size="14" />
                <input type="search" name="q" value="{{ request()->routeIs('dashboard.products.index') ? request('q') : '' }}"
                       placeholder="Search products" aria-label="Search products">
            </form>

            <nav class="side-nav" aria-label="Admin">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <x-icon name="grid" /> Dashboard
                </a>
                <a href="{{ route('dashboard.products.index') }}" class="{{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                    <x-icon name="box" /> Products <span class="badge">{{ $productCount }}</span>
                </a>
                <a href="{{ route('dashboard.categories.index') }}" class="{{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                    <x-icon name="tag" /> Product Categories <span class="badge">{{ $categoryCount }}</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <x-icon name="user" /> Profile
                </a>
            </nav>

            <div>
                <p class="side-label">Shortcuts</p>
                <nav class="side-nav side-nav-small" aria-label="Shortcuts">
                    <a href="{{ route('dashboard.products.create') }}"><x-icon name="plus" size="14" /> Add product</a>
                    <a href="{{ route('dashboard.products.index', ['stock' => 'low']) }}"
                       class="{{ request('stock') === 'low' ? 'active' : '' }}">
                        <x-icon name="alert" size="14" /> Low stock <span class="badge">{{ $lowStock }}</span>
                    </a>
                    <a href="{{ route('products.index') }}"><x-icon name="external" size="14" /> View shop</a>
                </nav>
            </div>

            <div class="side-user">
                <span class="avatar">{{ $initials }}</span>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</div>
                    <div class="muted" style="font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="icon-btn" title="Log out" aria-label="Log out"><x-icon name="logout" /></button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            @isset($header)
                <header style="margin-bottom: 18px;">{{ $header }}</header>
            @endisset

            {{ $slot }}
        </main>
    </div>
</body>
</html>
