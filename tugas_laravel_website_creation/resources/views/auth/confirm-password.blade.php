<x-guest-layout>
    <x-slot name="title">Confirm password</x-slot>

    <p class="eyebrow">( secure area )</p>
    <h2 class="display display-lg">Confirm your password.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        This is a secure area. Please enter your password again to continue.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="field">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="btn-block">Confirm</x-primary-button>
    </form>
</x-guest-layout>
