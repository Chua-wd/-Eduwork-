<x-guest-layout>
    <x-slot name="title">Sign up</x-slot>

    <p class="eyebrow">( sign up )</p>
    <h2 class="display display-lg" style="margin-bottom: 24px;">Create your Navir account.</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="field">
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="field">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="row-2">
            <!-- Password -->
            <div class="field">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-input-label for="password_confirmation" value="Confirm password" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <x-primary-button class="btn-block">Sign up</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <span class="muted">Already have an account? <a href="{{ route('login') }}" style="color: var(--ink);">Log in</a></span>
    </p>
</x-guest-layout>
