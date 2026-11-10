{{--
    Layout for Breeze guest pages (log in, sign up, forgot password, ...).
    Used via <x-guest-layout> ... </x-guest-layout>.
    Left: navy panel in the style of the home hero. Right: the form.
--}}
@php
    $featured = \App\Models\Product::where('name', 'like', '%Serum%')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Account' }} · Navir Men's Skincare</title>

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
                <h1 class="display">A men's routine,<br>made simple.</h1>
                <ul>
                    <li>Dermatologist tested</li>
                    <li>3-step routine</li>
                    <li>Made for humid climates</li>
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
