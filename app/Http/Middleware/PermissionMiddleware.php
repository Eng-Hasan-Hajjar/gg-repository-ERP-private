<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = auth()->user();

        // المستخدم غير مسجل دخول
        if (!$user) {
            abort(403, 'غير مصرح لك بالوصول');
        }

        // سوبر أدمين يتجاوز كل الصلاحيات
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // التحقق من الصلاحية
        if (!$user->hasPermission($permission)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
