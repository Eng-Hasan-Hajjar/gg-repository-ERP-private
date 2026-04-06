<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportsService;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Student;
use App\Models\Lead;
use App\Models\Exam;
use App\Models\Cashbox;
use App\Models\CashboxTransaction;

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
         * إحصائيات اليوم
         */
        $todayStats = $reports->todayStats();

        /**
         * إحصائيات الموارد البشرية
         */
        $hrStats = [
            'trainers' => Employee::where('type', 'trainer')->count(),
            'employees' => Employee::where('type', 'employee')->count(),
            'active_trainers' => Employee::where('type', 'trainer')->where('status', 'active')->count(),
            'active_employees' => Employee::where('type', 'employee')->where('status', 'active')->count(),
        ];

        /**
         * إحصائيات المستخدمين
         */
        $onlineUsers = User::whereHas(
            'sessions',
            fn($q) =>
            $q->whereNull('logout_at')->where('last_activity', '>=', now()->subSeconds(1200))
        )->count();

        $totalUsers = User::count();

        $todayLogins = UserSession::whereDate('login_at', today())->distinct('user_id')->count();

        /**
         * إحصائيات الطلاب
         */
        $studentStats = [
            'total' => Student::count(),
            'confirmed' => Student::where('is_confirmed', true)->count(),
            'pending' => Student::where('registration_status', 'pending')->count(),
            'today_new' => Student::whereDate('created_at', today())->count(),
        ];

        /**
         * إحصائيات CRM / العملاء المحتملين
         */
        $leadStats = [
            'total' => Lead::count(),
            'new' => Lead::where('stage', 'new')->count(),
            'followup' => Lead::where('stage', 'follow_up')->count(),
            'converted' => Lead::where('registration_status', 'registered')->count(),
        ];

        /**
         * إحصائيات الامتحانات
         */
        $examStats = [
            'total' => Exam::count(),
            'upcoming' => Exam::where('exam_date', '>=', today())->count(),
            'done' => Exam::where('exam_date', '<', today())->count(),
            'this_month' => Exam::whereMonth('exam_date', now()->month)->whereYear('exam_date', now()->year)->count(),
        ];

        /**
         * إحصائيات الصناديق المالية
         */
        $cashboxStats = [
            'total' => Cashbox::count(),
            'active' => Cashbox::where('status', 'active')->count(),
            'today_trx' => CashboxTransaction::whereDate('trx_date', today())->count(),
            'today_amount' => CashboxTransaction::whereDate('trx_date', today())->where('status', 'posted')->sum('amount'),
        ];



        /**
         * إحصائيات الأصول
         */
        $assetStats = [
            'total' => \App\Models\Asset::count(),
            'good' => \App\Models\Asset::where('condition', 'good')->count(),
            'maintenance' => \App\Models\Asset::where('condition', 'maintenance')->count(),
            'retired' => \App\Models\Asset::where('condition', 'retired')->count(),
        ];

        /**
         * إحصائيات الفروع
         */
        $branchStats = [
            'total' => \App\Models\Branch::count(),
            'students' => \App\Models\Student::count(),
            'employees' => \App\Models\Employee::count(),
            'assets' => \App\Models\Asset::count(),
        ];

        /**
         * إحصائيات الدبلومات
         */
        $diplomaStats = [
            'total' => \App\Models\Diploma::count(),
            'active' => \App\Models\Diploma::where('is_active', true)->count(),
            'online' => \App\Models\Diploma::where('type', 'online')->count(),
            'onsite' => \App\Models\Diploma::where('type', 'onsite')->count(),
        ];

        return view('dashboard', [
            'highlights' => $highlights,
            'todayStats' => $todayStats,
            'isDashboard' => true,
            'hrStats' => $hrStats,
            'onlineUsers' => $onlineUsers,
            'totalUsers' => $totalUsers,
            'todayLogins' => $todayLogins,
            'studentStats' => $studentStats,
            'leadStats' => $leadStats,
            'examStats' => $examStats,
            'cashboxStats' => $cashboxStats,
            'assetStats' => $assetStats,
            'branchStats' => $branchStats,
            'diplomaStats' => $diplomaStats,
        ]);
    }
}