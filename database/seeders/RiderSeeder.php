<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rider;

class RiderSeeder extends Seeder
{
    public function run()
    {
        // Create or find users for riders
        $riderUsers = [
            [
                'name' => 'Rider One',
                'email' => 'rider1@example.com',
                'password' => bcrypt('123'),
                'role' => 'rider',
            ],
            [
                'name' => 'Rider Two',
                'email' => 'rider2@example.com',
                'password' => bcrypt('123'),
                'role' => 'rider',
            ],
            [
                'name' => 'Rider Three',
                'email' => 'rider3@example.com',
                'password' => bcrypt('123'),
                'role' => 'rider',
            ],
            [
                'name' => 'Rider Four',
                'email' => 'rider4@example.com',
                'password' => bcrypt('123'),
                'role' => 'rider',
            ],
            [
                'name' => 'Rider Five',
                'email' => 'rider5@example.com',
                'password' => bcrypt('123'),
                'role' => 'rider',
            ],
        ];

        $users = [];
        foreach ($riderUsers as $userData) {
            $users[] = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role' => $userData['role'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Create riders linked to the users
        $riders = [
            [
                'user_id' => $users[0]->id,
                'phone_number' => '123-456-7890',
                'license_number' => 'LIC001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[1]->id,
                'phone_number' => '123-456-7891',
                'license_number' => 'LIC002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[2]->id,
                'phone_number' => '123-456-7892',
                'license_number' => 'LIC003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[3]->id,
                'phone_number' => '123-456-7893',
                'license_number' => 'LIC004',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[4]->id,
                'phone_number' => '123-456-7894',
                'license_number' => 'LIC005',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Rider::insert($riders);
        $this->command->info('Rider database seeded successfully!');
    }
}