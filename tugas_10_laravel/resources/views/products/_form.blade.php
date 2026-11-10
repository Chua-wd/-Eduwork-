{{-- Field form produk, dipakai bersama oleh create & edit lewat @include --}}
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
    <label for="name">Nama produk (sertakan ukuran, mis. "Hydra Moisturizer 50ml")</label>
    <input id="name" type="text" name="name" value="{{ old('name', $product?->name) }}">
</div>

<div class="field">
    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id">
        <option value="">-- Pilih kategori --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>

<div class="field">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description">{{ old('description', $product?->description) }}</textarea>
</div>

<div class="row-2">
    <div class="field">
        <label for="price">Harga (Rp)</label>
        <input id="price" type="number" name="price" value="{{ old('price', $product?->price) }}">
    </div>
    <div class="field">
        <label for="stock">Stok</label>
        <input id="stock" type="number" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}">
    </div>
</div>
