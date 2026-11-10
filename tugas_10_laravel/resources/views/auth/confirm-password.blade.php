<x-guest-layout>
    <x-slot name="title">Konfirmasi password</x-slot>

    <p class="eyebrow">( area aman )</p>
    <h2 class="display display-lg">Konfirmasi password.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        Ini area yang dilindungi. Masukkan password kamu sekali lagi untuk melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="field">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="btn-block">Konfirmasi</x-primary-button>
    </form>
</x-guest-layout>
