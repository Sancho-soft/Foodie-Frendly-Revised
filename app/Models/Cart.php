<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'food_id', 
        'quantity',
        'size',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function getTotalPrice()
    {
        $basePrice = $this->food->price;
        $total = $basePrice;

        // Get food-specific options
        $foodOptions = $this->food->getSpecificOptions();

        // Add size price
        if ($this->size && isset($foodOptions['size_options'])) {
            foreach ($foodOptions['size_options'] as $option) {
                if (strtolower($option['name']) === strtolower($this->size)) {
                    $total += $option['price'];
                    break;
                }
            }
        }

        // Add options prices
        if ($this->options && is_array($this->options) && isset($foodOptions['add_ons'])) {
            foreach ($this->options as $selectedOption) {
                foreach ($foodOptions['add_ons'] as $option) {
                    if (strtolower($option['name']) === strtolower($selectedOption)) {
                        $total += $option['price'];
                        break;
                    }
                }
            }
        }

        // Multiply by quantity
        return $total * $this->quantity;
    }
}