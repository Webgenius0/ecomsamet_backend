<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Basic Cleaning',
                'description' => 'A basic home cleaning service for general cleaning tasks like dusting and vacuuming.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Cleaning',
                'description' => 'A deep cleaning service with special treatments, such as sanitizing and premium surface care.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lawn Mowing',
                'description' => 'Professional lawn mowing service for residential and commercial properties.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
