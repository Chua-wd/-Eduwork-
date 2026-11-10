<x-admin-layout>
    <x-slot name="title">Product Categories</x-slot>

    <x-slot name="header">
        <div class="page-head">
            <div>
                <p class="eyebrow">( dashboard )</p>
                <h1 class="display display-lg">Product Categories</h1>
            </div>
            <a href="{{ route('dashboard.categories.create') }}" class="btn">+ Add category</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="table-wrap" style="max-width: 860px;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category name</th>
                    <th>Products</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="num muted">{{ $category->id }}</td>
                        <td class="name">{{ $category->name }}</td>
                        <td class="num">
                            {{-- products_count comes from withCount('products') in the controller --}}
                            <a href="{{ route('products.index', ['category' => $category->id]) }}">
                                {{ $category->products_count }} {{ \Illuminate\Support\Str::plural('product', $category->products_count) }}
                            </a>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-ghost btn-sm">Edit</a>
                                <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Delete the “{{ $category->name }}” category?{{ $category->products_count ? ' Its ' . $category->products_count . ' product(s) will become uncategorized.' : '' }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No categories yet. <a href="{{ route('dashboard.categories.create') }}">Add the first one</a>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
