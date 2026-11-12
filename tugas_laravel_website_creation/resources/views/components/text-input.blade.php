@props(['disabled' => false])

{{-- Gaya input diatur oleh ".field input" di layouts/partials/styles --}}
<input @disabled($disabled) {{ $attributes }}>
