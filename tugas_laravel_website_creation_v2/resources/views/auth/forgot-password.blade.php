<x-guest-layout>
    <x-slot name="title">Forgot password</x-slot>

    <p class="eyebrow">( forgot password )</p>
    <h2 class="display display-lg">Reset your password.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        Enter your account email and we'll send you a link to choose a new password.
    </p>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="field">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="btn-block">Email reset link</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <a href="{{ route('login') }}">&larr; Back to log in</a>
    </p>
</x-guest-layout>
