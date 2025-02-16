<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'category_id' => 1, // Ensure this category exists in 'categories' table
                'name' => 'Basic Cleaning',
                'description' => 'A basic home cleaning service',
                'price' => 50.00,
                'image' => json_encode(['image1.jpg', 'image2.jpg']),
                'duration' => '2 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Premium Cleaning',
                'description' => 'A deep cleaning service with special treatments',
                'price' => 100.00,
                'image' => json_encode(['premium1.jpg', 'premium2.jpg']),
                'duration' => '4 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Lawn Mowing',
                'description' => 'Professional lawn mowing service',
                'price' => 75.00,
                'image' => json_encode(['lawn1.jpg']),
                'duration' => '3 hours',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
