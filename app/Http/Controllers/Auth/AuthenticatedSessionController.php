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
        $request->authenticate();

        $request->session()->regenerate();


        // ✅ جلب المستخدم الحالي
        $user = Auth::user();




      $existingSession = UserSession::where('user_id', $user->id)
    ->whereNull('logout_at')
    ->latest()
    ->first();

if ($existingSession) {

    $sessionFile = storage_path('framework/sessions/' . $existingSession->session_id);

    // إذا ملف الجلسة غير موجود → يعني الجلسة ماتت فعلياً
    if (!file_exists($sessionFile)) {
        $existingSession->update([
            'logout_at' => now()
        ]);
    } else {
        Auth::logout();

        return back()->withErrors([
            'email' => 'المستخدم مسجل دخول من جهاز آخر حالياً.'
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
