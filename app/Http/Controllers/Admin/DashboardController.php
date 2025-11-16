<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Today's Analytics
        $todayStats = [
            'orders' => Order::whereDate('created_at', $today)->count(),
            'completed' => Order::whereDate('created_at', $today)
                              ->where('status', 'delivered')
                              ->count(),
            'revenue' => Order::whereDate('created_at', $today)
                           ->where('payment_status', 'paid')
                           ->sum('total_amount'),
            'customers' => User::whereDate('created_at', $today)
                             ->whereHas('roles', function($query) {
                                 $query->where('name', 'customer');
                             })->count(),
            'pending_orders' => Order::whereDate('created_at', $today)
                                   ->where('status', 'pending')
                                   ->count(),
        ];

        // Yesterday's comparison
        $yesterdayStats = [
            'orders' => Order::whereDate('created_at', $yesterday)->count(),
            'revenue' => Order::whereDate('created_at', $yesterday)
                           ->where('payment_status', 'paid')
                           ->sum('total_amount'),
            'customers' => User::whereDate('created_at', $yesterday)
                             ->whereHas('roles', function($query) {
                                 $query->where('name', 'customer');
                             })->count(),
        ];

        // Calculate percentage changes
        $changes = [
            'orders' => $this->calculatePercentageChange($yesterdayStats['orders'], $todayStats['orders']),
            'revenue' => $this->calculatePercentageChange($yesterdayStats['revenue'], $todayStats['revenue']),
            'customers' => $this->calculatePercentageChange($yesterdayStats['customers'], $todayStats['customers']),
        ];

        // General Statistics
        $stats = [
            'total_customers' => User::whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })->count(),
            'total_products' => Product::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'low_stock_products' => Product::where('manage_stock', true)
                                          ->whereColumn('stock', '<=', 'min_stock')
                                          ->where('stock', '>', 0)
                                          ->count(),
            'total_categories' => Category::where('is_active', true)->count(),
        ];

        // Recent Orders
        $recent_orders = Order::with(['user', 'items'])
                             ->latest()
                             ->limit(5)
                             ->get();

        // Top Products (this week)
        $top_products = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
                              ->join('order_items', 'products.id', '=', 'order_items.product_id')
                              ->join('orders', 'order_items.order_id', '=', 'orders.id')
                              ->where('orders.created_at', '>=', $thisWeek)
                              ->where('orders.payment_status', 'paid')
                              ->with('category')
                              ->groupBy('products.id')
                              ->orderBy('total_sold', 'DESC')
                              ->limit(5)
                              ->get();

        // Weekly Revenue Chart Data (last 7 days)
        $weeklyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                           ->where('payment_status', 'paid')
                           ->sum('total_amount');
            $weeklyRevenue[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue
            ];
        }

        // Monthly Order Status Distribution
        $orderStatusData = Order::select('status', DB::raw('count(*) as count'))
                               ->where('created_at', '>=', $thisMonth)
                               ->groupBy('status')
                               ->get()
                               ->mapWithKeys(function ($item) {
                                   return [$item['status'] => $item['count']];
                               });

        // Ensure all statuses are present with 0 if not exists
        $allStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        foreach ($allStatuses as $status) {
            if (!isset($orderStatusData[$status])) {
                $orderStatusData[$status] = 0;
            }
        }

        // Top Categories by Revenue (this month)
        $topCategories = Category::select('categories.id', 'categories.name', DB::raw('SUM(orders.total_amount) as total_revenue'))
                                ->join('products', 'categories.id', '=', 'products.category_id')
                                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->where('orders.created_at', '>=', $thisMonth)
                                ->where('orders.payment_status', 'paid')
                                ->groupBy('categories.id', 'categories.name')
                                ->orderBy('total_revenue', 'DESC')
                                ->limit(5)
                                ->get();

        // Hourly Orders Today
        $hourlyOrders = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                            ->whereDate('created_at', $today)
                            ->groupBy(DB::raw('HOUR(created_at)'))
                            ->orderBy('hour')
                            ->get()
                            ->pluck('count', 'hour');

        // Fill missing hours with 0
        $hourlyOrdersFormatted = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyOrdersFormatted[] = [
                'hour' => sprintf('%02d:00', $hour),
                'orders' => $hourlyOrders->get($hour, 0)
            ];
        }

        return view('admin.dashboard', compact(
            'todayStats',
            'changes',
            'stats',
            'recent_orders',
            'top_products',
            'weeklyRevenue',
            'orderStatusData',
            'topCategories',
            'hourlyOrdersFormatted'
        ));
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }

    public function stats(Request $request)
    {
        // API endpoint untuk real-time stats (AJAX)
        $today = Carbon::today();
        
        $stats = [
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)
                                   ->where('payment_status', 'paid')
                                   ->sum('total_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_count' => Product::where('manage_stock', true)
                                       ->whereColumn('stock', '<=', 'min_stock')
                                       ->where('stock', '>', 0)
                                       ->count(),
        ];

        return response()->json($stats);
    }
}