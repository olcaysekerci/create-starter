<?php

namespace Modules\Log\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Builder;

class Activity extends SpatieActivity
{
    /**
     * Accessor'ları JSON'a dahil et
     */
    protected $appends = [
        'user_name',
        'model_name', 
        'formatted_description',
        'ip_address',
        'user_agent'
    ];

    /**
     * Filtreleme scope'ları
     */
    public function scopeByUser(Builder $query, $userId): Builder
    {
        return $query->where('causer_id', $userId);
    }

    public function scopeByModel(Builder $query, string $modelType): Builder
    {
        return $query->where('subject_type', $modelType);
    }

    public function scopeByEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    public function scopeInDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Accessor'lar
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $descriptions = [
            'created' => 'oluşturdu',
            'updated' => 'güncelledi',
            'deleted' => 'sildi',
            'restored' => 'geri yükledi',
            'login' => 'giriş yaptı',
            'logout' => 'çıkış yaptı',
            'profile_updated' => 'profil güncelledi',
            'password_changed' => 'şifre değiştirdi',
        ];

        return $descriptions[$this->event] ?? $this->event;
    }

    public function getModelNameAttribute(): string
    {
        if (!$this->subject_type) {
            return 'Sistem';
        }

        $modelNames = [
            'App\Models\User' => 'Kullanıcı',
            'Modules\User\Models\User' => 'Kullanıcı',
        ];

        return $modelNames[$this->subject_type] ?? class_basename($this->subject_type);
    }

    public function getUserNameAttribute(): string
    {
        return $this->causer ? $this->causer->name : 'Sistem';
    }

    public function getIpAddressAttribute(): ?string
    {
        return $this->properties['ip_address'] ?? null;
    }

    public function getUserAgentAttribute(): ?string
    {
        return $this->properties['user_agent'] ?? null;
    }
} 