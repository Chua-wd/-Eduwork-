<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    // Nama model "ProductCategory" -> Laravel menebak tabel "product_categories".
    // Tabel kita bernama "categories", jadi ditulis eksplisit.
    protected $table = 'categories';

    protected $fillable = ['name'];

    // Relasi: satu kategori punya banyak produk (one-to-many).
    // Kolom penghubung di tabel products adalah "category_id"
    // (tebakan default Laravel "product_category_id", jadi ditulis eksplisit).
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
