<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductClick;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Main admin dashboard: summary of products, categories, product clicks, orders and revenue.
 * GET /dashboard?range=7|30|90  (default: last 30 days)
 */
class DashboardController extends Controller
{
    public const RANGES = [7, 30, 90];

    public function index(Request $request)
    {
        $range = in_array((int) $request->range, self::RANGES) ? (int) $request->range : 30;

        // Current period and the same-length period right before it (for the % change)
        $end = Carbon::now();
        $start = $end->copy()->subDays($range - 1)->startOfDay();
        $prevEnd = $start->copy()->subSecond();
        $prevStart = $start->copy()->subDays($range);

        // ===== The 3 numbers the task asks for =====
        $productCount = Product::count();
        $categoryCount = ProductCategory::count();
        $clicks = ProductClick::whereBetween('created_at', [$start, $end])->count();
        $prevClicks = ProductClick::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // ===== Extra KPIs =====
        $newProducts = Product::whereBetween('created_at', [$start, $end])->count();
        $emptyCategories = ProductCategory::doesntHave('products')->count();

        $orders = Order::whereBetween('created_at', [$start, $end]);
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd]);
        $orderCount = (clone $orders)->count();
        $prevOrderCount = (clone $prevOrders)->count();
        $revenue = (int) (clone $orders)->where('status', '!=', 'returned')->sum('total');
        $prevRevenue = (int) (clone $prevOrders)->where('status', '!=', 'returned')->sum('total');

        // Conversion = share of product clicks that turned into an order
        $conversion = $clicks ? $orderCount / $clicks * 100 : 0;
        $prevConversion = $prevClicks ? $prevOrderCount / $prevClicks * 100 : 0;

        $kpis = [
            ['label' => 'Products',           'value' => number_format($productCount),  'note' => $newProducts ? "{$newProducts} added in this period" : 'In the shop now', 'icon' => 'box'],
            ['label' => 'Product categories', 'value' => number_format($categoryCount), 'note' => $emptyCategories ? "{$emptyCategories} without products" : 'All have products', 'icon' => 'tag'],
            ['label' => 'Product clicks',     'value' => number_format($clicks),        'delta' => $this->change($clicks, $prevClicks), 'icon' => 'cursor'],
            ['label' => 'Orders',             'value' => number_format($orderCount),    'delta' => $this->change($orderCount, $prevOrderCount), 'icon' => 'bag'],
            ['label' => 'Revenue',            'value' => 'Rp ' . $this->compact($revenue), 'delta' => $this->change($revenue, $prevRevenue), 'icon' => 'chart'],
        ];

        // ===== Daily series for the line charts (days without data count as 0) =====
        $clicksByDay = ProductClick::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) AS day, COUNT(*) AS total')->groupBy('day')->pluck('total', 'day');
        $revenueByDay = Order::whereBetween('created_at', [$start, $end])->where('status', '!=', 'returned')
            ->selectRaw('DATE(created_at) AS day, SUM(total) AS total')->groupBy('day')->pluck('total', 'day');

        $clickSeries = [];
        $revenueSeries = [];
        for ($day = $start->copy(); $day <= $end; $day->addDay()) {
            $key = $day->toDateString();
            $label = $day->format('M j');
            $clickSeries[] = ['label' => $label, 'value' => (int) ($clicksByDay[$key] ?? 0)];
            $revenueSeries[] = ['label' => $label, 'value' => (int) ($revenueByDay[$key] ?? 0)];
        }

        // ===== Clicks by category (ranked bars) =====
        $clicksByCategory = ProductClick::whereBetween('product_clicks.created_at', [$start, $end])
            ->join('products', 'products.id', '=', 'product_clicks.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->selectRaw("COALESCE(categories.name, 'Uncategorized') AS name, COUNT(*) AS total")
            ->groupBy('categories.name')->orderByDesc('total')->get();

        // ===== Most-clicked products =====
        $topProducts = Product::with('category')
            ->withCount(['clicks' => fn ($q) => $q->whereBetween('created_at', [$start, $end])])
            ->withCount(['orders' => fn ($q) => $q->whereBetween('created_at', [$start, $end])])
            ->withSum(['orders as revenue' => fn ($q) => $q->whereBetween('created_at', [$start, $end])->where('status', '!=', 'returned')], 'total')
            ->orderByDesc('clicks_count')->take(5)->get();

        // ===== Order fulfillment (orders in the period, by status) =====
        $statusCounts = (clone $orders)->select('status', DB::raw('COUNT(*) AS total'))->groupBy('status')->pluck('total', 'status');
        $fulfillment = collect(Order::STATUSES)->map(fn ($status) => [
            'status' => $status,
            'count'  => (int) ($statusCounts[$status] ?? 0),
            'share'  => $orderCount ? ($statusCounts[$status] ?? 0) / $orderCount * 100 : 0,
        ]);
        $avgOrderValue = $orderCount ? (int) round((clone $orders)->avg('total')) : 0;
        $itemsPerOrder = $orderCount ? (clone $orders)->avg('quantity') : 0;

        // ===== Latest orders =====
        $recentOrders = Order::with(['user', 'product'])->latest()->take(6)->get();

        $lowStock = Product::where('stock', '<', 10)->count();

        return view('dashboard', compact(
            'range', 'start', 'end', 'kpis', 'clicks', 'conversion', 'prevConversion', 'orderCount',
            'clickSeries', 'revenueSeries', 'revenue', 'clicksByCategory', 'topProducts',
            'fulfillment', 'avgOrderValue', 'itemsPerOrder', 'recentOrders', 'lowStock',
        ));
    }

    // % change vs the previous period (null when there is nothing to compare with)
    private function change(int|float $now, int|float $before): ?float
    {
        return $before > 0 ? round(($now - $before) / $before * 100, 1) : null;
    }

    // 12,345,678 -> 12.3M
    private function compact(int $value): string
    {
        return match (true) {
            $value >= 1_000_000 => round($value / 1_000_000, 1) . 'M',
            $value >= 1_000     => round($value / 1_000, 1) . 'K',
            default             => (string) $value,
        };
    }
}
