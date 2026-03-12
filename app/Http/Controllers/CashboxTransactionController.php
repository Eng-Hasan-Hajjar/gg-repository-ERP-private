<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class CashboxTransactionController extends Controller
{

    public function index(Request $request, Cashbox $cashbox)
    {
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

        return view(
            'cashboxes.transactions.index',
            compact('cashbox', 'transactions', 'postedIn', 'postedOut')
        );
    }



    /*
        public function index(Request $request, Cashbox $cashbox)
        {
            $q = $cashbox->transactions()->newQuery();

            if ($request->filled('type'))   $q->where('type', $request->type);
            if ($request->filled('status')) $q->where('status', $request->status);

            if ($request->filled('search')) {
                $s = trim($request->search);
                $q->where(function($x) use ($s){
                    $x->where('category','like',"%$s%")
                      ->orWhere('reference','like',"%$s%")
                      ->orWhere('notes','like',"%$s%");
                });
            }

            $transactions = $q->latest('trx_date')->paginate(20)->withQueryString();

            // ملخص سريع
            $postedIn  = (float) $cashbox->transactions()->where('status','posted')->where('type','in')->sum('amount');
            $postedOut = (float) $cashbox->transactions()->where('status','posted')->where('type','out')->sum('amount');

            return view('cashboxes.transactions.index', compact('cashbox','transactions','postedIn','postedOut'));
        }
    */
    public function create(Cashbox $cashbox)
    {
        return view('cashboxes.transactions.create', compact('cashbox'));
    }

    public function store(Request $request, Cashbox $cashbox)
    {
        $data = $request->validate([
            'trx_date' => ['required', 'date'],
            'type' => ['required', 'in:in,out,transfer'],
            'to_cashbox_id' => ['required_if:type,transfer', 'nullable', 'exists:cashboxes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $data['currency'] = $cashbox->currency;
        $data['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }



        // إذا كان نوعه مناقلة
        if (($data['type'] ?? '') === 'transfer') {
            $toCashbox = Cashbox::findOrFail($data['to_cashbox_id']);

            if ($toCashbox->currency !== $cashbox->currency) {
                return back()->withErrors(['to_cashbox_id' => 'العملة يجب أن تكون متطابقة بين الصندوقين'])->withInput();
            }

            $commonData = [
                'trx_date' => $data['trx_date'],           // ← أضف هذا (من الطلب)
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'notes' => $data['notes'],
                'status' => 'draft',
                'attachment_path' => $data['attachment_path'] ?? null,
            ];

            // حركة الخروج (من الصندوق الحالي)
            $outTransaction = $cashbox->transactions()->create(array_merge($commonData, [
                'type' => 'out',
                'category' => 'مناقلة إلى ' . $toCashbox->name,
                'reference' => $data['reference'] ? $data['reference'] . ' (مناقلة)' : 'مناقلة',
            ]));

            // حركة الدخول (إلى الصندوق الوجهة)
            $inTransaction = $toCashbox->transactions()->create(array_merge($commonData, [
                'type' => 'in',
                'category' => 'مناقلة من ' . $cashbox->name,
                'reference' => $data['reference'] ? $data['reference'] . ' (مناقلة)' : 'مناقلة',
            ]));

            $successMessage = 'تم تسجيل عملية المناقلة بنجاح.';
        } else {
            // الحالات العادية (in/out)
            $cashbox->transactions()->create($data);
            $successMessage = 'تم إضافة الحركة بنجاح.';
        }


        // $cashbox->transactions()->create($data);
return redirect()->route('cashboxes.show', $cashbox)
                 ->with('success', $successMessage ?? 'تم إضافة الحركة بنجاح.');
      //  return redirect()->route('cashboxes.transactions.index', $cashbox)->with('success', 'تم إضافة الحركة بنجاح.');
    }

    public function edit(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        return view('cashboxes.transactions.edit', compact('cashbox', 'transaction'));
    }

public function update(Request $request, Cashbox $cashbox, CashboxTransaction $transaction)
{
    abort_unless($transaction->cashbox_id === $cashbox->id, 404);

    // إذا كانت الحركة مناقلة، نسمح فقط بتعديل الصندوق الوجهة + الحقول العادية
    if ($transaction->type === 'transfer') {
        $data = $request->validate([
            'trx_date'       => ['required', 'date'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'to_cashbox_id'  => ['required', 'exists:cashboxes,id'], // مطلوب للمناقلة
            'category'       => ['nullable', 'string', 'max:255'],
            'reference'      => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string', 'max:5000'],
            'attachment'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        // منع تغيير النوع (لأننا لا نرسله أصلاً في الـ form)
        unset($data['type']); // احتياطي

        $toCashbox = Cashbox::findOrFail($data['to_cashbox_id']);

        if ($toCashbox->currency !== $cashbox->currency) {
            return back()->withErrors(['to_cashbox_id' => 'العملة يجب أن تكون متطابقة'])->withInput();
        }

        // تحديث الحركة الحالية (out)
        $transaction->update($data);

        // تحديث الحركة المقابلة (in) في الصندوق الجديد
        // (افتراضًا أنك حفظت related_transaction_id أو تبحث عنها)
        // لو ما عندك related_transaction_id، يمكن البحث بطريقة مؤقتة (مثال بسيط):
        $related = CashboxTransaction::where('type', 'in')
            ->where('amount', $transaction->amount)
            ->where('trx_date', $transaction->trx_date)
            ->where('notes', $transaction->notes)
            ->first();

        if ($related) {
            $related->update([
                'cashbox_id' => $toCashbox->id,
                'category'   => 'مناقلة من ' . $cashbox->name,
            ]);
        }

        return redirect()->route('cashboxes.show', $cashbox)
                         ->with('success', 'تم تعديل وجهة المناقلة بنجاح.');
    }

    // الحالات العادية (in/out)
    $data = $request->validate([
        'trx_date'       => ['required', 'date'],
        'type'           => ['required', 'in:in,out'],
        'amount'         => ['required', 'numeric', 'min:0.01'],
        'category'       => ['nullable', 'string', 'max:255'],
        'reference'      => ['nullable', 'string', 'max:255'],
        'notes'          => ['nullable', 'string', 'max:5000'],
        'attachment'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
    ]);

    $data['currency'] = $cashbox->currency;

    if ($request->hasFile('attachment')) {
        $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
    }

    $transaction->update($data);

    return redirect()->route('cashboxes.show', $cashbox)
                     ->with('success', 'تم تحديث الحركة بنجاح.');
}

/*
    public function update(Request $request, Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);

        $data = $request->validate([
            'trx_date' => ['required', 'date'],
            'type' => ['required', 'in:in,out,transfer'],
            'to_cashbox_id' => ['required_if:type,transfer', 'nullable', 'exists:cashboxes,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $data['currency'] = $cashbox->currency;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }

        $transaction->update($data);

        return redirect()->route('cashboxes.transactions.index', $cashbox)->with('success', 'تم تحديث الحركة.');
    }
*/
    public function destroy(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        $transaction->delete();

        return redirect()->route('cashboxes.transactions.index', $cashbox)->with('success', 'تم حذف الحركة.');
    }

    // زر سريع: ترحيل (posted)
    public function post(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);

        if ($transaction->status !== 'posted') {
            $transaction->update([
                'status' => 'posted',
                'posted_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'تم ترحيل الحركة (Posted).');
    }





    public function exportPdf(Request $request, Cashbox $cashbox)
    {
        $q = $cashbox->transactions()
            ->with(['account.accountable', 'diploma']);

        // الفلاتر نفسها الموجودة عندك
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($query) use ($s) {
                $query->where('category', 'like', "%{$s}%")
                    ->orWhere('reference', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($request->filled('only_students')) {
            $q->whereNotNull('financial_account_id'); // حسب شرطك
        }

        // الترتيب
        $sort = $request->input('sort', 'trx_date');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['trx_date', 'amount', 'id'];

        if (!in_array($sort, $allowedSorts))
            $sort = 'trx_date';
        if (!in_array($direction, ['asc', 'desc']))
            $direction = 'desc';

        $transactions = $q->orderBy($sort, $direction)->get();

        // الإجماليات (اختياري)
        $postedIn = (float) $q->clone()->where('status', 'posted')->where('type', 'in')->sum('amount');
        $postedOut = (float) $q->clone()->where('status', 'posted')->where('type', 'out')->sum('amount');

        $pdf = PDF::loadView('cashboxes.transactions.pdf', compact('cashbox', 'transactions', 'postedIn', 'postedOut'));

        // أزل lowquality إذا كان موجودًا، وأضف هذه الخيارات
        $pdf->setOption('lowquality', false);                  // أو احذفه تمامًا
        $pdf->setOption('disable-smart-shrinking', true);      // ← مهم جدًا في كثير من حالات الـ empty pdf
        $pdf->setOption('encoding', 'UTF-8');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('no-outline', true);
        $pdf->setOption('print-media-type', true);            // يساعد في بعض الحالات

        return $pdf->setPaper('a4', 'portrait')
            ->inline('حركات-الصندوق.pdf');
    }


}
