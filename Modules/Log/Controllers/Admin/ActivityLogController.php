<?php

namespace Modules\Log\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Log\Services\ActivityLogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(Request $request): StreamedResponse
    {
        $activities = $this->activityLogService->getActivitiesForExport($request);
        
        $filename = 'aktivite-loglari-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        return response()->streamDownload(function () use ($activities) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM ekle (Excel için)
            fwrite($handle, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($handle, [
                'ID',
                'Kullanıcı',
                'Açıklama',
                'Model',
                'Olay',
                'IP Adresi',
                'Tarih',
                'Admin İşlemi'
            ], ';');
            
            // Data
            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->id,
                    $activity->user_name,
                    $activity->description,
                    $activity->model_name,
                    $activity->event,
                    $activity->ip_address ?? '',
                    $activity->created_at->format('d.m.Y H:i:s'),
                    $activity->is_admin_action ? 'Evet' : 'Hayır'
                ], ';');
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
} 