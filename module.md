module.md


# User Modülü

## Amaç

Kullanıcı yönetimi sağlar: kayıt, giriş, profil görüntüleme, yetkilendirme.  
Hem Web hem Admin tarafında ayrı controller ve Vue sayfaları içerir.

---

## Route Özeti

| Context | Method | URI             | Controller                     | Açıklama                  |
|---------|--------|------------------|--------------------------------|---------------------------|
| Web     | GET    | /profile         | Web\UserProfileController@show | Kullanıcı profil ekranı   |
| Web     | PUT    | /profile         | Web\UserProfileController@update | Profil güncelleme         |
| Admin   | GET    | /admin/users     | Admin\UserManagementController@index | Kullanıcı listesi      |
| Admin   | POST   | /admin/users     | Admin\UserManagementController@store | Kullanıcı ekleme       |

---

## Vue Sayfaları

### Web
- `Pages/Profile.vue`
- `Components/ProfileForm.vue`

### Admin
- `Pages/UserIndex.vue`
- `Components/UserTable.vue`
- `Layouts/AdminUserLayout.vue`

---

## Bağımlılıklar

| Modül       | Tür            | Açıklama                              |
|-------------|----------------|---------------------------------------|
| Notification| EventListener  | Yeni kullanıcı kaydında bildirim gönderir |
| Core/User   | Service        | Admin ve Web tarafı ortak UserService kullanır |

---

## Kullanılan Ortaklar

- `app/Global/Traits/HandlesAjax.php`
- `resources/js/Global/Components/Button.vue`

---

## TODO

- [ ] Şifre sıfırlama işlemi eklenecek
- [ ] Kullanıcı rolleri enum yapısına geçirilecek
- [ ] Web tarafında e-posta doğrulama özelliği aktif edilecek

---

## Notlar

- Bu modül hem Web hem Admin interface içerir
- Vue bileşenlerinde `defineProps`, `defineEmits` kullanımı zorunludur
- Route’lar context'e göre ayrı dosyalarda tanımlıdır: `Routes/web.php`, `Routes/admin.php`
