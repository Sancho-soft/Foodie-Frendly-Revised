<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Customer One',
                'email' => 'customer1@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639123456789',
            ],
            [
                'name' => 'Customer Two',
                'email' => 'customer2@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639234567890',
            ],
            [
                'name' => 'Customer Three',
                'email' => 'customer3@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639345678901',
            ],
            [
                'name' => 'Customer Four',
                'email' => 'customer4@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639456789012',
            ],
            [
                'name' => 'Customer Five',
                'email' => 'customer5@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639567890123',
            ],
            [
                'name' => 'Customer Six',
                'email' => 'customer6@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'phone' => '+639678901234',
            ],
        ];

        foreach ($customers as $customer) {
            User::firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => $customer['password'],
                    'role' => $customer['role'],
                    'phone' => $customer['phone'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('User seeded successfully!');
    }
}