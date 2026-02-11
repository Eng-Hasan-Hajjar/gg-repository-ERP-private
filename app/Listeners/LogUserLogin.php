<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        AuditLog::create([
            'user_id'    => $event->user->id,
            'action'     => 'login',
            'model'      => 'User',
            'model_id'   => $event->user->id,
            'description'=> 'تسجيل دخول إلى النظام',
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
