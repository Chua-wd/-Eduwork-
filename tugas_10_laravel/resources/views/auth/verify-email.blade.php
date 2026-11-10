<x-guest-layout>
    <x-slot name="title">Verifikasi email</x-slot>

    <p class="eyebrow">( verifikasi email )</p>
    <h2 class="display display-lg">Cek email kamu.</h2>
    <p class="muted" style="margin: 8px 0 24px;">
        Terima kasih sudah mendaftar! Klik link verifikasi yang baru kami kirim ke emailmu.
        Belum menerima email? Kami bisa mengirim ulang.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert">Link verifikasi baru sudah dikirim ke email yang kamu daftarkan.</div>
    @endif

    <div class="auth-links">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Kirim ulang email verifikasi</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
        </form>
    </div>
</x-guest-layout>
