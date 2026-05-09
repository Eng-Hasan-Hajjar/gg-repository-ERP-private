<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeByRoute
{
    public function handle($request, Closure $next)
    {
        $route = $request->route();

        if (!$route || !$route->getName()) {
            return $next($request);
        }

        $routeName = $route->getName();

        // السماح بالروابط العامة
        $publicRoutes = [
            'login',
            'password.request',
            'password.email',
            'password.reset',
            'verification.notice',
            'location.store',
            'location.skip',
            // فورم طلب الميديا العام
            'media.public.form',
            'media.public.store',
            'media.public.thanks',
            'csrf.refresh',
        ];

        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }

        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Super Admin bypass
        if ($user->roles()->where('name', 'super_admin')->exists()) {
            return $next($request);
        }

        $permission = $this->mapRouteToPermission($routeName);

        if ($permission && !$user->hasPermission($permission)) {
            abort(403);
        }

        return $next($request);
    }

    private function mapRouteToPermission($routeName)
    {
        if (!$routeName)
            return null;

        /*
        |------------------------------------------------------------------
        | تحويل خاص لبعض الأقسام التي اسم الصلاحية لا يتطابق مع اسم الـ route
        | مثال: media.index → view_media_requests (وليس view_media)
        |------------------------------------------------------------------
        */
        $customMap = [
            // طلبات الميديا
            'media.index' => 'view_media_requests',
            'media.create' => 'view_media_requests',
            'media.store' => 'view_media_requests',
            'media.show' => 'view_media_requests',
            'media.update' => 'view_media_requests',
            'media.cleanup' => 'view_media_requests',

            // قائمة النشر
            'media.publish.index' => 'view_media_requests',
            'media.publish.create' => 'view_media_requests',
            'media.publish.store' => 'view_media_requests',
            'media.publish.edit' => 'view_media_requests',
            'media.publish.update' => 'view_media_requests',
            'media.publish.destroy' => 'view_media_requests',

            // إدارة البرامج
            'programs.management.index' => 'view_program_management',
            'programs.management.edit' => 'view_program_management',
            'programs.management.update' => 'view_program_management',
            'programs.management.show' => 'view_program_management',

            // طلبات الأصول اللوجستية
            'asset-requests.index' => 'submit_asset_request',
            'asset-requests.create' => 'submit_asset_request',
            'asset-requests.store' => 'submit_asset_request',
            'asset-requests.approve' => 'manage_assets',
            'asset-requests.reject' => 'manage_assets',
            'asset-requests.destroy' => 'submit_asset_request',

            // مجموعات الرؤية
            'admin.visibility-groups.index' => 'manage_roles',
            'admin.visibility-groups.create' => 'manage_roles',
            'admin.visibility-groups.store' => 'manage_roles',
            'admin.visibility-groups.edit' => 'manage_roles',
            'admin.visibility-groups.update' => 'manage_roles',
            'admin.visibility-groups.destroy' => 'manage_roles',

            // الذمم المالية
            'debts.index' => 'view_debts',
            'debts.show' => 'view_debts',
            'debts.excel' => 'view_debts',
            'debts.student.excel' => 'view_debts',

            // كشف الحسابات
            'accounts.statement.index' => 'view_account_statement',
            'accounts.statement.excel' => 'view_account_statement',


        ];

        if (isset($customMap[$routeName])) {
            return $customMap[$routeName];
        }

        /*
        |------------------------------------------------------------------
        | التحويل التلقائي العام: module.action → view_module / create_module / ...
        |------------------------------------------------------------------
        */
        $parts = explode('.', $routeName);

        if (count($parts) < 2)
            return null;

        $module = $parts[0];
        $action = $parts[1];

        $map = [
            'index' => 'view_' . $module,
            'show' => 'view_' . $module,
            'create' => 'create_' . $module,
            'store' => 'create_' . $module,
            'edit' => 'edit_' . $module,
            'update' => 'edit_' . $module,
            'destroy' => 'delete_' . $module,
        ];

        return $map[$action] ?? null;
    }
}