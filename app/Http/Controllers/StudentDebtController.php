<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentDebtController extends Controller
{
    // ══════════════════════════════════════════
    // قائمة الذمم المالية لجميع الطلاب
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $students = Student::with([
            'financialAccount.transactions',
            'paymentPlans',
        ])
            ->whereHas('paymentPlans')
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = trim($request->search);
                $q->where(function ($x) use ($s) {
                    $x->where('full_name', 'like', "%$s%")
                        ->orWhere('phone', 'like', "%$s%")
                        ->orWhere('university_id', 'like', "%$s%");
                });
            })
            ->get();

        // ── حساب الذمة لكل طالب ──
        $debts = $students->map(fn($student) => $this->calcDebt($student));

        // ── فلتر الحالة ──
        if ($request->filled('debt_status')) {
            $debts = $debts->filter(function ($d) use ($request) {
                return match ($request->debt_status) {
                    'has_debt' => $d['remaining'] > 0,
                    'paid' => $d['remaining'] <= 0 && $d['remaining'] >= 0,
                    'overpaid' => $d['remaining'] < 0,
                    default => true,
                };
            })->values();
        }

        // ── ترتيب ──
        $debts = match ($request->get('sort', 'remaining')) {
            'name' => $debts->sortBy('name')->values(),
            'total' => $debts->sortByDesc('total')->values(),
            'paid' => $debts->sortByDesc('paid')->values(),
            default => $debts->sortByDesc('remaining')->values(),
        };

        // ── إجماليات ──
        $totalAmount = $debts->sum('total');
        $totalPaid = $debts->sum('paid');
        $totalDebt = $debts->sum('remaining');

        // ── Pagination يدوي ──
        $perPage = 20;
        $currentPage = (int) $request->get('page', 1);
        $total = $debts->count();

        $paginated = new LengthAwarePaginator(
            $debts->forPage($currentPage, $perPage)->values(),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('debts.index', compact(
            'paginated',
            'totalDebt',
            'totalAmount',
            'totalPaid'
        ));
    }

    // ══════════════════════════════════════════
    // تفاصيل ذمة طالب واحد
    // ══════════════════════════════════════════
    public function show(Student $student)
    {
        $student->load([
            'financialAccount.transactions.cashbox',
            'diplomas',
            'paymentPlans.diploma',
            'paymentPlans.installments',
        ]);

        $debtByDiploma = $student->paymentPlans->map(function ($plan) use ($student) {

            $paid = 0;
            $transactions = collect();

            if ($student->financialAccount) {
                $paid = (float) $student->financialAccount
                    ->transactions()
                    ->where('type', 'in')
                    ->where('status', 'posted')
                    ->where('diploma_id', $plan->diploma_id)
                    ->sum('amount');

                $transactions = $student->financialAccount
                    ->transactions()
                    ->with('cashbox')
                    ->where('type', 'in')
                    ->where('status', 'posted')
                    ->where('diploma_id', $plan->diploma_id)
                    ->orderBy('trx_date')
                    ->get();
            }

            $total = (float) $plan->total_amount;
            $remaining = $total - $paid;
            $pct = $total > 0 ? min(100, round($paid / $total * 100)) : 0;

            // ✅ صحيح — نستخدم $remaining المحسوب مباشرةً
            $installments = $plan->installments->map(function ($inst) use ($remaining) {
                $isPast = \Carbon\Carbon::parse($inst->due_date)->isPast();
                $inst->inst_label = $remaining <= 0
                    ? 'مسدّد'
                    : ($isPast ? 'متأخر' : 'قادم');
                $inst->inst_class = $remaining <= 0
                    ? 'success'
                    : ($isPast ? 'danger' : 'secondary');
                return $inst;
            });

            return [
                'plan' => $plan,
                'diploma' => $plan->diploma,
                'total' => $total,
                'paid' => $paid,
                'remaining' => $remaining,
                'pct' => $pct,
                'currency' => $plan->currency,
                'payment_type' => $plan->payment_type,
                'installments' => $installments,
                'transactions' => $transactions,
            ];
        });

        $summaryTotal = $debtByDiploma->sum('total');
        $summaryPaid = $debtByDiploma->sum('paid');
        $summaryRemaining = $debtByDiploma->sum('remaining');
        $summaryCurrency = $debtByDiploma->first()['currency'] ?? '';
        $summaryPct = $summaryTotal > 0
            ? min(100, round($summaryPaid / $summaryTotal * 100))
            : 0;

        return view('debts.show', compact(
            'student',
            'debtByDiploma',
            'summaryTotal',
            'summaryPaid',
            'summaryRemaining',
            'summaryCurrency',
            'summaryPct'
        ));
    }

    // ══════════════════════════════════════════
    // تصدير Excel
    // ══════════════════════════════════════════
    public function exportExcel(Request $request): StreamedResponse
    {
        $students = Student::with([
            'financialAccount.transactions',
            'paymentPlans',
        ])
            ->whereHas('paymentPlans')
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = trim($request->search);
                $q->where(function ($x) use ($s) {
                    $x->where('full_name', 'like', "%$s%")
                        ->orWhere('phone', 'like', "%$s%")
                        ->orWhere('university_id', 'like', "%$s%");
                });
            })
            ->get();

        $debts = $students->map(fn($s) => $this->calcDebt($s))
            ->sortByDesc('remaining')
            ->values();

        // ── بناء الـ Spreadsheet ──
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        $TEAL = '11998E';
        $TEAL_DARK = '0D7A6B';
        $WHITE = 'FFFFFFFF';

        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('الذمم المالية');
        $ws->setRightToLeft(true);

        $allBorders = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDEE2E6'],
                ],
            ],
        ];

        // ── العنوان ──
        $ws->mergeCells('A1:H1');
        $ws->setCellValue('A1', 'كشف الذمم المالية للطلاب — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 15, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        // ── رؤوس الأعمدة ──
        $headers = [
            'A' => ['#', 5],
            'B' => ['الرقم الجامعي', 18],
            'C' => ['اسم الطالب', 28],
            'D' => ['الهاتف', 16],
            'E' => ['إجمالي المستحق', 18],
            'F' => ['المدفوع', 16],
            'G' => ['المتبقي', 16],
            'H' => ['الحالة', 14],
        ];

        foreach ($headers as $col => [$label, $width]) {
            $ws->setCellValue("{$col}2", $label);
            $ws->getColumnDimension($col)->setWidth($width);
        }

        $ws->getStyle('A2:H2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL_DARK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
        ]);
        $ws->getRowDimension(2)->setRowHeight(26);

        // ── البيانات ──
        $row = 3;
        foreach ($debts as $idx => $d) {
            $rowBg = ($idx % 2 === 0) ? 'FFF8F9FA' : 'FFFFFFFF';

            if ($d['remaining'] > 0) {
                $statusLabel = 'عليه ذمة';
                $statusColor = 'FFB71C1C';
            } elseif ($d['remaining'] < 0) {
                $statusLabel = 'زيادة دفع';
                $statusColor = 'FF0D47A1';
            } else {
                $statusLabel = 'مسدّد';
                $statusColor = 'FF1B5E20';
            }

            $ws->setCellValue("A{$row}", $idx + 1);
            $ws->setCellValue("B{$row}", $d['university_id']);
            $ws->setCellValue("C{$row}", $d['name']);
            $ws->setCellValue("D{$row}", $d['phone']);
            $ws->setCellValue("E{$row}", $d['total']);
            $ws->setCellValue("F{$row}", $d['paid']);
            $ws->setCellValue("G{$row}", $d['remaining']);
            $ws->setCellValue("H{$row}", $statusLabel);

            $ws->getStyle("A{$row}:H{$row}")->applyFromArray(array_merge($allBorders, [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]));

            foreach (['E', 'F', 'G'] as $numCol) {
                $ws->getStyle("{$numCol}{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $ws->getStyle("{$numCol}{$row}")->getFont()->setBold(true);
            }

            $ws->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $ws->getStyle("H{$row}")->getFont()->setColor(new Color($statusColor))->setBold(true);

            if ($d['remaining'] > 0) {
                $ws->getStyle("G{$row}")->getFont()->setColor(new Color('FFB71C1C'));
            } elseif ($d['remaining'] == 0) {
                $ws->getStyle("G{$row}")->getFont()->setColor(new Color('FF1B5E20'));
            }

            $ws->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        // ── صف الإجماليات ──
        $lastDataRow = $row - 1;
        $ws->mergeCells("A{$row}:D{$row}");
        $ws->setCellValue("A{$row}", 'الإجمالي');
        $ws->setCellValue("E{$row}", "=SUM(E3:E{$lastDataRow})");
        $ws->setCellValue("F{$row}", "=SUM(F3:F{$lastDataRow})");
        $ws->setCellValue("G{$row}", "=SUM(G3:G{$lastDataRow})");

        $ws->getStyle("A{$row}:H{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        foreach (['E', 'F', 'G'] as $nc) {
            $ws->getStyle("{$nc}{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        }
        $ws->getRowDimension($row)->setRowHeight(28);

        $ws->freezePane('A3');

        $filename = 'الذمم-المالية-' . now()->format('Y-m-d') . '.xlsx';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ══════════════════════════════════════════
    // مساعد: حساب ذمة طالب واحد
    // ══════════════════════════════════════════
    private function calcDebt(Student $student): array
    {
        $plans = $student->paymentPlans ?? collect();
        $total = (float) $plans->sum('total_amount');

        $paid = 0;
        if ($student->financialAccount) {
            $paid = (float) $student->financialAccount
                ->transactions()
                ->where('type', 'in')
                ->where('status', 'posted')
                ->sum('amount');
        }

        $remaining = $total - $paid;

        if ($remaining > 0) {
            $statusLabel = 'عليه ذمة';
            $statusClass = 'danger';
            $remClass = 'danger';
        } elseif ($remaining < 0) {
            $statusLabel = 'زيادة دفع';
            $statusClass = 'primary';
            $remClass = 'info';
        } else {
            $statusLabel = 'مسدّد';
            $statusClass = 'success';
            $remClass = 'success';
        }

        return [
            'student_id' => $student->id,
            'university_id' => $student->university_id,
            'name' => $student->full_name,
            'phone' => $student->phone ?? '-',
            'total' => $total,
            'paid' => $paid,
            'remaining' => $remaining,
            'status_label' => $statusLabel,
            'status_class' => $statusClass,
            'rem_class' => $remClass,
            'currency' => $plans->first()?->currency ?? '',
            'diplomas_count' => $plans->count(),
        ];
    }


    // ══════════════════════════════════════════
// تصدير Excel — ذمة طالب واحد
// ══════════════════════════════════════════
    public function exportStudentExcel(Student $student): StreamedResponse
    {
        $student->load([
            'financialAccount.transactions.cashbox',
            'diplomas',
            'paymentPlans.diploma',
            'paymentPlans.installments',
        ]);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        $TEAL = '11998E';
        $TEAL_DARK = '0D7A6B';
        $WHITE = 'FFFFFFFF';

        $allBorders = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDEE2E6'],
                ]
            ],
        ];

        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('كشف الذمة');
        $ws->setRightToLeft(true);

        // ── العنوان الرئيسي ──
        $ws->mergeCells('A1:F1');
        $ws->setCellValue('A1', 'كشف حركات ذمة الطالب: ' . $student->full_name);
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        // ── معلومات الطالب ──
        $ws->mergeCells('A2:C2');
        $ws->setCellValue('A2', 'الرقم الجامعي: ' . $student->university_id);
        $ws->mergeCells('D2:F2');
        $ws->setCellValue('D2', 'الهاتف: ' . ($student->phone ?? '-') . '   |   تاريخ الكشف: ' . now()->format('Y-m-d'));
        $ws->getStyle('A2:F2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['argb' => 'FF' . $TEAL]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE1F5EE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(20);
        $ws->getRowDimension(3)->setRowHeight(6);

        // ── إجمالي الذمة ──
        $totalDebt = 0;
        $totalPaid = 0;

        $currentRow = 4;

        foreach ($student->paymentPlans as $plan) {

            $paid = 0;
            $transactions = collect();

            if ($student->financialAccount) {
                $paid = (float) $student->financialAccount
                    ->transactions()
                    ->where('type', 'in')
                    ->where('status', 'posted')
                    ->where('diploma_id', $plan->diploma_id)
                    ->sum('amount');

                $transactions = $student->financialAccount
                    ->transactions()
                    ->with('cashbox')
                    ->where('type', 'in')
                    ->where('status', 'posted')
                    ->where('diploma_id', $plan->diploma_id)
                    ->orderBy('trx_date')
                    ->get();
            }

            $total = (float) $plan->total_amount;
            $remaining = $total - $paid;
            $totalDebt += $total;
            $totalPaid += $paid;

            // ── عنوان الدبلومة ──
            $ws->mergeCells("A{$currentRow}:F{$currentRow}");
            $ws->setCellValue("A{$currentRow}", '🎓 ' . (optional($plan->diploma)->name ?? 'دبلومة غير معروفة'));
            $ws->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL_DARK]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $ws->getRowDimension($currentRow)->setRowHeight(24);
            $currentRow++;

            // ── ملخص الدبلومة ──
            $ws->setCellValue("A{$currentRow}", 'المستحق');
            $ws->setCellValue("B{$currentRow}", $total);
            $ws->setCellValue("C{$currentRow}", 'المدفوع');
            $ws->setCellValue("D{$currentRow}", $paid);
            $ws->setCellValue("E{$currentRow}", 'المتبقي');
            $ws->setCellValue("F{$currentRow}", $remaining);

            $ws->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray(array_merge($allBorders, [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFECFDF5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]));
            $ws->getStyle("B{$currentRow}")->getFont()->setColor(new Color('FF1B5E20'));
            $ws->getStyle("D{$currentRow}")->getFont()->setColor(new Color('FF1976D2'));
            $ws->getStyle("F{$currentRow}")->getFont()->setColor(new Color($remaining > 0 ? 'FFB71C1C' : 'FF1B5E20'));
            foreach (['B', 'D', 'F'] as $nc) {
                $ws->getStyle("{$nc}{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00 "' . $plan->currency . '"');
            }
            $ws->getRowDimension($currentRow)->setRowHeight(22);
            $currentRow++;

            // ── رؤوس جدول الدفعات ──
            if ($transactions->count() > 0) {

                $ws->setCellValue("A{$currentRow}", '#');
                $ws->setCellValue("B{$currentRow}", 'التاريخ');
                $ws->setCellValue("C{$currentRow}", 'المبلغ');
                $ws->setCellValue("D{$currentRow}", 'الصندوق');
                $ws->setCellValue("E{$currentRow}", 'مرجع');
                $ws->setCellValue("F{$currentRow}", 'ملاحظات');

                $ws->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => $WHITE]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0D7A6B']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
                ]);
                $ws->getRowDimension($currentRow)->setRowHeight(22);
                $currentRow++;

                // ── بيانات الدفعات ──
                foreach ($transactions as $idx => $trx) {
                    $rowBg = ($idx % 2 === 0) ? 'FFF8F9FA' : 'FFFFFFFF';

                    $ws->setCellValue("A{$currentRow}", $idx + 1);
                    $ws->setCellValue("B{$currentRow}", $trx->trx_date->format('Y-m-d'));
                    $ws->setCellValue("C{$currentRow}", (float) $trx->amount);
                    $ws->setCellValue("D{$currentRow}", optional($trx->cashbox)->name ?? '-');
                    $ws->setCellValue("E{$currentRow}", $trx->reference ?? '-');
                    $ws->setCellValue("F{$currentRow}", $trx->notes ?? '-');

                    $ws->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray(array_merge($allBorders, [
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]));
                    $ws->getStyle("C{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $ws->getStyle("C{$currentRow}")->getFont()->setColor(new Color('FF1B5E20'))->setBold(true);
                    foreach (['D', 'E', 'F'] as $tc) {
                        $ws->getStyle("{$tc}{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                    $ws->getRowDimension($currentRow)->setRowHeight(20);
                    $currentRow++;
                }

            } else {
                $ws->mergeCells("A{$currentRow}:F{$currentRow}");
                $ws->setCellValue("A{$currentRow}", 'لا توجد دفعات مسجّلة لهذه الدبلومة');
                $ws->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['argb' => 'FF94A3B8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $ws->getRowDimension($currentRow)->setRowHeight(20);
                $currentRow++;
            }

            // فراغ بين الدبلومات
            $ws->getRowDimension($currentRow)->setRowHeight(8);
            $currentRow++;
        }

        // ── صف الإجمالي الكلي ──
        $ws->mergeCells("A{$currentRow}:B{$currentRow}");
        $ws->setCellValue("A{$currentRow}", 'الإجمالي الكلي');
        $ws->setCellValue("C{$currentRow}", $totalDebt);
        $ws->setCellValue("D{$currentRow}", $totalPaid);
        $ws->setCellValue("E{$currentRow}", 'المتبقي الكلي');
        $ws->setCellValue("F{$currentRow}", $totalDebt - $totalPaid);

        $ws->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        foreach (['C', 'D', 'F'] as $nc) {
            $ws->getStyle("{$nc}{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
        }
        $remainingTotal = $totalDebt - $totalPaid;
        $ws->getStyle("F{$currentRow}")->getFont()
            ->setColor(new Color($remainingTotal > 0 ? 'FFFFF176' : 'FFA5D6A7'));
        $ws->getRowDimension($currentRow)->setRowHeight(30);

        // ── عرض الأعمدة ──
        $ws->getColumnDimension('A')->setWidth(5);
        $ws->getColumnDimension('B')->setWidth(14);
        $ws->getColumnDimension('C')->setWidth(16);
        $ws->getColumnDimension('D')->setWidth(22);
        $ws->getColumnDimension('E')->setWidth(18);
        $ws->getColumnDimension('F')->setWidth(28);

        $ws->freezePane('A4');
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'كشف-ذمة-' . $student->full_name . '-' . now()->format('Y-m-d') . '.xlsx';

        while (ob_get_level())
            ob_end_clean();

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}