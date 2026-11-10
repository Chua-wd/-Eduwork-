<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Isi tabel categories dengan data awal.
     */
    public function run(): void
    {
        $categories = ['Elektronik', 'Buku', 'Fashion', 'Aksesoris Komputer', 'Peralatan Rumah'];

        foreach ($categories as $name) {
            // firstOrCreate: buat jika belum ada, jadi aman dijalankan berulang kali
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
