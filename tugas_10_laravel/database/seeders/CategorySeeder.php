<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Isi tabel categories dengan data awal.
     */
    public function run(): void
    {
        // Kategori toko perawatan kulit pria "Navir"
        $categories = ['Pembersih Wajah', 'Pelembap', 'Serum', 'Tabir Surya', 'Cukur & Jenggot', 'Perawatan Tubuh'];

        foreach ($categories as $name) {
            // firstOrCreate: buat jika belum ada, jadi aman dijalankan berulang kali
            ProductCategory::firstOrCreate(['name' => $name]);
        }
    }
}
