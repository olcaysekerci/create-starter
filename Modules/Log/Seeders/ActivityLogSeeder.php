<?php

namespace Modules\Log\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Log\Models\Activity;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        // Önce mevcut test loglarını temizle
        Activity::truncate();
        
        // Test kullanıcıları al
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Kullanıcı bulunamadı. Önce UserSeeder çalıştırın.');
            return;
        }

        $admin = $users->first(); // İlk kullanıcı admin olsun
        
        // 1. Kullanıcı işlemleri (CRUD)
        foreach ($users->skip(1)->take(3) as $targetUser) {
            // Kullanıcı oluşturma
            activity()
                ->causedBy($admin)
                ->performedOn($targetUser)
                ->event('created')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                    'attributes' => [
                        'name' => $targetUser->name,
                        'email' => $targetUser->email,
                    ]
                ])
                ->log('Yeni kullanıcı oluşturdu');

            // Kullanıcı güncelleme
            activity()
                ->causedBy($admin)
                ->performedOn($targetUser)
                ->event('updated')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                    'old' => ['name' => 'Eski İsim'],
                    'attributes' => ['name' => $targetUser->name]
                ])
                ->log('Kullanıcı bilgilerini güncelledi');
        }

        // 2. Kendi profil işlemleri
        foreach ($users->take(4) as $user) {
            // Profil güncelleme
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->event('updated')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                ])
                ->log('Profil bilgilerini güncelledi');

            // Şifre değiştirme
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->event('password_changed')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                ])
                ->log('Şifresini değiştirdi');
        }

        // 3. Giriş/Çıkış logları
        foreach ($users as $user) {
            // Giriş
            activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                ])
                ->log('Sisteme giriş yaptı');

            // Çıkış
            activity()
                ->causedBy($user)
                ->event('logout')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                ])
                ->log('Sistemden çıkış yaptı');
        }

        // 4. Admin işlemleri
        $adminActions = [
            ['event' => 'system_backup', 'description' => 'Sistem yedeği aldı'],
            ['event' => 'cache_cleared', 'description' => 'Önbelleği temizledi'],
            ['event' => 'settings_updated', 'description' => 'Sistem ayarlarını güncelledi'],
            ['event' => 'maintenance_mode', 'description' => 'Bakım modunu etkinleştirdi'],
            ['event' => 'database_optimized', 'description' => 'Veritabanını optimize etti'],
        ];

        foreach ($adminActions as $action) {
            activity()
                ->causedBy($admin)
                ->event($action['event'])
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                ])
                ->log($action['description']);
        }

        // 5. Sistem logları (causer yok)
        $systemEvents = [
            ['event' => 'system_started', 'description' => 'Sistem başlatıldı'],
            ['event' => 'scheduled_backup', 'description' => 'Otomatik yedekleme tamamlandı'],
            ['event' => 'security_scan', 'description' => 'Güvenlik taraması yapıldı'],
            ['event' => 'error_occurred', 'description' => 'Sistem hatası oluştu'],
        ];

        foreach ($systemEvents as $event) {
            activity()
                ->event($event['event'])
                ->withProperties([
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'System/1.0',
                ])
                ->log($event['description']);
        }

        // 6. Geçmiş tarihli loglar
        for ($i = 1; $i <= 20; $i++) {
            $randomUser = $users->random();
            $randomDate = now()->subDays(rand(1, 30))->subHours(rand(0, 23));
            
            $log = activity()
                ->causedBy($randomUser)
                ->event('page_viewed')
                ->withProperties([
                    'ip_address' => $this->getRandomIp(),
                    'user_agent' => $this->getRandomUserAgent(),
                    'url' => '/admin/dashboard',
                ])
                ->log('Dashboard sayfasını görüntüledi');
                
            // Tarihi manuel olarak güncelle
            $log->created_at = $randomDate;
            $log->save();
        }

        $this->command->info('✅ ' . Activity::count() . ' adet anlamlı activity log oluşturuldu!');
    }

    private function getRandomIp(): string
    {
        $ips = [
            '192.168.1.1',
            '192.168.1.100', 
            '10.0.0.1',
            '172.16.0.1',
            '203.0.113.1',
            '198.51.100.1',
            '185.199.108.153',
            '151.101.1.140',
        ];

        return $ips[array_rand($ips)];
    }

    private function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        ];

        return $userAgents[array_rand($userAgents)];
    }
} 