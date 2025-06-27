<?php

if (!function_exists('send_mail')) {
    /**
     * Mail gönder ve logla
     */
    function send_mail(array $data): bool
    {
        $mailDispatcher = app(\Modules\MailNotification\Services\MailDispatcherService::class);
        return $mailDispatcher->send($data);
    }
}

if (!function_exists('send_test_mail')) {
    /**
     * Test mail gönder
     */
    function send_test_mail(string $to, string $subject = 'Test Mail'): bool
    {
        $mailDispatcher = app(\Modules\MailNotification\Services\MailDispatcherService::class);
        return $mailDispatcher->sendTestMail($to, $subject);
    }
}

if (!function_exists('get_mail_stats')) {
    /**
     * Mail istatistiklerini al
     */
    function get_mail_stats(): array
    {
        $mailDispatcher = app(\Modules\MailNotification\Services\MailDispatcherService::class);
        return $mailDispatcher->getStats();
    }
} 