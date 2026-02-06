<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardReportExport implements FromArray, WithHeadings
{
    public function __construct(private array $data) {}

    public function headings(): array
    {
        return ['البند', 'القيمة'];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data['cards'] as $c) {
            $rows[] = [
                $c['title'],
                (string)($c['value'] ?? 0),
            ];
        }
        return $rows;
    }
}
