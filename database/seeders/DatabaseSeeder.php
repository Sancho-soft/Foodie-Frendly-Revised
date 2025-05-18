<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users
        // User::firstOrCreate(
        //     ['email' => 'customer@gmail.com'],
        //     [
        //         'name' => 'Customer',
        //         'password' => Hash::make('123'),
        //         'role' => 'customer',
        //     ]
        // );
        
        // User::firstOrCreate(
        //     ['email' => 'gilgregenemantilla@gmail.com'],
        //     [
        //         'name' => 'Gilgre',
        //         'password' => Hash::make('123'),
        //         'role' => 'admin',
        //     ]
        // );
        
        // Seed Admin
        $this->call([
            AdminUserSeeder::class
        ]);
        
        // Seed User
        $this->call([
            UsersTableSeeder::class
        ]);

        // Seed sample food items
        $this->call([
            FoodSeeder::class
        ]);

         // Seed Rider
        $this->call([
            RiderSeeder::class
        ]);

        // Seed Orders
        $this->call([
            OrderTableSeeder::class
        ]);
        

    
        $this->command->info('Default database seeded successfully!');
    }
}
