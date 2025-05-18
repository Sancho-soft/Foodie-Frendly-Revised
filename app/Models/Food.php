<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = ['name', 'description', 'price', 'category', 'image', 'options'];

    protected $casts = [
        'options' => 'array'
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getBasePrice()
    {
        return $this->price;
    }

    public function getSpecificOptions()
    {
        // Define specific options based on food name or category
        $options = [
            // Burgers
            'Classic Burger' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Double Patty', 'price' => 60]
                ],
                'add_ons' => [
                    ['name' => 'Extra Patty', 'price' => 45]
                ]
            ],
            'Chicken Burger' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Double Fillet', 'price' => 55]
                ],
                'add_ons' => [
                    ['name' => 'Extra Patty', 'price' => 40]
                ]
            ],
            // Rice Meals
            'Beef Wellington' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Large', 'price' => 50]
                ],
                'add_ons' => [
                    ['name' => 'Extra Gravy', 'price' => 20],
                    ['name' => 'Garlic Rice', 'price' => 25],
                    ['name' => 'Mixed Vegetables', 'price' => 30],
                    ['name' => 'Mashed Potatoes', 'price' => 35]
                ]
            ],
            'Fried Chicken' => [
                'size_options' => [
                    ['name' => '1 Piece', 'price' => 0],
                    ['name' => '2 Pieces', 'price' => 85],
                    ['name' => '3 Pieces', 'price' => 170]
                ],
                'add_ons' => [
                    ['name' => 'Gravy', 'price' => 15],
                    ['name' => 'Coleslaw', 'price' => 25],
                    ['name' => 'Corn & Carrots', 'price' => 30]
                ]
            ],
            'Carbonara' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Family Size', 'price' => 80]
                ],
                'add_ons' => [
                    ['name' => 'Extra Cheese', 'price' => 25],
                    ['name' => 'Extra Bacon', 'price' => 35],
                    ['name' => 'Mushrooms', 'price' => 30],
                    ['name' => 'Garlic Bread', 'price' => 40]
                ]
            ],
            // Drinks
            'Cola Drink' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Large', 'price' => 15],
                    ['name' => 'Extra Large', 'price' => 25]
                ],
                'add_ons' => [
                    ['name' => 'Extra Ice', 'price' => 0]
                ]
            ],
            'Milk Tea' => [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Large', 'price' => 20],
                    ['name' => 'Extra Large', 'price' => 35]
                ],
                'add_ons' => [
                    ['name' => 'Pearls', 'price' => 15],
                    ['name' => 'Nata', 'price' => 15],
                    ['name' => 'Pudding', 'price' => 20],
                    ['name' => 'Crystal Jelly', 'price' => 15],
                    ['name' => 'Extra Cream', 'price' => 10]
                ]
            ]
        ];

        // Return default options if specific options not found
        if (!isset($options[$this->name])) {
            return [
                'size_options' => [
                    ['name' => 'Regular', 'price' => 0],
                    ['name' => 'Large', 'price' => 40]
                ],
                'add_ons' => [
                    ['name' => 'Extra Serving', 'price' => 35],
                    ['name' => 'Special Request', 'price' => 20]
                ]
            ];
        }

        return $options[$this->name];
    }
}