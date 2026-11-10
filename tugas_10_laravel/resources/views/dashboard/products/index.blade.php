<x-app-layout>
    <x-slot name="title">Products</x-slot>

    <x-slot name="header">
        <div class="page-head">
            <div>
                <p class="eyebrow">( dashboard )</p>
                <h1 class="display display-lg">Products</h1>
            </div>
            <a href="{{ route('dashboard.products.create') }}" class="btn">+ Add product</a>
        </div>
        <x-dashboard-nav />
    </x-slot>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="num muted">{{ $product->id }}</td>
                        <td class="thumb-cell">
                            <div class="visual"><x-product-visual :product="$product" /></div>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="name" style="text-decoration: none;">{{ $product->name }}</a>
                            <div class="eyebrow">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                        </td>
                        <td class="desc">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</td>
                        <td class="num">{{ $product->stock }}</td>
                        <td class="num">Rp {{ number_format($product->price) }}</td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-ghost btn-sm">Edit</a>
                                <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Delete “{{ $product->name }}”? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">No products yet. <a href="{{ route('dashboard.products.create') }}">Add the first one</a>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}

    @if ($products->isNotEmpty())
        <p class="page-info">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
        </p>
    @endif
</x-app-layout>
