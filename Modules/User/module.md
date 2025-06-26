module.md


# User Modülü

## Amaç

User modülü, sistemdeki kullanıcı yönetimi işlemlerini kapsamlı bir şekilde sağlar:
- **Web Tarafı**: Kullanıcı profil yönetimi, profil güncelleme
- **Admin Tarafı**: Kullanıcı CRUD işlemleri, filtreleme, Excel export, soft delete
- **Modern UI/UX**: Vue 3 + Inertia.js ile responsive tasarım
- **Güvenlik**: Form validation, request sınıfları, middleware koruması

---

## Özellikler

### ✅ Tamamlanan Özellikler
- [x] Kullanıcı listesi (filtreleme, arama, pagination)
- [x] Kullanıcı ekleme/düzenleme/silme (soft delete)
- [x] Excel export özelliği
- [x] Modern delete confirmation modal
- [x] Flash message sistemi
- [x] Dark mode desteği
- [x] Responsive tasarım
- [x] Form validation (Frontend + Backend)
- [x] Profil yönetimi
- [x] Test seeder (11 örnek kullanıcı)

### 🔄 Geliştirilmekte
- [ ] Kullanıcı rolleri ve yetkilendirme sistemi
- [ ] E-posta doğrulama workflow'u
- [ ] Şifre sıfırlama sistemi
- [ ] Kullanıcı aktivite logları

---

## Route Yapısı

### Web Routes (`/user/*`)
| Method | URI              | Controller                    | Action   | Açıklama              |
|--------|------------------|-------------------------------|----------|-----------------------|
| GET    | /user/profile    | Web\UserProfileController     | show     | Profil görüntüleme    |
| PUT    | /user/profile    | Web\UserProfileController     | update   | Profil güncelleme     |

### Admin Routes (`/admin/*`)
| Method | URI                    | Controller                      | Action   | Açıklama                 |
|--------|------------------------|---------------------------------|----------|--------------------------|
| GET    | /admin/                | Closure                         | -        | Admin dashboard          |
| GET    | /admin/users           | Admin\UserManagementController  | index    | Kullanıcı listesi        |
| POST   | /admin/users           | Admin\UserManagementController  | store    | Yeni kullanıcı ekleme    |
| GET    | /admin/users/{id}/edit | Admin\UserManagementController  | edit     | Kullanıcı düzenleme      |
| PUT    | /admin/users/{id}      | Admin\UserManagementController  | update   | Kullanıcı güncelleme     |
| DELETE | /admin/users/{id}      | Admin\UserManagementController  | destroy  | Kullanıcı silme (soft)   |

---

## Frontend Yapısı

### Admin Sayfaları
```
Resources/Views/Admin/
├── Pages/
│   └── UserIndex.vue              # Ana kullanıcı yönetimi sayfası
├── Components/
│   ├── UserTable.vue              # Kullanıcı tablosu bileşeni
│   └── CreateUserForm.vue         # Kullanıcı ekleme formu
└── Layouts/                       # (Boş - Global layout kullanılıyor)
```

### Web Sayfaları
```
Resources/Views/Web/
├── Pages/
│   └── Profile.vue                # Kullanıcı profil sayfası
├── Components/
│   └── ProfileForm.vue            # Profil düzenleme formu
└── Layouts/                       # (Boş - Global layout kullanılıyor)
```

### Kullanılan Global Bileşenler
- `AdminLayout.vue` - Ana admin layout
- `PageHeader.vue` - Sayfa başlığı ve aksiyonlar
- `FilterCard.vue` - Gelişmiş filtreleme kartı
- `DataTable.vue` - Veri tablosu
- `Modal.vue` - Modal pencereler
- `Button.vue` - Özelleştirilmiş butonlar
- `TextInput.vue`, `Select.vue` - Form elemanları
- `FlashMessage.vue` - Başarı/hata mesajları
- `DeleteConfirmationModal.vue` - Silme onayı

---

## Backend Yapısı

### Controllers
```
Controllers/
├── Admin/
│   └── UserManagementController.php    # Admin CRUD işlemleri
└── Web/
    └── UserProfileController.php       # Profil yönetimi
```

### Request Validation
```
Requests/
├── Admin/
│   ├── CreateUserRequest.php          # Kullanıcı ekleme validasyonu
│   └── UpdateUserRequest.php          # Kullanıcı güncelleme validasyonu
└── Web/
    └── UpdateProfileRequest.php       # Profil güncelleme validasyonu
```

### Services
```
Services/
└── UserService.php                    # İş mantığı katmanı
```

### Database
```
Seeders/
└── UserSeeder.php                     # Test verisi (11 kullanıcı)

Models/                                # (Ana User modeli app/Models/User.php'de)
```

### Tests
```
Tests/
├── Feature/
│   └── UserProfileTest.php           # Profil işlemleri testleri
├── Admin/                            # (Admin testleri için hazır)
└── Web/                              # (Web testleri için hazır)
```

---

## Teknik Detaylar

### Validation Kuralları
- **Email**: Unique, email format
- **Password**: Minimum 6 karakter, confirmation
- **Name**: Required, string, max 255

### Güvenlik Özellikleri
- Middleware: `auth`, `verified`
- CSRF koruması
- Form validation (frontend + backend)
- Soft delete (veri kaybı önleme)
- Mass assignment koruması

### UI/UX Özellikleri
- **Responsive**: Mobile-first tasarım
- **Dark Mode**: Tam destek
- **Accessibility**: ARIA labels, keyboard navigation
- **Performance**: Lazy loading, pagination
- **User Experience**: Loading states, error handling, success messages

### Excel Export
- XLSX format
- Filtered data export
- Turkish headers
- Date formatting

---

## Bağımlılıklar

### Laravel Packages
- `laravel/breeze` - Authentication scaffolding
- `inertiajs/inertia-laravel` - SPA without API
- `tightenco/ziggy` - Route helper for frontend

### Frontend Dependencies
- `vue@^3.4.0` - Frontend framework
- `@inertiajs/vue3` - Inertia.js Vue adapter
- `xlsx@^0.18.5` - Excel export functionality
- `@tailwindcss/forms` - Form styling

### Global Components
- `resources/js/Global/Components/*` - Paylaşılan UI bileşenleri
- `resources/js/Global/Layouts/AdminLayout.vue` - Admin layout
- `app/Global/Traits/HandlesAjax.php` - AJAX işlemleri

---

## Konfigürasyon

### Module Config (`config.php`)
```php
return [
    'name' => 'User',
    'enabled' => true,
    'routes' => [
        'web' => true,
        'admin' => true,
    ],
];
```

### Middleware Grupları
- **Web**: `auth`, `verified`
- **Admin**: `auth`, `verified` (+ gelecekte admin role)

---

## Geliştirme Notları

### Code Standards
- **SOLID Principles**: Service layer pattern
- **MVC Architecture**: Clear separation of concerns
- **DRY Principle**: Reusable components and services
- **Vue 3 Composition API**: Modern Vue.js patterns
- **TypeScript-like Props**: Comprehensive prop validation

### Performance Optimizations
- **Eager Loading**: N+1 query prevention
- **Pagination**: Large dataset handling
- **Component Lazy Loading**: Bundle size optimization
- **CSS Scoping**: Tailwind utility classes

### Future Enhancements
1. **Role-Based Access Control (RBAC)**
2. **Advanced User Analytics**
3. **Bulk Operations**
4. **Import/Export Improvements**
5. **Real-time Notifications**
6. **Advanced Search & Filtering**

---

## Test Coverage

### Mevcut Testler
- `UserProfileTest.php` - Profil CRUD işlemleri

### Test Edilmesi Gerekenler
- [ ] Admin CRUD operations
- [ ] Form validation scenarios
- [ ] Excel export functionality
- [ ] Soft delete operations
- [ ] Filter and search features

---

*Son güncelleme: 2025-01-26*
*Versiyon: 2.1.0*
