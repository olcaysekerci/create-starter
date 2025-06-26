<?php

namespace Modules\Log\Traits;

use Spatie\Activitylog\Traits\LogsActivity as SpatieLogsActivity;
use Spatie\Activitylog\LogOptions;

trait LogsActivity
{
    use SpatieLogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->getLogAttributes())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->logFillable()
            ->logUnguarded()
            ->useLogName('default')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getActivityDescription($eventName));
    }

    protected function getLogAttributes(): array
    {
        if (property_exists($this, 'logAttributes')) {
            return $this->logAttributes;
        }
        
        $fillable = $this->fillable ?? [];
        return array_filter($fillable, function($attribute) {
            return !in_array($attribute, ['password', 'remember_token']);
        });
    }

    protected function getActivityDescription(string $eventName): string
    {
        $modelName = $this->getModelDisplayName();
        $descriptions = [
            'created' => "{$modelName} oluşturuldu",
            'updated' => "{$modelName} güncellendi", 
            'deleted' => "{$modelName} silindi",
            'restored' => "{$modelName} geri yüklendi",
        ];

        return $descriptions[$eventName] ?? "{$modelName} {$eventName}";
    }

    protected function getModelDisplayName(): string
    {
        if (property_exists($this, 'displayName')) {
            return $this->displayName;
        }

        $modelNames = [
            'User' => 'Kullanıcı',
            'Post' => 'Gönderi',
            'Category' => 'Kategori',
            'Setting' => 'Ayar',
        ];

        $className = class_basename($this);
        return $modelNames[$className] ?? $className;
    }

    /**
     * Activity log'a ek özellikler ekle
     */
    public function tapActivity($activity, string $eventName): void
    {
        $properties = $activity->properties->toArray();
        
        $properties['ip_address'] = request()->ip();
        $properties['user_agent'] = request()->userAgent();
        
        if ($this->exists) {
            $properties['model_id'] = $this->getKey();
            $properties['model_name'] = $this->getModelDisplayName();
        }
        
        // Admin işlemi kontrolü - mevcut auth kullanıcısı ile işlem yapılan model farklıysa admin işlemi
        if (auth()->check()) {
            $currentUser = auth()->user();
            $isAdminAction = false;
            
            // Eğer başka bir kullanıcı üzerinde işlem yapılıyorsa admin işlemi
            if ($this instanceof \App\Models\User && $this->id !== $currentUser->id) {
                $isAdminAction = true;
            }
            
            // Admin kontrolü varsa onu da ekle
            if (method_exists($currentUser, 'isAdmin') && $currentUser->isAdmin()) {
                $isAdminAction = true;
            }
            
            $properties['admin_action'] = $isAdminAction;
        }
        
        if (method_exists($this, 'getAdditionalActivityProperties')) {
            $properties = array_merge($properties, $this->getAdditionalActivityProperties($eventName));
        }

        if ($eventName === 'updated' && isset($properties['old']) && isset($properties['attributes'])) {
            $properties['changes'] = $this->getReadableChanges($properties['old'], $properties['attributes']);
        }

        $activity->properties = $properties;
        $activity->save();
    }

    protected function getReadableChanges(array $old, array $new): array
    {
        $changes = [];
        
        foreach ($new as $key => $newValue) {
            $oldValue = $old[$key] ?? null;
            
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $this->formatAttributeValue($key, $oldValue),
                    'new' => $this->formatAttributeValue($key, $newValue),
                    'field_name' => $this->getFieldDisplayName($key)
                ];
            }
        }
        
        return $changes;
    }

    protected function formatAttributeValue(string $key, $value): string
    {
        if (is_null($value)) {
            return 'Boş';
        }
        
        if (is_bool($value)) {
            return $value ? 'Evet' : 'Hayır';
        }
        
        if ($key === 'password') {
            return '***';
        }
        
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }
        
        return (string) $value;
    }

    protected function getFieldDisplayName(string $key): string
    {
        $fieldNames = [
            'name' => 'Ad',
            'email' => 'E-posta',
            'title' => 'Başlık',
            'content' => 'İçerik',
            'description' => 'Açıklama',
            'status' => 'Durum',
            'created_at' => 'Oluşturulma Tarihi',
            'updated_at' => 'Güncellenme Tarihi',
        ];

        if (method_exists($this, 'getCustomFieldNames')) {
            $customNames = $this->getCustomFieldNames();
            $fieldNames = array_merge($fieldNames, $customNames);
        }

        return $fieldNames[$key] ?? ucfirst($key);
    }

    protected function getDeletedModelAttributes(): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name ?? $this->title ?? "#{$this->getKey()}",
            'model' => $this->getModelDisplayName(),
        ];
    }
} 