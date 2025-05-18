<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FoodSeeder extends Seeder
{
    public function run()
    {
        // Define the source and destination directories
        $sourceDir = public_path('image'); // Source where your images are currently located
        $destinationDir = storage_path('app/public/food_images');

        // Create the destination directory if it doesn't exist
        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
            $this->command->info("Created directory: {$destinationDir}");
        }

        // Define the food data with updated image paths
        $foods = [
            [
                'name' => 'Pepperoni Pizza',
                'description' => 'A classic pizza topped with pepperoni, mozzarella, and tomato sauce.',
                'price' => 15.99,
                'category' => 'Pizza',
                'image' => 'food_images/pepperoni_pizza.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cheese Pizza',
                'description' => 'Simple pizza with a generous layer of melted mozzarella cheese.',
                'price' => 12.50,
                'category' => 'Pizza',
                'image' => 'food_images/cheese_pizza.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pizza',
                'description' => 'A classic pizza with assorted toppings, mozzarella, and tomato sauce.',
                'price' => 14.00,
                'category' => 'Pizza',
                'image' => 'food_images/pizza.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Classic Burger',
                'description' => 'Juicy beef patty with lettuce, tomato, and a sesame seed bun.',
                'price' => 8.99,
                'category' => 'Burger',
                'image' => 'food_images/classic_burger.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chicken Burger',
                'description' => 'Grilled chicken breast with mayo, lettuce, and pickles.',
                'price' => 9.50,
                'category' => 'Burger',
                'image' => 'food_images/chicken_burger.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cola Drink',
                'description' => 'Refreshing cola served ice-cold.',
                'price' => 2.50,
                'category' => 'Drink',
                'image' => 'food_images/cola_drink.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beef Wellington',
                'description' => 'Tender beef tenderloin wrapped in pastry with mushrooms and shallots.',
                'price' => 29.99,
                'category' => 'Main Course',
                'image' => 'food_images/beef_wellington.avif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carbonara',
                'description' => 'Creamy pasta with pancetta, parmesan, and a rich egg-based sauce.',
                'price' => 13.50,
                'category' => 'Pasta',
                'image' => 'food_images/carbonara.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fried Chicken',
                'description' => 'Crispy fried chicken with a savory breading, served with dipping sauce.',
                'price' => 10.99,
                'category' => 'Main Course',
                'image' => 'food_images/fried_chicken.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sticky Toffee Pudding',
                'description' => 'Warm sponge cake made with dates, served with a rich toffee sauce.',
                'price' => 6.99,
                'category' => 'Dessert',
                'image' => 'food_images/sticky-toffee-pudding.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Copy each image from public/image to storage/app/public/food_images
        foreach ($foods as $food) {
            $imagePath = $food['image']; // e.g., food_images/pepperoni_pizza.jpg
            $filename = basename($imagePath); // e.g., pepperoni_pizza.jpg
            $sourceFile = $sourceDir . '/' . $filename;
            $destinationFile = $destinationDir . '/' . $filename;

            // Check if the source image exists and copy it to the destination
            if (File::exists($sourceFile)) {
                if (!File::exists($destinationFile)) {
                    File::copy($sourceFile, $destinationFile);
                    $this->command->info("Copied image: {$filename} to storage/app/public/food_images");
                } else {
                    $this->command->info("Image already exists: {$filename} in storage/app/public/food_images");
                }
            } else {
                $this->command->warn("Image not found in public/image: {$filename}");
            }
        }

        // Insert the food data into the database
        Food::insert($foods);
        $this->command->info('Food database seeded successfully!');
    }
}