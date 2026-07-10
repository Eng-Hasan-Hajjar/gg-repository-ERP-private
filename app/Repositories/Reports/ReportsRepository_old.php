<?php

namespace App\Repositories\Reports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportsRepository
{
    private function has(string $table): bool
    {
        return Schema::hasTable($table);
    }

    private function applyBranch(?int $branchId, string $tableAliasOrColumn = 'branch_id'): callable
    {
        return function ($q) use ($branchId, $tableAliasOrColumn) {
            if ($branchId) {
                $q->where($tableAliasOrColumn, $branchId);
            }
        };
    }

    // =========================
    // Students
    // =========================
    public function studentsTotal(?int $branchId = null): int
    {
        if (!$this->has('students'))
            return 0;
        return (int) DB::table('students')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
    }

    public function studentsConfirmed(?int $branchId = null): int
    {
        if (!$this->has('students'))
            return 0;

        // supports either is_confirmed boolean OR registration_status
        $q = DB::table('students')->when($branchId, fn($x) => $x->where('branch_id', $branchId));

        if (Schema::hasColumn('students', 'is_confirmed')) {
            $q->where('is_confirmed', 1);
        } elseif (Schema::hasColumn('students', 'registration_status')) {
            $q->where('registration_status', 'confirmed');
        }

        return (int) $q->count();
    }

    public function studentsUnconfirmed(?int $branchId = null): int
    {
        if (!$this->has('students'))
            return 0;

        $q = DB::table('students')->when($branchId, fn($x) => $x->where('branch_id', $branchId));

        if (Schema::hasColumn('students', 'is_confirmed')) {
            $q->where('is_confirmed', 0);
        } elseif (Schema::hasColumn('students', 'registration_status')) {
            $q->where('registration_status', '!=', 'confirmed');
        }

        return (int) $q->count();
    }

    // =========================
    // Employees
    // =========================
    public function employeesTotal(?int $branchId = null): int
    {
        // غيّر الاسم إذا جدولك اسمه staff/employees/users...
        $table = $this->has('employees') ? 'employees' : ($this->has('staff') ? 'staff' : null);
        if (!$table)
            return 0;

        return (int) DB::table($table)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
    }

    public function employeesActive(?int $branchId = null): int
    {
        $table = $this->has('employees') ? 'employees' : ($this->has('staff') ? 'staff' : null);
        if (!$table)
            return 0;

        $q = DB::table($table)->when($branchId, fn($x) => $x->where('branch_id', $branchId));

        // يدعم is_active أو status
        if (Schema::hasColumn($table, 'is_active')) {
            $q->where('is_active', 1);
        } elseif (Schema::hasColumn($table, 'status')) {
            $q->where('status', 'active');
        }

        return (int) $q->count();
    }

    public function employeesInactive(?int $branchId = null): int
    {
        $table = $this->has('employees') ? 'employees' : ($this->has('staff') ? 'staff' : null);
        if (!$table)
            return 0;

        $q = DB::table($table)->when($branchId, fn($x) => $x->where('branch_id', $branchId));

        if (Schema::hasColumn($table, 'is_active')) {
            $q->where('is_active', 0);
        } elseif (Schema::hasColumn($table, 'status')) {
            $q->where('status', '!=', 'active');
        }

        return (int) $q->count();
    }

    // =========================
    // Attendance "today"
    // =========================
    public function employeesPresentToday(?int $branchId = null): int
    {
        // غيّر إذا جدولك اسمه attendances
        if (!$this->has('attendances'))
            return 0;

        $q = DB::table('attendances');

        if (Schema::hasColumn('attendances', 'date')) {
            $q->whereDate('date', now()->toDateString());
        } elseif (Schema::hasColumn('attendances', 'created_at')) {
            $q->whereDate('created_at', now()->toDateString());
        }

        if ($branchId && Schema::hasColumn('attendances', 'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        // إذا عندك present/absent
        if (Schema::hasColumn('attendances', 'status')) {
            $q->where('status', 'present');
        }

        // unique employee_id if موجود
        if (Schema::hasColumn('attendances', 'employee_id')) {
            return (int) $q->distinct('employee_id')->count('employee_id');
        }

        return (int) $q->count();
    }

    public function employeesAbsentToday(?int $branchId = null): int
    {
        if (!$this->has('attendances'))
            return 0;

        $q = DB::table('attendances');

        if (Schema::hasColumn('attendances', 'date')) {
            $q->whereDate('date', now()->toDateString());
        } elseif (Schema::hasColumn('attendances', 'created_at')) {
            $q->whereDate('created_at', now()->toDateString());
        }

        if ($branchId && Schema::hasColumn('attendances', 'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        if (Schema::hasColumn('attendances', 'status')) {
            $q->where('status', 'absent');
        } else {
            // إذا ما في status ما في طريقة نعرف absent
            return 0;
        }

        if (Schema::hasColumn('attendances', 'employee_id')) {
            return (int) $q->distinct('employee_id')->count('employee_id');
        }

        return (int) $q->count();
    }

    // =========================
    // Tasks "today"
    // =========================
    public function tasksDueToday(?int $branchId = null): int
    {
        // غيّر إذا جدولك اسمه tasks
        if (!$this->has('tasks'))
            return 0;

        $q = DB::table('tasks');

        // due_date / date / deadline
        if (Schema::hasColumn('tasks', 'due_date')) {
            $q->whereDate('due_date', now()->toDateString());
        } elseif (Schema::hasColumn('tasks', 'deadline')) {
            $q->whereDate('deadline', now()->toDateString());
        } elseif (Schema::hasColumn('tasks', 'date')) {
            $q->whereDate('date', now()->toDateString());
        } else {
            return 0;
        }

        if ($branchId && Schema::hasColumn('tasks', 'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        return (int) $q->count();
    }

    // =========================
    // Finance Boxes / Payments
    // =========================
    public function boxesTotal(?int $branchId = null): int
    {
        // غيّر إذا جدولك اسمه boxes/cash_boxes/financial_boxes
        $table = $this->has('boxes') ? 'boxes'
            : ($this->has('financial_boxes') ? 'financial_boxes'
                : ($this->has('cash_boxes') ? 'cash_boxes' : null));

        if (!$table)
            return 0;

        return (int) DB::table($table)
            ->when($branchId, function ($q) use ($branchId, $table) {
                if (Schema::hasColumn($table, 'branch_id'))
                    $q->where('branch_id', $branchId);
            })
            ->count();
    }

    public function paymentsSum(string $from, string $to, ?int $branchId = null): float
    {
        // غيّر إذا جدول الدفعات اسمه payments/transactions/invoices
        $table = $this->has('payments') ? 'payments'
            : ($this->has('transactions') ? 'transactions' : null);

        if (!$table)
            return 0;

        $q = DB::table($table);

        // amount column
        $amountCol = Schema::hasColumn($table, 'amount') ? 'amount' : (Schema::hasColumn($table, 'total') ? 'total' : null);
        if (!$amountCol)
            return 0;

        // date column
        $dateCol = Schema::hasColumn($table, 'paid_at') ? 'paid_at'
            : (Schema::hasColumn($table, 'date') ? 'date'
                : (Schema::hasColumn($table, 'created_at') ? 'created_at' : null));

        if (!$dateCol)
            return 0;

        $q->whereDate($dateCol, '>=', $from)->whereDate($dateCol, '<=', $to);

        if ($branchId && Schema::hasColumn($table, 'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        return (float) $q->sum($amountCol);
    }

    // =========================
    // Simple chart: Students per branch
    // =========================
    public function studentsPerBranch(): array
    {
        if (!$this->has('students') || !$this->has('branches'))
            return [];

        // depends on branches.id + branches.name
        return DB::table('branches')
            ->leftJoin('students', 'students.branch_id', '=', 'branches.id')
            ->select('branches.name as branch', DB::raw('COUNT(students.id) as total'))
            ->groupBy('branches.name')
            ->orderBy('branches.name')
            ->get()
            ->map(fn($r) => ['branch' => $r->branch, 'total' => (int) $r->total])
            ->toArray();
    }



    // =========================
// REAL-TIME EXECUTIVE DATA
// =========================

    public function revenueToday(?int $branchId = null): float
    {
        $table = $this->has('payments') ? 'payments'
            : ($this->has('transactions') ? 'transactions' : null);

        if (!$table)
            return 0;

        $amountCol = Schema::hasColumn($table, 'amount') ? 'amount'
            : (Schema::hasColumn($table, 'total') ? 'total' : null);

        $dateCol = Schema::hasColumn($table, 'paid_at') ? 'paid_at'
            : (Schema::hasColumn($table, 'date') ? 'date' : 'created_at');

        $q = DB::table($table)
            ->whereDate($dateCol, now()->toDateString());

        if ($branchId && Schema::hasColumn($table, 'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        return (float) $q->sum($amountCol);
    }

    public function systemHealthStatus(): string
    {
        // بسيط وعملي
        $lastAudit = DB::table('audit_logs')->latest()->first();

        if (!$lastAudit)
            return 'unknown';

        $minutes = now()->diffInMinutes($lastAudit->created_at);

        if ($minutes <= 10)
            return 'online';
        if ($minutes <= 60)
            return 'degraded';

        return 'issues';
    }

    public function latestAudit(int $limit = 5): array
    {
        return DB::table('audit_logs')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn($l) => [
                'time' => $l->created_at,
                'action' => $l->action,
                'model' => $l->model,
                'description' => $l->description,
            ])->toArray();
    }








    private function kpiWithGrowth($current, $previous)
    {
        if ($previous == 0) {
            return [
                'value' => $current,
                'growth' => 0
            ];
        }

        $growth = (($current - $previous) / $previous) * 100;

        return [
            'value' => $current,
            'growth' => round($growth, 2)
        ];
    }





    // =========================
// Revenue Per Branch (Cashbox Transactions)
// =========================
    public function revenuePerBranch(string $from, string $to, ?int $branchId = null): array
    {
        if (!$this->has('cashbox_transactions') || !$this->has('cashboxes') || !$this->has('branches')) {
            return [];
        }

        $query = DB::table('branches')
            ->leftJoin('cashboxes', 'cashboxes.branch_id', '=', 'branches.id')
            ->leftJoin('cashbox_transactions', function ($join) use ($from, $to) {
                $join->on('cashbox_transactions.cashbox_id', '=', 'cashboxes.id')
                    ->where('cashbox_transactions.type', 'in')
                    ->where('cashbox_transactions.status', 'posted')
                    ->whereDate('cashbox_transactions.trx_date', '>=', $from)
                    ->whereDate('cashbox_transactions.trx_date', '<=', $to);
            })
            ->select(
                'branches.name as branch',
                DB::raw('COALESCE(SUM(cashbox_transactions.amount),0) as total')
            )
            ->groupBy('branches.name')
            ->orderBy('branches.name');

        if ($branchId) {
            $query->where('branches.id', $branchId);
        }

        return $query->get()
            ->map(fn($r) => [
                'branch' => $r->branch,
                'total' => (float) $r->total,
            ])
            ->toArray();
    }





    // =========================
// Students Monthly Growth
// =========================
    public function studentsGrowth(): array
    {
        if (!$this->has('students'))
            return [];

        return DB::table('students')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(id) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month,
                'total' => (int) $r->total
            ])
            ->toArray();
    }





    public function studentsGrowthByMonth(int $months = 12): array
    {
        if (!$this->has('students'))
            return [];

        return DB::table('students')
            ->selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month_key,
            DATE_FORMAT(created_at, '%m/%Y') as month_label,
            COUNT(*) as total
        ")
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month_label,
                'total' => (int) $r->total
            ])
            ->toArray();
    }





    public function systemAlerts(?int $limit = null): array
    {
        // ── جلب إعدادات الإشعارات من DB ──
        $followupHours = (int) \App\Models\SystemSetting::get('alert_followup_hours', 48);
        $warningHours = (int) \App\Models\SystemSetting::get('alert_warning_hours', 24);

        $now = now();
        $cutoff48 = $now->copy()->subHours($followupHours)->toDateTimeString();
        $cutoff24 = $now->copy()->subHours($warningHours)->toDateTimeString();

        $alerts = [];

        // =============================
        // ⏰ عملاء معلّقون
        // =============================
        if ($this->has('leads')) {

            // ✅ لا تُعرّف $now و$cutoff48 و$cutoff24 هنا مجدداً — استخدم المعرّفة أعلاه

            $urgentLeads = DB::table('leads')
                ->leftJoin('lead_followups', 'lead_followups.lead_id', '=', 'leads.id')
                ->select(
                    'leads.id',
                    'leads.full_name',
                    'leads.first_contact_date',
                    'leads.created_at as lead_created',
                    DB::raw('MAX(lead_followups.created_at) as last_followup_at')
                )
                ->where('leads.registration_status', 'pending')
                ->whereNotIn('leads.stage', ['rejected', 'registered', 'postponed'])
                ->groupBy('leads.id', 'leads.full_name', 'leads.first_contact_date', 'leads.created_at')
                ->havingRaw('
                (last_followup_at IS NULL AND COALESCE(leads.first_contact_date, leads.created_at) <= ?)
                OR
                (last_followup_at IS NOT NULL AND last_followup_at <= ?)
            ', [$cutoff48, $cutoff48])
                ->orderBy('leads.created_at')
                ->get();

            foreach ($urgentLeads as $lead) {
                $sinceDate = $lead->last_followup_at
                    ? \Carbon\Carbon::parse($lead->last_followup_at)
                    : \Carbon\Carbon::parse($lead->first_contact_date ?? $lead->lead_created)->startOfDay();

                $daysDiff = (int) $now->copy()->startOfDay()->diffInDays($sinceDate->copy()->startOfDay());
                $hoursDiff = (int) $now->diffInHours($sinceDate);
                $timeLabel = $daysDiff >= 1
                    ? "منذ {$daysDiff} " . ($daysDiff == 1 ? 'يوم' : 'أيام')
                    : "منذ {$hoursDiff} ساعة";

                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'bi-exclamation-triangle-fill',
                    'message' => "{$lead->full_name} — لم تتم المتابعة ({$timeLabel})",
                    'time' => $lead->last_followup_at ?? $lead->lead_created,
                    'url' => route('leads.show', $lead->id),
                    'roles' => ['super_admin', 'manager_crm_sales', 'staff_crm'],
                    'permissions' => [],
                ];
            }

            $warningLeads = DB::table('leads')
                ->leftJoin('lead_followups', 'lead_followups.lead_id', '=', 'leads.id')
                ->select(
                    'leads.id',
                    'leads.full_name',
                    'leads.first_contact_date',
                    'leads.created_at as lead_created',
                    DB::raw('MAX(lead_followups.created_at) as last_followup_at')
                )
                ->where('leads.registration_status', 'pending')
                ->whereNotIn('leads.stage', ['rejected', 'registered', 'postponed'])
                ->groupBy('leads.id', 'leads.full_name', 'leads.first_contact_date', 'leads.created_at')
                ->havingRaw('
                (last_followup_at IS NULL AND COALESCE(leads.first_contact_date, leads.created_at) BETWEEN ? AND ?)
                OR
                (last_followup_at IS NOT NULL AND last_followup_at BETWEEN ? AND ?)
            ', [$cutoff48, $cutoff24, $cutoff48, $cutoff24])
                ->orderBy('leads.created_at')
                ->get();

            foreach ($warningLeads as $lead) {
                $sinceDate = $lead->last_followup_at
                    ? \Carbon\Carbon::parse($lead->last_followup_at)
                    : \Carbon\Carbon::parse($lead->first_contact_date ?? $lead->lead_created)->startOfDay();

                $hoursDiff = (int) $now->diffInHours($sinceDate);

                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'bi-clock-history',
                    'message' => "{$lead->full_name} — يحتاج متابعة قريباً ({$hoursDiff} ساعة)",
                    'time' => $lead->last_followup_at ?? $lead->lead_created,
                    'url' => route('leads.show', $lead->id),
                    'roles' => ['super_admin', 'manager_crm_sales', 'staff_crm'],
                    'permissions' => [],
                ];
            }
        }



        // =============================
// طلبات الإجازة المعلقة
// =============================
        if ($this->has('leaves')) {
            $pendingLeaves = DB::table('leaves')
                ->where('status', 'pending')
                ->count();

            if ($pendingLeaves > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'bi-calendar-x',
                    'message' => "يوجد {$pendingLeaves} طلب إجازة بانتظار الموافقة",
                    'time' => now()->toDateTimeString(),
                    'url' => route('leaves.index'),
                    // ✅ super_admin أو مدير الدوام والإجازات فقط
                    'roles' => ['super_admin', 'manager_attendance'],
                    'permissions' => [],
                ];
            }

            // تفاصيل كل طلب على حدة
            $leaveRequests = DB::table('leaves')
                ->join('employees', 'employees.id', '=', 'leaves.employee_id')
                ->select(
                    'leaves.id',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.type',
                    'leaves.created_at',
                    'employees.full_name as employee_name'
                )
                ->where('leaves.status', 'pending')
                ->orderByDesc('leaves.created_at')
                ->get();

            foreach ($leaveRequests as $leave) {
                $alerts[] = [
                    'type' => 'info',
                    'icon' => 'bi-person-slash',
                    'message' => "طلب إجازة من {$leave->employee_name} — من {$leave->start_date} إلى {$leave->end_date}",
                    'time' => $leave->created_at,
                    'url' => route('leaves.index'),
                    // ✅ نفس الشرط
                    'roles' => ['super_admin', 'مدير الدوام والإجازات'],
                    'permissions' => [],
                ];
            }
        }


        // =============================
        // مهام متأخرة
        // =============================
        if ($this->has('tasks')) {
            $lateTasks = DB::table('tasks')
                ->whereDate('due_date', '<', now())
                ->where('status', '!=', 'done')
                ->count();

            if ($lateTasks > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'bi-list-check',
                    'message' => "يوجد {$lateTasks} مهام متأخرة",
                    'time' => now()->toDateTimeString(),
                    'url' => route('tasks.index', ['late' => 1]),
                    'roles' => ['super_admin', 'manager_hr'],
                    'permissions' => [],
                ];
            }
        }

        // =============================
        // تسجيل طلاب اليوم
        // =============================
        if ($this->has('students')) {
            $todayStudents = DB::table('students')
                ->whereDate('created_at', now())
                ->orderByDesc('id')
                ->get();

            foreach ($todayStudents as $s) {
                $alerts[] = [
                    'type' => 'info',
                    'icon' => 'bi-person-check',
                    'message' => "تم تسجيل طالب جديد {$s->full_name}",
                    'time' => $s->created_at,
                    'url' => route('students.show', $s->id),
                    'roles' => ['super_admin', 'manager_student_affairs'],
                    'permissions' => [],
                ];
            }
        }

        // =============================
        // تثبيت طالب
        // =============================
        if ($this->has('students')) {
            $convertedStudents = DB::table('students')
                ->whereNotNull('confirmed_at')
                ->whereDate('confirmed_at', now())
                ->orderByDesc('id')
                ->get();

            foreach ($convertedStudents as $s) {
                $alerts[] = [
                    'type' => 'success',
                    'icon' => 'bi-mortarboard',
                    'message' => "تم تثبيت الطالب بنجاح {$s->full_name}",
                    'time' => $s->confirmed_at,
                    'url' => route('students.show', $s->id),
                    'roles' => ['super_admin', 'manager_crm_sales', 'staff_crm'],
                    'permissions' => [],
                ];
            }
        }

        // =============================
        // حركات مالية
        // =============================
        if ($this->has('cashbox_transactions')) {
            $trx = DB::table('cashbox_transactions')
                ->whereDate(DB::raw('COALESCE(trx_date, created_at)'), now()->toDateString())
                ->orderByDesc('id')
                ->get();

            foreach ($trx as $t) {
                $alerts[] = [
                    'type' => 'success',
                    'icon' => 'bi-cash-coin',
                    'message' => "تم تسجيل حركة مالية بقيمة {$t->amount}",
                    'time' => $t->created_at,
                    'url' => route('cashboxes.show', $t->cashbox_id),
                    'roles' => ['super_admin', 'manager_finance'],
                    'permissions' => [],
                ];
            }
        }

        // =============================
        // ✅ طلبات اللوجستيات الجديدة
        // =============================
        if ($this->has('asset_requests')) {

            // طلبات عاجلة pending
            $urgentAssetRequests = DB::table('asset_requests')
                ->join('users', 'users.id', '=', 'asset_requests.user_id')
                ->leftJoin('branches', 'branches.id', '=', 'asset_requests.branch_id')
                ->select(
                    'asset_requests.id',
                    'asset_requests.title',
                    'asset_requests.type',
                    'asset_requests.priority',
                    'asset_requests.created_at',
                    'users.name as user_name',
                    'branches.name as branch_name'
                )
                ->where('asset_requests.status', 'pending')
                ->where('asset_requests.priority', 'urgent')
                ->orderByDesc('asset_requests.created_at')
                ->get();

            foreach ($urgentAssetRequests as $req) {
                $typeLabel = $req->type === 'purchase' ? 'شراء' : 'إصلاح';
                $branchText = $req->branch_name ? " ({$req->branch_name})" : '';

                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'bi-exclamation-circle-fill',
                    'message' => "🔴 طلب {$typeLabel} عاجل: {$req->title}{$branchText} — {$req->user_name}",
                    'time' => $req->created_at,
                    'url' => route('asset-requests.index'),
                    'roles' => ['super_admin'],
                    'permissions' => ['manage_assets'],
                ];
            }

            // طلبات عادية pending (آخر 24 ساعة)
            $normalAssetRequests = DB::table('asset_requests')
                ->join('users', 'users.id', '=', 'asset_requests.user_id')
                ->leftJoin('branches', 'branches.id', '=', 'asset_requests.branch_id')
                ->select(
                    'asset_requests.id',
                    'asset_requests.title',
                    'asset_requests.type',
                    'asset_requests.priority',
                    'asset_requests.created_at',
                    'users.name as user_name',
                    'branches.name as branch_name'
                )
                ->where('asset_requests.status', 'pending')
                ->where('asset_requests.priority', '!=', 'urgent')
                ->where('asset_requests.created_at', '>=', now()->subHours(24))
                ->orderByDesc('asset_requests.created_at')
                ->get();

            foreach ($normalAssetRequests as $req) {
                $typeLabel = $req->type === 'purchase' ? 'شراء' : 'إصلاح';
                $branchText = $req->branch_name ? " ({$req->branch_name})" : '';

                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'bi-box-seam',
                    'message' => "طلب {$typeLabel} جديد: {$req->title}{$branchText} — {$req->user_name}",
                    'time' => $req->created_at,
                    'url' => route('asset-requests.index'),
                    'roles' => ['super_admin'],
                    'permissions' => ['manage_assets'],
                ];
            }

            // إجمالي الطلبات المعلقة
            $totalPending = DB::table('asset_requests')
                ->where('status', 'pending')
                ->count();

            if ($totalPending > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'icon' => 'bi-inbox-fill',
                    'message' => "يوجد {$totalPending} طلب لوجستي بانتظار المراجعة",
                    'time' => now()->toDateTimeString(),
                    'url' => route('asset-requests.index'),
                    'roles' => ['super_admin'],
                    'permissions' => ['manage_assets'],
                ];
            }
        }

        // =============================
        // فلترة حسب الدور والصلاحية
        // =============================
        $user = auth()->user();

        $alerts = array_filter($alerts, function ($alert) use ($user) {
            // التحقق من الأدوار
            $hasRole = empty($alert['roles']);
            if (!$hasRole) {
                foreach ($alert['roles'] as $role) {
                    if ($user->hasRole($role)) {
                        $hasRole = true;
                        break;
                    }
                }
            }

            // التحقق من الصلاحيات
            $hasPermission = empty($alert['permissions']);
            if (!$hasPermission) {
                foreach ($alert['permissions'] as $perm) {
                    if ($user->hasPermission($perm)) {
                        $hasPermission = true;
                        break;
                    }
                }
            }

            // يظهر إذا كان لديه دور أو صلاحية
            return $hasRole || $hasPermission;
        });

        // ترتيب حسب الوقت
        usort($alerts, function ($a, $b) {
            return strtotime($b['time'] ?? now()) - strtotime($a['time'] ?? now());
        });

        $alerts = array_values($alerts);

        if ($limit) {
            $alerts = array_slice($alerts, 0, $limit);
        }

        return $alerts;
    }


    public function todayAlertsSummary(): array
    {
        $result = [
            'pending_leaves' => 0,
            'today_tasks' => 0,
        ];

        // طلبات الإجازة المعلقة
        if ($this->has('leaves')) {
            $result['pending_leaves'] = DB::table('leaves')
                ->where('status', 'pending')
                ->count();
        }

        // مهام اليوم
        if ($this->has('tasks')) {
            $result['today_tasks'] = DB::table('tasks')
                ->whereDate('due_date', now())
                ->where('status', '!=', 'done')
                ->count();
        }

        return $result;
    }

    public function todaySystemStats(): array
    {

        $stats = [
            'financial_transactions' => 0,
            'financial_amount' => 0,
            'new_students' => 0,
            'confirmed_students' => 0,
        ];

        if ($this->has('cashbox_transactions')) {

            $stats['financial_transactions'] = DB::table('cashbox_transactions')
                ->whereDate('created_at', now())
                ->count();

            $stats['financial_amount'] = DB::table('cashbox_transactions')
                ->whereDate('created_at', now())
                ->sum('amount');
        }

        if ($this->has('students')) {

            $stats['new_students'] = DB::table('students')
                ->whereDate('created_at', now())
                ->count();

            if (Schema::hasColumn('students', 'confirmed_at')) {

                $stats['confirmed_students'] = DB::table('students')
                    ->whereDate('confirmed_at', now())
                    ->count();
            }
        }

        return $stats;
    }

    public function highPriorityTasks(): array
    {
        if (!$this->has('tasks'))
            return [];

        return DB::table('tasks')
            ->whereIn('priority', ['high', 'urgent'])
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->limit(3)
            ->pluck('title')
            ->toArray();
    }





    public function systemActivityToday(): array
    {
        if (!$this->has('audit_logs')) {
            return ['count' => 0, 'last' => null];
        }

        $todayLogs = DB::table('audit_logs')
            ->whereDate('created_at', now())
            ->count();

        $lastLog = DB::table('audit_logs')
            ->latest()
            ->value('created_at');

        return [
            'count' => $todayLogs,
            'last' => $lastLog
        ];
    }

}
