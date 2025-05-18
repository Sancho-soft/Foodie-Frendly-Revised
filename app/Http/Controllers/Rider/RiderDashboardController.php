<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiderDashboardController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $rider = Auth::user()->rider;

        // Current Order
        $currentOrder = Order::where('rider_id', $rider->id)
                             ->where('status', 'delivering')
                             ->with('orderItems.food')
                             ->first();

        // Total Deliveries
        $totalDeliveries = Order::where('rider_id', $rider->id)
                                ->where('status', 'delivered')
                                ->count();

        // Total Earnings
        $deliveredOrders = Order::where('rider_id', $rider->id)
                                ->where('status', 'delivered')
                                ->get();
        $totalEarnings = $deliveredOrders->sum('delivery_fee'); // Sum delivery_fee for all orders

        // Calculate total for current order
        $currentOrderTotal = $currentOrder ? $this->calculateOrderTotal($currentOrder) : 0;

        return view('rider.index', compact('currentOrder', 'totalDeliveries', 'totalEarnings', 'currentOrderTotal'));
    }

    protected function calculateOrderTotal($order)
    {
        $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
        $deliveryFee = $order->delivery_fee ?? (DB::table('delivery_fees')->orderBy('date_added', 'desc')->first()->fee ?? 50.00);
        return $subtotal + $deliveryFee;
    }

    public function orders()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $rider = Auth::user()->rider;
        $currentOrder = Order::where('rider_id', $rider->id)
                             ->where('status', 'delivering')
                             ->with('orderItems.food')
                             ->first();

        $pendingOrders = Order::where('status', 'pending')
                              ->whereNull('rider_id')
                              ->with('orderItems.food')
                              ->get();

        // Calculate totals for pending orders
        $pendingOrderTotals = $pendingOrders->mapWithKeys(function ($order) {
            return [$order->id => $this->calculateOrderTotal($order)];
        })->toArray();

        $currentOrderTotal = $currentOrder ? $this->calculateOrderTotal($currentOrder) : 0;

        return view('rider.orders', compact('pendingOrders', 'currentOrder', 'pendingOrderTotals', 'currentOrderTotal'));
    }

    public function myDeliveries()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $rider = Auth::user()->rider;
        $deliveredOrders = Order::where('rider_id', $rider->id)
                                ->whereIn('status', ['delivered', 'cancelled'])
                                ->orderBy('updated_at', 'desc')
                                ->with('orderItems.food')
                                ->get();

        return view('rider.my-deliveries', compact('deliveredOrders'));
    }

    public function earnings()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        
        $rider = Auth::user()->rider;
        $deliveredOrders = Order::where('rider_id', $rider->id)
                                ->where('status', 'delivered')
                                ->with('orderItems.food')
                                ->get();

        $earnings = [];
        $totalBaseFare = 0;
        $totalTax = 0;
        $totalEarnings = 0;

        foreach ($deliveredOrders as $order) {
            // For this example, we'll use a fixed distance of 5km per delivery
            // In a real application, you would calculate the actual distance
            $distance = 5; // 5 kilometers per delivery
            
            $fareCalculation = $rider->calculateFare($distance);
            
            $earnings[] = [
                'order_id' => $order->id,
                'date' => $order->updated_at,
                'distance' => $distance,
                'base_fare' => $fareCalculation['base_fare'],
                'tax' => $fareCalculation['tax'],
                'total_fare' => $fareCalculation['total_fare']
            ];

            $totalBaseFare += $fareCalculation['base_fare'];
            $totalTax += $fareCalculation['tax'];
            $totalEarnings += $fareCalculation['total_fare'];
        }

        $summary = [
            'total_deliveries' => count($deliveredOrders),
            'total_distance' => count($deliveredOrders) * 5, // Total distance in km
            'total_base_fare' => $totalBaseFare,
            'total_tax' => $totalTax,
            'total_earnings' => $totalEarnings,
            'fare_per_km' => $rider->fare_per_km,
            'tax_rate' => $rider->tax_rate
        ];

        return view('rider.earnings', compact('earnings', 'summary'));
    }

    public function showProfile()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();
        return view('rider.profile-show', compact('user'));
    }

    public function editProfile()
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();
        return view('rider.profile', compact('user'));
    }

    public function startDelivery(Request $request, Order $order)
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        if ($order->status !== 'pending' || $order->rider_id) {
            return redirect()->route('rider.orders')->with('error', 'This order is already taken or not available.');
        }

        $rider = Auth::user()->rider;

        $currentOrder = Order::where('rider_id', $rider->id)
                            ->where('status', 'delivering')
                            ->first();

        if ($currentOrder) {
            return redirect()->route('rider.orders')->with('error', 'You are already handling an order. Please complete it first.');
        }

        $order->update([
            'rider_id' => $rider->id,
            'status' => 'delivering',
        ]);

        return redirect()->route('rider.orders')->with('success', 'Order delivery started successfully!');
    }

    public function finishDelivery(Request $request, Order $order)
    {
        if (Auth::check() && Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized action.');
        }
        if ($order->status !== 'delivering' || $order->rider_id !== Auth::user()->rider->id) {
            return redirect()->route('rider.index')->with('error', 'You cannot finish this order.');
        }

        $order->update([
            'status' => 'delivered',
        ]);

        return redirect()->route('rider.index')->with('success', 'Order delivered successfully!');
    }
}