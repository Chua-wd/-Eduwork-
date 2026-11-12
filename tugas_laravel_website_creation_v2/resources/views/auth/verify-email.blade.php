<x-guest-layout>
    <x-slot name="title">Verify email</x-slot>

    <p class="eyebrow">( verify email )</p>
    <h2 class="display display-lg">Check your inbox.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        Thanks for signing up! Please click the verification link we just emailed you.
        Didn't get it? We can send another one.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert">A new verification link has been sent to the email address you signed up with.</div>
    @endif

    <div class="auth-links">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Resend verification email</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">Log out</button>
        </form>
    </div>
</x-guest-layout>
