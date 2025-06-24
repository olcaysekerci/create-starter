# project.md

## Giriş

Bu proje, **Laravel + Inertia.js + Vue 3** teknolojileri ile geliştirilen, **modüler yapıya sahip bir monolith web uygulamasıdır.**  
Her işlevsel alan (User, Post, Notification vb.) bağımsız bir modül olarak tasarlanmıştır.  
Amaç; okunabilir, genişletilebilir, test edilebilir ve sürdürülebilir bir mimari sunmaktır.

---

## Backend Modül Yapısı

Tüm backend modülleri `Modules/` altında aşağıdaki yapıya göre organize edilir:

```
Modules/
└── User/
    ├── Actions/
    ├── Controllers/
    │   ├── Web/
    │   │   └── UserProfileController.php
    │   └── Admin/
    │       └── UserManagementController.php
    ├── DTO/
    ├── Enums/
    ├── Events/
    ├── Interfaces/
    ├── Jobs/
    ├── Listeners/
    ├── Migrations/
    ├── Models/
    ├── Policies/
    ├── Repositories/
    ├── Requests/
    │   ├── Web/
    │   └── Admin/
    ├── Resources/
    │   └── Views/
    │       ├── Web/
    │       │   ├── Pages/
    │       │   ├── Components/
    │       │   └── Layouts/
    │       └── Admin/
    │           ├── Pages/
    │           ├── Components/
    │           └── Layouts/
    ├── Routes/
    │   ├── web.php
    │   └── admin.php
    ├── Seeders/
    ├── Services/
    ├── Tests/
    │   ├── Web/
    │   └── Admin/
    ├── Traits/
    ├── module.md
    └── config.php

---

## Frontend Yapısı (Vue + Inertia)

Modül içindeki Vue dosyaları `Modules/X/Resources/Views/` klasöründe yer alır.

### Vue Dosya Yapısı

```
Modules/User/Resources/Views/
├── Pages/
│   ├── Index.vue
│   ├── Edit.vue
│   └── Show.vue
├── Components/
│   ├── UserTable.vue
│   └── UserForm.vue
└── Layouts/
    └── UserLayout.vue
```

### Ortak Vue Klasörü (Global)

```
resources/js/
└── Global/
    ├── Layouts/
    │   ├── AppLayout.vue
    │   ├── GuestLayout.vue
    │   └── AdminLayout.vue
    ├── Components/
    │   ├── Button.vue
    │   ├── Input.vue
    │   ├── Select.vue
    │   ├── Checkbox.vue
    │   ├── Table.vue
    │   ├── Modal.vue
    │   ├── Pagination.vue
    │   └── Notification.vue
    ├── Icons/
    │   ├── TrashIcon.vue
    │   ├── EditIcon.vue
    │   ├── SaveIcon.vue
    │   └── PlusIcon.vue
    └── Forms/
        ├── TextInput.vue
        ├── DatePicker.vue
        └── FormGroup.vue
```

---

## Ortak Backend Kaynaklar (`app/Global/`)

Tüm modüller tarafından erişilebilen ortak PHP sınıfları burada tutulur:

```
app/Global/
├── Traits/
│   └── HandlesAjax.php
├── Helpers/
│   └── StringHelper.php
├── Middleware/
│   └── CheckRole.php
```

---

## Route Sistemi

- Her modül kendi route dosyasını `Modules/X/Routes/web.php` altında tutar
- `routes/modules.php` dosyası tüm modül route’larını otomatik yükler
- Ziggy ile Laravel route isimleri Vue tarafına aktarılır

```php
// routes/modules.php
foreach (glob(base_path('Modules/*/Routes/web.php')) as $routeFile) {
    require $routeFile;
}
```

---

## Kodlama Kuralları

### Backend

- **Controller**: sadece yönlendirme, response ve request binding işlemleri
- **Action**: bir işi yapan sınıf (tek sorumluluk)
- **Service**: birden fazla işin birleşimi, genellikle 3rd party entegrasyonlar burada
- **DTO**: veri taşıma işlemleri, formdan gelen veriyi filtreler
- **Request**: yalnızca validation işlemleri yapılır
- **Policy**: yetki kontrolü burada yapılır
- **Repository**: veri erişim soyutlaması

### Frontend

- Her bileşende `name:` tanımı yapılmalı (debug ve profiler kolaylığı için)
- `props` ile veri alımı, `emit` ile olay iletimi açıkça tanımlanmalı
- `defineProps` ve `defineEmits` açık kullanılmalı (Composition API ile)
- Vue bileşenleri sadece kendi modülündeki bileşenleri kullanmalı
- Modül dışı bileşen ihtiyacı varsa, `Global/` altına alınmalı
- Kod tekrarında `Traits` veya `Actions` tercih edilmeli

---

## Ek Tavsiyeler

- Modül içinde başka modülün Vue bileşenini **doğrudan kullanma** – bağımlılığı azalt
- Ortak Vue bileşenleri sadece `resources/js/Global/` altında tanımlanmalı
- Ortak backend sınıflar için `app/Global/` yapısı sabit tutulmalı
- Ziggy ile route kullanımı `route('users.index')` şeklinde yapılmalı
- Her modülde `module.md` dosyası bulunmalı, içeriği güncel tutulmalı

---



## Test organizasyonu
- Her modül için `Tests/Feature/` klasörü zorunludur.
- Modül içinde `Web/` ve `Admin/` testleri ayrı olmalı.
- Geliştirilen her yeni route veya action için en az bir feature testi yazılmalı.
