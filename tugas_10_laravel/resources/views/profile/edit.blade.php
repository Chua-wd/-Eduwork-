<x-app-layout>
    <x-slot name="title">Profil</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( profil )</p>
        <h1 class="display display-lg">Pengaturan akun</h1>
    </x-slot>

    <div style="max-width: 760px;">
        <div class="section-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="section-card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="section-card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
