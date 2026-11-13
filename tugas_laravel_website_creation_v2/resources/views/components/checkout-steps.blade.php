{{-- Progress bar on the cart, checkout and payment pages: <x-checkout-steps :current="2" /> --}}
@props(['current'])

<ol class="steps" aria-label="Checkout steps">
    @foreach (['Cart', 'Checkout', 'Payment'] as $i => $label)
        @php $step = $i + 1; @endphp
        <li class="{{ $step < $current ? 'done' : ($step === $current ? 'on' : '') }}" @if ($step === $current) aria-current="step" @endif>
            <span class="n">{{ $step < $current ? '✓' : $step }}</span>{{ $label }}
        </li>
    @endforeach
</ol>
