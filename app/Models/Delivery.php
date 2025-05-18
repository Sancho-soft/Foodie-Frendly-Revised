<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the plural form of the model name
    protected $table = 'deliveries';

    // Define which attributes are mass assignable
    protected $fillable = [
        'order_id',
        'customer_name',
        'address',
        'status',
        'rider_id', // Assuming 'rider_id' is a foreign key linking to the user (rider)
    ];

    // Define a relationship to the User model (riders)
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
}
