<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * One placed order (one "Place order" click). Its products are the related Order rows.
 */
class Checkout extends Model
{
    protected $fillable = [
        'code', 'user_id', 'recipient_name', 'phone', 'email', 'address', 'note',
        'subtotal', 'shipping', 'total', 'payment_message', 'payment_sent_at',
    ];

    protected function casts(): array
    {
        return ['payment_sent_at' => 'datetime'];
    }

    // URLs use the code (/checkout/NVR-7KQ2M9XA/payment), not the id
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Text that is pasted into the "Message" field of the payment form.
     */
    public function paymentMessage(): string
    {
        $rp = fn (int $amount) => 'Rp ' . number_format($amount);
        $bank = config('shop.bank');

        $lines = $this->orders->map(fn (Order $order) =>
            "- {$order->product->name} x{$order->quantity} = {$rp($order->total)}"
        )->implode("\n");

        return implode("\n", array_filter([
            "Hi Navir, I'd like to confirm my order.",
            '',
            "Order no : {$this->code}",
            $lines,
            "Subtotal : {$rp($this->subtotal)}",
            'Shipping : ' . ($this->shipping ? $rp($this->shipping) : 'Free'),
            "TOTAL    : {$rp($this->total)}",
            '',
            "Ship to  : {$this->recipient_name} ({$this->phone})",
            "Address  : {$this->address}",
            $this->note ? "Note     : {$this->note}" : null,
            '',
            "Payment  : bank transfer to {$bank['name']} {$bank['account']} (a.n. {$bank['holder']})",
        ], fn ($line) => $line !== null));
    }
}
