<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;


class CashboxTransactionController extends Controller
{
    // ── مصفوفة الأنواع المشتركة ──
    private array $typeMeta = [
        'in' => ['label' => 'مقبوض', 'color' => 'success'],
        'out' => ['label' => 'مدفوع', 'color' => 'danger'],
        'transfer' => ['label' => 'مناقلة', 'color' => 'warning'],
        'exchange' => ['label' => 'تصريف', 'color' => 'info'],
    ];

    // ══════════════════════════════════════════
    // قائمة الحركات
    // ══════════════════════════════════════════
    public function index(Request $request, Cashbox $cashbox)
    {
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

        return view('cashboxes.transactions.index', compact(
            'cashbox',
            'transactions',
            'postedIn',
            'postedOut',
            'categories'
        ) + ['typeMeta' => $this->typeMeta]);
    }

    // ══════════════════════════════════════════
    // API: تفاصيل حركة واحدة (للمودال)
    // ══════════════════════════════════════════
    public function detail(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);

        $transaction->load(['account.accountable', 'diploma', 'cashbox.branch']);

        $meta = $this->typeMeta[$transaction->type] ?? ['label' => $transaction->type, 'color' => 'secondary'];

        return response()->json([
            'id' => $transaction->id,
            'trx_date' => $transaction->trx_date->format('Y-m-d'),
            'type' => $transaction->type,
            'type_label' => $meta['label'],
            'type_color' => $meta['color'],
            'display_type' => $transaction->display_type,
            'amount' => number_format($transaction->amount, 2),
            'currency' => $transaction->currency,
            'category' => $transaction->category ?? '-',
            'sub_category' => $transaction->sub_category ?? '-',
            'reference' => $transaction->reference ?? '-',
            'notes' => $transaction->notes ?? '-',
            'status' => $transaction->status,
            'status_label' => $transaction->status === 'posted' ? 'مُرحّل' : 'معلّق',
            'status_color' => $transaction->status === 'posted' ? 'primary' : 'secondary',
            'posted_at' => $transaction->posted_at?->format('Y-m-d H:i') ?? '-',
            'student' => optional(optional($transaction->account)->accountable)->full_name ?? '-',
            'diploma' => optional($transaction->diploma)->name ?? '-',
            'cashbox_name' => $transaction->cashbox->name ?? '-',
            'branch_name' => optional($transaction->cashbox->branch)->name ?? '-',
            'foreign_currency' => $transaction->foreign_currency ?? null,
            'foreign_amount' => $transaction->foreign_amount ? number_format($transaction->foreign_amount, 2) : null,
            'attachment_url' => $transaction->attachment_path
                ? asset('storage/' . $transaction->attachment_path)
                : null,
        ]);
    }

    // ══════════════════════════════════════════
    // إنشاء حركة
    // ══════════════════════════════════════════
    public function create(Cashbox $cashbox)
    {
        return view('cashboxes.transactions.create', compact('cashbox'));
    }

    // ══════════════════════════════════════════
    // قواعد التحقق
    // ══════════════════════════════════════════
    private function validationRules(bool $isTransfer = false, bool $isExchange = false): array
    {
        return [
            'trx_date' => ['required', 'date'],
            'type' => ['required', 'in:in,out,transfer,exchange'],
            'to_cashbox_id' => $isTransfer ? ['required', 'exists:cashboxes,id'] : ['nullable', 'exists:cashboxes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['nullable', 'string', 'max:255'],
            'sub_category' => ['nullable', 'string', 'max:255'],
            'foreign_currency' => $isExchange ? ['required', 'string', 'size:3'] : ['nullable', 'string', 'size:3'],
            'foreign_amount' => $isExchange ? ['required', 'numeric', 'min:0.01'] : ['nullable', 'numeric', 'min:0'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    // ══════════════════════════════════════════
    // حفظ حركة جديدة
    // ══════════════════════════════════════════
    public function store(Request $request, Cashbox $cashbox)
    {
        $type = $request->input('type');
        $isTransfer = ($type === 'transfer');
        $isExchange = ($type === 'exchange');

        $data = $request->validate($this->validationRules($isTransfer, $isExchange));

        $data['currency'] = $cashbox->currency;
        $data['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }

        if ($isTransfer) {
            $toCashbox = Cashbox::findOrFail($data['to_cashbox_id']);
            if ($toCashbox->currency !== $cashbox->currency) {
                return back()->withErrors(['to_cashbox_id' => 'العملة يجب أن تكون متطابقة بين الصندوقين'])->withInput();
            }

            $common = [
                'trx_date' => $data['trx_date'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'sub_category' => $data['sub_category'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'draft',
                'attachment_path' => $data['attachment_path'] ?? null,
            ];

            $cashbox->transactions()->create(array_merge($common, [
                'type' => 'out',
                'category' => $data['category'] ?? 'مناقلة إلى ' . $toCashbox->name,
                'reference' => ($data['reference'] ?? '') . ' (مناقلة خارج)',
            ]));

            $toCashbox->transactions()->create(array_merge($common, [
                'type' => 'in',
                'category' => $data['category'] ?? 'مناقلة من ' . $cashbox->name,
                'reference' => ($data['reference'] ?? '') . ' (مناقلة دخول)',
            ]));

            return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم تسجيل عملية المناقلة بنجاح.');
        }

        // تصريف أو عادي — يُحفظ بنوعه الأصلي
        $cashbox->transactions()->create($data);

        return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم إضافة الحركة بنجاح.');
    }

    // ══════════════════════════════════════════
    // تعديل حركة
    // ══════════════════════════════════════════
    public function edit(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        return view('cashboxes.transactions.edit', compact('cashbox', 'transaction'));
    }

    public function update(Request $request, Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);

        $type = $transaction->type;
        $isTransfer = ($type === 'transfer');
        $isExchange = ($type === 'exchange');

        $rules = [
            'trx_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['nullable', 'string', 'max:255'],
            'sub_category' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];

        if ($isTransfer)
            $rules['to_cashbox_id'] = ['required', 'exists:cashboxes,id'];
        if ($isExchange) {
            $rules['foreign_currency'] = ['required', 'string', 'size:3'];
            $rules['foreign_amount'] = ['required', 'numeric', 'min:0.01'];
        }

        $data = $request->validate($rules);
        unset($data['type']);
        $data['currency'] = $cashbox->currency;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }

        if ($isTransfer) {
            $toCashbox = Cashbox::findOrFail($data['to_cashbox_id']);
            if ($toCashbox->currency !== $cashbox->currency) {
                return back()->withErrors(['to_cashbox_id' => 'العملة يجب أن تكون متطابقة'])->withInput();
            }
            $transaction->update($data);

            $related = CashboxTransaction::where('type', 'in')
                ->where('amount', $transaction->amount)
                ->where('trx_date', $transaction->trx_date)
                ->where('notes', $transaction->notes)->first();

            if ($related) {
                $related->update([
                    'cashbox_id' => $toCashbox->id,
                    'category' => 'مناقلة من ' . $cashbox->name,
                    'sub_category' => $data['sub_category'] ?? null,
                ]);
            }
            return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم تعديل المناقلة بنجاح.');
        }

        $transaction->update($data);
        return redirect()->route('cashboxes.show', $cashbox)->with('success', 'تم تحديث الحركة بنجاح.');
    }

    // ══════════════════════════════════════════
    // حذف
    // ══════════════════════════════════════════
    public function destroy(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        $transaction->delete();
        return redirect()->route('cashboxes.transactions.index', $cashbox)->with('success', 'تم حذف الحركة.');
    }

    // ══════════════════════════════════════════
    // ترحيل
    // ══════════════════════════════════════════
    public function post(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        if ($transaction->status !== 'posted') {
            $transaction->update(['status' => 'posted', 'posted_at' => now()]);
        }
        return redirect()->back()->with('success', 'تم ترحيل الحركة.');
    }

    // ══════════════════════════════════════════
    // تصدير PDF
    // ══════════════════════════════════════════
    public function exportPdf(Request $request, Cashbox $cashbox)
    {
        $q = $cashbox->transactions()->with(['account.accountable', 'diploma']);

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
            $q->where(function ($x) use ($s) {
                $x->where('category', 'like', "%$s%")
                    ->orWhere('reference', 'like', "%$s%")
                    ->orWhere('notes', 'like', "%$s%");
            });
        }

        $sort = in_array($request->input('sort'), ['trx_date', 'amount', 'id']) ? $request->input('sort') : 'trx_date';
        $direction = in_array($request->input('direction'), ['asc', 'desc']) ? $request->input('direction') : 'desc';

        $transactions = $q->orderBy($sort, $direction)->get();
        $postedIn = (float) $q->clone()->where('status', 'posted')->where('type', 'in')->sum('amount');
        $postedOut = (float) $q->clone()->where('status', 'posted')->whereIn('type', ['out', 'exchange'])->sum('amount');

        $pdf = PDF::loadView('cashboxes.transactions.pdf', compact('cashbox', 'transactions', 'postedIn', 'postedOut'));
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('encoding', 'UTF-8');
        $pdf->setOption('enable-local-file-access', true);

        return $pdf->setPaper('a4', 'portrait')->inline('حركات-الصندوق.pdf');
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



        while (ob_get_level()) {
            ob_end_clean();
        }


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