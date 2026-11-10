<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Fill the products table with dummy data, grouped by category.
     * Run CategorySeeder first (see DatabaseSeeder).
     */
    public function run(): void
    {
        // Product names include the size (ml/g); the size is also printed on the drawn packaging.
        $products = [
            'Cleansers' => [
                ['name' => 'Charcoal Deep Face Wash 100ml', 'price' => 89000, 'stock' => 40,
                 'description' => 'Activated charcoal face wash that lifts oil and dirt without drying out your skin.'],
                ['name' => 'Oil Control Gel Cleanser 150ml', 'price' => 99000, 'stock' => 32,
                 'description' => 'Gel cleanser with salicylic acid for oily and breakout-prone skin.'],
            ],
            'Moisturizers' => [
                ['name' => 'Daily Hydra Moisturizer 50ml', 'price' => 129000, 'stock' => 28,
                 'description' => 'Lightweight moisturizer with hyaluronic acid. Absorbs fast and never feels sticky.'],
                ['name' => 'Matte Finish Moisturizer 50ml', 'price' => 139000, 'stock' => 20,
                 'description' => 'Hydrates while keeping shine under control all day, with a matte finish.'],
            ],
            'Serums' => [
                ['name' => 'Niacinamide 10% Serum 30ml', 'price' => 159000, 'stock' => 35,
                 'description' => '10% niacinamide serum that minimizes pores, evens out skin tone and reduces excess oil.'],
                ['name' => 'Vitamin C Bright Serum 30ml', 'price' => 169000, 'stock' => 24,
                 'description' => 'Stable vitamin C for brighter skin and protection against free radicals.'],
                ['name' => 'Cooling Under-Eye Serum 15ml', 'price' => 119000, 'stock' => 8,
                 'description' => 'Caffeine eye serum with a cooling tip for tired eyes and puffiness.'],
            ],
            'Sunscreen' => [
                ['name' => 'Sport Sunscreen SPF 50 50ml', 'price' => 109000, 'stock' => 45,
                 'description' => 'SPF 50 PA++++ sunscreen that is sweat-resistant with no white cast. Made for outdoor days.'],
            ],
            'Shave & Beard' => [
                ['name' => 'Cedar Beard Oil 30ml', 'price' => 119000, 'stock' => 22,
                 'description' => 'Cedarwood-scented beard oil that softens beard hair and soothes the skin underneath.'],
                ['name' => 'Sensitive Shaving Foam 200ml', 'price' => 79000, 'stock' => 30,
                 'description' => 'Shaving foam with aloe vera for a smooth shave without irritation.'],
            ],
            'Body Care' => [
                ['name' => 'Marine Body Wash 250ml', 'price' => 89000, 'stock' => 38,
                 'description' => 'Fresh ocean-scented body wash that cleans without drying out your skin.'],
                ['name' => 'Fresh Deodorant Stick 50g', 'price' => 69000, 'stock' => 50,
                 'description' => 'Alcohol-free deodorant with up to 48 hours of odor protection.'],
            ],
        ];

        foreach ($products as $categoryName => $items) {
            $category = ProductCategory::where('name', $categoryName)->firstOrFail();

            foreach ($items as $item) {
                // updateOrCreate: find by name, then create it or update its data
                Product::updateOrCreate(
                    ['name' => $item['name']],
                    $item + ['category_id' => $category->id]
                );
            }
        }
    }
}
