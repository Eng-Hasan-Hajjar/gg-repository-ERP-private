<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserSession;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */


 public function store(LoginRequest $request): RedirectResponse
{
    // تحقق من صحة البيانات بدون تسجيل الدخول
    $request->authenticate();






    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.'
        ]);
    }

    // تنظيف الجلسات القديمة للمستخدم
    UserSession::where('user_id', $user->id)
        ->whereNull('logout_at')
        ->where('last_activity', '<', now()->subMinutes(5))
        ->update([
            'logout_at' => now()
        ]);

    // التحقق من وجود جلسة نشطة
    $activeSession = UserSession::where('user_id', $user->id)
        ->whereNull('logout_at')
        ->first();

    if ($activeSession) {
        return back()->withErrors([
            'email' => 'هذا الحساب مسجل دخول حالياً من جهاز آخر.'
        ]);
    }

    // الآن فقط نقوم بتسجيل الدخول
    Auth::attempt($request->only('email', 'password'));

    $request->session()->regenerate();

    $now = now();

    UserSession::create([
        'user_id' => Auth::id(),
        'login_at' => $now,
        'session_id' => session()->getId(),
        'last_activity' => $now,
        'work_date' => $now->toDateString(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->intended(route('dashboard', absolute: false));
}


/*
 public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    // تنظيف الجلسات القديمة
    UserSession::whereNull('logout_at')
        ->where('last_activity', '<', now()->subMinutes(1))
        ->update([
            'logout_at' => now()
        ]);

    // التحقق من وجود جلسة نشطة
    $existingSession = UserSession::where('user_id', $user->id)
        ->whereNull('logout_at')
        ->latest()
        ->first();

    if ($existingSession) {

        $inactive = now()->diffInMinutes($existingSession->last_activity);

        if ($inactive > 5) {

            $existingSession->update([
                'logout_at' => now()
            ]);

        } else {

            Auth::logout();

            return back()->withErrors([
                'email' => 'هذا الحساب مسجل دخول حالياً من جهاز آخر.'
            ]);
        }
    }

  
            // ✅ إنشاء جلسة تتبع

        $now = now();

        // اليوم الفعلي حسب الساعة الحالية
        $workDate = $now->toDateString();

        UserSession::create([
            'user_id' => $user->id,
            'login_at' => now(),
            'session_id' => session()->getId(), // 👈 مهم
            'last_activity' => now(), // ← أضف هذا
            'work_date' => $workDate,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);



        return redirect()->intended(route('dashboard', absolute: false));
}

*/


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {


        $user = auth()->user(); // ⚠️ خزن المستخدم قبل logout

        if ($user) {
            UserSession::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first()?->update([
                        'logout_at' => now()
                    ]);
        }



        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
