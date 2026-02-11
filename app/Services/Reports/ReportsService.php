<?php

namespace App\Services\Reports;

use App\Repositories\Reports\ReportsRepository;

class ReportsService
{
    public function __construct(private ReportsRepository $repo) {}

 public function getDashboard(array $filters): array
{
    $branchId = isset($filters['branch_id']) && $filters['branch_id'] !== '' 
        ? (int)$filters['branch_id'] 
        : null;

    $from = $filters['from'];
    $to   = $filters['to'];

    $cards = [
        [
            'title' => 'إجمالي الطلاب',
            'value' => $this->repo->studentsTotal($branchId),
            'icon'  => 'bi-people',
        ],
        [
            'title' => 'الطلاب المثبتون',
            'value' => $this->repo->studentsConfirmed($branchId),
            'icon'  => 'bi-check2-circle',
        ],
        [
            'title' => 'الطلاب غير المثبتين',
            'value' => $this->repo->studentsUnconfirmed($branchId),
            'icon'  => 'bi-hourglass-split',
        ],
        [
            'title' => 'إجمالي الموظفين',
            'value' => $this->repo->employeesTotal($branchId),
            'icon'  => 'bi-person-badge',
        ],
        [
            'title' => 'الموظفون الفعّالون',
            'value' => $this->repo->employeesActive($branchId),
            'icon'  => 'bi-person-check',
        ],
        [
            'title' => 'الموظفون غير الفعّالين',
            'value' => $this->repo->employeesInactive($branchId),
            'icon'  => 'bi-person-x',
        ],
        [
            'title' => 'دوام اليوم (حضور)',
            'value' => $this->repo->employeesPresentToday($branchId),
            'icon'  => 'bi-calendar-check',
        ],
        [
            'title' => 'دوام اليوم (غياب)',
            'value' => $this->repo->employeesAbsentToday($branchId),
            'icon'  => 'bi-calendar-x',
        ],
        [
            'title' => 'مهام مستحقة اليوم',
            'value' => $this->repo->tasksDueToday($branchId),
            'icon'  => 'bi-list-check',
        ],
        [
            'title' => 'عدد الصناديق المالية',
            'value' => $this->repo->boxesTotal($branchId),
            'icon'  => 'bi-cash-coin',
        ],
        [
            'title' => 'مجموع الدفعات ضمن الفترة',
            'value' => number_format($this->repo->paymentsSum($from, $to, $branchId), 2),
            'icon'  => 'bi-graph-up-arrow',
            'suffix'=> ' (حسب العملة/الجدول)',
        ],
    ];

    return [
        'filters' => $filters,
        'cards'   => $cards,
        'charts'  => [
            'students_per_branch' => $this->repo->studentsPerBranch(),
        ],

        // ✅ المفتاح الذي تنتظره صفحة executive
        'executive' => [
            'revenue_today' => $this->repo->revenueToday($branchId),
            'health'        => $this->repo->systemHealthStatus(),
            'latest_audit'  => $this->repo->latestAudit(5),
        ],
    ];
}

}
