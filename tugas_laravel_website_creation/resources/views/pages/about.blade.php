@extends('layouts.app')

@section('title', 'About')

@section('content')
    <p class="eyebrow">( about navir )</p>
    <h1 class="display display-xl" style="max-width: 900px;">Skincare made for busy men.</h1>

    <div class="grid" style="margin-top: 40px;">
        @foreach ([
            ['01', 'Simple', 'Three core steps: cleanse, treat, protect. No ten-step routine.'],
            ['02', 'Tested', 'Every formula is dermatologist tested and safe for sensitive skin.'],
            ['03', 'Made for the tropics', 'Light textures that absorb fast and never feel sticky in humid weather.'],
        ] as [$no, $title, $text])
            <div class="pill" style="padding: 24px;">
                <div class="eyebrow">{{ $no }}</div>
                <div class="display display-md" style="margin-top: 4px;">{{ $title }}</div>
                <p class="muted" style="margin: 8px 0 0;">{{ $text }}</p>
            </div>
        @endforeach
    </div>
@endsection
