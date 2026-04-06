<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceDetailSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $from,
        private string $to,
        private ?int $branchId,
        private ?int $employeeId
    ) {}

    public function title(): string { return 'التفاصيل اليومية'; }

    public function headings(): array
    {
        return [
            'التاريخ', 'الموظف', 'الفرع', 'وقت الدخول', 'وقت الخروج',
            'بداية الاستراحة', 'نهاية الاستراحة', 'الاستراحة (د)',
            'التأخير (د)', 'ساعات العمل', 'الحالة',
        ];
    }

    public function collection()
    {
        $statusMap = [
            'present'   => 'حاضر',
            'late'      => 'متأخر',
            'absent'    => 'غائب',
            'off'       => 'عطلة',
            'leave'     => 'إجازة',
            'scheduled' => 'مجدول',
        ];

        return AttendanceRecord::query()
            ->with('employee.branch')
            ->whereBetween('work_date', [$this->from, $this->to])
            ->when($this->branchId,   fn($q) => $q->whereHas('employee', fn($x) => $x->where('branch_id', $this->branchId)))
            ->when($this->employeeId, fn($q) => $q->where('employee_id', $this->employeeId))
            ->orderBy('employee_id')
            ->orderBy('work_date')
            ->get()
            ->map(fn($r) => [
                $r->work_date->format('Y-m-d'),
                $r->employee->full_name    ?? '-',
                $r->employee->branch->name ?? '-',
                $r->check_in_at?->format('H:i')    ?? '-',
                $r->check_out_at?->format('H:i')   ?? '-',
                $r->break_start_at?->format('H:i') ?? '-',
                $r->break_end_at?->format('H:i')   ?? '-',
                (int) $r->break_minutes,
                (int) $r->late_minutes,
                round($r->worked_minutes / 60, 2),
                $statusMap[$r->status] ?? $r->status,
            ]);
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF10B981']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}