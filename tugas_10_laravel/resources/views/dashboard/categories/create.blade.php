<x-app-layout>
    <x-slot name="title">Add category</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( dashboard )</p>
        <h1 class="display display-lg">Add category</h1>
        <x-dashboard-nav />
    </x-slot>

    <form action="{{ route('dashboard.categories.store') }}" method="POST" class="form-card" style="max-width: 480px;">
        @csrf
        @include('dashboard.categories._form')

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn">Save category</button>
            <a href="{{ route('dashboard.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</x-app-layout>
