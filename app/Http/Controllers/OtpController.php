<?php

namespace App\Http\Controllers;

use App\Notifications\EmailOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // إذا تم التحقق مسبقًا
        if ($user->email_otp_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (!$user->email_otp_code || !$user->email_otp_expires_at) {
            return back()->withErrors(['code' => 'No verification code found. Please resend code.']);
        }

        if (now()->greaterThan($user->email_otp_expires_at)) {
            return back()->withErrors(['code' => 'Code expired. Please resend a new code.']);
        }

        // مقارنة الكود
        if (!hash_equals((string)$user->email_otp_code, (string)$request->code)) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }

        // نجاح: ثبت التحقق وامسح الكود
        $user->forceFill([
            'email_otp_verified_at' => now(),
            'email_otp_code' => null,
            'email_otp_expires_at' => null,
        ])->save();

        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        // توليد كود جديد
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_otp_verified_at' => null,
            'email_otp_code' => $code,
            'email_otp_expires_at' => now()->addMinutes(10),
        ])->save();

        $user->notify(new EmailOtpNotification($code, 10));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
