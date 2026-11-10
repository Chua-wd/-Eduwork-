<section>
    <header>
        <h2>Informasi profil</h2>
        <p class="intro">Perbarui nama dan alamat email akunmu.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row-2">
            <div class="field">
                <x-input-label for="name" value="Nama" />
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
                Email kamu belum terverifikasi.
                <button form="send-verification" class="btn btn-ghost btn-sm">Kirim ulang email verifikasi</button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="saved">Link verifikasi baru sudah dikirim ke emailmu.</p>
            @endif
        @endif

        <div style="display: flex; align-items: center; gap: 14px;">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="saved">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
