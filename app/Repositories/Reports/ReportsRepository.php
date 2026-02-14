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
        if (!$this->has('students')) return 0;
        return (int) DB::table('students')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();
    }

    public function studentsConfirmed(?int $branchId = null): int
    {
        if (!$this->has('students')) return 0;

        // supports either is_confirmed boolean OR registration_status
        $q = DB::table('students')->when($branchId, fn($x)=>$x->where('branch_id',$branchId));

        if (Schema::hasColumn('students', 'is_confirmed')) {
            $q->where('is_confirmed', 1);
        } elseif (Schema::hasColumn('students', 'registration_status')) {
            $q->where('registration_status', 'confirmed');
        }

        return (int) $q->count();
    }

    public function studentsUnconfirmed(?int $branchId = null): int
    {
        if (!$this->has('students')) return 0;

        $q = DB::table('students')->when($branchId, fn($x)=>$x->where('branch_id',$branchId));

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
        if (!$table) return 0;

        return (int) DB::table($table)
            ->when($branchId, fn($q)=>$q->where('branch_id',$branchId))
            ->count();
    }

    public function employeesActive(?int $branchId = null): int
    {
        $table = $this->has('employees') ? 'employees' : ($this->has('staff') ? 'staff' : null);
        if (!$table) return 0;

        $q = DB::table($table)->when($branchId, fn($x)=>$x->where('branch_id',$branchId));

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
        if (!$table) return 0;

        $q = DB::table($table)->when($branchId, fn($x)=>$x->where('branch_id',$branchId));

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
        if (!$this->has('attendances')) return 0;

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
        if (!$this->has('attendances')) return 0;

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
        if (!$this->has('tasks')) return 0;

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

        if ($branchId && Schema::hasColumn('tasks','branch_id')) {
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

        if (!$table) return 0;

        return (int) DB::table($table)
            ->when($branchId, function($q) use ($branchId, $table){
                if (Schema::hasColumn($table,'branch_id')) $q->where('branch_id', $branchId);
            })
            ->count();
    }

    public function paymentsSum(string $from, string $to, ?int $branchId = null): float
    {
        // غيّر إذا جدول الدفعات اسمه payments/transactions/invoices
        $table = $this->has('payments') ? 'payments'
            : ($this->has('transactions') ? 'transactions' : null);

        if (!$table) return 0;

        $q = DB::table($table);

        // amount column
        $amountCol = Schema::hasColumn($table,'amount') ? 'amount' : (Schema::hasColumn($table,'total') ? 'total' : null);
        if (!$amountCol) return 0;

        // date column
        $dateCol = Schema::hasColumn($table,'paid_at') ? 'paid_at'
            : (Schema::hasColumn($table,'date') ? 'date'
            : (Schema::hasColumn($table,'created_at') ? 'created_at' : null));

        if (!$dateCol) return 0;

        $q->whereDate($dateCol, '>=', $from)->whereDate($dateCol, '<=', $to);

        if ($branchId && Schema::hasColumn($table,'branch_id')) {
            $q->where('branch_id', $branchId);
        }

        return (float) $q->sum($amountCol);
    }

    // =========================
    // Simple chart: Students per branch
    // =========================
    public function studentsPerBranch(): array
    {
        if (!$this->has('students') || !$this->has('branches')) return [];

        // depends on branches.id + branches.name
        return DB::table('branches')
            ->leftJoin('students', 'students.branch_id', '=', 'branches.id')
            ->select('branches.name as branch', DB::raw('COUNT(students.id) as total'))
            ->groupBy('branches.name')
            ->orderBy('branches.name')
            ->get()
            ->map(fn($r) => ['branch' => $r->branch, 'total' => (int)$r->total])
            ->toArray();
    }



    // =========================
// REAL-TIME EXECUTIVE DATA
// =========================

public function revenueToday(?int $branchId = null): float
{
    $table = $this->has('payments') ? 'payments'
        : ($this->has('transactions') ? 'transactions' : null);

    if (!$table) return 0;

    $amountCol = Schema::hasColumn($table,'amount') ? 'amount'
        : (Schema::hasColumn($table,'total') ? 'total' : null);

    $dateCol = Schema::hasColumn($table,'paid_at') ? 'paid_at'
        : (Schema::hasColumn($table,'date') ? 'date' : 'created_at');

    $q = DB::table($table)
        ->whereDate($dateCol, now()->toDateString());

    if ($branchId && Schema::hasColumn($table,'branch_id')) {
        $q->where('branch_id', $branchId);
    }

    return (float) $q->sum($amountCol);
}

public function systemHealthStatus(): string
{
    // بسيط وعملي
    $lastAudit = DB::table('audit_logs')->latest()->first();

    if (!$lastAudit) return 'unknown';

    $minutes = now()->diffInMinutes($lastAudit->created_at);

    if ($minutes <= 10) return 'online';
    if ($minutes <= 60) return 'degraded';

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
        'growth' => round($growth,2)
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
            'total'  => (float) $r->total,
        ])
        ->toArray();
}





// =========================
// Students Monthly Growth
// =========================
public function studentsGrowth(): array
{
    if (!$this->has('students')) return [];

    return DB::table('students')
        ->select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("COUNT(id) as total")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(fn($r)=>[
            'month' => $r->month,
            'total' => (int)$r->total
        ])
        ->toArray();
}





public function studentsGrowthByMonth(int $months = 12): array
{
    if (!$this->has('students')) return [];

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





public function systemAlerts(): array
{
    $alerts = [];

    // =============================
    // مهام متأخرة
    // =============================
    if ($this->has('tasks')) {

        $lateTasks = DB::table('tasks')
            ->whereDate('due_date','<', now())
            ->where('status','!=','done')
            ->count();

        if ($lateTasks > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'bi-list-check',
                'message' => "يوجد {$lateTasks} مهام متأخرة",
                'url' => route('tasks.index', [
                    'late' => 1
                ])

            ];
        }
    }

    // =============================
    // لا يوجد تسجيل طلاب اليوم
    // =============================
    if ($this->has('students')) {

        $todayStudents = DB::table('students')
            ->whereDate('created_at', now())
            ->count();

        if ($todayStudents == 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'bi-people',
                'message' => 'لا يوجد تسجيل طلاب اليوم',
                'url' => route('students.index', [
                    'search' => now()->format('Y')
                ])
            ];
        }
    }

    // =============================
    // لا توجد حركات مالية اليوم
    // =============================
    if ($this->has('cashbox_transactions')) {

        $trx = DB::table('cashbox_transactions')
            ->whereDate('trx_date', now())
            ->count();

        if ($trx == 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'bi-cash-coin',
                'message' => 'لا توجد أي حركة مالية اليوم',
                'url' => route('cashboxes.index')
            ];
        }
    }

    return $alerts;
}






public function todayAlertsSummary(): array
{
    $result = [
        'pending_leaves' => 0,
        'today_tasks'    => 0,
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



public function highPriorityTasks(): array
{
    if (!$this->has('tasks')) return [];

    return DB::table('tasks')
        ->whereIn('priority', ['high','urgent'])
        ->where('status','!=','done')
        ->orderBy('due_date')
        ->limit(3)
        ->pluck('title')
        ->toArray();
}





public function systemActivityToday(): array
{
    if (!$this->has('audit_logs')) {
        return ['count'=>0,'last'=>null];
    }

    $todayLogs = DB::table('audit_logs')
        ->whereDate('created_at', now())
        ->count();

    $lastLog = DB::table('audit_logs')
        ->latest()
        ->value('created_at');

    return [
        'count' => $todayLogs,
        'last'  => $lastLog
    ];
}

}
