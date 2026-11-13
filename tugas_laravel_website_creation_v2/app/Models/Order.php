<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // Order statuses, in the order an order moves through them
    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'returned'];

    // One row per product. Rows from the same "Place order" share a checkout_id.
    protected $fillable = ['checkout_id', 'user_id', 'product_id', 'quantity', 'total', 'status'];

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
