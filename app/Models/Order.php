<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rider_id',
        'total_amount',
        'delivery_address',
        'payment_method',
        'delivery_fee',
        'status',
        'payment_status',
        'order_date',
        'created_at',
        'updated_at',
    ];

    // Cast order_date to a Carbon instance
    protected $casts = [
        'order_date' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}