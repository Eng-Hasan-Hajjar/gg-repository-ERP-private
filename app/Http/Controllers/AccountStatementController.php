<?php

namespace App\Http\Controllers;

use App\Models\CashboxTransaction;
use App\Models\Cashbox;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccountStatementController extends Controller
{
    private array $typeMeta = [
        'in'       => ['label' => 'مقبوض',  'color' => 'success'],
        'out'      => ['label' => 'مدفوع',  'color' => 'danger'],
        'transfer' => ['label' => 'مناقلة', 'color' => 'warning'],
        'exchange' => ['label' => 'تصريف',  'color' => 'info'],
    ];

    // ══════════════════════════════════════════
    // كشف الحسابات الشامل
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $q = CashboxTransaction::query()
            ->with(['cashbox.branch', 'account.accountable', 'diploma']);

        if ($request->filled('cashbox_id'))
            $q->where('cashbox_id', $request->cashbox_id);

        if ($request->filled('type'))
            $q->where('type', $request->type);

        if ($request->filled('status'))
            $q->where('status', $request->status);

        if ($request->filled('category'))
            $q->where('category', $request->category);

        if ($request->filled('currency'))
            $q->where('currency', $request->currency);

        if ($request->filled('date_from'))
            $q->whereDate('trx_date', '>=', $request->date_from);

        if ($request->filled('date_to'))
            $q->whereDate('trx_date', '<=', $request->date_to);

        if ($request->filled('amount_min'))
            $q->where('amount', '>=', $request->amount_min);

        if ($request->filled('amount_max'))
            $q->where('amount', '<=', $request->amount_max);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                  ->orWhere('sub_category', 'like', "%$s%")
                  ->orWhere('reference', 'like', "%$s%")
                  ->orWhere('notes', 'like', "%$s%")
                  ->orWhereHas('account.accountable', function ($q2) use ($s) {
                      $q2->where('full_name', 'like', "%$s%")
                         ->orWhere('phone', 'like', "%$s%");
                  });
            });
        }

        $sort      = in_array($request->get('sort'), ['trx_date', 'amount', 'id', 'cashbox_id'])
            ? $request->get('sort') : 'trx_date';
        $direction = in_array($request->get('direction'), ['asc', 'desc'])
            ? $request->get('direction') : 'desc';

        $transactions = $q->orderBy($sort, $direction)->paginate(30)->withQueryString();

        // ── إجماليات — query منفصل ──
        $qTotals = CashboxTransaction::query();

        if ($request->filled('cashbox_id'))
            $qTotals->where('cashbox_id', $request->cashbox_id);
        if ($request->filled('type'))
            $qTotals->where('type', $request->type);
        if ($request->filled('status'))
            $qTotals->where('status', $request->status);
        if ($request->filled('currency'))
            $qTotals->where('currency', $request->currency);
        if ($request->filled('date_from'))
            $qTotals->whereDate('trx_date', '>=', $request->date_from);
        if ($request->filled('date_to'))
            $qTotals->whereDate('trx_date', '<=', $request->date_to);
        if ($request->filled('amount_min'))
            $qTotals->where('amount', '>=', $request->amount_min);
        if ($request->filled('amount_max'))
            $qTotals->where('amount', '<=', $request->amount_max);
        if ($request->filled('search')) {
            $s = trim($request->search);
            $qTotals->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                  ->orWhere('sub_category', 'like', "%$s%")
                  ->orWhere('reference', 'like', "%$s%")
                  ->orWhere('notes', 'like', "%$s%")
                  ->orWhereHas('account.accountable', function ($q2) use ($s) {
                      $q2->where('full_name', 'like', "%$s%")
                         ->orWhere('phone', 'like', "%$s%");
                  });
            });
        }

        $summaryIn  = (float) (clone $qTotals)->where('type', 'in')->where('status', 'posted')->sum('amount');
        $summaryOut = (float) (clone $qTotals)->whereIn('type', ['out', 'exchange'])->where('status', 'posted')->sum('amount');
        $summaryNet = $summaryIn - $summaryOut;

        $cashboxes  = Cashbox::orderBy('name')->get();
        $categories = \App\Models\CashboxTransaction::$CATEGORIES;

        return view('accounts.statement.index', compact(
            'transactions',
            'summaryIn',
            'summaryOut',
            'summaryNet',
            'cashboxes',
            'categories'
        ) + ['typeMeta' => $this->typeMeta]);
    }

    // ══════════════════════════════════════════
    // تصدير Excel
    // ══════════════════════════════════════════
    public function exportExcel(Request $request): StreamedResponse
    {
        $q = CashboxTransaction::query()
            ->with(['cashbox.branch', 'account.accountable', 'diploma']);

        if ($request->filled('cashbox_id'))
            $q->where('cashbox_id', $request->cashbox_id);
        if ($request->filled('type'))
            $q->where('type', $request->type);
        if ($request->filled('status'))
            $q->where('status', $request->status);
        if ($request->filled('category'))
            $q->where('category', $request->category);
        if ($request->filled('currency'))
            $q->where('currency', $request->currency);
        if ($request->filled('date_from'))
            $q->whereDate('trx_date', '>=', $request->date_from);
        if ($request->filled('date_to'))
            $q->whereDate('trx_date', '<=', $request->date_to);
        if ($request->filled('amount_min'))
            $q->where('amount', '>=', $request->amount_min);
        if ($request->filled('amount_max'))
            $q->where('amount', '<=', $request->amount_max);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                  ->orWhere('sub_category', 'like', "%$s%")
                  ->orWhere('reference', 'like', "%$s%")
                  ->orWhere('notes', 'like', "%$s%")
                  ->orWhereHas('account.accountable', function ($q2) use ($s) {
                      $q2->where('full_name', 'like', "%$s%")
                         ->orWhere('phone', 'like', "%$s%");
                  });
            });
        }

        $sort      = in_array($request->get('sort'), ['trx_date', 'amount', 'id', 'cashbox_id'])
            ? $request->get('sort') : 'trx_date';
        $direction = in_array($request->get('direction'), ['asc', 'desc'])
            ? $request->get('direction') : 'desc';

        $transactions = $q->orderBy($sort, $direction)->get();

        $typeLabels = [
            'in'       => 'مقبوض',
            'out'      => 'مدفوع',
            'transfer' => 'مناقلة',
            'exchange' => 'تصريف',
        ];

        $TYPE_STYLES = [
            'in'       => ['bg' => 'FFE8F5E9', 'fg' => 'FF1B5E20'],
            'out'      => ['bg' => 'FFFDECEA', 'fg' => 'FFB71C1C'],
            'transfer' => ['bg' => 'FFFFF3E0', 'fg' => 'FFE65100'],
            'exchange' => ['bg' => 'FFE3F2FD', 'fg' => 'FF0D47A1'],
        ];

        $STATUS_STYLES = [
            'posted' => ['label' => 'مُرحّل', 'fg' => 'FF1B5E20'],
            'draft'  => ['label' => 'معلّق',  'fg' => 'FF6C757D'],
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        $AMBER_DRK = 'B45309';
        $WHITE     = 'FFFFFFFF';

        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('كشف الحسابات');
        $ws->setRightToLeft(true);

        $allBorders = [
            'borders' => ['allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => ['argb' => 'FFDEE2E6'],
            ]],
        ];

        // ── العنوان ──
        $ws->mergeCells('A1:N1');
        $ws->setCellValue('A1', 'كشف الحسابات الشامل — نظام نماء — ' . now()->format('Y-m-d'));
        $ws->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 15, 'color' => ['argb' => $WHITE]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $AMBER_DRK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(36);

        // ── الفلاتر المطبقة ──
        $filterParts = [];
        if ($request->filled('search'))     $filterParts[] = 'بحث: ' . $request->search;
        if ($request->filled('cashbox_id')) $filterParts[] = 'الصندوق: #' . $request->cashbox_id;
        if ($request->filled('type'))       $filterParts[] = 'النوع: ' . ($typeLabels[$request->type] ?? $request->type);
        if ($request->filled('status'))     $filterParts[] = 'الحالة: ' . ($request->status === 'posted' ? 'مُرحّل' : 'معلّق');
        if ($request->filled('currency'))   $filterParts[] = 'العملة: ' . $request->currency;
        if ($request->filled('date_from'))  $filterParts[] = 'من: ' . $request->date_from;
        if ($request->filled('date_to'))    $filterParts[] = 'إلى: ' . $request->date_to;
        if ($request->filled('amount_min')) $filterParts[] = 'المبلغ من: ' . $request->amount_min;
        if ($request->filled('amount_max')) $filterParts[] = 'المبلغ إلى: ' . $request->amount_max;

        $filterText = count($filterParts)
            ? implode('  |  ', $filterParts)
            : 'جميع الحركات بدون فلترة';

        $ws->mergeCells('A2:N2');
        $ws->setCellValue('A2', $filterText);
        $ws->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF' . $AMBER_DRK]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF3E0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(20);

        $ws->mergeCells('A3:N3');
        $ws->setCellValue('A3', 'عدد الحركات: ' . $transactions->count());
        $ws->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 9, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(3)->setRowHeight(16);

        // ── رؤوس الأعمدة ──
        $headers = [
            'A' => ['#',                4],
            'B' => ['التاريخ',        13],
            'C' => ['الصندوق',        18],
            'D' => ['الفرع',          15],
            'E' => ['النوع',          10],
            'F' => ['الطالب/الشخص',  22],
            'G' => ['الدبلومة',       18],
            'H' => ['التصنيف',        16],
            'I' => ['التصنيف الثانوي',18],
            'J' => ['المبلغ',         14],
            'K' => ['العملة',          9],
            'L' => ['مبلغ أجنبي',    13],
            'M' => ['مرجع',           15],
            'N' => ['الحالة',         11],
        ];

        foreach ($headers as $col => [$label, $width]) {
            $ws->setCellValue("{$col}4", $label);
            $ws->getColumnDimension($col)->setWidth($width);
        }

        $ws->getStyle('A4:N4')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $AMBER_DRK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
        ]);
        $ws->getRowDimension(4)->setRowHeight(26);

        // ── البيانات ──
        $row      = 5;
        $totalIn  = 0;
        $totalOut = 0;

        if ($transactions->isEmpty()) {
            $ws->mergeCells('A5:N5');
            $ws->setCellValue('A5', 'لا توجد حركات مطابقة للفلترة المحددة');
            $ws->getStyle('A5')->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['argb' => 'FF94A3B8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $ws->getRowDimension(5)->setRowHeight(30);
            $row = 6;
        } else {
            foreach ($transactions as $idx => $t) {
                $rowBg      = ($idx % 2 === 0) ? 'FFF8F9FA' : 'FFFFFFFF';
                $personName = optional(optional($t->account)->accountable)->full_name ?? '-';
                $statusInfo = $STATUS_STYLES[$t->status] ?? ['label' => $t->status, 'fg' => 'FF6C757D'];
                $typeInfo   = $TYPE_STYLES[$t->type]     ?? ['bg' => 'FFF8F9FA', 'fg' => 'FF6C757D'];

                $ws->setCellValue("A{$row}", $idx + 1);
                $ws->setCellValue("B{$row}", $t->trx_date->format('Y-m-d'));
                $ws->setCellValue("C{$row}", optional($t->cashbox)->name ?? '-');
                $ws->setCellValue("D{$row}", optional(optional($t->cashbox)->branch)->name ?? '-');
                $ws->setCellValue("E{$row}", $typeLabels[$t->type] ?? $t->type);
                $ws->setCellValue("F{$row}", $personName);
                $ws->setCellValue("G{$row}", optional($t->diploma)->name ?? '-');
                $ws->setCellValue("H{$row}", $t->category ?? '-');
                $ws->setCellValue("I{$row}", $t->sub_category ?? '-');
                $ws->setCellValue("J{$row}", (float) $t->amount);
                $ws->setCellValue("K{$row}", $t->currency);
                $ws->setCellValue("L{$row}", $t->foreign_amount
                    ? number_format((float) $t->foreign_amount, 2) . ' ' . $t->foreign_currency
                    : '-');
                $ws->setCellValue("M{$row}", $t->reference ?? '-');
                $ws->setCellValue("N{$row}", $statusInfo['label']);

                $ws->getStyle("A{$row}:N{$row}")->applyFromArray(array_merge($allBorders, [
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]));

                $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $ws->getStyle("J{$row}")->getFont()->setBold(true);

                $ws->getStyle("E{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => $typeInfo['fg']]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $typeInfo['bg']]],
                ]);

                $ws->getStyle("N{$row}")->getFont()
                    ->setColor(new Color($statusInfo['fg']))->setBold(true);

                foreach (['C', 'D', 'F', 'G', 'H', 'I', 'M'] as $tc) {
                    $ws->getStyle("{$tc}{$row}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                if ($t->type === 'in') {
                    $ws->getStyle("J{$row}")->getFont()->setColor(new Color('FF1B5E20'));
                    if ($t->status === 'posted') {
                        $totalIn += (float) $t->amount;
                    }
                } elseif (in_array($t->type, ['out', 'exchange'])) {
                    $ws->getStyle("J{$row}")->getFont()->setColor(new Color('FFB71C1C'));
                    if ($t->status === 'posted') {
                        $totalOut += (float) $t->amount;
                    }
                }

                $ws->getRowDimension($row)->setRowHeight(22);
                $row++;
            }
        }

        // ── إجمالي المقبوض ──
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", 'إجمالي المقبوض (posted)');
        $ws->setCellValue("J{$row}", $totalIn);
        $ws->getStyle("A{$row}:N{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1B5E20']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getRowDimension($row)->setRowHeight(26);
        $row++;

        // ── إجمالي المدفوع ──
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", 'إجمالي المدفوع والتصريف (posted)');
        $ws->setCellValue("J{$row}", $totalOut);
        $ws->getStyle("A{$row}:N{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFB71C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getRowDimension($row)->setRowHeight(26);
        $row++;

        // ── الصافي ──
        $net = $totalIn - $totalOut;
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", 'الصافي');
        $ws->setCellValue("J{$row}", $net);
        $ws->getStyle("A{$row}:N{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => $WHITE]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $AMBER_DRK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getStyle("J{$row}")->getFont()
            ->setColor(new Color($net >= 0 ? 'FFFFF176' : 'FFFFCDD2'));
        $ws->getRowDimension($row)->setRowHeight(30);

        $ws->freezePane('A5');
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'كشف-الحسابات-' . now()->format('Y-m-d') . '.xlsx';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}