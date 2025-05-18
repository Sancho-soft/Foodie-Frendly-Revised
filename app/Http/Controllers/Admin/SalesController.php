<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request, default to last 30 days
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();

        // Fetch orders within the date range
        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->with('orderItems.food')
            ->get();

        // Calculate total sales (subtotal + delivery_fee)
        $totalSales = $orders->sum(function ($order) {
            return $order->total_amount + ($order->delivery_fee ?? 0.00);
        });

        // Calculate total orders
        $totalOrders = $orders->count();

        // Calculate average order value
        $avgOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Calculate popular item
        $popularItemData = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->select('food_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('food_id')
            ->orderByDesc('total_quantity')
            ->first();

        $popularItem = $popularItemData ? Food::find($popularItemData->food_id)->name : 'N/A';
        $popularCount = $popularItemData ? $popularItemData->total_quantity : 0;

        // Weekly sales trend (last 7 days)
        $weeklySales = [
            'labels' => [],
            'data' => []
        ];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklySales['labels'][] = $date->format('M d');
            $dailySales = Order::whereDate('order_date', $date)
                ->where('status', 'delivered')
                ->get()
                ->sum(function ($order) {
                    return $order->total_amount + ($order->delivery_fee ?? 0.00);
                });
            $weeklySales['data'][] = (float) $dailySales;
        }

        // Sales by category
        $uniqueCategories = Food::select('category')->distinct()->pluck('category')->map(function ($cat) {
            return $cat ?? 'Uncategorized';
        })->all();
        
        $categories = [];
        foreach ($uniqueCategories as $cat) {
            $categories[] = [
                'name' => $cat,
                'sales' => 0,
                'count' => 0,
                'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)
            ];
        }

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $category = $item->food->category ?? 'Uncategorized';
                $price = $item->price * $item->quantity;
                foreach ($categories as &$cat) {
                    if ($cat['name'] === $category) {
                        $cat['sales'] += $price;
                        $cat['count']++;
                    }
                }
            }
        }

        // Ensure sales values are numeric for Chart.js
        $categories = array_map(function ($cat) {
            $cat['sales'] = (float) $cat['sales'];
            return $cat;
        }, $categories);

        // Recent orders for detailed table
        $recentOrders = Order::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->with(['orderItems.food', 'user'])
            ->orderByDesc('order_date')
            ->get();

        return view('admin.sales_report', compact(
            'totalSales',
            'totalOrders',
            'avgOrder',
            'popularItem',
            'popularCount',
            'weeklySales',
            'categories',
            'orders',
            'recentOrders'
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return redirect()->route('admin.sales_report.index', [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    }
}