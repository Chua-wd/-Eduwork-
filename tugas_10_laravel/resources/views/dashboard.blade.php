<x-app-layout>
    <x-slot name="title">Akun saya</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( akun saya )</p>
        <h1 class="display display-lg">Halo, {{ Auth::user()->name }}.</h1>
        <p class="muted" style="margin: 6px 0 0;">Kamu sudah masuk. Dari sini kamu bisa mengelola produk dan profilmu.</p>
    </x-slot>

    @php
        $productCount = \App\Models\Product::count();
        $categoryCount = \App\Models\ProductCategory::count();
    @endphp

    <div class="grid">
        <a href="{{ route('products.index') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Produk di toko</div>
            <div class="display display-lg">{{ $productCount }}</div>
            <p class="muted" style="margin: 6px 0 0;">dalam {{ $categoryCount }} kategori &rarr;</p>
        </a>
        <a href="{{ route('products.create') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Kelola produk</div>
            <div class="display display-md" style="margin-top: 6px;">+ Tambah produk baru</div>
            <p class="muted" style="margin: 6px 0 0;">Edit & hapus ada di halaman detail produk.</p>
        </a>
        <a href="{{ route('profile.edit') }}" class="pill card" style="padding: 22px;">
            <div class="eyebrow">Profil</div>
            <div class="display display-md" style="margin-top: 6px;">{{ Auth::user()->email }}</div>
            <p class="muted" style="margin: 6px 0 0;">Ubah nama, email, atau password &rarr;</p>
        </a>
    </div>
</x-app-layout>
