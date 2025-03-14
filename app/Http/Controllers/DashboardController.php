<?php
// filepath: g:\projects\restaurant-system\app\Http\Controllers\DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'stats' => $this->getStats('today')
        ]);
    }

    public function getData(Request $request)
    {
        $range = $request->input('range', 'today');
        $stats = $this->getStats($range);

        return response()->json($stats);
    }

    private function getStats($range)
    {
        $now = Carbon::now();

        // Set date range based on selection
        switch ($range) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                break;
            case 'last7':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                break;
            case 'thismonth':
                $startDate = $now->copy()->startOfMonth();
                break;
            case 'lastmonth':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            default:
                $startDate = $now->copy()->startOfDay();
        }

        // Set end date to now if not explicitly defined
        if (!isset($endDate)) {
            $endDate = $now;
        }

        // Get orders data for the date range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate]);
        $orderCount = $orders->count();
        $revenue = $orders->sum('total_price');

        // Chart data
        $chartData = [];

        if (in_array($range, ['today', 'yesterday'])) {
            // Hourly breakdown
            for ($i = 0; $i < 24; $i++) {
                $hour = ($range === 'today' ? $now->copy()->startOfDay() : $now->copy()->subDay()->startOfDay())->addHours($i);
                $nextHour = $hour->copy()->addHour();

                $hourOrders = Order::whereBetween('created_at', [$hour, $nextHour])->count();
                $hourRevenue = Order::whereBetween('created_at', [$hour, $nextHour])->sum('total_price');

                $chartData[] = [
                    'label' => $hour->format('g A'), // 1 AM, 2 AM, etc.
                    'orders' => $hourOrders,
                    'revenue' => $hourRevenue
                ];
            }
        } else if ($range === 'last7') {
            // Daily breakdown for last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $day = $now->copy()->subDays($i)->startOfDay();
                $nextDay = $day->copy()->addDay();

                $dayOrders = Order::whereBetween('created_at', [$day, $nextDay])->count();
                $dayRevenue = Order::whereBetween('created_at', [$day, $nextDay])->sum('total_price');

                $chartData[] = [
                    'label' => $day->format('D'), // Mon, Tue, etc.
                    'orders' => $dayOrders,
                    'revenue' => $dayRevenue
                ];
            }
        } else {
            // Daily breakdown for month
            $daysInRange = $startDate->diffInDays($endDate) + 1;
            $daysToShow = min($daysInRange, 30); // Cap at 30 days

            for ($i = $daysToShow - 1; $i >= 0; $i--) {
                $day = $endDate->copy()->subDays($i)->startOfDay();
                $nextDay = $day->copy()->addDay();

                $dayOrders = Order::whereBetween('created_at', [$day, $nextDay])->count();
                $dayRevenue = Order::whereBetween('created_at', [$day, $nextDay])->sum('total_price');

                $chartData[] = [
                    'label' => $day->format('M d'), // Jan 01, etc.
                    'orders' => $dayOrders,
                    'revenue' => $dayRevenue
                ];
            }
        }

        // Popular items for the date range
        $popularItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('items.id', 'items.name', 'items.image', DB::raw('SUM(order_items.quantity) as quantity'))
            ->groupBy('items.id', 'items.name', 'items.image')
            ->orderByDesc('quantity')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('orderItems')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();

        // Get top categories by revenue
        $topCategories = DB::table('categories')
            ->join('items', 'categories.id', '=', 'items.category_id')
            ->join('order_items', 'items.id', '=', 'order_items.item_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('categories.id', 'categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Calculate daily order average within the range
        $dailyAverageOrders = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as order_date, COUNT(*) as count')
            ->groupBy('order_date')
            ->get()
            ->avg('count') ?? 0;

        // Average order value within the range
        $averageOrderValue = $orderCount > 0 ? $revenue / $orderCount : 0;

        return [
            'range' => $range,
            'orderCount' => $orderCount,
            'revenue' => $revenue,
            'chartData' => $chartData,
            'popularItems' => $popularItems,
            'recentOrders' => $recentOrders,
            'topCategories' => $topCategories,
            'dailyAverageOrders' => $dailyAverageOrders,
            'averageOrderValue' => $averageOrderValue
        ];
    }
}
