<?php

use App\Models\AuditLog;

if (!function_exists('audit_log')) {

    function audit_log($action, $description = null, $model = null, $modelId = null)
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'description' => $description,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // لا نكسر النظام إذا فشل التسجيل
        }
    }
}







if (! function_exists('t')) {

    function t(string $key): string
    {
        static $translations = null;

        if ($translations === null) {
            $translations = require resource_path('lang/ar/modules.php');
        }

        return $translations[$key] ?? $key;
    }
}