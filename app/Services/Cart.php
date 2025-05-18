<?php

namespace App\Services;

use App\Models\Food;

class Cart
{
    /**
     * Add a food item to the cart.
     *
     * @param int $foodId
     * @param int $quantity
     * @return void
     */
    public static function add($foodId, $quantity = 1)
    {
        $cart = session()->get('cart', []);
        $food = Food::findOrFail($foodId);

        if (isset($cart[$foodId])) {
            $cart[$foodId]['quantity'] += $quantity;
        } else {
            $cart[$foodId] = [
                'name' => $food->name,
                'quantity' => $quantity,
                'price' => $food->price,
                'image' => $food->image,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update the quantity of a food item in the cart.
     *
     * @param int $foodId
     * @param int $quantity
     * @return void
     */
    public static function update($foodId, $quantity)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$foodId])) {
            if ($quantity <= 0) {
                unset($cart[$foodId]);
            } else {
                $cart[$foodId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }
    }

    /**
     * Remove a food item from the cart.
     *
     * @param int $foodId
     * @return void
     */
    public static function remove($foodId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$foodId])) {
            unset($cart[$foodId]);
            session()->put('cart', $cart);
        }
    }

    /**
     * Get the current cart contents.
     *
     * @return array
     */
    public static function get()
    {
        return session()->get('cart', []);
    }

    /**
     * Clear the entire cart.
     *
     * @return void
     */
    public static function clear()
    {
        session()->forget('cart');
    }

    /**
     * Calculate the total cost of items in the cart.
     *
     * @return float
     */
    public static function total()
    {
        $cart = self::get();
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    }
}