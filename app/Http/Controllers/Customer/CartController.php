<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderReceipt;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $foods = Food::query();

        // Apply search if provided
        if (request()->has('q') && request()->input('q')) {
            $searchTerm = request()->input('q');
            $foods->where('name', 'like', "%$searchTerm%")
                  ->orWhere('description', 'like', "%$searchTerm%");
        }

        // Apply category filter if provided
        if (request()->has('category') && request()->input('category')) {
            $category = request()->input('category');
            $foods->where('category', $category);
        }

        $foods = $foods->get();
        $categories = Food::distinct()->pluck('category')->filter()->values(); // Get unique categories, remove nulls

        return view('customer.index', compact('foods', 'categories'));
    }

    public function viewCart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('food')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->food->price * $item->quantity;
        });
        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, $foodId)
    {
        $food = Food::findOrFail($foodId);
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('food_id', $foodId)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $validated['quantity']]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'food_id' => $foodId,
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()->route('home')->with('success', 'Food added to cart!');
    }

    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Cart updated!');
    }

    public function removeFromCart($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->delete();
        return redirect()->route('cart')->with('success', 'Item removed from cart!');
    }

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $cartItems = Cart::where('user_id', $user->id)->with('food')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Validate request
            $validated = $request->validate([
                'payment_method' => 'required|string|in:Cash on Delivery,GCash,PayMaya',
                'delivery_address' => 'required|string|max:255',
            ]);

            // Validate cart items are still available
            foreach ($cartItems as $item) {
                if (!$item->food) {
                    return redirect()->back()->with('error', 'Some items in your cart are no longer available.');
                }
            }

            // Calculate total
            $subtotal = $cartItems->sum(function ($item) {
                return $item->food->price * $item->quantity;
            });

            // Get delivery fee from settings
            $deliveryFee = DB::table('delivery_fees')
                ->orderBy('date_added', 'desc')
                ->first()->fee ?? 50.00;

            // Fixed tax fee
            $taxFee = 20.00;

            // Calculate total with delivery fee and tax
            $total = $subtotal + $deliveryFee + $taxFee;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'delivery_address' => $validated['delivery_address'],
                'payment_method' => $validated['payment_method'],
                'delivery_fee' => $deliveryFee,
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_date' => now(),
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item->food_id,
                    'quantity' => $item->quantity,
                    'price' => $item->food->price,
                ]);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            try {
                // Send receipt email
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {
                // Log email error but don't rollback transaction
                \Log::error('Failed to send order receipt email: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('order.view', $order->id)
                ->with('success', 'Order placed successfully! Check your email for the receipt.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order placement failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.food', 'rider.user')
            ->latest()
            ->paginate(10);
        return view('customer.order-history', compact('orders'));
    }

    public function viewOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('orderItems.food', 'rider.user')
            ->firstOrFail();
        return view('customer.order-details', compact('order'));
    }

    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->route('order.view', $order->id)
                ->with('error', 'Order cannot be canceled because it is already ' . ucfirst($order->status) . '.');
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('order.view', $order->id)
            ->with('success', 'Order canceled successfully!');
    }

    public function getOrderStatus($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('rider.user')
            ->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'rider' => $order->rider ? [
                'name' => $order->rider->user->name ?? 'Not assigned',
                'phone' => $order->rider->phone_number ?? 'N/A',
            ] : null,
        ]);
    }
}