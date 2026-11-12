{{--
    Breeze uses an Alpine.js modal here. This project doesn't use Alpine/npm,
    so the confirmation uses <details> (native HTML open/close, no JavaScript).
--}}
<section>
    <header>
        <h2>Delete account</h2>
        <p class="intro">
            Once your account is deleted, all of its data is permanently removed.
            Download anything you want to keep first.
        </p>
    </header>

    <details @if ($errors->userDeletion->isNotEmpty()) open @endif>
        <summary class="btn btn-danger btn-sm">Delete account</summary>

        <form method="post" action="{{ route('profile.destroy') }}" style="margin-top: 16px;">
            @csrf
            @method('delete')

            <p style="margin: 0 0 12px;">Are you sure you want to delete your account? Enter your password to confirm.</p>

            <div class="field" style="max-width: 360px;">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" name="password" type="password" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <x-danger-button>Yes, delete my account</x-danger-button>
        </form>
    </details>
</section>
