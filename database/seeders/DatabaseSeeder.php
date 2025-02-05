<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ApiuserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([UserSeeder::class, ]);
        // $this->call([ApiuserSeeder::class, ]);
        $this->call([CategorySeeder::class,]);
        $this->call([ServiceSeeder::class,]);

    }
}
