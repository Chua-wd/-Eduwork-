{{-- Small line icons (24x24, stroke = current text color): <x-icon name="box" /> --}}
@props(['name', 'size' => 16])

@php
    $paths = [
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'box'      => '<path d="M21 8 12 3 3 8v8l9 5 9-5V8Z"/><path d="m3 8 9 5 9-5M12 13v8"/>',
        'tag'      => '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'cursor'   => '<path d="m4 4 7 17 2.5-7.5L21 11 4 4Z"/>',
        'bag'      => '<path d="M5 8h14l-1 13H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
        'chart'    => '<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-6"/>',
        'alert'    => '<path d="M12 3 2 20h20L12 3Z"/><path d="M12 10v4M12 17h.01"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'logout'   => '<path d="M15 4h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4"/><path d="M10 17l5-5-5-5M15 12H4"/>',
        'up'       => '<path d="M7 17 17 7M8 7h9v9"/>',
        'down'     => '<path d="M7 7l10 10M17 8v9H8"/>',
        'check'    => '<path d="m5 12 5 5 9-10"/>',
    ];
@endphp

<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes }}>{!! $paths[$name] ?? '' !!}</svg>
