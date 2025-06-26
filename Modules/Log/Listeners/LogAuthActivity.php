<?php

namespace Modules\Log\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Modules\Log\Services\ActivityLogService;

class LogAuthActivity
{
    public function __construct(
        private ActivityLogService $activityLogService
    ) {}

    public function handleLogin(Login $event): void
    {
        $this->activityLogService->logAuthActivity('Sisteme giriş yaptı', $event->user->id);
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            $this->activityLogService->logAuthActivity('Sistemden çıkış yaptı', $event->user->id);
        }
    }

    public function handleRegistered(Registered $event): void
    {
        $this->activityLogService->logAuthActivity('Sisteme kayıt oldu', $event->user->id);
    }
} 