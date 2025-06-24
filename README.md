# Laravel Modular Starter

**Laravel + Inertia.js + Vue 3** ile geliştirilmiş modüler yapıya sahip starter proje.

## 🚀 Özellikler

- **Modüler Mimari**: Her işlevsel alan bağımsız modül olarak organize edilmiş
- **Laravel 11**: En güncel Laravel framework
- **Inertia.js**: SPA deneyimi sunan köprü teknolojisi  
- **Vue 3**: Modern ve reaktif frontend framework
- **Tailwind CSS**: Utility-first CSS framework
- **SOLID Principles**: Temiz kod mimarisi
- **Türkçe Arayüz**: Türkçe dil desteği

## 📁 Proje Yapısı

```
├── Modules/                    # Modüler yapı
│   └── User/                   # Örnek User modülü
│       ├── Controllers/        # Web ve Admin controllers
│       ├── Services/           # Business logic
│       ├── Requests/           # Form validation
│       ├── Resources/Views/    # Vue bileşenleri
│       ├── Routes/             # Modül route'ları
│       └── Tests/              # Modül testleri
├── app/Global/                 # Ortak backend kaynakları
│   ├── Traits/                 # Ortak trait'ler
│   ├── Helpers/                # Yardımcı fonksiyonlar
│   └── Middleware/             # Ortak middleware'ler
└── resources/js/Global/        # Ortak Vue bileşenleri
    ├── Layouts/                # Layout bileşenleri
    ├── Components/             # UI bileşenleri
    ├── Forms/                  # Form bileşenleri
    └── Icons/                  # Icon bileşenleri
```

## 🛠️ Kurulum

1. **Projeyi klonlayın**
   ```bash
   git clone <repo-url> my-project
   cd my-project
   ```

2. **Bağımlılıkları yükleyin**
   ```bash
   composer install
   npm install
   ```

3. **Environment dosyasını hazırlayın**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Veritabanını hazırlayın**
   ```bash
   php artisan migrate --seed
   ```

5. **Asset'leri derleyin**
   ```bash
   npm run dev
   ```

6. **Geliştirme sunucusunu başlatın**
   ```bash
   php artisan serve
   ```

## 📋 Kullanım

### Yeni Modül Oluşturma

1. **Modül klasör yapısını oluşturun:**
   ```bash
   mkdir -p Modules/YourModule/{Actions,Controllers/{Web,Admin},Services,Routes,Resources/Views/{Web,Admin}}
   ```

2. **Route dosyalarını ekleyin:**
   ```php
   // Modules/YourModule/Routes/web.php
   Route::get('/your-route', [YourController::class, 'index']);
   ```

3. **Controller oluşturun:**
   ```php
   namespace Modules\YourModule\Controllers\Web;
   
   class YourController extends Controller {
       // Controller logic
   }
   ```

### Vue Bileşeni Oluşturma

```vue
<template>
    <AppLayout title="Sayfa Başlığı">
        <!-- İçerik -->
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Global/Layouts/AppLayout.vue'
</script>
```

## 🧪 Testler

```bash
# Tüm testleri çalıştır
php artisan test

# Belirli bir modül testini çalıştır  
php artisan test --filter=UserProfileTest
```

## 📚 Dokümantasyon

- [project.md](project.md) - Detaylı proje mimarisi
- [module.md](module.md) - Modül yapısı örneği
- [conventions.md](conventions.md) - Kod yazım kuralları
- [testing.md](testing.md) - Test yazım rehberi
- [package.md](package.md) - Kullanılan paketler

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit edin (`git commit -m 'Add amazing feature'`)
4. Push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.
