<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <p class="eyebrow">( masuk )</p>
    <h2 class="display display-lg" style="margin-bottom: 24px;">Selamat datang kembali.</h2>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="field">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="field">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="auth-links" style="margin: 0 0 20px;">
            <!-- Remember Me -->
            <label for="remember_me" class="check">
                <input id="remember_me" type="checkbox" name="remember">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <x-primary-button class="btn-block">Masuk</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <span class="muted">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--ink);">Daftar</a></span>
    </p>
</x-guest-layout>
