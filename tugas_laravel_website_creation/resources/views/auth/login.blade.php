<x-guest-layout>
    <x-slot name="title">Log in</x-slot>

    <p class="eyebrow">( log in )</p>
    <h2 class="display display-lg" style="margin-bottom: 24px;">Welcome back.</h2>

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
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <x-primary-button class="btn-block">Log in</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <span class="muted">Don't have an account? <a href="{{ route('register') }}" style="color: var(--ink);">Sign up</a></span>
    </p>
</x-guest-layout>
