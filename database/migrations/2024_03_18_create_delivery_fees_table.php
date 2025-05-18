<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_fees', function (Blueprint $table) {
            $table->id();
            $table->decimal('fee', 8, 2);
            $table->timestamp('date_added')->useCurrent();
            $table->timestamps();
        });

        // Insert default delivery fee
        DB::table('delivery_fees')->insert([
            'fee' => 50.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('delivery_fees');
    }
}; 