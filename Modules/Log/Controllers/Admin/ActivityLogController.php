<?php

namespace Modules\Log\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Log\Services\ActivityLogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLogService
    ) {}

    public function index(Request $request): Response
    {
        $activities = $this->activityLogService->getActivities($request);
        $stats = $this->activityLogService->getActivityStats();
        $eventTypes = $this->activityLogService->getEventTypes();
        $modelTypes = $this->activityLogService->getModelTypes();
        
        // Kullanıcı listesi filtreleme için
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Log/ActivityIndex', [
            'activities' => $activities,
            'stats' => $stats,
            'filters' => [
                'event_types' => $eventTypes,
                'model_types' => $modelTypes,
                'users' => $users,
            ],
            'current_filters' => $request->only([
                'user_id', 'model_type', 'event', 'date_from', 'date_to', 'search'
            ]),
        ]);
    }
} 