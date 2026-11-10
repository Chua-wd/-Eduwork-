<x-guest-layout>
    <x-slot name="title">Lupa password</x-slot>

    <p class="eyebrow">( lupa password )</p>
    <h2 class="display display-lg">Atur ulang password.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        Masukkan email akunmu. Kami akan mengirim link untuk membuat password baru.
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

        <x-primary-button class="btn-block">Kirim link reset password</x-primary-button>
    </form>

    <p class="auth-links" style="justify-content: center;">
        <a href="{{ route('login') }}">&larr; Kembali ke halaman masuk</a>
    </p>
</x-guest-layout>
