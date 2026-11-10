{{-- Product form fields, shared by create & edit via @include --}}
@php $product = $product ?? null; @endphp

@if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="field">
    <label for="name">Product name (include the size, e.g. “Hydra Moisturizer 50ml”)</label>
    <input id="name" type="text" name="name" value="{{ old('name', $product?->name) }}" required>
</div>

<div class="field">
    <label for="category_id">Category</label>
    <select id="category_id" name="category_id">
        <option value="">— Uncategorized —</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description">{{ old('description', $product?->description) }}</textarea>
</div>

<div class="row-2">
    <div class="field">
        <label for="price">Price (Rp)</label>
        <input id="price" type="number" name="price" min="0" value="{{ old('price', $product?->price) }}" required>
    </div>
    <div class="field">
        <label for="stock">Stock</label>
        <input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required>
    </div>
</div>

<div class="field">
    <label for="image">Image (JPG, PNG or WebP, max 2 MB)</label>
    <div class="image-field">
        @if ($product)
            <div class="visual"><x-product-visual :product="$product" /></div>
        @endif
        <div style="flex: 1;">
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <p class="muted" style="font-size: 12px; margin: 6px 0 0;">
                @if ($product?->image)
                    Leave empty to keep the current image.
                @else
                    Optional. Without an image, the shop shows a drawn illustration of the packaging.
                @endif
            </p>
            @if ($product?->image)
                <label class="check" style="margin-top: 8px;">
                    <input type="checkbox" name="remove_image" value="1"> Remove image (use the illustration)
                </label>
            @endif
        </div>
    </div>
</div>
