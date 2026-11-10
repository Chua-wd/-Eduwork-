<section>
    <header>
        <h2>Ganti password</h2>
        <p class="intro">Gunakan password yang panjang dan acak agar akunmu tetap aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="field">
            <x-input-label for="update_password_current_password" value="Password saat ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="row-2">
            <div class="field">
                <x-input-label for="update_password_password" value="Password baru" />
                <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" />
            </div>

            <div class="field">
                <x-input-label for="update_password_password_confirmation" value="Ulangi password baru" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 14px;">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="saved">Password tersimpan.</p>
            @endif
        </div>
    </form>
</section>
