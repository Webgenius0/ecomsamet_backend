<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiuserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('api_users')->insert([
            [
                'fullname' => 'John Doe',
                'phone' => '1234567890', // Make sure this is unique
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'), // Always hash passwords
                'image' => 'profile.jpg', // You can use a default image or leave it null
                'provider_id' => null, // If using a provider (like Google/Facebook), you can add it here
                'mobile_verfi_otp' => '123456', // A sample OTP for mobile verification
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fullname' => 'Jane Smith',
                'phone' => '0987654321', // Ensure this phone number is unique
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password456'),
                'image' => 'profile2.jpg',
                'provider_id' => null,
                'mobile_verfi_otp' => '654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fullname' => 'Alex Johnson',
                'phone' => '1122334455',
                'email' => 'alex.johnson@example.com',
                'password' => Hash::make('password789'),
                'image' => null, // You can leave the image as null
                'provider_id' => 'google1234', // Example provider ID
                'mobile_verfi_otp' => '789012',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
