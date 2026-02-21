<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {
        // المدة المسموحة بدون نشاط (بالثواني)
        $timeout = 600; // دقيقة واحدة — غيّرها لاحقاً كما تريد

        if (Auth::check()) {

            $lastActivity = session('last_activity');

            if ($lastActivity) {

                $inactiveTime = now()->timestamp - $lastActivity;

                if ($inactiveTime > $timeout) {

                    // تسجيل logout حقيقي في جدول الجلسات
                    \App\Models\UserSession::where('user_id', Auth::id())
                        ->whereNull('logout_at')
                        ->latest('login_at')
                        ->first()?->update([
                            'logout_at' => now(),
                             'last_activity' => now(), // ← مهم جداً
                        ]);

                    Auth::logout();

                    session()->invalidate();
                    session()->regenerateToken();



                        // ✅ تحديث النشاط في SESSION
    session(['last_activity' => now()->timestamp]);

    // ✅ تحديث النشاط في DATABASE (هذا كان ناقص!)
    \App\Models\UserSession::where('user_id', Auth::id())
        ->whereNull('logout_at')
        ->latest('login_at')
        ->first()?->update([
            'last_activity' => now()
        ]);


$currentSessionId = session()->getId();

$valid = \App\Models\UserSession::where('user_id', Auth::id())
    ->where('session_id', $currentSessionId)
    ->whereNull('logout_at')
    ->exists();

if (!$valid) {
    Auth::logout();
    session()->invalidate();
    return redirect('/login');
}




                    return redirect('/login')
                        ->with('message', 'تم تسجيل الخروج بسبب عدم النشاط');
                }
            }

            // تحديث آخر نشاط
            session(['last_activity' => now()->timestamp]);
        }

        return $next($request);
    }
}