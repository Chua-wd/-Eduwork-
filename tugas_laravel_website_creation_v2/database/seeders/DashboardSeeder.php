<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Dummy data so the admin dashboard isn't empty:
 * 40 customers, ~120 days of product clicks (growing traffic) and the orders that came from them.
 * Run after CategorySeeder and ProductSeeder.
 */
class DashboardSeeder extends Seeder
{
    private const DAYS = 120;
    private const CONVERSION = 0.032; // about 3.2% of clicks become an order

    public function run(): void
    {
        if (Order::exists() || ProductClick::exists()) {
            $this->command?->warn('Orders/clicks already exist - DashboardSeeder skipped.');
            return;
        }

        mt_srand(2026); // same "random" data every time

        $customerIds = User::factory()->count(40)->create()->pluck('id')->all();
        $products = Product::with('category')->get()->keyBy('id');

        // Some categories get more traffic than others
        $categoryWeight = [
            'Serums' => 9, 'Cleansers' => 7, 'Moisturizers' => 7,
            'Sunscreen' => 6, 'Shave & Beard' => 5, 'Body Care' => 4,
        ];
        $weights = $products->map(fn ($p) => ($categoryWeight[$p->category?->name] ?? 4) + mt_rand(0, 3))->all();

        $now = Carbon::now();
        $clicks = [];
        $orders = [];

        for ($daysAgo = self::DAYS - 1; $daysAgo >= 0; $daysAgo--) {
            $day = $now->copy()->subDays($daysAgo)->startOfDay();
            $progress = 1 - $daysAgo / (self::DAYS - 1);           // 0 = oldest day, 1 = today
            $weekend = $day->isWeekend() ? 1.2 : 1.0;
            $wave = 1 + 0.12 * sin($daysAgo / 6);
            $noise = 0.85 + $this->rand() * 0.3;
            $dayClicks = (int) round((45 + 55 * $progress) * $weekend * $wave * $noise);

            // Today: only the part of the day that has already happened
            $secondsAvailable = $daysAgo === 0 ? max(60, $now->secondsSinceMidnight()) : 86400;
            if ($daysAgo === 0) {
                $dayClicks = (int) round($dayClicks * $secondsAvailable / 86400);
            }

            for ($i = 0; $i < $dayClicks; $i++) {
                $clicks[] = [
                    'product_id' => $this->pick($weights),
                    'user_id'    => mt_rand(1, 4) === 1 ? $customerIds[array_rand($customerIds)] : null,
                    'created_at' => $day->copy()->addSeconds(mt_rand(0, $secondsAvailable - 1)),
                ];
            }

            $dayOrders = (int) round($dayClicks * self::CONVERSION + ($this->rand() - 0.5) * 2);
            for ($i = 0; $i < max(0, $dayOrders); $i++) {
                $product = $products[$this->pick($weights)];
                $quantity = $this->pick([1 => 70, 2 => 22, 3 => 8]);
                $time = $day->copy()->addSeconds(mt_rand(0, $secondsAvailable - 1));

                $orders[] = [
                    'user_id'    => $customerIds[array_rand($customerIds)],
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'total'      => $product->price * $quantity,
                    'status'     => $this->statusFor($daysAgo),
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
            }
        }

        // Insert in chunks: much faster than one query per row
        foreach (array_chunk($clicks, 1000) as $chunk) {
            DB::table('product_clicks')->insert($chunk);
        }
        foreach (array_chunk($orders, 500) as $chunk) {
            DB::table('orders')->insert($chunk);
        }

        $this->command?->info(count($clicks) . ' clicks and ' . count($orders) . ' orders created.');
    }

    // Older orders are further along: recent ones are pending/processing, old ones delivered
    private function statusFor(int $daysAgo): string
    {
        return match (true) {
            $daysAgo <= 1 => $this->pick(['pending' => 55, 'processing' => 45]),
            $daysAgo <= 4 => $this->pick(['processing' => 35, 'shipped' => 65]),
            $daysAgo <= 9 => $this->pick(['shipped' => 45, 'delivered' => 55]),
            default       => $this->pick(['delivered' => 95, 'returned' => 5]),
        };
    }

    // Pick a key from [key => weight]
    private function pick(array $weights): int|string
    {
        $roll = $this->rand() * array_sum($weights);
        foreach ($weights as $key => $weight) {
            if (($roll -= $weight) < 0) {
                return $key;
            }
        }

        return array_key_last($weights);
    }

    private function rand(): float
    {
        return mt_rand() / mt_getrandmax();
    }
}
