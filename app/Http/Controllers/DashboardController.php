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

use App\Models\PaymentPlan;
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




        // إحصائيات المهام
        $taskStats = [
            'total' => \App\Models\Task::count(),
            'todo' => \App\Models\Task::where('status', 'todo')->count(),
            'done' => \App\Models\Task::where('status', 'done')->count(),
            'overdue' => \App\Models\Task::where('status', '!=', 'done')
                ->whereDate('due_date', '<', today())->count(),
        ];

        // إحصائيات التقارير
        $taskReportStats = [
            'total' => \App\Models\TaskReport::count(),
            'today' => \App\Models\TaskReport::whereDate('report_date', today())->count(),
            'weekly' => \App\Models\TaskReport::where('report_type', 'weekly')->count(),
            'monthly' => \App\Models\TaskReport::where('report_type', 'monthly')->count(),
        ];

        // إحصائيات الميديا
        $mediaStats = [
            'total' => \App\Models\MediaRequest::count(),
            'pending' => \App\Models\MediaRequest::where('design_done', false)->count(),
            'done' => \App\Models\MediaRequest::where('design_done', true)->count(),
            'this_month' => \App\Models\MediaRequest::whereMonth('created_at', now()->month)->count(),
        ];




        // إحصائيات الدوام والإجازات
        $attendanceStats = [
            'present_today' => \App\Models\AttendanceRecord::whereDate('work_date', today())
                ->whereIn('status', ['present', 'late'])->count(),
            'absent_today' => \App\Models\AttendanceRecord::whereDate('work_date', today())
                ->where('status', 'absent')->count(),
            'pending_leaves' => \App\Models\LeaveRequest::where('status', 'pending')->count(),
            'approved_leaves' => \App\Models\LeaveRequest::where('status', 'approved')
                ->whereDate('start_date', '>=', today())->count(),
        ];

        // إحصائيات إدارة البرامج — من جدول ProgramManagement
        $programStats = [
            'total' => \App\Models\ProgramManagement::count(),
            'online' => \App\Models\ProgramManagement::whereHas('diploma', fn($q) =>
                $q->where('type', 'online'))->count(),
            'onsite' => \App\Models\ProgramManagement::whereHas('diploma', fn($q) =>
                $q->where('type', 'onsite'))->count(),
            'inactive' => \App\Models\Diploma::where('is_active', false)->count(),
        ];



        // إحصائيات اللوحة الرئيسية
        $dashboardStats = [
            'total_students' => \App\Models\Student::count(),
            'revenue_today' => \App\Models\CashboxTransaction::whereDate('trx_date', today())
                ->where('status', 'posted')
                ->where('type', 'in')
                ->sum('amount'),
            'active_employees' => \App\Models\Employee::where('status', 'active')->count(),
            'overdue_tasks' => \App\Models\Task::where('status', '!=', 'done')
                ->whereDate('due_date', '<', today())->count(),
        ];




        // عملاء معلقون أكثر من 48 ساعة
        $urgentLeads = \App\Models\Lead::where('registration_status', 'pending')
            ->whereNotIn('stage', ['rejected', 'registered', 'postponed'])
            ->where('created_at', '<=', now()->subHours(48))
            ->count();

        // آخر نشاط بالعربي
        $lastActivityAr = $lastActivity
            ? \Carbon\Carbon::parse($lastActivity->created_at)->locale('ar')->diffForHumans()
            : '—';


        // نسب الـ Progress Bars
        $convRate = $leadStats['total'] > 0
            ? round(($leadStats['converted'] / $leadStats['total']) * 100) : 0;

        $confRate = $studentStats['total'] > 0
            ? round(($studentStats['confirmed'] / $studentStats['total']) * 100) : 0;

        $doneRate = $taskStats['total'] > 0
            ? round(($taskStats['done'] / $taskStats['total']) * 100) : 0;



        // ── إحصائيات الذمم ──
        $studentsWithPlans = Student::whereHas('paymentPlans')->with([
            'financialAccount.transactions',
            'paymentPlans',
        ])->get();

        $debtStats = [
            'total_students' => $studentsWithPlans->count(),
            'has_debt' => 0,
            'paid' => 0,
            'total_remaining' => 0,
        ];

        foreach ($studentsWithPlans as $s) {
            $total = (float) $s->paymentPlans->sum('total_amount');
            $paid = $s->financialAccount
                ? (float) $s->financialAccount->transactions()
                    ->where('type', 'in')->where('status', 'posted')->sum('amount')
                : 0;
            $remaining = $total - $paid;

            if ($remaining > 0) {
                $debtStats['has_debt']++;
                $debtStats['total_remaining'] += $remaining;
            } else {
                $debtStats['paid']++;
            }
        }

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
            'taskStats' => $taskStats,
            'taskReportStats' => $taskReportStats,
            'mediaStats' => $mediaStats,
            'attendanceStats' => $attendanceStats,
            'programStats' => $programStats,
            'dashboardStats' => $dashboardStats,
            'urgentLeads' => $urgentLeads,
            'lastActivityAr' => $lastActivityAr,
            'convRate' => $convRate,
            'confRate' => $confRate,
            'doneRate' => $doneRate,
             'debtStats' => $debtStats,


        ]);
    }
}