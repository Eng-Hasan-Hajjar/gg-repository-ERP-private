<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Cashbox;
use Illuminate\Http\Request;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;



class CashboxController extends Controller
{
        // ── مصفوفة الأنواع المشتركة ──
    private array $typeMeta = [
        'in' => ['label' => 'مقبوض', 'color' => 'success'],
        'out' => ['label' => 'مدفوع', 'color' => 'danger'],
        'transfer' => ['label' => 'مناقلة', 'color' => 'warning'],
        'exchange' => ['label' => 'تصريف', 'color' => 'info'],
    ];


    public function index(Request $request ,Cashbox $cashbox)
    {
        $q = Cashbox::query()->with('branch');

        if ($request->filled('branch_id'))
            $q->where('branch_id', $request->branch_id);
        if ($request->filled('currency'))
            $q->where('currency', $request->currency);
        if ($request->filled('status'))
            $q->where('status', $request->status);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%$s%")->orWhere('code', 'like', "%$s%");
            });
        }


       

        return view('cashboxes.index', [
            'cashboxes' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
          

        ]);
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            $branches = Branch::orderBy('name')->get();
        } else {
            $branches = Branch::where('id', $user->employee?->branch_id)->get();
        }

        return view('cashboxes.create', [
            'branches' => $branches,
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:cashboxes,code'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:active,inactive'],
            'opening_balance' => ['nullable', 'numeric'],
        ]);

        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {
            $data['branch_id'] = $user->employee?->branch_id;
        }

        $cashbox = Cashbox::create($data);

        return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم إنشاء الصندوق بنجاح.');
    }

    public function show(Cashbox $cashbox, Request $request)
    {
        $cashbox->load('branch');




        $q = $cashbox->transactions()
            ->with(['account.accountable', 'diploma'])
            ->newQuery();

        if ($request->filled('type'))
            $q->where('type', $request->type);
        if ($request->filled('status'))
            $q->where('status', $request->status);

        if ($request->filled('only_students')) {
            $q->whereNotNull('financial_account_id');
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                    ->orWhere('reference', 'like', "%$s%")
                    ->orWhere('notes', 'like', "%$s%");
            });
        }

        // $transactions = $q->latest('trx_date')->paginate(20)->withQueryString();





        $sort = $request->get('sort', 'trx_date');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['trx_date', 'amount', 'id'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'trx_date';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $transactions = $q->orderBy($sort, $direction)
            ->paginate(20)
            ->withQueryString();






        $postedIn = (float) $cashbox->transactions()->where('status', 'posted')->where('type', 'in')->sum('amount');
        $postedOut = (float) $cashbox->transactions()->where('status', 'posted')->where('type', 'out')->sum('amount');

        $postedIn = (float) $cashbox->transactions()->where('status', 'posted')->where('type', 'in')->sum('amount');
        $postedOut = (float) $cashbox->transactions()->where('status', 'posted')->whereIn('type', ['out', 'exchange'])->sum('amount');

        // بعد سطر $transactions = $q->orderBy(...)->paginate(20)->withQueryString();

        $typeMeta = [
            'in' => ['label' => 'مقبوض', 'color' => 'success'],
            'out' => ['label' => 'مدفوع', 'color' => 'danger'],
            'transfer' => ['label' => 'مناقلة', 'color' => 'warning'],
            'exchange' => ['label' => 'تصريف', 'color' => 'info'],
        ];



          $q = $cashbox->transactions()
            ->with(['account.accountable', 'diploma'])
            ->newQuery();

        // فلتر النوع
        if ($request->filled('type'))
            $q->where('type', $request->type);

        // فلتر الحالة
        if ($request->filled('status'))
            $q->where('status', $request->status);

        // فلتر دفعات الطلاب فقط
        if ($request->filled('only_students'))
            $q->whereNotNull('financial_account_id');

        // فلتر التصنيف الرئيسي ← جديد
        if ($request->filled('category'))
            $q->where('category', $request->category);

        // بحث نصي
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                    ->orWhere('sub_category', 'like', "%$s%")
                    ->orWhere('reference', 'like', "%$s%")
                    ->orWhere('notes', 'like', "%$s%")
                    // ── البحث باسم الطالب عبر العلاقة ──
                    ->orWhereHas('account.accountable', function ($q2) use ($s) {
                        $q2->where('first_name', 'like', "%$s%")
                            ->orWhere('last_name', 'like', "%$s%")
                            ->orWhere('full_name', 'like', "%$s%");
                    });
            });
        }

        // ترتيب
        $sort = in_array($request->get('sort'), ['trx_date', 'amount', 'id'])
            ? $request->get('sort') : 'trx_date';
        $direction = in_array($request->get('direction'), ['asc', 'desc'])
            ? $request->get('direction') : 'desc';

        $transactions = $q->orderBy($sort, $direction)->paginate(20)->withQueryString();

        // إجماليات
        $postedIn = (float) $cashbox->transactions()->where('status', 'posted')->where('type', 'in')->sum('amount');
        $postedOut = (float) $cashbox->transactions()->where('status', 'posted')->whereIn('type', ['out', 'exchange'])->sum('amount');

        // التصنيفات للفلتر المنسدل
        $categories = \App\Models\CashboxTransaction::$CATEGORIES;




        return view('cashboxes.show', compact('cashbox', 'transactions', 'postedIn', 'postedOut', 'typeMeta'
          , 'cashbox',
            'transactions',
            'postedIn',
            'postedOut',
            'categories'
        ) + ['typeMeta' => $this->typeMeta]
        );

    }

    public function edit(Cashbox $cashbox)
    {
        return view('cashboxes.edit', [
            'cashbox' => $cashbox,
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Cashbox $cashbox)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:cashboxes,code,' . $cashbox->id],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:active,inactive'],
            'opening_balance' => ['nullable', 'numeric'],
        ]);

        $cashbox->update($data);

        return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم تحديث الصندوق.');
    }

    public function destroy(Cashbox $cashbox)
    {
        $cashbox->delete();
        return redirect()->route('cashboxes.index')->with('success', 'تم حذف الصندوق.');
    }






    


    // ══════════════════════════════════════════════════════════════
// أضف هذه الدالة داخل CashboxTransactionController
// وأضف الـ use في أعلى الملف:
//   use PhpOffice\PhpSpreadsheet\Spreadsheet;
//   use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//   use PhpOffice\PhpSpreadsheet\Style\{Fill, Font, Alignment, Border, Color};
//   use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//   use Symfony\Component\HttpFoundation\StreamedResponse;
// ══════════════════════════════════════════════════════════════

    public function exportExcel(Request $request, Cashbox $cashbox): StreamedResponse
    {
        // ── جلب البيانات مع نفس فلاتر الـ index ──
        $q = $cashbox->transactions()->with(['account.accountable', 'diploma'])->newQuery();

        if ($request->filled('type'))
            $q->where('type', $request->type);
        if ($request->filled('status'))
            $q->where('status', $request->status);
        if ($request->filled('category'))
            $q->where('category', $request->category);
        if ($request->filled('only_students'))
            $q->whereNotNull('financial_account_id');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(
                fn($x) => $x
                    ->where('category', 'like', "%$s%")
                    ->orWhere('sub_category', 'like', "%$s%")
                    ->orWhere('reference', 'like', "%$s%")
                    ->orWhere('notes', 'like', "%$s%")
            );
        }

        $sort = in_array($request->get('sort'), ['trx_date', 'amount', 'id']) ? $request->get('sort') : 'trx_date';
        $direction = in_array($request->get('direction'), ['asc', 'desc']) ? $request->get('direction') : 'desc';

        $transactions = $q->orderBy($sort, $direction)->get();

        // ── مصفوفة الأنواع ──
        $typeLabels = ['in' => 'مقبوض', 'out' => 'مدفوع', 'transfer' => 'مناقلة', 'exchange' => 'تصريف'];

        // ══════════════════════════════
        // بناء الـ Spreadsheet
        // ══════════════════════════════
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        // ── الألوان ──
        $TEAL = '11998E';
        $TEAL_DARK = '0D7A6B';
        $WHITE = 'FFFFFFFF';
        $GRAY_LIGHT = 'FFF8F9FA';
        $GRAY_MED = 'FFDEE2E6';

        $TYPE_STYLES = [
            'in' => ['bg' => 'FFE8F5E9', 'fg' => 'FF1B5E20'],
            'out' => ['bg' => 'FFFDECEA', 'fg' => 'FFB71C1C'],
            'transfer' => ['bg' => 'FFFFF3E0', 'fg' => 'FFE65100'],
            'exchange' => ['bg' => 'FFE3F2FD', 'fg' => 'FF0D47A1'],
        ];
        $STATUS_STYLES = [
            'posted' => ['label' => 'مُرحّل', 'bg' => 'FFE8F5E9', 'fg' => 'FF1B5E20'],
            'draft' => ['label' => 'معلّق', 'bg' => 'FFF8F9FA', 'fg' => 'FF6C757D'],
        ];

        // ══════════════════════════════════════════
        // الشيت 1: حركات الصندوق
        // ══════════════════════════════════════════
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('حركات الصندوق');
        $ws->setRightToLeft(true);

        // دالة مساعدة للحدود
        $allBorders = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDEE2E6']],
            ],
        ];

        // ── صف العنوان ──
        $ws->mergeCells('A1:L1');
        $ws->setCellValue('A1', 'كشف حركات الصندوق — ' . $cashbox->name);
        $ws->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(1)->setRowHeight(38);

        // ── صف المعلومات ──
        $ws->mergeCells('A2:D2');
        $ws->setCellValue('A2', 'الفرع: ' . ($cashbox->branch->name ?? '-'));
        $ws->mergeCells('E2:H2');
        $ws->setCellValue('E2', 'العملة: ' . $cashbox->currency);
        $ws->mergeCells('I2:L2');
        $ws->setCellValue('I2', 'تاريخ التصدير: ' . now()->format('Y-m-d'));
        $ws->getStyle('A2:L2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['argb' => 'FF' . $TEAL]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE1F5EE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getRowDimension(2)->setRowHeight(20);
        $ws->getRowDimension(3)->setRowHeight(6);

        // ── رؤوس الأعمدة ──
        $headers = [
            'A' => ['#', 4],
            'B' => ['التاريخ', 13],
            'C' => ['النوع', 10],
            'D' => ['الطالب', 22],
            'E' => ['الدبلومة', 22],
            'F' => ['التصنيف', 18],
            'G' => ['التصنيف الثانوي', 20],
            'H' => ['المرجع', 16],
            'I' => ['المبلغ', 14],
            'J' => ['العملة', 9],
            'K' => ['الحالة', 11],
            'L' => ['ملاحظات', 30],
        ];

        foreach ($headers as $col => [$label, $width]) {
            $ws->setCellValue("{$col}4", $label);
            $ws->getColumnDimension($col)->setWidth($width);
        }

        $ws->getStyle('A4:L4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL_DARK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
        ]);
        $ws->getRowDimension(4)->setRowHeight(26);

        // ── البيانات ──
        $row = 5;
        foreach ($transactions as $idx => $t) {
            $isEven = ($idx % 2 === 0);
            $rowBg = $isEven ? 'FFF8F9FA' : 'FFFFFFFF';

            $ws->setCellValue("A{$row}", $t->id);
            $ws->setCellValue("B{$row}", $t->trx_date->format('Y-m-d'));
            $ws->setCellValue("C{$row}", $typeLabels[$t->type] ?? $t->type);
            $ws->setCellValue("D{$row}", optional(optional($t->account)->accountable)->full_name ?? '-');
            $ws->setCellValue("E{$row}", optional($t->diploma)->name ?? '-');
            $ws->setCellValue("F{$row}", $t->category ?? '-');
            $ws->setCellValue("G{$row}", $t->sub_category ?? '-');
            $ws->setCellValue("H{$row}", $t->reference ?? '-');
            $ws->setCellValue("I{$row}", (float) $t->amount);
            $ws->setCellValue("J{$row}", $t->currency);
            $ws->setCellValue("K{$row}", $STATUS_STYLES[$t->status]['label'] ?? $t->status);
            $ws->setCellValue("L{$row}", $t->notes ?? '');

            // تنسيق الصف
            $ws->getStyle("A{$row}:L{$row}")->applyFromArray(array_merge($allBorders, [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]));

            // المبلغ
            $ws->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
            $ws->getStyle("I{$row}")->getFont()->setBold(true);

            // نوع ملوّن
            $ts = $TYPE_STYLES[$t->type] ?? ['bg' => 'FFF8F9FA', 'fg' => 'FF6C757D'];
            $ws->getStyle("C{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => $ts['fg']]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $ts['bg']]],
            ]);

            // حالة ملوّنة
            $ss = $STATUS_STYLES[$t->status] ?? ['bg' => 'FFF8F9FA', 'fg' => 'FF6C757D'];
            $ws->getStyle("K{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => $ss['fg']]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $ss['bg']]],
            ]);

            // محاذاة يمين للنصوص
            foreach (['D', 'E', 'F', 'G', 'L'] as $textCol) {
                $ws->getStyle("{$textCol}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }

            $ws->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        // ── صف الإجمالي ──
        $lastDataRow = $row - 1;
        $ws->mergeCells("A{$row}:H{$row}");
        $ws->setCellValue("A{$row}", 'إجمالي المقبوض (posted)');
        $ws->setCellValue("I{$row}", "=SUMPRODUCT((K5:K{$lastDataRow}=\"مُرحّل\")*(C5:C{$lastDataRow}=\"مقبوض\")*I5:I{$lastDataRow})");
        $ws->mergeCells("J{$row}:L{$row}");
        $ws->setCellValue("J{$row}", $cashbox->currency);

        $ws->getStyle("A{$row}:L{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getRowDimension($row)->setRowHeight(26);
        $row++;

        // إجمالي المدفوع
        $ws->mergeCells("A{$row}:H{$row}");
        $ws->setCellValue("A{$row}", 'إجمالي المدفوع والتصريف (posted)');
        $ws->setCellValue("I{$row}", "=SUMPRODUCT((K5:K{$lastDataRow}=\"مُرحّل\")*ISNUMBER(MATCH(C5:C{$lastDataRow},{\"مدفوع\",\"تصريف\"},0))*I5:I{$lastDataRow})");
        $ws->mergeCells("J{$row}:L{$row}");
        $ws->setCellValue("J{$row}", $cashbox->currency);

        $ws->getStyle("A{$row}:L{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFB71C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getRowDimension($row)->setRowHeight(26);

        // تجميد الرأس
        $ws->freezePane('A5');

        // ══════════════════════════════════════════
        // الشيت 2: ملخص التصنيف
        // ══════════════════════════════════════════
        $ws2 = $spreadsheet->createSheet();
        $ws2->setTitle('ملخص التصنيف');
        $ws2->setRightToLeft(true);

        $ws2->mergeCells('A1:D1');
        $ws2->setCellValue('A1', 'ملخص الحركات حسب التصنيف');
        $ws2->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws2->getRowDimension(1)->setRowHeight(32);

        foreach (['A' => ['التصنيف', 22], 'B' => ['إجمالي المقبوض', 18], 'C' => ['إجمالي المدفوع', 18], 'D' => ['الصافي', 16]] as $col => [$lbl, $w]) {
            $ws2->setCellValue("{$col}2", $lbl);
            $ws2->getColumnDimension($col)->setWidth($w);
        }
        $ws2->getStyle('A2:D2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL_DARK]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $WHITE]]],
        ]);
        $ws2->getRowDimension(2)->setRowHeight(26);

        $categories = array_keys(\App\Models\CashboxTransaction::$CATEGORIES);
        $r2 = 3;
        foreach ($categories as $cat) {
            $bg2 = ($r2 % 2 === 0) ? 'FFF8F9FA' : 'FFFFFFFF';
            $ws2->setCellValue("A{$r2}", $cat);
            $ws2->setCellValue("B{$r2}", "=SUMPRODUCT(('حركات الصندوق'!F5:'حركات الصندوق'!F{$lastDataRow}=\"{$cat}\")*('حركات الصندوق'!C5:'حركات الصندوق'!C{$lastDataRow}=\"مقبوض\")*'حركات الصندوق'!I5:'حركات الصندوق'!I{$lastDataRow})");
            $ws2->setCellValue("C{$r2}", "=SUMPRODUCT(('حركات الصندوق'!F5:'حركات الصندوق'!F{$lastDataRow}=\"{$cat}\")*('حركات الصندوق'!C5:'حركات الصندوق'!C{$lastDataRow}=\"مدفوع\")*'حركات الصندوق'!I5:'حركات الصندوق'!I{$lastDataRow})");
            $ws2->setCellValue("D{$r2}", "=B{$r2}-C{$r2}");

            $ws2->getStyle("A{$r2}:D{$r2}")->applyFromArray(array_merge($allBorders, [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg2]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]));
            $ws2->getStyle("A{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            foreach (['B', 'C'] as $mc) {
                $ws2->getStyle("{$mc}{$r2}")->getFont()->setColor(new Color($mc === 'B' ? 'FF1B5E20' : 'FFB71C1C'));
                $ws2->getStyle("{$mc}{$r2}")->getFont()->setBold(true);
            }
            foreach (['B', 'C', 'D'] as $nc) {
                $ws2->getStyle("{$nc}{$r2}")->getNumberFormat()->setFormatCode('#,##0.00');
            }
            $ws2->getRowDimension($r2)->setRowHeight(22);
            $r2++;
        }

        // إجمالي الشيت 2
        $lastCatRow = $r2 - 1;
        $ws2->setCellValue("A{$r2}", 'الإجمالي');
        foreach (['B', 'C', 'D'] as $tc) {
            $ws2->setCellValue("{$tc}{$r2}", "=SUM({$tc}3:{$tc}{$lastCatRow})");
            $ws2->getStyle("{$tc}{$r2}")->getNumberFormat()->setFormatCode('#,##0.00');
        }
        $ws2->getStyle("A{$r2}:D{$r2}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $WHITE]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $TEAL]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $ws2->getRowDimension($r2)->setRowHeight(26);

        $spreadsheet->setActiveSheetIndex(0);

        // ── إرسال الملف ──
        $filename = 'حركات-' . $cashbox->code . '-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }




    
}
