<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Isi tabel products dengan data dummy, dikelompokkan per kategori.
     * Jalankan CategorySeeder lebih dulu (lihat DatabaseSeeder).
     */
    public function run(): void
    {
        // Nama produk menyertakan ukuran (ml/g); ukuran ini ikut tampil di label kemasan.
        $products = [
            'Pembersih Wajah' => [
                ['name' => 'Charcoal Deep Face Wash 100ml', 'price' => 89000, 'stock' => 40,
                 'description' => 'Sabun wajah arang aktif yang mengangkat minyak dan kotoran tanpa membuat kulit kering.'],
                ['name' => 'Oil Control Gel Cleanser 150ml', 'price' => 99000, 'stock' => 32,
                 'description' => 'Gel pembersih dengan salicylic acid untuk kulit berminyak dan berjerawat.'],
            ],
            'Pelembap' => [
                ['name' => 'Daily Hydra Moisturizer 50ml', 'price' => 129000, 'stock' => 28,
                 'description' => 'Pelembap ringan berbahan hyaluronic acid. Cepat meresap dan tidak lengket.'],
                ['name' => 'Matte Finish Moisturizer 50ml', 'price' => 139000, 'stock' => 20,
                 'description' => 'Melembapkan sekaligus mengontrol kilap sepanjang hari, hasil akhir matte.'],
            ],
            'Serum' => [
                ['name' => 'Niacinamide 10% Serum 30ml', 'price' => 159000, 'stock' => 35,
                 'description' => 'Serum niacinamide 10% untuk menyamarkan pori, meratakan warna kulit, dan mengurangi minyak berlebih.'],
                ['name' => 'Vitamin C Bright Serum 30ml', 'price' => 169000, 'stock' => 24,
                 'description' => 'Vitamin C stabil untuk kulit lebih cerah dan perlindungan dari radikal bebas.'],
                ['name' => 'Cooling Under-Eye Serum 15ml', 'price' => 119000, 'stock' => 18,
                 'description' => 'Serum mata dengan kafein dan efek dingin untuk mata lelah dan kantung mata.'],
            ],
            'Tabir Surya' => [
                ['name' => 'Sport Sunscreen SPF 50 50ml', 'price' => 109000, 'stock' => 45,
                 'description' => 'Tabir surya SPF 50 PA++++, tahan keringat, tanpa white cast. Cocok untuk aktivitas luar ruangan.'],
            ],
            'Cukur & Jenggot' => [
                ['name' => 'Cedar Beard Oil 30ml', 'price' => 119000, 'stock' => 22,
                 'description' => 'Minyak jenggot aroma cedarwood yang melembutkan rambut jenggot dan menenangkan kulit di bawahnya.'],
                ['name' => 'Sensitive Shaving Foam 200ml', 'price' => 79000, 'stock' => 30,
                 'description' => 'Busa cukur dengan aloe vera untuk cukuran halus tanpa iritasi.'],
            ],
            'Perawatan Tubuh' => [
                ['name' => 'Marine Body Wash 250ml', 'price' => 89000, 'stock' => 38,
                 'description' => 'Sabun mandi aroma laut yang segar, membersihkan tanpa membuat kulit kering.'],
                ['name' => 'Fresh Deodorant Stick 50g', 'price' => 69000, 'stock' => 50,
                 'description' => 'Deodoran tanpa alkohol, perlindungan bau hingga 48 jam.'],
            ],
        ];

        foreach ($products as $categoryName => $items) {
            $category = ProductCategory::where('name', $categoryName)->firstOrFail();

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
