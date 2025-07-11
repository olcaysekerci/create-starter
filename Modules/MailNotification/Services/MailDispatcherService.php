<?php

namespace Modules\MailNotification\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\MailNotification\Models\MailLog;
use Modules\MailNotification\Enums\MailStatus;
use Illuminate\Mail\Message;

class MailDispatcherService
{
    /**
     * Mail gönder ve logla
     */
    public function send(array $data): bool
    {
        Log::info('MailDispatcherService::send başlatıldı', [
            'input_data' => $data
        ]);

        $mailData = $this->prepareMailData($data);
        
        Log::info('MailDispatcherService::send - hazırlanan veri', [
            'prepared_data' => $mailData
        ]);
        
        // Mail log kaydı oluştur
        $mailLog = $this->createMailLog($mailData);
        
        Log::info('MailDispatcherService::send - mail log oluşturuldu', [
            'log_id' => $mailLog->id,
            'recipient' => $mailLog->recipient
        ]);
        
        try {
            // Mail gönder
            $sent = $this->sendMail($mailData);
            
            if ($sent) {
                $mailLog->markAsSent();
                Log::info('Mail başarıyla gönderildi', [
                    'recipient' => $mailData['to'],
                    'subject' => $mailData['subject'],
                    'log_id' => $mailLog->id
                ]);
                return true;
            } else {
                $mailLog->markAsFailed('Mail gönderimi başarısız');
                Log::error('Mail gönderimi başarısız', [
                    'recipient' => $mailData['to'],
                    'subject' => $mailData['subject'],
                    'log_id' => $mailLog->id
                ]);
                return false;
            }
        } catch (\Exception $e) {
            $mailLog->markAsFailed($e->getMessage());
            Log::error('Mail gönderim hatası', [
                'recipient' => $mailData['to'],
                'subject' => $mailData['subject'],
                'error' => $e->getMessage(),
                'log_id' => $mailLog->id
            ]);
            return false;
        }
    }

    /**
     * Test mail gönder
     */
    public function sendTestMail(string $to, string $subject = 'Test Mail'): bool
    {
        Log::info('MailDispatcherService::sendTestMail çağrıldı', [
            'to' => $to,
            'subject' => $subject
        ]);

        $content = $this->getTestMailContent();
        
        $result = $this->send([
            'to' => $to,
            'subject' => $subject,
            'content' => $content,
            'type' => 'test'
        ]);

        Log::info('MailDispatcherService::sendTestMail sonucu', [
            'to' => $to,
            'subject' => $subject,
            'result' => $result
        ]);

        return $result;
    }

    /**
     * Mail verilerini hazırla
     */
    private function prepareMailData(array $data): array
    {
        $config = config('modules.MailNotification.settings.mail_notification', []);
        
        return [
            'to' => $data['to'],
            'subject' => $data['subject'] ?? 'Bildirim',
            'content' => $data['content'] ?? '',
            'type' => $data['type'] ?? 'notification',
            'from_email' => $data['from_email'] ?? ($config['default_from_email'] ?? env('MAIL_FROM_ADDRESS', 'noreply@example.com')),
            'from_name' => $data['from_name'] ?? ($config['default_from_name'] ?? env('MAIL_FROM_NAME', 'Application')),
            'metadata' => $data['metadata'] ?? [],
        ];
    }

    /**
     * Mail log kaydı oluştur
     */
    private function createMailLog(array $mailData): MailLog
    {
        return MailLog::create([
            'recipient' => $mailData['to'],
            'subject' => $mailData['subject'],
            'content' => $mailData['content'],
            'type' => $mailData['type'],
            'status' => MailStatus::PENDING,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $mailData['metadata'],
            'retry_count' => 0,
        ]);
    }

    /**
     * Mail gönder
     */
    private function sendMail(array $mailData): bool
    {
        try {
            Mail::raw($mailData['content'], function (Message $message) use ($mailData) {
                $message->to($mailData['to'])
                        ->subject($mailData['subject'])
                        ->from($mailData['from_email'], $mailData['from_name']);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Mail gönderim hatası', [
                'error' => $e->getMessage(),
                'recipient' => $mailData['to']
            ]);
            return false;
        }
    }

    /**
     * Test mail içeriği
     */
    private function getTestMailContent(): string
    {
        return "Merhaba,\n\n" .
               "Mail sistemi başarıyla çalışıyor. Bu mail, sistem ayarlarınızın doğru yapılandırıldığını onaylar.\n\n" .
               "Teknik Destek Ekibi\n" . config('app.name');
    }

    /**
     * Başarısız mailleri yeniden dene
     */
    public function retryFailedMails(): int
    {
        $failedMails = MailLog::failed()
            ->where('retry_count', '<', config('modules.MailNotification.settings.mail_notification.max_retry_attempts', 3))
            ->get();

        $retriedCount = 0;

        foreach ($failedMails as $mailLog) {
            if ($mailLog->canRetry()) {
                $mailLog->incrementRetryCount();
                
                $mailData = [
                    'to' => $mailLog->recipient,
                    'subject' => $mailLog->subject,
                    'content' => $mailLog->content,
                    'type' => $mailLog->type,
                ];

                if ($this->sendMail($mailData)) {
                    $mailLog->markAsSent();
                    $retriedCount++;
                }
            }
        }

        return $retriedCount;
    }

    /**
     * Tekil mail yeniden dene
     */
    public function retrySingleMail(MailLog $mailLog): bool
    {
        if (!$mailLog->canRetry()) {
            return false;
        }

        $mailLog->incrementRetryCount();
        
        $mailData = [
            'to' => $mailLog->recipient,
            'subject' => $mailLog->subject,
            'content' => $mailLog->content,
            'type' => $mailLog->type,
        ];

        if ($this->sendMail($mailData)) {
            $mailLog->markAsSent();
            return true;
        }

        return false;
    }

    /**
     * Mail istatistikleri
     */
    public function getStats(): array
    {
        return [
            'total' => MailLog::count(),
            'sent' => MailLog::sent()->count(),
            'failed' => MailLog::failed()->count(),
            'pending' => MailLog::pending()->count(),
            'today' => MailLog::whereDate('created_at', today())->count(),
            'this_week' => MailLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => MailLog::whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * Eski mail loglarını temizle
     */
    public function cleanupOldLogs(int $days = 90): int
    {
        $cutoffDate = now()->subDays($days);
        return MailLog::where('created_at', '<', $cutoffDate)->delete();
    }
} 