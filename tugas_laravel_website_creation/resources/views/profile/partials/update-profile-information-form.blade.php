<section>
    <header>
        <h2>Profile information</h2>
        <p class="intro">Update your account's name and email address.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row-2">
            <div class="field">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="field">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p class="muted" style="font-size: 13px;">
                Your email address is unverified.
                <button form="send-verification" class="btn btn-ghost btn-sm">Resend verification email</button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="saved">A new verification link has been sent to your email address.</p>
            @endif
        @endif

        <div style="display: flex; align-items: center; gap: 14px;">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="saved">Saved.</p>
            @endif
        </div>
    </form>
</section>
