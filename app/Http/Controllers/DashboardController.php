<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;
use App\Models\Employee;
class DashboardController extends Controller
{
    public function index(ReportsService $reports)
    {
        /**
         * البيانات العلوية الذكية (Highlights)
         */
        $highlights = $reports->dashboardHighlights();
        $todayActivities = \App\Models\AuditLog::whereDate('created_at', today())->count();

        $lastActivity = \App\Models\AuditLog::latest()->first();
        /**
         * ممكن لاحقاً نضيف KPIs أو Charts هنا
         */

        $todayStats = $reports->todayStats();







        $hrStats = [
            'trainers' => Employee::where('type', 'trainer')->count(),
            'employees' => Employee::where('type', 'employee')->count(),

            'active_trainers' => Employee::where('type', 'trainer')
                ->where('status', 'active')
                ->count(),

            'active_employees' => Employee::where('type', 'employee')
                ->where('status', 'active')
                ->count(),
        ];



        return view('dashboard', [
            'highlights' => $highlights,
            'todayStats' => $todayStats,
            'isDashboard' => true, // مهم لتفعيل Layout الخاص بالداشبورد
         'hrStats' => $hrStats,
        ]);
    }
}
