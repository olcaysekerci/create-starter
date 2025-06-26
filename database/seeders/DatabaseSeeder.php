<?php

namespace Database\Seeders;

use App\Models\User;
use Modules\User\Seeders\UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // User modülü seeder'ını çalıştır
        $this->call([
            UserSeeder::class,
            \Modules\Log\Seeders\ActivityLogSeeder::class,
        ]);
    }
}
