<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // لو مو مسجل دخول، خليه يمر (auth middleware يتكفل)
        if (!$user) {
            return $next($request);
        }

        // إذا OTP متحقق، اسمح
        if ($user->email_otp_verified_at) {
            return $next($request);
        }

        // اسمح لصفحات OTP نفسها
        if ($request->routeIs('otp.*')) {
            return $next($request);
        }

        return redirect()->route('otp.show');
    }
}
