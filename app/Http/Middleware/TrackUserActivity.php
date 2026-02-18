<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\UserSession;

class TrackUserActivity
{
public function handle($request, Closure $next)
{
    if (auth()->check()) {

        $session = UserSession::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'work_date' => today(),
            ],
            [
                'login_at' => now(),
                'last_activity' => now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        $minutes = now()->diffInMinutes($session->last_activity);

        if ($minutes >= 1) {
            $session->increment('online_minutes', $minutes);
        }

        $session->update([
            'last_activity' => now()
        ]);
    }

    return $next($request);
}

}
