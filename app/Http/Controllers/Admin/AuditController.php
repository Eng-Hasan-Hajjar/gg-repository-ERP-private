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

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب الإجراء
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // فلترة حسب الموديل
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }


        
        // فلترة حسب التاريخ
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }



        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get(); // ✅ هنا نمرر المتغير المفقود

        return view('admin.audit.index', compact('logs', 'users'));
    }
}
