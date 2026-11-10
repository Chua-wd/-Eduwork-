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
        // Test account (password: "password")
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password']
        );

        // Order matters: categories, then products (need category_id),
        // then dashboard data (clicks & orders need products)
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            DashboardSeeder::class,
        ]);
    }
}
