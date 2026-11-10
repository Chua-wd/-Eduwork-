{{--
    Layout utama Navir. Bisa dipakai 2 cara:
    1) Halaman toko:  @extends('layouts.app') + @section('content')
    2) Halaman Breeze (dashboard, profil): <x-app-layout> ... </x-app-layout>
       -> isi masuk ke $slot, judul opsional lewat <x-slot name="header">
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Akun') · Navir Men's Skincare</title>

    @include('layouts.partials.styles')
</head>
<body class="@yield('body_class')">
    <x-navbar />

    {{-- Bagian lebar penuh (opsional), misalnya hero gelap di beranda --}}
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
