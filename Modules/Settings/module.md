# Settings Modülü

## Amaç

Settings modülü, sistem genelindeki tüm ayarları merkezi olarak yönetmek için geliştirilmiştir:
- **Mail Ayarları**: SMTP, gönderici bilgileri, mail konfigürasyonu
- **Uygulama Ayarları**: Site adı, URL, zaman dilimi, dil
- **Sistem Ayarları**: Bakım modu, debug modu, log seviyesi
- **Genişletilebilir Yapı**: Gelecekte yeni ayar kategorileri eklenebilir

---

## Özellikler

### ✅ Tamamlanan Özellikler
- [x] Mail ayarları yönetimi sayfası
- [x] Ayarları kaydetme ve güncelleme
- [x] Test mail gönderimi
- [x] Ayar kategorileri (Mail, App, System)
- [x] Responsive tasarım
- [x] Dark mode desteği
- [x] Form validation

### 🔄 Geliştirilmekte
- [ ] Uygulama ayarları sayfası
- [ ] Sistem ayarları sayfası
- [ ] Ayar geçmişi (audit log)
- [ ] Ayar import/export
- [ ] Çoklu ortam desteği

---

## Route Yapısı

### Admin Routes (`/admin/settings/*`)
| Method | URI                    | Controller                    | Action   | Açıklama                 |
|--------|------------------------|-------------------------------|----------|--------------------------|
| GET    | /admin/settings        | Admin\SettingsController      | index    | Ayarlar ana sayfası      |
| GET    | /admin/settings/mail   | Admin\SettingsController      | mail     | Mail ayarları sayfası    |
| POST   | /admin/settings/mail   | Admin\SettingsController      | updateMail| Mail ayarlarını güncelle |
| POST   | /admin/settings/test-mail| Admin\SettingsController   | testMail | Test mail gönder         |

---

## Frontend Yapısı

### Admin Sayfaları
```
Resources/Views/Admin/
├── Pages/
│   ├── SettingsIndex.vue         # Ana ayarlar sayfası
│   └── MailSettings.vue          # Mail ayarları sayfası
└── Components/                   # (Şimdilik boş - gerekirse eklenecek)
```

### Kullanılan Global Bileşenler
- `AdminLayout.vue` - Ana admin layout
- `PageHeader.vue` - Sayfa başlığı ve aksiyonlar
- `FormSection.vue` - Form bölümleri
- `FormGroup.vue` - Form grupları
- `TextInput.vue`, `Select.vue` - Form elemanları
- `Button.vue` - Özelleştirilmiş butonlar
- `FlashMessage.vue` - Başarı/hata mesajları
- `Alert.vue` - Uyarı mesajları

---

## Backend Yapısı

### Controllers
```
Controllers/
└── Admin/
    └── SettingsController.php    # Admin ayar yönetimi
```

### Services
```
Services/
└── SettingsService.php           # Ayar işlemleri business logic
```

### Models
```
Models/
└── Setting.php                   # Ayar modeli (gelecekte)
```

### Database
```
Migrations/                       # (Şimdilik boş - config dosyaları kullanılıyor)
Seeders/                          # (Şimdilik boş)
```

---

## Teknik Detaylar

### SettingsService
- **Config Yönetimi**: Laravel config dosyalarını yönetir
- **Environment Variables**: .env dosyasını günceller
- **Validation**: Ayar değerlerini doğrular
- **Cache Management**: Config cache'ini temizler

### Mail Ayarları
- **SMTP Konfigürasyonu**: Host, port, kullanıcı adı, şifre
- **Gönderici Bilgileri**: From address, from name
- **Test Fonksiyonu**: Mail ayarlarını test etme
- **Güvenlik**: Şifreleri güvenli şekilde saklama

### Ayar Kategorileri
- **Mail**: E-posta gönderim ayarları
- **App**: Uygulama genel ayarları
- **System**: Sistem seviyesi ayarlar

---

## Kullanım Örnekleri

### Mail Ayarı Güncelleme
```php
// Servis üzerinden
$settingsService = app(SettingsService::class);
$settingsService->updateMailSettings([
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'user@gmail.com',
    'password' => 'password',
    'encryption' => 'tls',
    'from_address' => 'noreply@example.com',
    'from_name' => 'My Application'
]);
```

### Test Mail Gönderimi
```php
// Test mail gönder
$settingsService->testMailSettings('test@example.com');
```

---

## Güvenlik Özellikleri

### Middleware Koruması
- **Auth**: Sadece giriş yapmış kullanıcılar
- **Verified**: Email doğrulanmış kullanıcılar
- **Admin**: Admin yetkisi (gelecekte eklenecek)

### Veri Güvenliği
- Hassas bilgiler (şifreler) şifrelenir
- CSRF koruması
- Form validation
- Input sanitization

---

## Konfigürasyon

### Module Config (`config.php`)
```php
'settings' => [
    'mail' => [
        'enabled' => true,
        'driver' => env('MAIL_MAILER', 'smtp'),
        'host' => env('MAIL_HOST', 'mailpit'),
        // ... diğer mail ayarları
    ],
    'app' => [
        'name' => env('APP_NAME', 'Laravel'),
        // ... diğer app ayarları
    ],
],
```

### Environment Variables
```env
# Mail Settings
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# App Settings
APP_NAME="Laravel"
APP_URL="http://localhost"
APP_TIMEZONE="UTC"
APP_LOCALE="en"
```

---

## UI/UX Özellikleri

### Modern Tasarım
- **Responsive**: Mobile-first yaklaşım
- **Dark Mode**: Tam destek
- **Icons**: Contextual iconlar
- **Colors**: Kategori bazlı renk kodlaması
- **Typography**: Okunabilir font hierarchy

### Kullanıcı Deneyimi
- **Loading States**: Yükleme animasyonları
- **Error Handling**: Hata mesajları
- **Success Feedback**: Başarı bildirimleri
- **Test Feature**: Mail test gönderimi
- **Form Validation**: Gerçek zamanlı doğrulama

---

## Bağımlılıklar

### Laravel Packages
- `inertiajs/inertia-laravel` - SPA framework
- `tightenco/ziggy` - Route helper

### Frontend Dependencies
- `vue@^3.4.0` - Frontend framework
- `@inertiajs/vue3` - Inertia.js Vue adapter
- `@tailwindcss/forms` - Form styling

### Global Components
- `resources/js/Global/Components/*` - Paylaşılan UI bileşenleri
- `resources/js/Global/Layouts/AdminLayout.vue` - Admin layout

---

## Test Coverage

### Test Edilmesi Gerekenler
- [ ] Controller testleri
- [ ] Service testleri  
- [ ] Form validation testleri
- [ ] Mail test gönderimi
- [ ] Config güncelleme testleri

---

## Gelecek Geliştirmeler

### Kısa Vadeli
1. **App Settings**: Uygulama ayarları sayfası
2. **System Settings**: Sistem ayarları sayfası
3. **Setting History**: Ayar değişiklik geçmişi
4. **Backup/Restore**: Ayar yedekleme

### Uzun Vadeli
1. **Multi-Environment**: Çoklu ortam desteği
2. **Setting Templates**: Ayar şablonları
3. **API Integration**: External servis entegrasyonu
4. **Advanced Validation**: Gelişmiş doğrulama kuralları

---

## Troubleshooting

### Yaygın Sorunlar
1. **Ayarlar kaydedilmiyor**: Config cache'ini temizleyin
2. **Mail test çalışmıyor**: Mail ayarlarını kontrol edin
3. **Permission hatası**: Dosya izinlerini kontrol edin

### Maintenance
```bash
# Config cache'ini temizle
php artisan config:clear

# Cache'leri temizle
php artisan cache:clear

# Mail ayarlarını test et
php artisan tinker
>>> app(\Modules\Settings\Services\SettingsService::class)->testMailSettings('test@example.com')
```

---

*Son güncelleme: 2025-01-26*
*Versiyon: 1.0.0* 