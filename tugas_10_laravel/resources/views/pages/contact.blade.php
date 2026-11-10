@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <p class="eyebrow">( contact )</p>
    <h1 class="display display-xl">Questions?</h1>

    <div class="grid" style="margin-top: 40px;">
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Email</div>
            <div class="display display-md">hello@navir.test</div>
        </div>
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Opening hours</div>
            <div class="display display-md">Mon–Fri, 9 AM–5 PM</div>
        </div>
        <div class="pill" style="padding: 24px;">
            <div class="eyebrow">Address</div>
            <div class="display display-md">Jakarta, Indonesia</div>
        </div>
    </div>
@endsection
