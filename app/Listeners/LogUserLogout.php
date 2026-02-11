<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\AuditLog;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        AuditLog::create([
            'user_id'    => $event->user->id,
            'action'     => 'logout',
            'model'      => 'User',
            'model_id'   => $event->user->id,
            'description'=> 'تسجيل خروج من النظام',
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
