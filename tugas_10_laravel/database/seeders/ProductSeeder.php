<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Isi tabel products dengan data dummy, dikelompokkan per kategori.
     * Jalankan CategorySeeder lebih dulu (lihat DatabaseSeeder).
     */
    public function run(): void
    {
        $products = [
            'Elektronik' => [
                ['name' => 'Laptop A',            'description' => 'Laptop 14 inch, RAM 16GB, SSD 512GB', 'price' => 8500000, 'stock' => 10],
                ['name' => 'HP Samsung',          'description' => 'Smartphone layar AMOLED 6.5 inch',    'price' => 3500000, 'stock' => 15],
                ['name' => 'Earbuds Wireless',    'description' => 'Bluetooth 5.3 dengan noise cancelling', 'price' => 450000, 'stock' => 30],
            ],
            'Buku' => [
                ['name' => 'Belajar MySQL Dasar', 'description' => 'Panduan database MySQL untuk pemula', 'price' => 95000,  'stock' => 25],
                ['name' => 'Laravel untuk Pemula', 'description' => 'Membangun web dengan framework Laravel', 'price' => 120000, 'stock' => 20],
                ['name' => 'Clean Code',          'description' => 'Menulis kode yang rapi dan mudah dirawat', 'price' => 150000, 'stock' => 12],
            ],
            'Fashion' => [
                ['name' => 'Kaos Polos Hitam',    'description' => 'Katun combed 30s, ukuran M-XL',       'price' => 75000,  'stock' => 50],
                ['name' => 'Jaket Hoodie',        'description' => 'Bahan fleece tebal dan hangat',       'price' => 210000, 'stock' => 18],
            ],
            'Aksesoris Komputer' => [
                ['name' => 'Keyboard Mechanical', 'description' => 'Switch blue dengan lampu RGB',        'price' => 450000, 'stock' => 25],
                ['name' => 'Mouse Wireless',      'description' => 'Mouse 2.4GHz hemat baterai',          'price' => 180000, 'stock' => 40],
            ],
            'Peralatan Rumah' => [
                ['name' => 'Rice Cooker 1.8L',    'description' => 'Penanak nasi dengan fitur penghangat', 'price' => 350000, 'stock' => 8],
                ['name' => 'Kipas Angin Berdiri', 'description' => '3 tingkat kecepatan, 16 inch',        'price' => 275000, 'stock' => 6],
            ],
        ];

        foreach ($products as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->firstOrFail();

            foreach ($items as $item) {
                // updateOrCreate: cari berdasarkan nama, buat baru atau perbarui datanya
                Product::updateOrCreate(
                    ['name' => $item['name']],
                    $item + ['category_id' => $category->id]
                );
            }
        }
    }
}
