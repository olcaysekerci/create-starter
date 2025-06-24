# testing.md

## Hedef

- Her modülde en az 1 Feature Test bulunmalı
- Controller test edilmez, sadece Feature üzerinden davranış test edilir
- Action ve Service unit test ihtiyacı varsa yazılır

## Vue Testleri

- Test edilmeye değer bileşenler: form, hesaplama yapan component
- Çok basit UI parçaları (sadece button vs.) testlenmez

## Kullanım

- Feature test: `php artisan test --filter=CreateUserTest`
