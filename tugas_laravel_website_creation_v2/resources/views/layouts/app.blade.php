{{--
    Main Navir layout. Can be used in two ways:
    1) Shop pages:  @extends('layouts.app') + @section('content')
    2) Breeze pages (dashboard, profile): <x-app-layout> ... </x-app-layout>
       -> content goes into $slot, optional heading via <x-slot name="header">
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Account') · Navir Men's Skincare</title>

    @include('layouts.partials.styles')
</head>
<body class="@yield('body_class')">
    <x-navbar />

    {{-- Optional full-width section, e.g. the dark hero on the home page --}}
    @yield('hero')

    <main>
        <div class="container">
            @isset($header)
                <header style="margin-bottom: 24px;">{{ $header }}</header>
            @endisset

            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <x-footer />
</body>
</html>
