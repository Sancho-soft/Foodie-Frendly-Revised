<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rider;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderTableSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Retrieve existing customers (users with role 'customer')
        $customers = User::where('role', 'customer')->get();

        if ($customers->isEmpty()) {
            $this->command->error('No customers found. Please seed the UsersTableSeeder first.');
            return;
        }

        // Step 2: Retrieve existing riders
        $riders = Rider::with('user')->get();

        if ($riders->isEmpty()) {
            $this->command->error('No riders found. Please seed the RiderSeeder first.');
            return;
        }

        // Step 3: Retrieve existing foods
        $foods = Food::all();

        if ($foods->isEmpty()) {
            $this->command->error('No foods found. Please seed the FoodSeeder first.');
            return;
        }

        // Step 4: Fetch the latest delivery fee
        $deliveryFee = DB::table('delivery_fees')
            ->orderBy('date_added', 'desc')
            ->first()->fee ?? 50.00;

        // Step 5: Define possible statuses, payment methods, and addresses
        $statuses = ['pending', 'delivering', 'delivered', 'cancelled'];
        $paymentMethods = ['Cash on Delivery', 'GCash', 'PayMaya'];
        $addresses = [
            '123 Main St, City A',
            '456 Oak St, City B',
            '789 Pine St, City C',
            '101 Maple St, City D',
            '202 Birch St, City E',
            '303 Cedar St, City F',
        ];

        // Step 6: Create 25 Orders
        for ($i = 0; $i < 25; $i++) {
            // Determine customer, rider, status, and payment method
            $customer = $customers[$i % $customers->count()];
            $rider = ($statuses[$i % 4] === 'pending' || $statuses[$i % 4] === 'cancelled') ? null : $riders[$i % $riders->count()];
            $status = $statuses[$i % 4];
            $paymentMethod = $paymentMethods[$i % 3];
            $orderDate = now()->subDays(rand(0, 10))->subHours(rand(0, 23));

            // Create the order
            $order = Order::create([
                'user_id' => $customer->id,
                'rider_id' => $rider ? $rider->id : null,
                'total_amount' => 0, // Will be updated to items' subtotal
                'delivery_fee' => $deliveryFee,
                'delivery_address' => $addresses[$i % count($addresses)],
                'status' => $status,
                'payment_status' => $status === 'delivered' ? 'completed' : 'pending',
                'payment_method' => $paymentMethod,
                'order_date' => $orderDate,
            ]);

            // Add 1 to 3 random food items to the order
            $numItems = rand(1, 3);
            $selectedFoodIds = $foods->shuffle()->take($numItems)->pluck('id')->toArray();
            $orderItems = [];

            foreach ($selectedFoodIds as $foodId) {
                $food = $foods->find($foodId);
                $quantity = rand(1, 3);
                $orderItems[] = [
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                    'price' => $food->price,
                ];
            }

            // Calculate items' subtotal and create order items
            $itemsTotal = 0;
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item['food_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                $itemsTotal += $item['price'] * $item['quantity'];
            }

            // Update total_amount to items' subtotal only
            $order->update(['total_amount' => $itemsTotal]);
        }

        $this->command->info('25 Orders and their Order Items seeded successfully with delivery fees!');
    }
}