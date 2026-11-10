{{--
    Product image component:
        <x-product-visual :product="$product" />
        <x-product-visual :product="$product" view="back" />    (back of the pack)
        <x-product-visual :product="$product" view="detail" />  (close-up)
    - If the product has an uploaded image (products.image), that photo is shown.
    - Otherwise an SVG illustration of the packaging is drawn; the shape is picked
      from the product name: tube, jar, dropper or can.
--}}
@props(['product', 'view' => 'front', 'tone' => 'light'])  {{-- tone="dark" for dark backgrounds --}}

@if ($product->image)
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy"
         {{ $attributes->merge(['style' => 'width: 100%; height: 100%; object-fit: cover;' . ($view === 'detail' ? ' transform: scale(1.7);' : '')]) }}>
@else
@php
    $name = strtolower($product->name);

    // 1) Bentuk kemasan
    $shape = match (true) {
        str_contains($name, 'serum') || str_contains($name, 'oil') => 'dropper',
        str_contains($name, 'moisturizer') || str_contains($name, 'cream') => 'jar',
        str_contains($name, 'foam') || str_contains($name, 'deodorant') => 'can',
        default => 'tube',
    };

    // 2) Warna (navy / putih / biru baja / biru), dipilih dari id produk
    $palettes = [
        ['body' => '#0f2a4a', 'cap' => '#e6ecf3', 'label' => '#ffffff', 'text' => '#0f2a4a'],
        ['body' => '#f6f8fb', 'cap' => '#0f2a4a', 'label' => '#dbe7f3', 'text' => '#0f2a4a'],
        ['body' => '#2f5d8a', 'cap' => '#0f2a4a', 'label' => '#ffffff', 'text' => '#0f2a4a'],
        ['body' => '#1d4f86', 'cap' => '#dfe6ee', 'label' => '#ffffff', 'text' => '#0f2a4a'],
    ];
    $c = $palettes[($product->id ?? 0) % count($palettes)];

    // 3) Teks label: nama tanpa ukuran, dipecah 2 baris + ukuran
    preg_match('/(\d+)\s?(ml|g)\b/i', $product->name, $m);
    $size = $m ? $m[1] . ' ' . strtolower($m[2]) : '';
    $title = trim(preg_replace('/\s*\d+\s?(ml|g)\b/i', '', $product->name));
    $words = explode(' ', $title);
    $half = (int) ceil(count($words) / 2);
    $line1 = implode(' ', array_slice($words, 0, $half));
    $line2 = implode(' ', array_slice($words, $half));

    // 4) Posisi label & bayangan per bentuk
    $geo = [
        'tube'    => ['lx' => 140, 'ly' => 186, 'lw' => 120, 'lh' => 128, 'floor' => 452],
        'jar'     => ['lx' => 130, 'ly' => 280, 'lw' => 140, 'lh' => 112, 'floor' => 428],
        'dropper' => ['lx' => 144, 'ly' => 246, 'lw' => 112, 'lh' => 140, 'floor' => 448],
        'can'     => ['lx' => 142, 'ly' => 214, 'lw' => 116, 'lh' => 156, 'floor' => 448],
    ][$shape];
    $cx = $geo['lx'] + $geo['lw'] / 2;
    $cy = $geo['ly'] + $geo['lh'] / 2;

    // 5) viewBox: "detail" = zoom ke label
    $viewBox = $view === 'detail'
        ? ($cx - 100) . ' ' . ($cy - 125) . ' 200 250'
        : '0 0 400 500';

    $uid = 'pv' . \Illuminate\Support\Str::random(6);
@endphp

<svg viewBox="{{ $viewBox }}" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="{{ $product->name }}" {{ $attributes }}>
    <defs>
        <radialGradient id="{{ $uid }}-glow" cx="50%" cy="42%" r="60%">
            <stop offset="0" stop-color="{{ $tone === 'dark' ? '#8fbbe0' : '#ffffff' }}" stop-opacity="{{ $tone === 'dark' ? '.18' : '.75' }}"/>
            <stop offset="1" stop-color="{{ $tone === 'dark' ? '#8fbbe0' : '#ffffff' }}" stop-opacity="0"/>
        </radialGradient>
        <linearGradient id="{{ $uid }}-shade" x1="0" x2="1" y1="0" y2="0">
            <stop offset="0" stop-color="#000" stop-opacity=".22"/>
            <stop offset=".22" stop-color="#fff" stop-opacity=".22"/>
            <stop offset=".5" stop-color="#fff" stop-opacity="0"/>
            <stop offset=".85" stop-color="#000" stop-opacity=".08"/>
            <stop offset="1" stop-color="#000" stop-opacity=".25"/>
        </linearGradient>
        <radialGradient id="{{ $uid }}-floor" cx="50%" cy="50%" r="50%">
            <stop offset="0" stop-color="{{ $tone === 'dark' ? '#02080f' : '#0f2a4a' }}" stop-opacity="{{ $tone === 'dark' ? '.6' : '.22' }}"/>
            <stop offset="1" stop-color="{{ $tone === 'dark' ? '#02080f' : '#0f2a4a' }}" stop-opacity="0"/>
        </radialGradient>
    </defs>

    {{-- latar & bayangan lantai --}}
    @if ($tone !== 'dark')
        <rect x="-200" y="-200" width="800" height="900" fill="url(#{{ $uid }}-glow)"/>
    @endif
    <ellipse cx="200" cy="{{ $geo['floor'] }}" rx="125" ry="14" fill="url(#{{ $uid }}-floor)"/>

    @if ($shape === 'tube')
        <rect x="128" y="46" width="144" height="24" rx="3" fill="{{ $c['body'] }}"/>
        <rect x="128" y="46" width="144" height="24" rx="3" fill="url(#{{ $uid }}-shade)"/>
        @for ($i = 0; $i < 9; $i++)
            <line x1="{{ 138 + $i * 15 }}" y1="50" x2="{{ 138 + $i * 15 }}" y2="66" stroke="#000" stroke-opacity=".12" stroke-width="2"/>
        @endfor
        <path d="M128 70 L272 70 L286 372 Q286 384 274 384 L126 384 Q114 384 114 372 Z" fill="{{ $c['body'] }}"/>
        <path d="M128 70 L272 70 L286 372 Q286 384 274 384 L126 384 Q114 384 114 372 Z" fill="url(#{{ $uid }}-shade)"/>
        <rect x="146" y="380" width="108" height="10" fill="{{ $c['cap'] }}"/>
        <rect x="134" y="386" width="132" height="62" rx="9" fill="{{ $c['cap'] }}"/>
        <rect x="134" y="386" width="132" height="62" rx="9" fill="url(#{{ $uid }}-shade)"/>
    @elseif ($shape === 'jar')
        <rect x="104" y="250" width="192" height="172" rx="24" fill="{{ $c['body'] }}"/>
        <rect x="104" y="250" width="192" height="172" rx="24" fill="url(#{{ $uid }}-shade)"/>
        <rect x="96" y="196" width="208" height="60" rx="12" fill="{{ $c['cap'] }}"/>
        <rect x="96" y="196" width="208" height="60" rx="12" fill="url(#{{ $uid }}-shade)"/>
        @for ($i = 0; $i < 3; $i++)
            <line x1="100" y1="{{ 212 + $i * 14 }}" x2="300" y2="{{ 212 + $i * 14 }}" stroke="#000" stroke-opacity=".08" stroke-width="2"/>
        @endfor
    @elseif ($shape === 'dropper')
        <rect x="178" y="36" width="44" height="74" rx="22" fill="{{ $c['cap'] }}"/>
        <rect x="178" y="36" width="44" height="74" rx="22" fill="url(#{{ $uid }}-shade)"/>
        <rect x="160" y="104" width="80" height="48" rx="6" fill="{{ $c['cap'] }}"/>
        <rect x="160" y="104" width="80" height="48" rx="6" fill="url(#{{ $uid }}-shade)"/>
        @for ($i = 0; $i < 7; $i++)
            <line x1="{{ 168 + $i * 11 }}" y1="108" x2="{{ 168 + $i * 11 }}" y2="148" stroke="#000" stroke-opacity=".1" stroke-width="2"/>
        @endfor
        <rect x="176" y="150" width="48" height="18" fill="{{ $c['body'] }}"/>
        <path d="M128 200 Q128 168 160 168 L240 168 Q272 168 272 200 L272 424 Q272 442 254 442 L146 442 Q128 442 128 424 Z" fill="{{ $c['body'] }}"/>
        <path d="M128 200 Q128 168 160 168 L240 168 Q272 168 272 200 L272 424 Q272 442 254 442 L146 442 Q128 442 128 424 Z" fill="url(#{{ $uid }}-shade)"/>
    @else {{-- can --}}
        <rect x="128" y="134" width="144" height="308" rx="16" fill="{{ $c['body'] }}"/>
        <rect x="128" y="134" width="144" height="308" rx="16" fill="url(#{{ $uid }}-shade)"/>
        <rect x="136" y="126" width="128" height="12" rx="4" fill="#c9d3de"/>
        <rect x="140" y="58" width="120" height="74" rx="20" fill="{{ $c['cap'] }}"/>
        <rect x="140" y="58" width="120" height="74" rx="20" fill="url(#{{ $uid }}-shade)"/>
    @endif

    {{-- label --}}
    <rect x="{{ $geo['lx'] }}" y="{{ $geo['ly'] }}" width="{{ $geo['lw'] }}" height="{{ $geo['lh'] }}" rx="4" fill="{{ $c['label'] }}"/>
    <g fill="{{ $c['text'] }}" font-family="Inter, system-ui, sans-serif" text-anchor="middle">
        @if ($view === 'back')
            <text x="{{ $cx }}" y="{{ $geo['ly'] + 22 }}" font-size="9" font-weight="600" letter-spacing="2">HOW TO USE</text>
            @for ($i = 0; $i < 5; $i++)
                <rect x="{{ $geo['lx'] + 12 }}" y="{{ $geo['ly'] + 36 + $i * 13 }}" width="{{ $geo['lw'] - 24 - ($i % 2) * 18 }}" height="4" rx="2" fill="{{ $c['text'] }}" fill-opacity=".25"/>
            @endfor
            <text x="{{ $cx }}" y="{{ $geo['ly'] + $geo['lh'] - 14 }}" font-size="8" fill-opacity=".7">For men · all skin types</text>
        @else
            <text x="{{ $cx }}" y="{{ $geo['ly'] + 30 }}" font-size="13" font-weight="600" letter-spacing="3.5">NAVIR</text>
            <line x1="{{ $cx - 14 }}" y1="{{ $geo['ly'] + 40 }}" x2="{{ $cx + 14 }}" y2="{{ $geo['ly'] + 40 }}" stroke="{{ $c['text'] }}" stroke-opacity=".4"/>
            <text x="{{ $cx }}" y="{{ $geo['ly'] + 62 }}" font-size="11">{{ $line1 }}</text>
            <text x="{{ $cx }}" y="{{ $geo['ly'] + 77 }}" font-size="11">{{ $line2 }}</text>
            <text x="{{ $cx }}" y="{{ $geo['ly'] + $geo['lh'] - 14 }}" font-size="9" fill-opacity=".6" letter-spacing="1">{{ strtoupper($size) }}</text>
        @endif
    </g>
</svg>
@endif
