<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Fill the categories table with starter data.
     */
    public function run(): void
    {
        // Categories of the "Navir" men's skincare shop
        $categories = ['Cleansers', 'Moisturizers', 'Serums', 'Sunscreen', 'Shave & Beard', 'Body Care'];

        foreach ($categories as $name) {
            // firstOrCreate: only creates it if it doesn't exist yet, so it's safe to run again
            ProductCategory::firstOrCreate(['name' => $name]);
        }
    }
}
