@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert']) }}>
        {{ $status }}
    </div>
@endif
