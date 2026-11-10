@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <p class="eyebrow">( kontak )</p>
    <h1 class="display display-xl">Ada pertanyaan?</h1>

    <div class="grid" style="margin-top: 40px;">
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Email</div>
            <div class="display display-md">halo@navir.test</div>
        </div>
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Jam layanan</div>
            <div class="display display-md">Senin–Jumat, 09.00–17.00</div>
        </div>
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Alamat</div>
            <div class="display display-md">Jakarta, Indonesia</div>
        </div>
    </div>
@endsection
