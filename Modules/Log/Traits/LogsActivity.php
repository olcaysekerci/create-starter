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
            ->setDescriptionForEvent(fn(string $eventName) => $this->getActivityDescription($eventName));
    }

    protected function getLogAttributes(): array
    {
        // Her modelde override edilebilir
        return $this->fillable ?? ['*'];
    }

    protected function getActivityDescription(string $eventName): string
    {
        $modelName = class_basename($this);
        $descriptions = [
            'created' => "{$modelName} oluşturuldu",
            'updated' => "{$modelName} güncellendi", 
            'deleted' => "{$modelName} silindi",
            'restored' => "{$modelName} geri yüklendi",
        ];

        return $descriptions[$eventName] ?? "{$modelName} {$eventName}";
    }

    /**
     * Activity log'a ek özellikler ekle
     */
    public function tapActivity($activity, string $eventName): void
    {
        $properties = $activity->properties->toArray();
        
        // IP ve User Agent bilgilerini ekle
        $properties['ip_address'] = request()->ip();
        $properties['user_agent'] = request()->userAgent();
        
        // Model spesifik bilgiler eklenebilir
        if (method_exists($this, 'getAdditionalActivityProperties')) {
            $properties = array_merge($properties, $this->getAdditionalActivityProperties($eventName));
        }

        $activity->properties = $properties;
        $activity->save();
    }
} 