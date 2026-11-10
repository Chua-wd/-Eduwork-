<x-app-layout>
    <x-slot name="title">Add product</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( dashboard )</p>
        <h1 class="display display-lg">Add product</h1>
        <x-dashboard-nav />
    </x-slot>

    {{-- enctype is required for file uploads --}}
    <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('dashboard.products._form')

        <div style="display: flex; gap: 8px; margin-top: 8px;">
            <button type="submit" class="btn">Save product</button>
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</x-app-layout>
