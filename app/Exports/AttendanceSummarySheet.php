<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceSummarySheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $from,
        private string $to,
        private ?int $branchId,
        private ?int $employeeId
    ) {}

    public function title(): string { return 'ملخص الدوام'; }

    public function headings(): array
    {
        return [
            'الموظف', 'الفرع', 'أيام الحضور', 'أيام الغياب',
            'أيام الإجازة', 'التأخير (د)', 'وقت الاستراحة (د)',
            'إجمالي ساعات العمل', 'صافي ساعات العمل',
        ];
    }

    public function collection()
    {
        return AttendanceRecord::query()
            ->select([
                'employee_id',
                DB::raw("SUM(worked_minutes)  as worked_minutes"),
                DB::raw("SUM(break_minutes)   as break_minutes"),
                DB::raw("SUM(late_minutes)    as late_minutes"),
                DB::raw("SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent_days"),
                DB::raw("SUM(CASE WHEN status='leave'  THEN 1 ELSE 0 END) as leave_days"),
                DB::raw("SUM(CASE WHEN status IN ('present','late') THEN 1 ELSE 0 END) as present_days"),
            ])
            ->whereBetween('work_date', [$this->from, $this->to])
            ->when($this->branchId,   fn($q) => $q->whereHas('employee', fn($x) => $x->where('branch_id', $this->branchId)))
            ->when($this->employeeId, fn($q) => $q->where('employee_id', $this->employeeId))
            ->groupBy('employee_id')
            ->with('employee.branch')
            ->get()
            ->map(fn($r) => [
                $r->employee->full_name    ?? '-',
                $r->employee->branch->name ?? '-',
                (int) $r->present_days,
                (int) $r->absent_days,
                (int) $r->leave_days,
                (int) $r->late_minutes,
                (int) $r->break_minutes,
                round($r->worked_minutes / 60, 2),
                round(max(0, $r->worked_minutes - $r->break_minutes) / 60, 2),
            ]);
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0EA5E9']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}