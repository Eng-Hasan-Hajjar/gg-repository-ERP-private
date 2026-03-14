<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;

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


        return view('dashboard', [
            'highlights'  => $highlights,
            'todayStats'=>$todayStats,
            'isDashboard' => true, // مهم لتفعيل Layout الخاص بالداشبورد
        ]);
    }
}
