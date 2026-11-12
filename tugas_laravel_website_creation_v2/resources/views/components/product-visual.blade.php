{{--
    Product image component:
        <x-product-visual :product="$product" />
        <x-product-visual :product="$product" view="detail" />  (close-up: the same photo zoomed in)
        <x-product-visual :product="$featured" tone="dark" />   (on the dark home/login hero)
    - products.image holds the photo uploaded in the dashboard (uploads/products/...).
    - Without an image a "No photo yet" placeholder is shown (product name + size).
    - view="back" shows the same photo as "front": there is only one photo per product.
--}}
@props(['product', 'view' => 'front', 'tone' => 'light'])

@php
    // show.blade.php passes preserveAspectRatio for the old SVG; it means nothing on <img>/<div>
    $attributes = $attributes->except(['preserveAspectRatio', 'preserveaspectratio']);
@endphp

@if ($product->image)
    {{-- tone="dark" is only used for the hero product on the home and login pages, so load it first --}}
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
         @if ($tone === 'dark') fetchpriority="high" @else loading="lazy" @endif
         {{ $attributes->merge(['style' => 'display: block; width: 100%; height: 100%; object-fit: cover;' . ($view === 'detail' ? ' transform: scale(1.7);' : '')]) }}>
@else
    @php
        preg_match('/(\d+)\s?(ml|g)\b/i', $product->name, $m);
        $size = $m ? $m[1] . ' ' . strtolower($m[2]) : null;
        $title = trim(preg_replace('/\s*\d+\s?(ml|g)\b/i', '', $product->name)) ?: $product->name;
    @endphp
    <div role="img" aria-label="{{ $product->name }}, no photo yet"
         {{ $attributes->class(['pv-empty', 'pv-empty--dark' => $tone === 'dark']) }}>
        <span class="pv-empty__initial" aria-hidden="true">{{ mb_substr($title, 0, 1) }}</span>
        <span class="pv-empty__name" aria-hidden="true">{{ $title }}</span>
        @if ($size)
            <span class="pv-empty__size" aria-hidden="true">{{ $size }}</span>
        @endif
        <span class="pv-empty__note" aria-hidden="true">No photo yet</span>
    </div>
@endif
