<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeByRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle($request, Closure $next)
{
    // ❌ تجاهل أي Route بدون اسم
    $route = $request->route();

    if (!$route || !$route->getName()) {
        return $next($request);
    }

    // ❌ تجاهل صفحات Laravel الأساسية
    if (str_starts_with($route->getName(), 'login') ||
        str_starts_with($route->getName(), 'password') ||
        str_starts_with($route->getName(), 'verification')) {
        return $next($request);
    }

    $user = auth()->user();

    if (!$user) {
        abort(401);
    }

    // ✅ Super Admin bypass
    if ($user->roles()->where('name', 'super_admin')->exists()) {
        return $next($request);
    }

    $permission = $this->mapRouteToPermission($route->getName());

    if ($permission && !$user->hasPermission($permission)) {
        abort(403);
    }

    return $next($request);
}

private function mapRouteToPermission($routeName)
{
    if (!$routeName) return null;

    $parts = explode('.', $routeName);

    if (count($parts) < 2) return null;

    $module = $parts[0];   // students
    $action = $parts[1];   // index

    $map = [
        'index'   => 'view_' . $module,
        'show'    => 'view_' . $module,
        'create'  => 'create_' . $module,
        'store'   => 'create_' . $module,
        'edit'    => 'edit_' . $module,
        'update'  => 'edit_' . $module,
        'destroy' => 'delete_' . $module,
    ];

    return $map[$action] ?? null;
}


}
