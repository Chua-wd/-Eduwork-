<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'image', 'price', 'stock'];

    // Inverse one-to-many: every product belongs to one category.
    // Used in Blade as $product->category->name
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Every visit to this product's page (dashboard: "product clicks")
    public function clicks(): HasMany
    {
        return $this->hasMany(ProductClick::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
