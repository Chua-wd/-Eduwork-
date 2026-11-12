<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One visit to a product page. Recorded in ProductController@show.
 */
class ProductClick extends Model
{
    // The table only has created_at (a click never changes afterwards)
    public const UPDATED_AT = null;

    protected $fillable = ['product_id', 'user_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
