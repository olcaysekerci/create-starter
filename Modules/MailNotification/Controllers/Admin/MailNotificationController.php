<?php

namespace Modules\MailNotification\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\MailNotification\Models\MailLog;
use Modules\MailNotification\Services\MailDispatcherService;
use Modules\MailNotification\Enums\MailStatus;

class MailNotificationController extends Controller
{
    protected $mailDispatcher;

    public function __construct(MailDispatcherService $mailDispatcher)
    {
        $this->mailDispatcher = $mailDispatcher;
    }

    /**
     * Mail logları listesi
     */
    public function index(Request $request)
    {
        $query = MailLog::query();

        // Filtreleme
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('recipient')) {
            $query->byRecipient($request->recipient);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        // Sıralama
        $query->orderBy('created_at', 'desc');

        // Pagination
        $mailLogs = $query->paginate(15)->withQueryString();

        // İstatistikler
        $stats = $this->mailDispatcher->getStats();

        // Filtre seçenekleri
        $statusOptions = collect(MailStatus::cases())->map(function ($status) {
            return [
                'value' => $status->value,
                'label' => $status->label(),
            ];
        });

        $typeOptions = MailLog::distinct('type')
            ->whereNotNull('type')
            ->pluck('type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => ucfirst($type),
                ];
            });

        return Inertia::render('Admin/MailNotificationIndex', [
            'mailLogs' => $mailLogs,
            'stats' => $stats,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'type' => $request->type,
                'recipient' => $request->recipient,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
            'filterOptions' => [
                'status' => $statusOptions,
                'type' => $typeOptions,
            ],
        ]);
    }

    /**
     * Mail detayı
     */
    public function show($id)
    {
        $mailLog = MailLog::findOrFail($id);

        return Inertia::render('Admin/MailNotificationShow', [
            'mailLog' => $mailLog,
        ]);
    }

    /**
     * Test mail gönder
     */
    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
        ]);

        $email = $request->email;
        $subject = $request->subject ?? 'Test Mail - ' . config('app.name');

        try {
            $sent = $this->mailDispatcher->sendTestMail($email, $subject);

            if ($sent) {
                return back()->with('success', 'Test mail başarıyla gönderildi!');
            } else {
                return back()->with('error', 'Test mail gönderilemedi. Lütfen mail ayarlarını kontrol edin.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Test mail gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Başarısız mailleri yeniden dene
     */
    public function retry()
    {
        try {
            $retriedCount = $this->mailDispatcher->retryFailedMails();

            if ($retriedCount > 0) {
                return back()->with('success', "{$retriedCount} mail başarıyla yeniden gönderildi.");
            } else {
                return back()->with('info', 'Yeniden gönderilecek mail bulunamadı.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Mail yeniden gönderimi sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Eski logları temizle
     */
    public function cleanup(Request $request)
    {
        $days = $request->get('days', 90);

        try {
            $deletedCount = $this->mailDispatcher->cleanupOldLogs($days);

            return back()->with('success', "{$deletedCount} eski mail logu temizlendi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Log temizleme sırasında hata oluştu: ' . $e->getMessage());
        }
    }
} 