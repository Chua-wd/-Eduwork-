<x-app-layout>
    <x-slot name="title">Edit product</x-slot>

    <x-slot name="header">
        <p class="eyebrow">( dashboard )</p>
        <h1 class="display display-lg">Edit product</h1>
        <x-dashboard-nav />
    </x-slot>

    <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('dashboard.products._form', ['product' => $product])

        <div style="display: flex; gap: 8px; margin-top: 8px;">
            <button type="submit" class="btn">Save changes</button>
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-ghost">Cancel</a>
            <a href="{{ route('products.show', $product) }}" class="btn btn-ghost" style="margin-left: auto;">View in shop &#8599;</a>
        </div>
    </form>
</x-app-layout>
