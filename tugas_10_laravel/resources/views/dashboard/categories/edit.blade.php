<x-app-layout>
    <x-slot name="title">Edit category</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( dashboard )</p>
        <h1 class="display display-lg">Edit category</h1>
        <x-dashboard-nav />
    </x-slot>

    <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" class="form-card" style="max-width: 480px;">
        @csrf
        @method('PUT')
        @include('dashboard.categories._form', ['category' => $category])

        <p class="muted" style="font-size: 13px; margin-top: 0;">
            {{ $category->products()->count() }} product(s) use this category.
        </p>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn">Save changes</button>
            <a href="{{ route('dashboard.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</x-app-layout>
