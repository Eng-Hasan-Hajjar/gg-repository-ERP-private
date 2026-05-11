<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Branch;
use App\Models\Diploma;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentReportController extends Controller
{
    // ══════════════════════════════════════════
    // صفحة التقارير
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $q = Student::with(['branch', 'diplomas', 'profile', 'crmInfo']);

        // فلاتر
        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }
        if ($request->filled('diploma_id')) {
            $q->whereHas('diplomas', fn($x) => $x->where('diplomas.id', $request->diploma_id));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(fn($x) => $x
                ->where('full_name', 'like', "%$s%")
                ->orWhere('university_id', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%")
            );
        }

        $students = $q->latest()->paginate(20)->withQueryString();

        // إحصائيات
        $stats = [
            'total'     => Student::count(),
            'active'    => Student::where('status', 'active')->count(),
            'confirmed' => Student::where('is_confirmed', true)->count(),
            'today'     => Student::whereDate('created_at', today())->count(),
        ];

        return view('students.reports.index', [
            'students' => $students,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'stats'    => $stats,
            'statusOptions' => $this->statusLabels(),
        ]);
    }

    // ══════════════════════════════════════════
    // تقرير 1: قائمة الطلاب العامة
    // ══════════════════════════════════════════
    public function exportList(Request $request): StreamedResponse
    {
        $students = $this->buildQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('قائمة الطلاب');
        $ws->setRightToLeft(true);

        $TEAL = 'FF11998E';
        $WHITE = 'FFFFFFFF';

        // العنوان
        $ws->mergeCells('A1:I1');
        $ws->setCellValue('A1', 'قائمة الطلاب — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        // رأس الجدول
        $headers = ['#', 'الرقم الجامعي', 'الاسم الكامل', 'الهاتف', 'واتساب', 'الفرع', 'الحالة', 'التسجيل', 'تاريخ الإضافة'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $ws->setCellValue("{$col}2", $h);
        }
        $ws->getStyle('A2:I2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0D7A6B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(22);

        // البيانات
        $statusLabels = $this->statusLabels();
        $row = 3;
        foreach ($students as $i => $s) {
            $ws->setCellValue("A{$row}", $i + 1);
            $ws->setCellValue("B{$row}", $s->university_id);
            $ws->setCellValue("C{$row}", $s->full_name);
            $ws->setCellValue("D{$row}", $s->phone ?? '-');
            $ws->setCellValue("E{$row}", $s->whatsapp ?? '-');
            $ws->setCellValue("F{$row}", $s->branch->name ?? '-');
            $ws->setCellValue("G{$row}", $statusLabels[$s->status] ?? $s->status);
            $ws->setCellValue("H{$row}", $s->registration_status === 'confirmed' ? 'مُثبت' : 'معلّق');
            $ws->setCellValue("I{$row}", $s->created_at->format('Y-m-d'));

            if ($row % 2 === 0) {
                $ws->getStyle("A{$row}:I{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0FDF4');
            }
            $ws->getStyle("A{$row}:I{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDEE2E6']]],
            ]);
            $row++;
        }

        // عرض الأعمدة
        foreach (['A' => 5, 'B' => 16, 'C' => 25, 'D' => 16, 'E' => 16, 'F' => 18, 'G' => 22, 'H' => 14, 'I' => 14] as $col => $width) {
            $ws->getColumnDimension($col)->setWidth($width);
        }

        $ws->freezePane('A3');

        return $this->download($spreadsheet, 'قائمة-الطلاب-' . now()->format('Y-m-d') . '.xlsx');
    }

    // ══════════════════════════════════════════
    // تقرير 2: تفاصيل الطلاب الكاملة
    // ══════════════════════════════════════════
    public function exportDetail(Request $request): StreamedResponse
    {
        $students = $this->buildQuery($request)->with('profile')->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('تفاصيل الطلاب');
        $ws->setRightToLeft(true);

        $BLUE = 'FF1E40AF';
        $WHITE = 'FFFFFFFF';

        $ws->mergeCells('A1:N1');
        $ws->setCellValue('A1', 'التقرير التفصيلي للطلاب — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $BLUE]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        $headers = ['#', 'الرقم الجامعي', 'الاسم', 'الهاتف', 'الفرع', 'الحالة', 'الجنسية', 'تاريخ التولد', 'الرقم الوطني', 'المستوى التعليمي', 'مستوى اللغة', 'الاسم باللاتيني', 'ملاحظات', 'تاريخ الإضافة'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i);
            $ws->setCellValue("{$col}2", $h);
        }
        $ws->getStyle('A2:N2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(22);

        $statusLabels = $this->statusLabels();
        $row = 3;
        foreach ($students as $i => $s) {
            $p = $s->profile;
            $ws->setCellValue("A{$row}", $i + 1);
            $ws->setCellValue("B{$row}", $s->university_id);
            $ws->setCellValue("C{$row}", $s->full_name);
            $ws->setCellValue("D{$row}", $s->phone ?? '-');
            $ws->setCellValue("E{$row}", $s->branch->name ?? '-');
            $ws->setCellValue("F{$row}", $statusLabels[$s->status] ?? $s->status);
            $ws->setCellValue("G{$row}", $p?->nationality ?? '-');
            $ws->setCellValue("H{$row}", $p?->birth_date?->format('Y-m-d') ?? '-');
            $ws->setCellValue("I{$row}", $p?->national_id ?? '-');
            $ws->setCellValue("J{$row}", $p?->education_level ?? '-');
            $ws->setCellValue("K{$row}", $p?->level ?? '-');
            $ws->setCellValue("L{$row}", $p?->arabic_full_name ?? '-');
            $ws->setCellValue("M{$row}", $p?->notes ?? '-');
            $ws->setCellValue("N{$row}", $s->created_at->format('Y-m-d'));

            if ($row % 2 === 0) {
                $ws->getStyle("A{$row}:N{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFEFF6FF');
            }
            $ws->getStyle("A{$row}:N{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDEE2E6']]],
            ]);
            $row++;
        }

        foreach (['A' => 5, 'B' => 16, 'C' => 24, 'D' => 16, 'E' => 16, 'F' => 22, 'G' => 14, 'H' => 14, 'I' => 16, 'J' => 18, 'K' => 14, 'L' => 22, 'M' => 24, 'N' => 14] as $col => $width) {
            $ws->getColumnDimension($col)->setWidth($width);
        }
        $ws->freezePane('A3');

        return $this->download($spreadsheet, 'تفاصيل-الطلاب-' . now()->format('Y-m-d') . '.xlsx');
    }

    // ══════════════════════════════════════════
    // تقرير 3: الطلاب حسب الدبلومة
    // ══════════════════════════════════════════
    public function exportDiplomas(Request $request): StreamedResponse
    {
        $students = $this->buildQuery($request)->with('diplomas')->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        $PURPLE = 'FF6D28D9';
        $WHITE = 'FFFFFFFF';

        // ورقة ملخص
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('الطلاب حسب الدبلومة');
        $ws->setRightToLeft(true);

        $ws->mergeCells('A1:H1');
        $ws->setCellValue('A1', 'تقرير الطلاب حسب الدبلومة — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $PURPLE]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        $headers = ['#', 'الرقم الجامعي', 'الاسم', 'الهاتف', 'الفرع', 'الدبلومة', 'كود الدبلومة', 'حالة الدبلومة'];
        foreach ($headers as $i => $h) {
            $ws->setCellValue(chr(65 + $i) . '2', $h);
        }
        $ws->getStyle('A2:H2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF5B21B6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(22);

        $diplomaStatus = ['active' => 'مستمر', 'waiting' => 'بانتظار', 'finished' => 'منتهي'];
        $row = 3;
        $num = 1;
        foreach ($students as $s) {
            foreach ($s->diplomas as $d) {
                $ws->setCellValue("A{$row}", $num++);
                $ws->setCellValue("B{$row}", $s->university_id);
                $ws->setCellValue("C{$row}", $s->full_name);
                $ws->setCellValue("D{$row}", $s->phone ?? '-');
                $ws->setCellValue("E{$row}", $s->branch->name ?? '-');
                $ws->setCellValue("F{$row}", $d->name);
                $ws->setCellValue("G{$row}", $d->code);
                $ws->setCellValue("H{$row}", $diplomaStatus[$d->pivot->status] ?? $d->pivot->status);

                if ($row % 2 === 0) {
                    $ws->getStyle("A{$row}:H{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF5F3FF');
                }
                $ws->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDEE2E6']]],
                ]);
                $row++;
            }
        }

        foreach (['A' => 5, 'B' => 16, 'C' => 24, 'D' => 16, 'E' => 16, 'F' => 26, 'G' => 14, 'H' => 14] as $col => $width) {
            $ws->getColumnDimension($col)->setWidth($width);
        }
        $ws->freezePane('A3');

        return $this->download($spreadsheet, 'الطلاب-حسب-الدبلومة-' . now()->format('Y-m-d') . '.xlsx');
    }

    // ══════════════════════════════════════════
    // تقرير 4: بيانات CRM للطلاب
    // ══════════════════════════════════════════
    public function exportCrm(Request $request): StreamedResponse
    {
        $students = $this->buildQuery($request)->with('crmInfo')->get();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('بيانات CRM');
        $ws->setRightToLeft(true);

        $RED = 'FFDC2626';
        $WHITE = 'FFFFFFFF';

        $ws->mergeCells('A1:L1');
        $ws->setCellValue('A1', 'تقرير بيانات CRM للطلاب — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $RED]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        $headers = ['#', 'الرقم الجامعي', 'الاسم', 'الهاتف', 'الفرع', 'المصدر', 'المرحلة', 'البلد', 'المحافظة', 'الجهة', 'الاحتياج', 'تاريخ التحويل'];
        foreach ($headers as $i => $h) {
            $ws->setCellValue(chr(65 + $i) . '2', $h);
        }
        $ws->getStyle('A2:L2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFB91C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(22);

        $sourceLabels = ['ad' => 'إعلان', 'referral' => 'إحالة', 'social' => 'تواصل اجتماعي', 'website' => 'موقع', 'expo' => 'معرض', 'other' => 'أخرى'];
        $stageLabels  = ['new' => 'جديد', 'follow_up' => 'متابعة', 'interested' => 'مهتم', 'registered' => 'مسجل', 'rejected' => 'مرفوض', 'postponed' => 'مؤجل'];

        $row = 3;
        foreach ($students as $i => $s) {
            $crm = $s->crmInfo;
            $ws->setCellValue("A{$row}", $i + 1);
            $ws->setCellValue("B{$row}", $s->university_id);
            $ws->setCellValue("C{$row}", $s->full_name);
            $ws->setCellValue("D{$row}", $s->phone ?? '-');
            $ws->setCellValue("E{$row}", $s->branch->name ?? '-');
            $ws->setCellValue("F{$row}", $sourceLabels[$crm?->source ?? ''] ?? '-');
            $ws->setCellValue("G{$row}", $stageLabels[$crm?->stage ?? ''] ?? '-');
            $ws->setCellValue("H{$row}", $crm?->country ?? '-');
            $ws->setCellValue("I{$row}", $crm?->province ?? '-');
            $ws->setCellValue("J{$row}", $crm?->organization ?? '-');
            $ws->setCellValue("K{$row}", $crm?->need ?? '-');
            $ws->setCellValue("L{$row}", $crm?->converted_at?->format('Y-m-d') ?? '-');

            if ($row % 2 === 0) {
                $ws->getStyle("A{$row}:L{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFF1F2');
            }
            $ws->getStyle("A{$row}:L{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDEE2E6']]],
            ]);
            $row++;
        }

        foreach (['A' => 5, 'B' => 16, 'C' => 24, 'D' => 16, 'E' => 16, 'F' => 16, 'G' => 14, 'H' => 14, 'I' => 14, 'J' => 20, 'K' => 22, 'L' => 14] as $col => $width) {
            $ws->getColumnDimension($col)->setWidth($width);
        }
        $ws->freezePane('A3');

        return $this->download($spreadsheet, 'بيانات-CRM-الطلاب-' . now()->format('Y-m-d') . '.xlsx');
    }

    // ── مساعدات ──
    private function buildQuery(Request $request)
    {
        $q = Student::with(['branch', 'diplomas', 'profile', 'crmInfo']);

        if ($request->filled('branch_id'))  $q->where('branch_id', $request->branch_id);
        if ($request->filled('status'))     $q->where('status', $request->status);
        if ($request->filled('diploma_id')) {
            $q->whereHas('diplomas', fn($x) => $x->where('diplomas.id', $request->diploma_id));
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(fn($x) => $x
                ->where('full_name', 'like', "%$s%")
                ->orWhere('university_id', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%")
            );
        }
        return $q;
    }

    private function download(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        while (ob_get_level()) ob_end_clean();
        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function statusLabels(): array
    {
        return [
            'active' => 'مستمر في الدراسة', 'waiting' => 'قيد الانتظار',
            'paid' => 'مدفوع', 'withdrawn' => 'منسحب', 'failed' => 'راسب',
            'absent_exam' => 'لم يتقدم للامتحان', 'certificate_delivered' => 'تم تسليم الشهادة',
            'certificate_waiting' => 'الشهادة قيد الإصدار', 'registration_ended' => 'انتهى التسجيل',
            'dismissed' => 'فُصل الطالب', 'frozen' => 'تم تجميد القيد',
        ];
    }
}