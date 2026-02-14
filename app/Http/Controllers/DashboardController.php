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

        /**
         * ممكن لاحقاً نضيف KPIs أو Charts هنا
         */
        return view('dashboard', [
            'highlights'  => $highlights,
            'isDashboard' => true, // مهم لتفعيل Layout الخاص بالداشبورد
        ]);
    }
}
