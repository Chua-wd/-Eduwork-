{{--
    Versi Breeze memakai modal Alpine.js. Proyek ini tidak memakai Alpine/npm,
    jadi konfirmasinya memakai <details> (buka-tutup bawaan HTML, tanpa JavaScript).
--}}
<section>
    <header>
        <h2>Hapus akun</h2>
        <p class="intro">
            Setelah akun dihapus, semua datanya akan hilang permanen.
            Simpan dulu data yang masih kamu perlukan.
        </p>
    </header>

    <details @if ($errors->userDeletion->isNotEmpty()) open @endif>
        <summary class="btn btn-danger btn-sm">Hapus akun</summary>

        <form method="post" action="{{ route('profile.destroy') }}" style="margin-top: 16px;">
            @csrf
            @method('delete')

            <p style="margin: 0 0 12px;">Yakin ingin menghapus akunmu? Masukkan password untuk konfirmasi.</p>

            <div class="field" style="max-width: 360px;">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" name="password" type="password" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <x-danger-button>Ya, hapus akun saya</x-danger-button>
        </form>
    </details>
</section>
