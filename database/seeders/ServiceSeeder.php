<?php

namespace Database\Seeders;

use App\Models\AdditionalService;
use App\Models\Services;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            DB::beginTransaction();

            try {
                // Create a sample service
                $service = Services::create([
                    'user_id' => 3,
                    'category_id' => 1,
                    'service_name' => 'Premium Haircut',
                    'service_details' => 'A professional haircut and styling service.',
                    'price' => 25.00,
                    'service_images' => json_encode(['haircut1.jpg', 'haircut2.jpg']),
                    'duration' => '45 minutes',
                    'location' => 'Dhaka, Bangladesh'
                ]);

                // Create additional services for the above service
                $additionalServices = [
                    [
                        'service_id' => $service->id,
                        'name' => 'Beard Trim',
                        'price' => 8.00,
                        'details' => 'Shaping and styling the beard.',
                        'images' => json_encode(['beardtrim1.jpg'])
                    ],
                    [
                        'service_id' => $service->id,
                        'name' => 'Hair Wash',
                        'price' => 5.00,
                        'details' => 'Shampoo and conditioning.',
                        'images' => json_encode(['hairwash1.jpg'])
                    ]
                ];



                foreach ($additionalServices as $serviceData) {
                    AdditionalService::create($serviceData);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                echo "Error seeding data: " . $e->getMessage();
            }

    }
}
