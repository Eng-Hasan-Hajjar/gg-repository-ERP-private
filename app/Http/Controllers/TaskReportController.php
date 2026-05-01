<?php
namespace App\Http\Controllers;

use App\Models\TaskReport;
use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskReportController extends Controller
{


    public function index(Request $request)
    {

        $query = TaskReport::with(['employee', 'task']);

        $user = auth()->user();

        // الموظف يرى تقاريره فقط
        if (!$user->hasRole('super_admin')) {
            $query->where('employee_id', $user->employee->id);
        }

        // فلترة الموظف (للسوبر أدمن)
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // فلترة نوع التقرير
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        // فلترة المهمة
        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // فلترة تاريخ من
        if ($request->filled('from')) {
            $query->whereDate('report_date', '>=', $request->from);
        }

        // فلترة تاريخ إلى
        if ($request->filled('to')) {
            $query->whereDate('report_date', '<=', $request->to);
        }

        // البحث
        if ($request->filled('search')) {
            $s = $request->search;

            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                    ->orWhere('notes', 'like', "%$s%");
            });
        }

        // فلتر سريع
        if ($request->filled('quick')) {

            if ($request->quick == 'today') {
                $query->whereDate('report_date', now());
            }

            if ($request->quick == 'week') {
                $query->whereBetween('report_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
            }

            if ($request->quick == 'month') {
                $query->whereMonth('report_date', now()->month);
            }

        }




        // إذا ليس super_admin ولا يملك manage_tasks → يرى تقاريره فقط
        if (!$user->hasRole('super_admin') && !$user->hasPermission('manage_tasks_reports')) {
            $employeeId = $user->employee?->id;
            $query->where('employee_id', $employeeId);
        }

        if ($user->hasRole('super_admin') || $user->hasPermission('manage_tasks_reports')) {
            $employees = Employee::orderBy('full_name')->get();
        } else {
            $employees = Employee::where('id', $user->employee?->id)->get();
        }


        $reports = $query->latest()->paginate(20)->withQueryString();

        return view('task_reports.index', [

            'reports' => $reports,

            'employees' => $employees,

            'tasks' => Task::orderBy('title')->get()

        ]);

    }

    public function create()
    {

        $tasks = Task::orderBy('title')->get();

        return view('task_reports.create', compact('tasks'));
    }
    public function store(Request $request)
    {

        $data = $request->validate([

            'task_id' => ['nullable', 'exists:tasks,id'],

            'report_type' => ['required', 'in:daily,weekly,monthly'],

            'report_date' => ['required', 'date'],

            'title' => ['nullable', 'string', 'max:255'],

            'notes' => ['nullable', 'string'],

            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xlsx,xls', 'max:5120']
        ]);
        $employee = auth()->user()->employee;

        if (!$employee) {

            return back()->withErrors([
                'employee' => 'يجب أن يكون الحساب مرتبط بموظف حتى يتم رفع التقرير.'
            ]);

        }

        $data['employee_id'] = $employee->id;

        if ($request->hasFile('file')) {

            $path = $request->file('file')
                ->store('task_reports', 'public');

            $data['file_path'] = $path;
        }

        

        // تحقق من عدم وجود تقرير مكرر
        $exists = TaskReport::where('employee_id', $employee->id)
            ->where('report_type', $request->report_type)
            ->whereDate('report_date', $request->report_date)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'report_type' => 'لقد قمت برفع تقرير ' . match ($request->report_type) {
                        'daily' => 'يومي',
                        'weekly' => 'أسبوعي',
                        'monthly' => 'شهري',
                        default => ''
                    } . ' لهذا التاريخ مسبقاً.'
                ]);
        }






        TaskReport::create($data);

        return redirect()
            ->route('reports.task.index')
            ->with('success', 'تم رفع التقرير بنجاح');
    }


    public function show(TaskReport $report)
    {
        return view('task_reports.show', compact('report'));
    }

    public function destroy(TaskReport $report)
    {

        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return back()->with('success', 'تم حذف التقرير');
    }


    public function updateNotes(Request $request, TaskReport $report)
    {
        $report->update([
            'notes' => $request->notes
        ]);

        return back()->with('success', 'تم تعديل الملاحظات بنجاح');
    }



    public function exportExcel(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
{
    // ── نفس منطق الفلترة من index() ──
    $query = TaskReport::with(['employee', 'task']);
    $user  = auth()->user();

    if (!$user->hasRole('super_admin') && !$user->hasPermission('manage_tasks_reports')) {
        $query->where('employee_id', $user->employee?->id);
    }

    if ($request->filled('employee_id'))
        $query->where('employee_id', $request->employee_id);

    if ($request->filled('report_type'))
        $query->where('report_type', $request->report_type);

    if ($request->filled('task_id'))
        $query->where('task_id', $request->task_id);

    if ($request->filled('from'))
        $query->whereDate('report_date', '>=', $request->from);

    if ($request->filled('to'))
        $query->whereDate('report_date', '<=', $request->to);

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(fn($q) => $q->where('title', 'like', "%$s%")->orWhere('notes', 'like', "%$s%"));
    }

    if ($request->filled('quick')) {
        match ($request->quick) {
            'today' => $query->whereDate('report_date', now()),
            'week'  => $query->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('report_date', now()->month),
            default => null,
        };
    }

    $reports = $query->latest()->get();

    // ── بناء Excel ──
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

    $BLUE      = '2563EB';
    $BLUE_DARK = '1D4ED8';
    $WHITE     = 'FFFFFFFF';

    $TYPE_STYLES = [
        'daily'   => ['bg' => 'FFE0F2FE', 'fg' => 'FF0369A1', 'label' => 'يومي'],
        'weekly'  => ['bg' => 'FFEDE9FE', 'fg' => 'FF4338CA', 'label' => 'أسبوعي'],
        'monthly' => ['bg' => 'FFD1FAE5', 'fg' => 'FF047857', 'label' => 'شهري'],
    ];

    $ws = $spreadsheet->getActiveSheet();
    $ws->setTitle('تقارير المهام');
    $ws->setRightToLeft(true);

    $allBorders = [
        'borders' => ['allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color'       => ['argb' => 'FFDEE2E6'],
        ]],
    ];

    // ── العنوان ──
    $ws->mergeCells('A1:G1');
    $ws->setCellValue('A1', 'تقارير المهام — نظام نماء — ' . now()->format('Y-m-d'));
    $ws->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 15, 'color' => ['argb' => $WHITE]],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $BLUE]],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
    ]);
    $ws->getRowDimension(1)->setRowHeight(36);

    // ── معلومات الفلتر ──
    $filterParts = [];
    if ($request->filled('from') || $request->filled('to'))
        $filterParts[] = 'الفترة: ' . ($request->from ?? '...') . ' → ' . ($request->to ?? now()->format('Y-m-d'));
    if ($request->filled('report_type'))
        $filterParts[] = 'النوع: ' . ($TYPE_STYLES[$request->report_type]['label'] ?? $request->report_type);
    if ($request->filled('quick'))
        $filterParts[] = 'فلتر سريع: ' . match($request->quick) {'today'=>'اليوم','week'=>'هذا الأسبوع','month'=>'هذا الشهر',default=>$request->quick};

    $filterText = count($filterParts) ? implode('  |  ', $filterParts) : 'جميع التقارير';
    $ws->mergeCells('A2:G2');
    $ws->setCellValue('A2', $filterText);
    $ws->getStyle('A2')->applyFromArray([
        'font'      => ['size' => 10, 'color' => ['argb' => 'FF' . $BLUE]],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
    ]);
    $ws->getRowDimension(2)->setRowHeight(20);
    $ws->getRowDimension(3)->setRowHeight(5);

    // ── رؤوس الأعمدة ──
    $headers = [
        'A' => ['#',           5],
        'B' => ['الموظف',     24],
        'C' => ['نوع التقرير',14],
        'D' => ['التاريخ',    14],
        'E' => ['العنوان',    28],
        'F' => ['الملاحظات', 40],
        'G' => ['ملف مرفق',  14],
    ];

    foreach ($headers as $col => [$label, $width]) {
        $ws->setCellValue("{$col}4", $label);
        $ws->getColumnDimension($col)->setWidth($width);
    }

    $ws->getStyle('A4:G4')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $BLUE_DARK]],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
    ]);
    $ws->getRowDimension(4)->setRowHeight(26);

    // ── البيانات ──
    $row = 5;
    foreach ($reports as $idx => $r) {
        $rowBg    = ($idx % 2 === 0) ? 'FFF8F9FA' : 'FFFFFFFF';
        $typeInfo = $TYPE_STYLES[$r->report_type] ?? ['bg' => 'FFF8F9FA', 'fg' => 'FF6C757D', 'label' => $r->report_type];

        $ws->setCellValue("A{$row}", $idx + 1);
        $ws->setCellValue("B{$row}", $r->employee->full_name ?? '-');
        $ws->setCellValue("C{$row}", $typeInfo['label']);
        $ws->setCellValue("D{$row}", $r->report_date?->format('Y-m-d') ?? '-');
        $ws->setCellValue("E{$row}", $r->title ?? '-');
        $ws->setCellValue("F{$row}", $r->notes ?? '-');
        $ws->setCellValue("G{$row}", $r->file_path ? 'نعم ✓' : '-');

        $ws->getStyle("A{$row}:G{$row}")->applyFromArray(array_merge($allBorders, [
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]));

        // تلوين نوع التقرير
        $ws->getStyle("C{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => $typeInfo['fg']]],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => $typeInfo['bg']]],
        ]);

        // محاذاة يمين للنصوص
        foreach (['B', 'E', 'F'] as $tc) {
            $ws->getStyle("{$tc}{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }

        // تلوين خلية الملف
        if ($r->file_path) {
            $ws->getStyle("G{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF047857'))->setBold(true);
        }

        $ws->getRowDimension($row)->setRowHeight(24);
        $row++;
    }

    // ── صف الإجمالي ──
    $lastDataRow = $row - 1;
    $ws->mergeCells("A{$row}:E{$row}");
    $ws->setCellValue("A{$row}", 'إجمالي التقارير: ' . $reports->count());
    $ws->setCellValue("F{$row}", 'يومي: ' . $reports->where('report_type', 'daily')->count() .
        '  |  أسبوعي: ' . $reports->where('report_type', 'weekly')->count() .
        '  |  شهري: '   . $reports->where('report_type', 'monthly')->count());

    $ws->getStyle("A{$row}:G{$row}")->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $BLUE]],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
    ]);
    $ws->getRowDimension($row)->setRowHeight(28);

    $ws->freezePane('A5');

    $filename = 'تقارير-المهام-' . now()->format('Y-m-d') . '.xlsx';

    while (ob_get_level()) ob_end_clean();

    return response()->streamDownload(function () use ($spreadsheet) {
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
    }, $filename, [
        'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control'       => 'max-age=0',
    ]);
}

}