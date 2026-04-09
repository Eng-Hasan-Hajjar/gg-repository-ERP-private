<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;
use Carbon\Carbon;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {

        $timeout = 120000000000000000;

        if (Auth::check()) {

            $currentSessionId = session()->getId();

            $session = UserSession::where('session_id', $currentSessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session || $session->logout_at) {

                Auth::logout();

                session()->invalidate();

                session()->regenerateToken();

                return redirect('/login');
            }


            $userId = Auth::id();
            $currentSessionId = session()->getId();

            /*
            ======================================
            ① التحقق هل الجلسة مازالت موجودة
            (للـ Force Logout من الأدمن)
            ======================================
            */

            $validSession = \App\Models\UserSession::where('user_id', $userId)
                ->where('session_id', $currentSessionId)
                ->whereNull('logout_at')
                ->exists();

            if (!$validSession) {

                Auth::logout();

                session()->invalidate();
                session()->regenerateToken();

                return redirect('/login');
            }

            /*
            ======================================
            ② التحقق من الخمول
            ======================================
            */

            $lastActivity = session('last_activity');

            if ($lastActivity) {

                $inactiveTime = now()->timestamp - $lastActivity;

                if ($inactiveTime > $timeout) {

                    \App\Models\UserSession::where('user_id', $userId)
                        ->where('session_id', $currentSessionId)
                        ->whereNull('logout_at')
                        ->update([
                            'logout_at' => now(),
                            'last_activity' => now(),
                        ]);

                    Auth::logout();

                    session()->invalidate();
                    session()->regenerateToken();

                    return redirect('/login')
                        ->with('message', 'تم تسجيل الخروج بسبب عدم النشاط');
                }
            }

            /*
            ======================================
            ③ تحديث النشاط
            ======================================
            */

            session(['last_activity' => now()->timestamp]);

            \App\Models\UserSession::where('user_id', $userId)
                ->where('session_id', $currentSessionId)
                ->whereNull('logout_at')
                ->update([
                    'last_activity' => now()
                ]);
        }

        return $next($request);
    }



}