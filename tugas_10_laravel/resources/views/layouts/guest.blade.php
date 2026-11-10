{{--
    Layout untuk halaman tamu dari Breeze (login, daftar, lupa password, dll).
    Dipakai lewat <x-guest-layout> ... </x-guest-layout>.
    Kiri: panel navy bergaya hero beranda. Kanan: form.
--}}
@php
    $featured = \App\Models\Product::where('name', 'like', '%Serum%')->first();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Akun' }} · Navir Men's Skincare</title>

    @include('layouts.partials.styles')
</head>
<body>
    <div class="auth">
        <aside class="auth-side">
            <a href="/" class="brand">
                <x-application-logo class="brand-mark" />
                NAVIR
            </a>

            @if ($featured)
                <div style="position: absolute; inset: 12% 18% 30%; opacity: .9;">
                    <x-product-visual :product="$featured" tone="dark" />
                </div>
            @endif

            <div style="position: relative;">
                <h1 class="display">Rutinitas pria,<br>tanpa ribet.</h1>
                <ul>
                    <li>Diuji dermatologis</li>
                    <li>3 langkah</li>
                    <li>Cocok iklim tropis</li>
                </ul>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
