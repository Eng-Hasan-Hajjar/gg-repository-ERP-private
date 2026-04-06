<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceReportExport implements WithMultipleSheets
{
    public function __construct(
        private string $from,
        private string $to,
        private ?int $branchId,
        private ?int $employeeId
    ) {}

    public function sheets(): array
    {
        return [
            new AttendanceSummarySheet($this->from, $this->to, $this->branchId, $this->employeeId),
            new AttendanceDetailSheet($this->from, $this->to, $this->branchId, $this->employeeId),
        ];
    }
}