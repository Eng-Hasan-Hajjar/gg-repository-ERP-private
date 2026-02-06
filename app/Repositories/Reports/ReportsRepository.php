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
}
