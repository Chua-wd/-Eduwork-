@extends('layouts.app')

@section('title', $product->name)

@section('content')
    @php
        preg_match('/(\d+)\s?(ml|g)\b/i', $product->name, $m);
        $size = $m ? $m[1] . ' ' . strtolower($m[2]) : null;
        $title = trim(preg_replace('/\s*\d+\s?(ml|g)\b/i', '', $product->name));
        $views = ['front' => 'Depan', 'back' => 'Belakang', 'detail' => 'Detail'];
    @endphp

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <section class="split" style="display: grid; grid-template-columns: 1.55fr 1fr; gap: 20px; align-items: start;">
        {{-- Kiri: galeri --}}
        <div class="visual" style="position: relative; aspect-ratio: auto; height: min(78vh, 760px);">
            <div style="position: absolute; top: 14px; left: 14px; z-index: 2; display: flex; gap: 8px;">
                @foreach ($views as $key => $label)
                    <button type="button" class="thumb {{ $loop->first ? 'active' : '' }}" data-view="{{ $key }}" aria-label="{{ $label }}"
                            style="width: 64px; height: 44px; padding: 0; border-radius: 6px; cursor: pointer; overflow: hidden;
                                   background: var(--bg); border: 1px solid {{ $loop->first ? 'var(--navy)' : 'transparent' }};">
                        <x-product-visual :product="$product" :view="$key" style="width: 100%; height: 100%;" preserveAspectRatio="xMidYMid slice" />
                    </button>
                @endforeach
            </div>

            @foreach ($views as $key => $label)
                <div class="main-view" data-view="{{ $key }}" style="height: 100%; {{ $loop->first ? '' : 'display: none;' }}">
                    <x-product-visual :product="$product" :view="$key" />
                </div>
            @endforeach
        </div>

        {{-- Kanan: info produk --}}
        <div style="display: flex; flex-direction: column; min-height: min(78vh, 760px);">
            <p class="eyebrow">Navir · {{ $product->category?->name ?? 'Tanpa kategori' }}</p>
            <h1 class="display display-lg">{{ $title }}@if ($size)<br>{{ $size }}@endif</h1>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                <div class="chips">
                    @if ($size)
                        <span class="chip active">{{ strtoupper($size) }}</span>
                    @endif
                    <span class="chip">{{ $product->stock > 0 ? 'Stok ' . $product->stock : 'Stok habis' }}</span>
                </div>
            </div>

            <hr class="rule">

            <p class="eyebrow">( info produk )</p>
            <p class="lead" style="margin: 6px 0 0;">{{ $product->description }}</p>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 28px;">
                <div class="display display-md">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <a href="/checkout" class="btn">Beli sekarang</a>
            </div>

            <div class="accordion" style="margin-top: auto; padding-top: 28px;">
                <details>
                    <summary>Ringkasan produk</summary>
                    <div class="content">{{ $product->description }}</div>
                </details>
                <details>
                    <summary>Spesifikasi</summary>
                    <div class="content">
                        <dl class="spec">
                            <dt>Kategori</dt><dd>{{ $product->category?->name ?? '-' }}</dd>
                            <dt>Ukuran</dt><dd>{{ $size ?? '-' }}</dd>
                            <dt>Stok</dt><dd>{{ $product->stock }}</dd>
                            <dt>Kode produk</dt><dd>NVR-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</dd>
                        </dl>
                    </div>
                </details>
                <details>
                    <summary>Pengiriman &amp; pengembalian</summary>
                    <div class="content">
                        Dikirim 1–2 hari kerja. Gratis ongkir untuk pembelian di atas Rp 200.000.
                        Produk yang belum dibuka dapat dikembalikan dalam 7 hari.
                    </div>
                </details>
            </div>

            {{-- Aksi admin (CRUD dari tugas sebelumnya) --}}
            <div style="display: flex; gap: 8px; margin-top: 16px;">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section style="margin-top: 56px;">
            <h2 class="display display-lg" style="margin-bottom: 18px;">Produk serupa</h2>
            <div class="grid">
                @foreach ($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif

    <script>
        // Klik thumbnail -> tampilkan gambar besar yang sesuai
        document.querySelectorAll('.thumb').forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                document.querySelectorAll('.thumb').forEach(function (t) { t.style.borderColor = 'transparent'; });
                thumb.style.borderColor = 'var(--navy)';
                document.querySelectorAll('.main-view').forEach(function (v) {
                    v.style.display = v.dataset.view === thumb.dataset.view ? '' : 'none';
                });
            });
        });
    </script>
@endsection
