<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserSession;

class TrackUserActivity
{
    public function handle($request, Closure $next)
    {
   


        // تنظيف الجلسات القديمة
        UserSession::whereNull('logout_at')
            ->where('last_activity', '<', now()->subMinutes(30))
            ->update([
                'logout_at' => now()
            ]);

        if (auth()->check()) {

            $sessionId = session()->getId();
            $userId = auth()->id();


            $route = $request->route()?->getName();

            if (!$route) {
                $route = $request->path() ?: 'dashboard';
            }

            $url = $request->fullUrl() ?: url()->current();

            $session = UserSession::where('user_id', $userId)
                ->whereNull('logout_at')
                ->latest()
                ->first();

            if (!$session) {

                UserSession::create([
                    'user_id' => $userId,
                    'login_at' => now(),
                    'session_id' => $sessionId,
                    'last_activity' => now(),
                    'work_date' => now()->toDateString(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'current_route' => $route,
                    'current_url' => $url,
                ]);

            } else {

                $session->last_activity = now();
                $session->current_route = $route;
                $session->current_url = $url;
                $session->save();
            }
        }


        return $next($request);

    }
}