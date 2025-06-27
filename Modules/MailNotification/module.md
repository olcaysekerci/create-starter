# Mail Notification Modülü

## Amaç

Mail notification modülü, sistemdeki tüm e-posta gönderimlerini merkezi olarak yönetmek ve loglamak için geliştirilmiştir:
- **Merkezi Mail Gönderimi**: Tüm mailler tek bir servis üzerinden gönderilir
- **Mail Loglama**: Gönderilen tüm mailler veritabanına kaydedilir
- **Admin Panel**: Mail loglarını görüntüleme, filtreleme, analiz
- **Test Sistemi**: Mail gönderimini test etme özelliği

---

## Özellikler

### ✅ Tamamlanan Özellikler
- [x] Merkezi MailDispatcher servisi
- [x] Mail log modeli ve migration
- [x] Admin panelinde mail logları listesi
- [x] Mail test gönderimi özelliği
- [x] Gelişmiş filtreleme (alıcı, tür, durum, tarih)
- [x] Mail detay görüntüleme
- [x] Responsive tasarım
- [x] Dark mode desteği

### 🔄 Geliştirilmekte
- [ ] Mail template sistemi
- [ ] Queue sistemi entegrasyonu
- [ ] Mail retry mekanizması
- [ ] Email analytics
- [ ] Bulk mail gönderimi

---

## Route Yapısı

### Admin Routes (`/admin/mail-notifications/*`)
| Method | URI                           | Controller                           | Action   | Açıklama                 |
|--------|-------------------------------|--------------------------------------|----------|--------------------------|
| GET    | /admin/mail-notifications     | Admin\MailNotificationController     | index    | Mail logları listesi     |
| GET    | /admin/mail-notifications/{id}| Admin\MailNotificationController     | show     | Mail detayı              |
| POST   | /admin/mail-notifications/test| Admin\MailNotificationController     | test     | Test mail gönder         |

---

## Frontend Yapısı

### Admin Sayfaları
```
Resources/Views/Admin/
├── Pages/
│   ├── MailNotificationIndex.vue    # Ana mail logları sayfası
│   └── MailNotificationShow.vue     # Mail detay sayfası
└── Components/                      # (Şimdilik boş - gerekirse eklenecek)
```

### Kullanılan Global Bileşenler
- `AdminLayout.vue` - Ana admin layout
- `PageHeader.vue` - Sayfa başlığı ve aksiyonlar
- `FilterCard.vue` - Gelişmiş filtreleme kartı
- `DataTable.vue` - Mail logları tablosu
- `Pagination.vue` - Sayfalama
- `Modal.vue` - Test mail modal'ı
- `Button.vue` - Özelleştirilmiş butonlar
- `FormGroup.vue`, `TextInput.vue`, `Select.vue` - Form elemanları
- `SearchInput.vue` - Arama kutusu
- `FlashMessage.vue` - Başarı/hata mesajları

---

## Backend Yapısı

### Controllers
```
Controllers/
└── Admin/
    └── MailNotificationController.php    # Admin mail yönetimi
```

### Models
```
Models/
└── MailLog.php                           # Mail log modeli
```

### Services
```
Services/
└── MailDispatcherService.php             # Merkezi mail gönderim servisi
```

### Enums
```
Enums/
└── MailStatus.php                        # Mail durum enum'ları
```

### Database
```
Migrations/
└── create_mail_logs_table.php            # Mail logları tablosu

Seeders/
└── MailLogSeeder.php                     # Test mail logları
```

---

## Teknik Detaylar

### MailDispatcherService
- **Merkezi Gönderim**: Tüm mailler bu servis üzerinden gönderilir
- **Otomatik Loglama**: Her mail gönderimi otomatik loglanır
- **Hata Yönetimi**: Gönderim hataları yakalanır ve loglanır
- **Retry Mekanizması**: Başarısız gönderimler için retry desteği

### Mail Log Bilgileri
- **Temel**: Alıcı, konu, içerik, gönderim tarihi
- **Durum**: Başarılı, başarısız, bekliyor
- **Teknik**: IP adresi, User Agent, hata mesajı
- **Metadata**: Mail türü, template, ek veriler

### Filtreleme Özellikleri
- Alıcıya göre filtreleme
- Mail türüne göre filtreleme
- Duruma göre filtreleme (başarılı, başarısız, bekliyor)
- Tarih aralığı filtreleme
- Metin arama (konu, içerik, alıcı)

---

## Kullanım Örnekleri

### Mail Gönderme
```php
// Servis üzerinden
$mailDispatcher = app(MailDispatcherService::class);
$mailDispatcher->send([
    'to' => 'user@example.com',
    'subject' => 'Hoş geldiniz',
    'content' => 'Merhaba, hoş geldiniz!',
    'type' => 'welcome'
]);

// Helper fonksiyon ile
send_mail([
    'to' => 'user@example.com',
    'subject' => 'Test Mail',
    'content' => 'Bu bir test mailidir.',
    'type' => 'test'
]);
```

### Mail Logları Sorgulama
```php
// Kullanıcının mail geçmişi
$userMails = MailLog::where('recipient', 'user@example.com')->get();

// Başarısız mailler
$failedMails = MailLog::where('status', 'failed')->get();

// Bugünkü mailler
$todayMails = MailLog::whereDate('created_at', today())->get();
```

---

## Güvenlik Özellikleri

### Middleware Koruması
- **Auth**: Sadece giriş yapmış kullanıcılar
- **Verified**: Email doğrulanmış kullanıcılar
- **Admin**: Admin yetkisi (gelecekte eklenecek)

### Veri Güvenliği
- Hassas bilgiler loglanmaz
- Mail içeriği şifrelenebilir
- IP adresi ve User Agent kaydetme
- Soft delete desteği

---

## Konfigürasyon

### Module Config (`config.php`)
```php
'mail_notification' => [
    'enabled' => true,
    'log_all_mails' => true,
    'retention_days' => 90,
    'max_retry_attempts' => 3,
    'default_from_email' => env('MAIL_FROM_ADDRESS'),
    'default_from_name' => env('MAIL_FROM_NAME'),
],
```

### Environment Variables
```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## UI/UX Özellikleri

### Modern Tasarım
- **Responsive**: Mobile-first yaklaşım
- **Dark Mode**: Tam destek
- **Icons**: Contextual iconlar
- **Colors**: Duruma göre renk kodlaması
- **Typography**: Okunabilir font hierarchy

### Kullanıcı Deneyimi
- **Loading States**: Yükleme animasyonları
- **Error Handling**: Hata mesajları
- **Success Feedback**: Başarı bildirimleri
- **Test Feature**: Mail test gönderimi
- **Real-time**: Anında filtreleme

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

### Mevcut Test Verisi
- `MailLogSeeder.php` - 20+ örnek mail log kaydı
- Farklı durumlar (başarılı, başarısız, bekliyor)
- Farklı mail türleri
- Son 30 günlük tarih aralığı

### Test Edilmesi Gerekenler
- [ ] Controller testleri
- [ ] Service testleri  
- [ ] Mail gönderim testleri
- [ ] Log kaydetme testleri
- [ ] Filtreleme testleri

---

## Gelecek Geliştirmeler

### Kısa Vadeli
1. **Mail Templates**: Blade template sistemi
2. **Queue Integration**: Background mail gönderimi
3. **Retry Logic**: Başarısız mailler için retry
4. **Email Analytics**: Gönderim istatistikleri

### Uzun Vadeli
1. **Bulk Mail**: Toplu mail gönderimi
2. **Email Campaigns**: Mail kampanyaları
3. **Advanced Templates**: Drag & drop template builder
4. **Email Tracking**: Açılma, tıklama takibi
5. **Integration**: External email services

---

## Troubleshooting

### Yaygın Sorunlar
1. **Mailler gönderilmiyor**: Mail konfigürasyonunu kontrol edin
2. **Loglar kaydedilmiyor**: Veritabanı bağlantısını kontrol edin
3. **Test mail çalışmıyor**: Mail ayarlarını kontrol edin

### Maintenance
```bash
# Eski mail loglarını temizle (90 günden eski)
php artisan mail:cleanup --days=90

# Mail istatistikleri
php artisan tinker
>>> MailLog::count()
>>> MailLog::where('status', 'sent')->count()
```

---

*Son güncelleme: 2025-01-26*
*Versiyon: 1.0.0* 