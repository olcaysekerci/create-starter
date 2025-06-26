<?php

namespace Modules\User\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcı
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Normal kullanıcılar
        $users = [
            [
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ayşe Demir',
                'email' => 'ayse@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mehmet Kaya',
                'email' => 'mehmet@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Email doğrulanmamış
            ],
            [
                'name' => 'Fatma Özkan',
                'email' => 'fatma@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ali Çelik',
                'email' => 'ali@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Email doğrulanmamış
            ],
            [
                'name' => 'Zeynep Arslan',
                'email' => 'zeynep@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mustafa Doğan',
                'email' => 'mustafa@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Elif Şahin',
                'email' => 'elif@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => null, // Email doğrulanmamış
            ],
            [
                'name' => 'Emre Yıldız',
                'email' => 'emre@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Seda Avcı',
                'email' => 'seda@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ User seeder tamamlandı: 1 admin + 10 normal kullanıcı eklendi');
    }
} 