@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <p class="eyebrow">( tentang navir )</p>
    <h1 class="display display-xl" style="max-width: 900px;">Perawatan kulit yang dibuat untuk pria sibuk.</h1>

    <div class="grid" style="margin-top: 40px;">
        @foreach ([
            ['01', 'Sederhana', 'Tiga langkah inti: bersihkan, rawat, lindungi. Tanpa rutinitas sepuluh tahap.'],
            ['02', 'Teruji', 'Setiap formula diuji dermatologis dan aman untuk kulit sensitif.'],
            ['03', 'Untuk iklim tropis', 'Tekstur ringan dan cepat meresap, tidak lengket di cuaca lembap.'],
        ] as [$no, $title, $text])
            <div class="pill" style="padding: 24px;">
                <div class="eyebrow">{{ $no }}</div>
                <div class="display display-md" style="margin-top: 4px;">{{ $title }}</div>
                <p class="muted" style="margin: 8px 0 0;">{{ $text }}</p>
            </div>
        @endforeach
    </div>
@endsection
