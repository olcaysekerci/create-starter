# package.md

## Amaç

Bu dosya, projede kullanılan **Laravel (Composer)** ve **JavaScript (NPM)** paketlerini ve her birinin **kullanım amacını** açıklar.  
Geliştirme sırasında yeni bir paket eklendiğinde buraya işlenmelidir.

---

## PHP / Laravel Paketleri (Composer)

| Paket                     | Amaç / Kullanım Yeri                                   | Durum     |
|---------------------------|--------------------------------------------------------|-----------|
| `inertiajs/inertia-laravel` | Vue ile backend arasında sayfa geçiş köprüsü         | Zorunlu   |
| `spatie/laravel-permission` | Kullanıcı rolleri ve izinleri                        | Zorunlu   |
| `spatie/laravel-sluggable`  | Slug üretimi (Post başlıkları vb.)                   | Opsiyonel |
| `barryvdh/laravel-debugbar` | Geliştirme sırasında debug aracı                     | Geliştirici Aracı |
| `doctrine/dbal`             | Migration rename/dropColumn desteği için             | Yardımcı |
| `laravel/sanctum`           | API token auth (kullanılacaksa)                      | Opsiyonel |
| `nunomaduro/collision`      | Terminal error ve stack trace görselleştirme         | Geliştirici Aracı |

---

## JavaScript / Vue / NPM Paketleri

| Paket                      | Amaç / Kullanım Yeri                             | Durum     |
|----------------------------|--------------------------------------------------|-----------|
| `@inertiajs/inertia`       | Sayfa geçişleri ve Inertia temel paketi         | Zorunlu   |
| `@inertiajs/vue3`          | Vue 3 için Inertia adaptörü                      | Zorunlu   |
| `@vitejs/plugin-vue`       | Vite ile Vue dosyalarının build edilmesi        | Zorunlu   |
| `ziggy-js`                 | Laravel route'larını Vue tarafında kullanmak    | Zorunlu   |
| `axios`                    | API çağrıları                                    | Zorunlu   |
| `tailwindcss`              | Stil kütüphanesi (utility-first)                | Zorunlu   |
| `vue`                      | Vue framework kendisi                           | Zorunlu   |
| `vue-toastification`       | Bildirim sistemi (opsiyonel UI)                 | Opsiyonel |
| `heroicons/vue`            | Icon bileşenleri                                | Opsiyonel |
| `laravel-vite-plugin`      | Laravel + Vite entegrasyonu                     | Zorunlu   |

---

## Paket Ekleme Kuralları

- Her yeni Composer veya NPM paketi yüklendiğinde bu dosyaya **amaç ve durum bilgisiyle** eklenmelidir.
- Geliştirici araçları (debugbar, collision vs.) sadece local ortamda çalışacak şekilde `require-dev` olarak kurulmalıdır.
- Opsiyonel paketler ayrı belirtilmeli. Kullanılmıyorsa `package.json` / `composer.json` üzerinden kaldırılmalı.

---

## Güncelleme Notu

> `composer update` veya `npm update` yapıldığında önemli bir değişiklik varsa bu dosyaya tarih atılarak ek bilgi girilmelidir.
