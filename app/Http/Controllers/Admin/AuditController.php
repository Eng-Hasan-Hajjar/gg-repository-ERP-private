<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('ip')) {
            $query->where('ip', 'like', '%' . $request->ip . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%$s%")
                    ->orWhere('ip', 'like', "%$s%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"));
            });
        }

        // إحصائيات سريعة
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'created' => AuditLog::where('action', 'created')->whereDate('created_at', today())->count(),
            'deleted' => AuditLog::where('action', 'deleted')->whereDate('created_at', today())->count(),
        ];

        // قائمة النماذج الفريدة للفلتر
        $models = AuditLog::select('model')
            ->distinct()
            ->whereNotNull('model')
            ->pluck('model');

        $logs = $query->paginate(25)->withQueryString();
        $users = User::orderBy('name')->get();



        $hasFilter = request()->hasAny(['user_id', 'action', 'model', 'ip', 'date_from', 'date_to', 'search'])
            && array_filter(request()->only(['user_id', 'action', 'model', 'ip', 'date_from', 'date_to', 'search']));

        return view('admin.audit.index', compact('logs', 'users', 'stats', 'models', 'hasFilter'));


    }
}