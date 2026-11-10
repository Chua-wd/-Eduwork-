<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'image', 'price', 'stock'];

    // Relasi kebalikannya: setiap produk milik satu kategori (inverse one-to-many).
    // Dipakai di Blade sebagai $product->category->name
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
