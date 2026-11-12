@props(['value'])

{{-- Gaya label diatur oleh ".field label" di layouts/partials/styles --}}
<label {{ $attributes }}>
    {{ $value ?? $slot }}
</label>
