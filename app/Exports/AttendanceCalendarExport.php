<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceCalendarExport implements FromArray, WithStyles, WithTitle
{
    public function __construct(
        public string $month,
        public array $days,
        public $employees,
        public array $recordsMap,
        public array $letterMap
    ) {}

    public function array(): array
    {
        $header = array_merge(['الموظف', 'الفرع'], array_map(fn($d) => (string) (int) substr($d, 8, 2), $this->days));
        $rows = [$header];

        foreach ($this->employees as $emp) {
            $row = [
                $emp->full_name,
                $emp->branch->name ?? '-',
            ];

            foreach ($this->days as $date) {
                $status = $this->recordsMap[$emp->id][$date] ?? null;
                $row[] = $this->letterMap[$status] ?? '-';
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header + freeze panes
        $sheet->getStyle('A1:ZZ1')->getFont()->setBold(true);
        $sheet->freezePane('C2');
        return [];
    }

    public function title(): string
    {
        return 'Calendar '.$this->month;
    }
}
