# Log Modülü

## Amaç

Log modülü, sistemdeki tüm aktiviteleri izlemek ve kaydetmek için geliştirilmiştir:
- **Activity Log**: Kullanıcı aktivitelerini, model değişikliklerini kaydetme
- **Auth Events**: Giriş/çıkış, kayıt olma işlemlerini loglama
- **System Events**: Sistem olaylarını kaydetme
- **Admin Panel**: Logları görüntüleme, filtreleme, temizleme

---

## Özellikler

### ✅ Tamamlanan Özellikler
- [x] Otomatik model değişiklik logları (User modeli)
- [x] Kullanıcı giriş/çıkış/kayıt logları
- [x] Admin panelinde log görüntüleme
- [x] Gelişmiş filtreleme (kullanıcı, tarih, olay türü, model)
- [x] Arama özelliği
- [x] Pagination desteği
- [x] Log detay sayfası
- [x] Eski logları temizleme özelliği
- [x] IP adresi ve User Agent kaydetme
- [x] İstatistik kartları (bugün, bu hafta, bu ay, toplam)
- [x] Dark mode desteği
- [x] Responsive tasarım
- [x] Test seeder (örnek log verileri)

### 🔄 Geliştirilmekte
- [ ] Excel export özelliği
- [ ] Real-time log monitoring
- [ ] Log level'ları (info, warning, error)
- [ ] Email notifications for critical events
- [ ] API endpoint'leri
- [ ] Bulk operations

---

## Route Yapısı

### Admin Routes (`/admin/logs/*`)
| Method | URI                    | Controller                    | Action   | Açıklama                 |
|--------|------------------------|-------------------------------|----------|--------------------------|
| GET    | /admin/logs            | Admin\ActivityLogController   | index    | Log listesi              |
| GET    | /admin/logs/{id}       | Admin\ActivityLogController   | show     | Log detayı               |
| POST   | /admin/logs/cleanup    | Admin\ActivityLogController   | cleanup  | Eski logları temizle     |

---

## Frontend Yapısı

### Admin Sayfaları
```
Resources/Views/Admin/
├── Pages/
│   ├── ActivityIndex.vue          # Ana log listesi sayfası
│   └── ActivityShow.vue           # Log detay sayfası
└── Components/                    # (Şimdilik boş - gerekirse eklenecek)
```

### Kullanılan Global Bileşenler
- `AdminLayout.vue` - Ana admin layout
- `PageHeader.vue` - Sayfa başlığı ve aksiyonlar
- `StatCard.vue` - İstatistik kartları
- `FilterCard.vue` - Gelişmiş filtreleme kartı
- `DataTable.vue` - Log tablosu
- `Pagination.vue` - Sayfalama
- `Modal.vue` - Temizleme modal'ı
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
    └── ActivityLogController.php      # Admin log yönetimi
```

### Models
```
Models/
└── Activity.php                       # Spatie Activity Log modelini extend eden özel model
```

### Services
```
Services/
└── ActivityLogService.php             # Log işlemleri business logic
```

### Traits
```
Traits/
└── LogsActivity.php                   # Modellerde kullanılacak log trait'i
```

### Listeners
```
Listeners/
└── LogAuthActivity.php                # Auth event listener'ı
```

### Seeders
```
Seeders/
└── ActivityLogSeeder.php              # Test log verileri
```

---

## Teknik Detaylar

### Spatie Activity Log Entegrasyonu
- **Paket**: `spatie/laravel-activitylog`
- **Özel Model**: `Modules\Log\Models\Activity`
- **Config**: `config/activitylog.php`
- **Trait**: `Modules\Log\Traits\LogsActivity`

### Loglanan Bilgiler
- **Temel**: Açıklama, olay türü, tarih/saat
- **Kullanıcı**: Kullanıcı ID, isim, email
- **Teknik**: IP adresi, User Agent
- **Model**: Model türü, model ID
- **Değişiklikler**: Eski ve yeni değerler (JSON)

### Filtreleme Özellikleri
- Kullanıcıya göre filtreleme
- Olay türüne göre filtreleme (created, updated, deleted, login, logout)
- Model türüne göre filtreleme
- Tarih aralığı filtreleme
- Metin arama (açıklama ve kullanıcı adı)

### Performans Optimizasyonları
- **Eager Loading**: Kullanıcı ve model ilişkileri
- **Pagination**: Büyük veri setleri için
- **Indexing**: Veritabanı indeksleri
- **Cleanup**: Eski logları temizleme

---

## Kullanım Örnekleri

### Model'de Activity Log Kullanımı
```php
use Modules\Log\Traits\LogsActivity;

class User extends Model
{
    use LogsActivity;
    
    // Otomatik olarak create, update, delete işlemleri loglanır
}
```

### Manuel Log Kaydetme
```php
// Servis üzerinden
$activityLogService->logCustomActivity('Özel bir işlem yapıldı', [
    'extra_data' => 'değer'
]);

// Direkt activity helper ile
activity()
    ->causedBy(auth()->user())
    ->withProperties(['key' => 'value'])
    ->log('Bir işlem gerçekleştirildi');
```

### Auth Event Logları
```php
// Otomatik olarak şu events loglanır:
// - Login: "Sisteme giriş yaptı"
// - Logout: "Sistemden çıkış yaptı"  
// - Registered: "Sisteme kayıt oldu"
```

---

## Güvenlik Özellikleri

### Middleware Koruması
- **Auth**: Sadece giriş yapmış kullanıcılar
- **Verified**: Email doğrulanmış kullanıcılar
- **Admin**: Admin yetkisi (gelecekte eklenecek)

### Veri Güvenliği
- Hassas bilgiler loglanmaz (şifreler vs.)
- IP adresi ve User Agent kaydetme
- Soft delete desteği
- Batch UUID ile işlem gruplama

---

## Konfigürasyon

### Module Config (`config.php`)
```php
'settings' => [
    'activity_log' => [
        'enabled' => true,
        'log_models' => true,
        'log_auth' => true,
        'cleanup_days' => 30,
    ],
],
```

### Spatie Config (`config/activitylog.php`)
- Custom model: `Modules\Log\Models\Activity`
- Cleanup: 365 gün
- Database connection: Default

---

## UI/UX Özellikleri

### Modern Tasarım
- **Responsive**: Mobile-first yaklaşım
- **Dark Mode**: Tam destek
- **Icons**: Contextual iconlar
- **Colors**: Olay türüne göre renk kodlaması
- **Typography**: Okunabilir font hierarchy

### Kullanıcı Deneyimi
- **Loading States**: Yükleme animasyonları
- **Error Handling**: Hata mesajları
- **Success Feedback**: Başarı bildirimleri
- **Keyboard Navigation**: Accessibility desteği
- **Breadcrumbs**: Navigasyon yardımı

### Filtreleme & Arama
- **Real-time**: Anında filtreleme
- **Multi-filter**: Çoklu filtre desteği
- **Date Picker**: Tarih seçici
- **Search**: Fuzzy search
- **Clear Filters**: Filtreleri temizle

---

## Bağımlılıklar

### Laravel Packages
- `spatie/laravel-activitylog` - Activity logging
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
- `ActivityLogSeeder.php` - 50+ örnek log kaydı
- Farklı kullanıcılar için aktiviteler
- Sistem aktiviteleri
- Rastgele IP ve User Agent'lar
- Son 30 günlük tarih aralığı

### Test Edilmesi Gerekenler
- [ ] Controller testleri
- [ ] Service testleri  
- [ ] Model scope testleri
- [ ] Event listener testleri
- [ ] Trait functionality testleri

---

## Gelecek Geliştirmeler

### Kısa Vadeli
1. **Excel Export**: Filtrelenmiş logları Excel'e aktarma
2. **API Endpoints**: REST API desteği
3. **Bulk Operations**: Toplu log işlemleri
4. **Performance Monitoring**: Slow query detection

### Uzun Vadeli
1. **Real-time Dashboard**: WebSocket ile canlı log izleme
2. **Advanced Analytics**: Log trend analizi
3. **Machine Learning**: Anormal aktivite tespiti
4. **Integration**: External log services (Elasticsearch, etc.)
5. **Mobile App**: Mobile log monitoring

---

## Troubleshooting

### Yaygın Sorunlar
1. **Loglar kaydetmiyor**: `ACTIVITY_LOGGER_ENABLED=true` kontrol edin
2. **Performance sorunu**: Cleanup işlemini düzenli çalıştırın
3. **Disk alanı**: Eski logları temizleyin
4. **Memory usage**: Pagination kullanın

### Maintenance
```bash
# Eski logları temizle (30 günden eski)
php artisan activitylog:clean --days=30

# Log istatistikleri
php artisan tinker
>>> Activity::count()
>>> Activity::where('created_at', '>=', now()->subDays(7))->count()
```

---

*Son güncelleme: 2025-01-26*
*Versiyon: 1.0.0* 