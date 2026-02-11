use App\Models\AuditLog;

function audit_log($action, $description = null, $model = null, $modelId = null)
{
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'model' => $model,
        'model_id' => $modelId,
        'description' => $description,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
