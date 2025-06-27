<?php

namespace Modules\Settings\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsService
{
    /**
     * Mail ayarlarını güncelle
     */
    public function updateMailSettings(array $settings): bool
    {
        try {
            // .env dosyasını güncelle
            $this->updateEnvFile([
                'MAIL_MAILER' => $settings['driver'] ?? 'smtp',
                'MAIL_HOST' => $settings['host'] ?? 'mailpit',
                'MAIL_PORT' => $settings['port'] ?? 1025,
                'MAIL_USERNAME' => $settings['username'] ?? null,
                'MAIL_PASSWORD' => $settings['password'] ?? null,
                'MAIL_ENCRYPTION' => $settings['encryption'] ?? null,
                'MAIL_FROM_ADDRESS' => $settings['from_address'] ?? 'hello@example.com',
                'MAIL_FROM_NAME' => $settings['from_name'] ?? 'Application',
            ]);

            // Config cache'ini temizle
            Artisan::call('config:clear');
            Artisan::call('config:cache');

            Log::info('Mail ayarları güncellendi', [
                'host' => $settings['host'] ?? 'mailpit',
                'port' => $settings['port'] ?? 1025,
                'from_address' => $settings['from_address'] ?? 'hello@example.com',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Mail ayarları güncellenirken hata oluştu', [
                'error' => $e->getMessage(),
                'settings' => $settings
            ]);
            return false;
        }
    }

    /**
     * Mail ayarlarını test et
     */
    public function testMailSettings(string $to): bool
    {
        try {
            Mail::raw('Bu bir test mailidir. Mail ayarlarınız başarıyla çalışıyor!', function ($message) use ($to) {
                $message->to($to)
                        ->subject('Mail Ayarları Test - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Mail test başarılı', ['to' => $to]);
            return true;
        } catch (\Exception $e) {
            Log::error('Mail test başarısız', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }

    /**
     * Mevcut mail ayarlarını al
     */
    public function getMailSettings(): array
    {
        return [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];
    }

    /**
     * Uygulama ayarlarını güncelle
     */
    public function updateAppSettings(array $settings): bool
    {
        try {
            $this->updateEnvFile([
                'APP_NAME' => $settings['name'] ?? 'Laravel',
                'APP_URL' => $settings['url'] ?? 'http://localhost',
                'APP_TIMEZONE' => $settings['timezone'] ?? 'UTC',
                'APP_LOCALE' => $settings['locale'] ?? 'en',
            ]);

            Artisan::call('config:clear');
            Artisan::call('config:cache');

            Log::info('Uygulama ayarları güncellendi', $settings);
            return true;
        } catch (\Exception $e) {
            Log::error('Uygulama ayarları güncellenirken hata oluştu', [
                'error' => $e->getMessage(),
                'settings' => $settings
            ]);
            return false;
        }
    }

    /**
     * Mevcut uygulama ayarlarını al
     */
    public function getAppSettings(): array
    {
        return [
            'name' => config('app.name'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];
    }

    /**
     * Sistem ayarlarını güncelle
     */
    public function updateSystemSettings(array $settings): bool
    {
        try {
            $this->updateEnvFile([
                'APP_DEBUG' => $settings['debug_mode'] ?? false,
                'LOG_LEVEL' => $settings['log_level'] ?? 'debug',
            ]);

            Artisan::call('config:clear');
            Artisan::call('config:cache');

            Log::info('Sistem ayarları güncellendi', $settings);
            return true;
        } catch (\Exception $e) {
            Log::error('Sistem ayarları güncellenirken hata oluştu', [
                'error' => $e->getMessage(),
                'settings' => $settings
            ]);
            return false;
        }
    }

    /**
     * Mevcut sistem ayarlarını al
     */
    public function getSystemSettings(): array
    {
        return [
            'debug_mode' => config('app.debug'),
            'log_level' => config('logging.default'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];
    }

    /**
     * .env dosyasını güncelle
     */
    private function updateEnvFile(array $variables): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            throw new \Exception('.env dosyası bulunamadı');
        }

        $envContent = file_get_contents($envPath);

        foreach ($variables as $key => $value) {
            // Boolean değerleri string'e çevir
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            // Null değerleri boş string yap
            if ($value === null) {
                $value = '';
            }

            // Mevcut değeri güncelle veya yeni ekle
            if (strpos($envContent, $key . '=') !== false) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    /**
     * Tüm ayarları al
     */
    public function getAllSettings(): array
    {
        return [
            'mail' => $this->getMailSettings(),
            'app' => $this->getAppSettings(),
            'system' => $this->getSystemSettings(),
        ];
    }

    /**
     * Ayar kategorilerini al
     */
    public function getSettingCategories(): array
    {
        return [
            'mail' => [
                'name' => 'Mail Ayarları',
                'description' => 'E-posta gönderim ayarları',
                'icon' => 'mail',
                'color' => 'blue',
            ],
            'app' => [
                'name' => 'Uygulama Ayarları',
                'description' => 'Genel uygulama ayarları',
                'icon' => 'app',
                'color' => 'green',
            ],
            'system' => [
                'name' => 'Sistem Ayarları',
                'description' => 'Sistem seviyesi ayarlar',
                'icon' => 'system',
                'color' => 'purple',
            ],
        ];
    }
} 