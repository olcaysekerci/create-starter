<?php

namespace Modules\MailNotification\Seeders;

use Illuminate\Database\Seeder;
use Modules\MailNotification\Models\MailLog;
use Modules\MailNotification\Enums\MailStatus;

class MailLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mailTypes = ['welcome', 'reset', 'notification', 'test', 'alert'];
        $statuses = [MailStatus::SENT, MailStatus::FAILED, MailStatus::PENDING];
        $subjects = [
            'Hoş Geldiniz',
            'Şifre Sıfırlama',
            'Sistem Bildirimi',
            'Test Mail',
            'Güvenlik Uyarısı',
            'Hesap Doğrulama',
            'Yeni Mesaj',
            'Sistem Güncellemesi'
        ];

        $contents = [
            'Merhaba, sisteme hoş geldiniz! Hesabınız başarıyla oluşturuldu.',
            'Şifrenizi sıfırlamak için aşağıdaki linke tıklayın.',
            'Sistemde yeni bir güncelleme yapıldı. Lütfen kontrol edin.',
            'Bu bir test mailidir. Mail sistemi çalışıyor.',
            'Hesabınızda şüpheli aktivite tespit edildi.',
            'Email adresinizi doğrulamak için linke tıklayın.',
            'Yeni bir mesajınız var.',
            'Sistem bakımı planlanmıştır.'
        ];

        $recipients = [
            'admin@example.com',
            'user1@example.com',
            'user2@example.com',
            'test@example.com',
            'info@example.com',
            'support@example.com',
            'noreply@example.com',
            'contact@example.com'
        ];

        for ($i = 0; $i < 25; $i++) {
            $status = $statuses[array_rand($statuses)];
            $type = $mailTypes[array_rand($mailTypes)];
            $subject = $subjects[array_rand($subjects)];
            $content = $contents[array_rand($contents)];
            $recipient = $recipients[array_rand($recipients)];
            
            $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $sentAt = $status === MailStatus::SENT ? $createdAt->copy()->addMinutes(rand(1, 5)) : null;

            MailLog::create([
                'recipient' => $recipient,
                'subject' => $subject,
                'content' => $content . "\n\nGönderim ID: " . ($i + 1),
                'type' => $type,
                'status' => $status,
                'sent_at' => $sentAt,
                'error_message' => $status === MailStatus::FAILED ? 'SMTP bağlantı hatası' : null,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'user_id' => rand(1, 10),
                    'session_id' => 'session_' . rand(1000, 9999),
                    'request_id' => 'req_' . rand(10000, 99999),
                ],
                'retry_count' => $status === MailStatus::FAILED ? rand(1, 3) : 0,
                'last_retry_at' => $status === MailStatus::FAILED ? $createdAt->copy()->addHours(rand(1, 24)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Özel test mailleri
        MailLog::create([
            'recipient' => 'test@example.com',
            'subject' => 'Mail Sistemi Test',
            'content' => "Bu mail sistemi test amaçlı gönderilmiştir.\n\nGönderim Tarihi: " . now()->format('d.m.Y H:i:s') . "\nTest ID: TEST_001",
            'type' => 'test',
            'status' => MailStatus::SENT,
            'sent_at' => now()->subMinutes(5),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => ['test' => true, 'source' => 'seeder'],
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(5),
        ]);

        MailLog::create([
            'recipient' => 'admin@example.com',
            'subject' => 'Sistem Bildirimi',
            'content' => "Mail notification modülü başarıyla kuruldu ve çalışıyor.\n\nModül: MailNotification\nVersiyon: 1.0.0\nDurum: Aktif",
            'type' => 'notification',
            'status' => MailStatus::SENT,
            'sent_at' => now()->subMinutes(2),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => ['module' => 'MailNotification', 'version' => '1.0.0'],
            'created_at' => now()->subMinutes(3),
            'updated_at' => now()->subMinutes(2),
        ]);
    }
} 