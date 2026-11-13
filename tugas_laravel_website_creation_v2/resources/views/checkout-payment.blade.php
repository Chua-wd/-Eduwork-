@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    @php $bank = config('shop.bank'); @endphp

    <x-checkout-steps :current="3" />

    <div class="alert">
        Order <b>{{ $checkout->code }}</b> saved. One more step: send us the form below so we can confirm it.
    </div>
    <h1 class="display display-lg" style="margin-bottom: 20px;">Payment details</h1>

    <div class="split" style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 20px; align-items: start;">
        <div>
            <div class="pay-box">
                <p class="eyebrow">( bank transfer )</p>
                <div style="display: flex; justify-content: space-between; margin-top: 8px;"><span class="muted">Bank</span><span>{{ $bank['name'] }}</span></div>
                <div class="acct">{{ $bank['account'] }}</div>
                <div class="muted" style="font-size: 13px;">a.n. {{ $bank['holder'] }}</div>
                <hr class="rule">
                <div class="muted" style="font-size: 13px;">Amount to pay</div>
                <div class="display display-md">Rp {{ number_format($checkout->total) }}</div>
                <button type="button" class="btn btn-ghost btn-sm" style="margin-top: 12px;" id="copy-account"
                        data-account="{{ preg_replace('/\D/', '', $bank['account']) }}">Copy account number</button>
            </div>
            <p class="hint" style="margin-top: 12px;">Please transfer within 24 hours. We confirm by WhatsApp or email.</p>
        </div>

        <form method="POST" action="{{ route('checkout.payment.send', $checkout) }}" class="form-card" style="max-width: none;">
            @csrf
            <p class="eyebrow" style="margin-bottom: 12px;">( confirmation form )</p>
            <div class="row-2">
                <div class="field">
                    <label for="pay-name">Name</label>
                    <input id="pay-name" value="{{ $checkout->recipient_name }}" readonly>
                </div>
                <div class="field">
                    <label for="pay-email">Email</label>
                    <input id="pay-email" value="{{ $checkout->email }}" readonly>
                </div>
            </div>
            <div class="field">
                <label for="message">Message <span class="tag">filled in automatically</span></label>
                <textarea id="message" name="message" class="prefilled" required maxlength="5000">{{ old('message', $prefill) }}</textarea>
                @error('message')
                    <div class="hint" style="color: #b42318;">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn" style="width: 100%;">Send confirmation</button>
        </form>
    </div>

    <script>
        // Copy the account number (digits only) to the clipboard
        document.getElementById('copy-account').addEventListener('click', function () {
            var button = this;
            navigator.clipboard.writeText(button.dataset.account).then(function () {
                button.textContent = 'Copied ✓';
            });
        });
    </script>
@endsection
