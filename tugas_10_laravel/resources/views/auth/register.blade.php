<x-guest-layout>
    <x-slot name="title">Daftar</x-slot>

    <p class="eyebrow">( daftar )</p>
    <h2 class="display display-lg" style="margin-bottom: 24px;">Buat akun Navir.</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="field">
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="field">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="row-2">
            <div class="field">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-input-label for="password_confirmation" value="Ulangi password" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <x-primary-button class="btn-block">Daftar</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <span class="muted">Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--ink);">Masuk</a></span>
    </p>
</x-guest-layout>
