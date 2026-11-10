<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pengguna contoh untuk testing (password: "password")
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password']
        );

        // Urutan penting: kategori dulu, baru produk (produk butuh category_id)
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
