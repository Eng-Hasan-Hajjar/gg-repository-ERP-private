<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;

class AssetReportController extends Controller
{
    public function index(Request $request)
    {
        // إجمالي حسب العملة
        $totalByCurrency = Asset::query()
            ->whereNotNull('purchase_cost')        // ✅ purchase_cost
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->currency, fn($q) => $q->where('currency', $request->currency))
            ->selectRaw('currency, SUM(purchase_cost) as total, COUNT(*) as count')
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        // إجمالي حسب الفرع والعملة
        $byBranchAndCurrency = Asset::query()
            ->with('branch')
            ->whereNotNull('purchase_cost')        // ✅ purchase_cost
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->currency, fn($q) => $q->where('currency', $request->currency))
            ->selectRaw('branch_id, currency, SUM(purchase_cost) as total, COUNT(*) as count')
            ->groupBy('branch_id', 'currency')
            ->orderBy('branch_id')
            ->get()
            ->groupBy('branch_id');

        // قائمة الأصول التفصيلية
        $assets = Asset::query()
            ->with(['branch', 'category'])
            ->whereNotNull('purchase_cost')        // ✅ purchase_cost
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->currency, fn($q) => $q->where('currency', $request->currency))
            ->latest()
            ->get();

        $branches = Branch::orderBy('name')->get();

        return view('assets.report', compact(
            'totalByCurrency',
            'byBranchAndCurrency',
            'assets',
            'branches'
        ));
    }
    public function export(Request $request)
    {
        $assets = Asset::query()
            ->with(['branch', 'category'])
            ->whereNotNull('purchase_cost')
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->currency, fn($q) => $q->where('currency', $request->currency))
            ->latest()
            ->get();

        // ── إحصائيات للملف ──
        $totalByCurrency = $assets->groupBy('currency')->map(fn($g) => [
            'total' => $g->sum('purchase_cost'),
            'count' => $g->count(),
        ]);

        $byBranch = $assets->groupBy(fn($a) => ($a->branch->name ?? 'غير محدد'))
            ->map(fn($g) => $g->groupBy('currency')->map(fn($cg) => [
                'total' => $cg->sum('purchase_cost'),
                'count' => $cg->count(),
            ]));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setTitle('التقرير المالي للأصول')
            ->setCreator('Namaa ERP');

        // ════════════════════════════════════════
        // SHEET 1: التقرير الرئيسي
        // ════════════════════════════════════════
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('التقرير المالي');
        $ws->setRightToLeft(true);

        $green = '1D6A4A';
        $green2 = '10b981';
        $teal = '0d9488';
        $white = 'FFFFFF';
        $gray1 = 'F1F5F9';
        $gray2 = 'E2E8F0';
        $dark = '0F172A';
        $gold = 'F59E0B';

        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => $white], 'size' => 14, 'name' => 'Arial'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $green]],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];

        $styleSectionTitle = [
            'font' => ['bold' => true, 'color' => ['rgb' => $white], 'size' => 11, 'name' => 'Arial'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $teal]],
            'alignment' => ['horizontal' => 'right', 'vertical' => 'center'],
        ];

        $styleColHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => $dark], 'name' => 'Arial'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $gray2]],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CBD5E1']]],
        ];

        $styleTotal = [
            'font' => ['bold' => true, 'color' => ['rgb' => $dark], 'name' => 'Arial'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D1FAE5']],
            'alignment' => ['horizontal' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '6EE7B7']]],
        ];

        $row = 1;

        // ── صف العنوان ──
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", '📊 التقرير المالي للأصول — نظام نماء أكاديمي');
        $ws->getStyle("A{$row}")->applyFromArray($styleHeader);
        $ws->getRowDimension($row)->setRowHeight(35);
        $row++;

        // ── معلومات الفلتر ──
        $ws->mergeCells("A{$row}:I{$row}");
        $filterInfo = 'تاريخ التقرير: ' . now()->format('Y-m-d H:i');
        if ($request->branch_id)
            $filterInfo .= ' | الفرع: ' . (\App\Models\Branch::find($request->branch_id)->name ?? '-');
        if ($request->currency)
            $filterInfo .= ' | العملة: ' . $request->currency;
        if ($request->condition)
            $filterInfo .= ' | الحالة: ' . ['good' => 'جيد', 'maintenance' => 'صيانة', 'retired' => 'خارج الخدمة'][$request->condition];
        $ws->setCellValue("A{$row}", $filterInfo);
        $ws->getStyle("A{$row}")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B'], 'name' => 'Arial', 'size' => 10],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => 'right'],
        ]);
        $ws->getRowDimension($row)->setRowHeight(20);
        $row++;
        $row++; // فراغ

        // ════════════════════════════════════════
        // قسم 1: الإجمالي حسب العملة
        // ════════════════════════════════════════
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", '💰 الإجمالي حسب العملة');
        $ws->getStyle("A{$row}")->applyFromArray($styleSectionTitle);
        $ws->getRowDimension($row)->setRowHeight(25);
        $row++;

        // رأس الجدول
        $ws->setCellValue("A{$row}", 'العملة');
        $ws->setCellValue("B{$row}", 'عدد الأصول');
        $ws->setCellValue("C{$row}", 'إجمالي القيمة');
        foreach (['A', 'B', 'C'] as $col) {
            $ws->getStyle("{$col}{$row}")->applyFromArray($styleColHeader);
        }
        $row++;

        $grandTotal = 0;
        foreach ($totalByCurrency as $currency => $data) {
            $ws->setCellValue("A{$row}", $currency);
            $ws->setCellValue("B{$row}", $data['count']);
            $ws->setCellValue("C{$row}", number_format($data['total'], 2) . ' ' . $currency);
            $ws->getStyle("A{$row}:C{$row}")->applyFromArray([
                'font' => ['name' => 'Arial'],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0FDF4']],
                'alignment' => ['horizontal' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'D1FAE5']]],
            ]);
            $ws->getStyle("C{$row}")->getFont()->setBold(true)->getColor()->setRGB('059669');
            $grandTotal += $data['total'];
            $row++;
        }

        // صف الإجمالي الكلي
        $ws->mergeCells("A{$row}:B{$row}");
        $ws->setCellValue("A{$row}", 'الإجمالي الكلي');
        $ws->setCellValue("C{$row}", number_format($grandTotal, 2));
        $ws->getStyle("A{$row}:C{$row}")->applyFromArray($styleTotal);
        $ws->getStyle("C{$row}")->getFont()->setBold(true)->getColor()->setRGB('065F46');
        $row++;
        $row++; // فراغ

        // ════════════════════════════════════════
        // قسم 2: الإجمالي حسب الفرع والعملة
        // ════════════════════════════════════════
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", '🏢 الإجمالي حسب الفرع والعملة');
        $ws->getStyle("A{$row}")->applyFromArray($styleSectionTitle);
        $ws->getRowDimension($row)->setRowHeight(25);
        $row++;

        // رأس الجدول
        foreach (['A' => 'الفرع', 'B' => 'العملة', 'C' => 'عدد الأصول', 'D' => 'إجمالي القيمة'] as $col => $label) {
            $ws->setCellValue("{$col}{$row}", $label);
            $ws->getStyle("{$col}{$row}")->applyFromArray($styleColHeader);
        }
        $row++;

        foreach ($byBranch as $branchName => $currencies) {
            $isFirst = true;
            foreach ($currencies as $currency => $data) {
                $ws->setCellValue("A{$row}", $isFirst ? $branchName : '');
                $ws->setCellValue("B{$row}", $currency);
                $ws->setCellValue("C{$row}", $data['count']);
                $ws->setCellValue("D{$row}", number_format($data['total'], 2) . ' ' . $currency);
                $fillColor = $isFirst ? 'EFF6FF' : 'F8FAFF';
                $ws->getStyle("A{$row}:D{$row}")->applyFromArray([
                    'font' => ['name' => 'Arial'],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $fillColor]],
                    'alignment' => ['horizontal' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'BFDBFE']]],
                ]);
                if ($isFirst) {
                    $ws->getStyle("A{$row}")->getFont()->setBold(true);
                }
                $ws->getStyle("D{$row}")->getFont()->setBold(true)->getColor()->setRGB('1D4ED8');
                $isFirst = false;
                $row++;
            }
        }
        $row++; // فراغ

        // ════════════════════════════════════════
        // قسم 3: تفاصيل الأصول
        // ════════════════════════════════════════
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", '📦 تفاصيل الأصول');
        $ws->getStyle("A{$row}")->applyFromArray($styleSectionTitle);
        $ws->getRowDimension($row)->setRowHeight(25);
        $row++;

        // رأس الأعمدة
        $headers = [
            'A' => '#',
            'B' => 'اسم الأصل',
            'C' => 'رمز الأصل',
            'D' => 'الفرع',
            'E' => 'التصنيف',
            'F' => 'الحالة',
            'G' => 'الكمية',
            'H' => 'سعر الشراء',
            'I' => 'تاريخ الشراء'
        ];
        foreach ($headers as $col => $label) {
            $ws->setCellValue("{$col}{$row}", $label);
            $ws->getStyle("{$col}{$row}")->applyFromArray($styleColHeader);
        }
        $headerRow = $row;
        $row++;

        $conditionMap = ['good' => 'جيد', 'maintenance' => 'صيانة', 'out_of_service' => 'خارج الخدمة', 'retired' => 'خارج الخدمة'];
        $conditionColors = ['good' => 'D1FAE5', 'maintenance' => 'FEF3C7', 'out_of_service' => 'FEE2E2', 'retired' => 'FEE2E2'];

        $dataStartRow = $row;
        foreach ($assets as $i => $a) {
            $isEven = ($i % 2 === 0);
            $baseFill = $isEven ? 'FFFFFF' : 'F8FAFC';
            $condColor = $conditionColors[$a->condition] ?? 'F1F5F9';

            $ws->setCellValue("A{$row}", $i + 1);
            $ws->setCellValue("B{$row}", $a->name);
            $ws->setCellValue("C{$row}", $a->asset_tag ?? '-');
            $ws->setCellValue("D{$row}", $a->branch->name ?? '-');
            $ws->setCellValue("E{$row}", $a->category->name ?? '-');
            $ws->setCellValue("F{$row}", $conditionMap[$a->condition] ?? $a->condition);
            $ws->setCellValue("G{$row}", $a->quantity ?? 1);
            $ws->setCellValue("H{$row}", number_format($a->purchase_cost, 2) . ' ' . ($a->currency ?? 'USD'));
            $ws->setCellValue("I{$row}", $a->purchase_date?->format('Y-m-d') ?? '-');

            $ws->getStyle("A{$row}:I{$row}")->applyFromArray([
                'font' => ['name' => 'Arial', 'size' => 10],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $baseFill]],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'E2E8F0']]],
            ]);

            // لون خلية الحالة
            $ws->getStyle("F{$row}")->getFill()->setFillType('solid')
                ->getStartColor()->setRGB($condColor);

            // سعر الشراء بخط أخضر وعريض
            $ws->getStyle("H{$row}")->getFont()->setBold(true)->getColor()->setRGB('059669');

            $ws->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        $dataEndRow = $row - 1;

        // ── صف المجاميع النهائية ──
        $ws->mergeCells("A{$row}:G{$row}");
        $ws->setCellValue("A{$row}", 'الإجمالي: ' . $assets->count() . ' أصل');
        $ws->setCellValue("H{$row}", '=SUM(H' . $dataStartRow . ':H' . $dataEndRow . ')'); // formula placeholder
        // نستخدم الإجمالي المحسوب مسبقاً
        $totalVal = $assets->groupBy('currency')->map(fn($g) => $g->sum('purchase_cost') . ' ' . $g->first()->currency)->implode(' | ');
        $ws->setCellValue("H{$row}", $totalVal);
        $ws->setCellValue("I{$row}", now()->format('Y-m-d'));
        $ws->getStyle("A{$row}:I{$row}")->applyFromArray($styleTotal);
        $ws->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('065F46');

        // ── تعريض الأعمدة ──
        $ws->getColumnDimension('A')->setWidth(6);
        $ws->getColumnDimension('B')->setWidth(28);
        $ws->getColumnDimension('C')->setWidth(18);
        $ws->getColumnDimension('D')->setWidth(16);
        $ws->getColumnDimension('E')->setWidth(18);
        $ws->getColumnDimension('F')->setWidth(14);
        $ws->getColumnDimension('G')->setWidth(10);
        $ws->getColumnDimension('H')->setWidth(18);
        $ws->getColumnDimension('I')->setWidth(16);

        // Freeze header row
        $ws->freezePane("A" . ($headerRow + 1));

        // Auto filter على رأس جدول التفاصيل
        $ws->setAutoFilter("A{$headerRow}:I{$dataEndRow}");

        // ════════════════════════════════════════
        // SHEET 2: ملخص سريع
        // ════════════════════════════════════════
        $ws2 = $spreadsheet->createSheet();
        $ws2->setTitle('ملخص');
        $ws2->setRightToLeft(true);

        $ws2->mergeCells('A1:D1');
        $ws2->setCellValue('A1', 'ملخص التقرير المالي');
        $ws2->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $white], 'size' => 16, 'name' => 'Arial'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $green]],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $ws2->getRowDimension(1)->setRowHeight(40);

        $r2 = 3;
        $summaryItems = [
            ['إجمالي الأصول', $assets->count()],
            ['إجمالي القيم', number_format($assets->sum('purchase_cost'), 2)],
            ['عدد الفروع', $byBranch->count()],
            ['عدد العملات', $totalByCurrency->count()],
            ['أصول بحالة جيد', $assets->where('condition', 'good')->count()],
            ['أصول في الصيانة', $assets->where('condition', 'maintenance')->count()],
            ['أصول خارج الخدمة', $assets->whereIn('condition', ['out_of_service', 'retired'])->count()],
        ];

        foreach ($summaryItems as [$label, $value]) {
            $ws2->setCellValue("A{$r2}", $label);
            $ws2->setCellValue("B{$r2}", $value);
            $ws2->getStyle("A{$r2}")->applyFromArray([
                'font' => ['bold' => true, 'name' => 'Arial'],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $gray1]],
                'alignment' => ['horizontal' => 'right'],
            ]);
            $ws2->getStyle("B{$r2}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $green], 'size' => 12, 'name' => 'Arial'],
                'alignment' => ['horizontal' => 'center'],
            ]);
            $ws2->getRowDimension($r2)->setRowHeight(22);
            $r2++;
        }

        $ws2->getColumnDimension('A')->setWidth(25);
        $ws2->getColumnDimension('B')->setWidth(20);

        // ── تفعيل الورقة الأولى ──
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'assets-financial-report-' . now()->format('Y-m-d') . '.xlsx';

        while (ob_get_level())
            ob_end_clean();

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}