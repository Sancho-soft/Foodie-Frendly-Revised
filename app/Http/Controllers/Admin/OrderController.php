<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->paginate(10);
        return view('admin.order_menu', compact('orders'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $foods = Food::all();
        return view('admin.orders.create', compact('customers', 'foods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'delivery_address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:Cash on Delivery,GCash,PayMaya',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Fetch the delivery fee
        $deliveryFeeRecord = DB::table('delivery_fees')
            ->orderBy('date_added', 'desc')
            ->first();
        $deliveryFee = $deliveryFeeRecord ? $deliveryFeeRecord->fee : 50.00;

        // Calculate subtotal
        $subTotal = 0;
        foreach ($request->items as $item) {
            $food = Food::find($item['food_id']);
            if (!$food) {
                return redirect()->back()->with('error', 'Invalid food item selected.');
            }
            $subTotal += $food->price * $item['quantity'];
        }

        // Create the order with subtotal as total_amount
        $order = Order::create([
            'user_id' => $request->customer_id,
            'total_amount' => $subTotal, // Store subtotal initially
            'delivery_address' => $request->delivery_address,
            'payment_method' => $request->payment_method,
            'delivery_fee' => $deliveryFee,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_date' => now(),
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $food = Food::find($item['food_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'],
                'price' => $food->price,
            ]);
        }

        return redirect()->route('admin.order_menu')->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load('user', 'rider', 'orderItems.food');
        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        return redirect()->route('admin.order_menu')->with('success', 'Order cancelled successfully.');
    }

    public function startDelivery(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('admin.order_menu')->with('error', 'Only pending orders can be moved to delivering.');
        }
        $order->update(['status' => 'delivering']);
        return redirect()->route('admin.order_menu')->with('success', 'Order status updated to delivering.');
    }

    public function completeDelivery(Order $order)
    {
        if ($order->status !== 'delivering') {
            return redirect()->route('admin.order_menu')->with('error', 'Only delivering orders can be marked as delivered.');
        }
        $order->update(['status' => 'delivered']);
        return redirect()->route('admin.order_menu')->with('success', 'Order status updated to delivered.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,delivering,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);
        return redirect()->route('admin.order_menu')->with('success', 'Order status updated successfully.');
    }

    public function setDeliveryFee()
    {
        $currentDeliveryFee = DB::table('delivery_fees')
            ->orderBy('date_added', 'desc')
            ->first()->fee ?? 50.00;
        return view('admin.set_delivery_fee', compact('currentDeliveryFee'));
    }

    public function updateDeliveryFee(Request $request)
    {
        $request->validate([
            'delivery_fee' => 'required|numeric|min:0|max:999.99'
        ]);

        DB::table('delivery_fees')->insert([
            'fee' => $request->delivery_fee,
            'date_added' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.order_menu')->with('success', 'Delivery fee updated successfully!');
    }
}