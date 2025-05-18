<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'license_number',
        'fare_per_km',
        'tax_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateFare($distance)
    {
        $baseFare = $this->fare_per_km * $distance;
        $tax = ($baseFare * $this->tax_rate) / 100;
        return [
            'base_fare' => $baseFare,
            'tax' => $tax,
            'total_fare' => $baseFare + $tax
        ];
    }
}