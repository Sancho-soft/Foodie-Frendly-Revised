<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFareAndTaxToRiders extends Migration
{
    public function up()
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->decimal('fare_per_km', 8, 2)->default(20.00); // Default fare of ₱20 per km
            $table->decimal('tax_rate', 5, 2)->default(12.00); // Default 12% tax rate
        });
    }

    public function down()
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropColumn(['fare_per_km', 'tax_rate']);
        });
    }
} 