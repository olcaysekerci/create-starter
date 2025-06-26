<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Log\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Activity Log için özel ayarlar
     */
    protected $logAttributes = ['name', 'email']; // Sadece bu alanları logla
    protected $displayName = 'Kullanıcı'; // Türkçe model adı

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Activity log için özel alan adları
     */
    public function getCustomFieldNames(): array
    {
        return [
            'name' => 'Ad Soyad',
            'email' => 'E-posta Adresi',
            'email_verified_at' => 'E-posta Doğrulama Tarihi',
            'created_at' => 'Kayıt Tarihi',
            'updated_at' => 'Son Güncelleme',
        ];
    }

    /**
     * Activity log için ek özellikler
     */
    public function getAdditionalActivityProperties(string $eventName): array
    {
        $properties = [];

        if ($eventName === 'created') {
            $properties['user_type'] = 'standard';
            $properties['registration_ip'] = request()->ip();
        }

        if ($eventName === 'updated') {
            // Şifre değişikliği kontrolü
            if ($this->wasChanged('password')) {
                $properties['password_changed'] = true;
                $properties['password_change_time'] = now()->toISOString();
            }

            // Email değişikliği kontrolü
            if ($this->wasChanged('email')) {
                $properties['email_changed'] = true;
                $properties['old_email'] = $this->getOriginal('email');
                $properties['new_email'] = $this->email;
            }
        }

        if ($eventName === 'deleted') {
            $properties['deleted_user_info'] = [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'registration_date' => $this->created_at?->toDateString(),
            ];
        }

        return $properties;
    }

    /**
     * Admin kontrolü (gelecekte role sistemi için)
     */
    public function isAdmin(): bool
    {
        // Şimdilik ilk kullanıcı admin olsun
        return $this->id === 1;
    }
}
