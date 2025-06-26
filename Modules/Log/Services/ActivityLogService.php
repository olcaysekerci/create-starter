<?php

namespace Modules\Log\Services;

use Modules\Log\Models\Activity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function getActivities(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        // Filtreleme
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('model_type')) {
            $query->byModel($request->model_type);
        }

        if ($request->filled('event')) {
            $query->byEvent($request->event);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('causer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->paginate($perPage);
    }

    public function getActivityStats(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'today' => Activity::where('created_at', '>=', $today)->count(),
            'this_week' => Activity::where('created_at', '>=', $thisWeek)->count(),
            'this_month' => Activity::where('created_at', '>=', $thisMonth)->count(),
            'total' => Activity::count(),
        ];
    }

    public function getRecentActivities(int $limit = 10): Collection
    {
        return Activity::with('causer', 'subject')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getUserActivities(int $userId, int $limit = 20): Collection
    {
        return Activity::with('subject')
            ->byUser($userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getModelActivities(string $modelType, int $modelId, int $limit = 20): Collection
    {
        return Activity::with('causer')
            ->where('subject_type', $modelType)
            ->where('subject_id', $modelId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getEventTypes(): array
    {
        return Activity::distinct()
            ->pluck('event')
            ->filter()
            ->values()
            ->toArray();
    }

    public function getModelTypes(): array
    {
        return Activity::distinct()
            ->pluck('subject_type')
            ->filter()
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => class_basename($type),
                ];
            })
            ->values()
            ->toArray();
    }

    public function logCustomActivity(string $description, array $properties = []): void
    {
        $properties = array_merge($properties, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        activity()
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log($description);
    }

    public function logAuthActivity(string $event, ?int $userId = null): void
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        
        $properties = [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        activity()
            ->causedBy($user)
            ->withProperties($properties)
            ->log($event);
    }

    public function cleanupOldLogs(int $days = 30): int
    {
        $cutoffDate = now()->subDays($days);
        
        return Activity::where('created_at', '<', $cutoffDate)->delete();
    }
} 