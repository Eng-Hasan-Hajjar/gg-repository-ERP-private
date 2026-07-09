<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentDynamicExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    /**
     * @param Collection $students          مجموعة الطلاب (Eloquent Collection)
     * @param array      $columns           مفاتيح الأعمدة المختارة بالترتيب المطلوب
     * @param array      $allColumnsLabels  [key => label] لكل الأعمدة المتاحة
     * @param array      $labels            مصفوفة الترجمة (studentArabicLabels)
     */
    public function __construct(
        private Collection $students,
        private array $columns,
        private array $allColumnsLabels,
        private array $labels
    ) {}

    public function title(): string
    {
        return 'تقرير الطلاب';
    }

    public function collection()
    {
        return $this->students;
    }

    /**
     * ✅ العناوين تُبنى ديناميكياً حسب الأعمدة المختارة فقط
     */
    public function headings(): array
    {
        return array_map(fn($key) => $this->allColumnsLabels[$key] ?? $key, $this->columns);
    }

    /**
     * ✅ كل صف يُبنى حسب نفس ترتيب الأعمدة المختارة فقط —
     *    هذا هو قلب الديناميكية: أي عمود غير مطلوب لا يُحسب حتى.
     */
    public function map($student): array
    {
        $row = [];

        foreach ($this->columns as $col) {
            $row[] = $this->resolveColumnValue($student, $col);
        }

        return $row;
    }

    private function resolveColumnValue($student, string $col)
    {
        switch ($col) {
            case 'id':
                return $student->id;

            case 'university_id':
                return $student->university_id;

            case 'full_name':
                return $student->full_name;

            case 'latin_name':
                return $student->profile->arabic_full_name ?? '-';

            case 'phone':
                return $student->phone ?? '-';

            case 'whatsapp':
                return $student->whatsapp ?? '-';

            case 'branch':
                return $student->branch->name ?? '-';

            case 'diplomas':
                return $student->diplomas->pluck('name')->implode('، ') ?: '-';

            case 'mode':
                return $this->labels['mode'][$student->mode] ?? '-';

            case 'status':
                return $this->labels['student_status'][$student->status] ?? '-';

            case 'registration_status':
                return $this->labels['registration_status'][$student->registration_status] ?? '-';

            case 'nationality':
                return $student->profile->nationality ?? '-';

            case 'birth_date':
                return $student->profile->birth_date
                    ? \Carbon\Carbon::parse($student->profile->birth_date)->format('Y-m-d')
                    : '-';

            case 'national_id':
                return $student->profile->national_id ?? '-';

            case 'crm_source':
                return $this->labels['crm_source'][$student->crmInfo->source ?? ''] ?? '-';

            case 'crm_stage':
                return $this->labels['crm_stage'][$student->crmInfo->stage ?? ''] ?? '-';

            case 'organization':
                return $student->crmInfo->organization ?? '-';

            case 'exam_score':
                return $student->profile->exam_score ?? '-';

            case 'language_level':
                return $student->diplomas->pluck('pivot.language_level')->filter()->implode('، ') ?: '-';

            case 'certificate_agreement':
                return $student->diplomas->pluck('pivot.certificate_agreement')->filter()->implode('، ') ?: '-';

            case 'created_at':
                return $student->created_at?->format('Y-m-d H:i');

            default:
                return '-';
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        $lastCol = chr(64 + count($this->columns)); // A, B, C ...

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF11998E'],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        return [];
    }
}