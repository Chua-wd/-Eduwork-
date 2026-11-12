{{-- Category form field, shared by create & edit via @include --}}
@php $category = $category ?? null; @endphp

<div class="field">
    <label for="name">Category name</label>
    <input id="name" type="text" name="name" value="{{ old('name', $category?->name) }}" required autofocus>
    <x-input-error :messages="$errors->get('name')" />
</div>
