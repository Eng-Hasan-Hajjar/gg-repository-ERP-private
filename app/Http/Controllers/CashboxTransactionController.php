<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxTransaction;
use Illuminate\Http\Request;

class CashboxTransactionController extends Controller
{
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

    public function create(Cashbox $cashbox)
    {
        return view('cashboxes.transactions.create', compact('cashbox'));
    }

    public function store(Request $request, Cashbox $cashbox)
    {
        $data = $request->validate([
            'trx_date' => ['required','date'],
            'type' => ['required','in:in,out'],
            'amount' => ['required','numeric','min:0.01'],
            'category' => ['nullable','string','max:255'],
            'reference' => ['nullable','string','max:255'],
            'notes' => ['nullable','string','max:5000'],
            'attachment' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        $data['currency'] = $cashbox->currency;
        $data['status'] = 'draft';

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }

        $cashbox->transactions()->create($data);

        return redirect()->route('cashboxes.transactions.index',$cashbox)->with('success','تم إضافة الحركة بنجاح.');
    }

    public function edit(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        return view('cashboxes.transactions.edit', compact('cashbox','transaction'));
    }

    public function update(Request $request, Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);

        $data = $request->validate([
            'trx_date' => ['required','date'],
            'type' => ['required','in:in,out'],
            'amount' => ['required','numeric','min:0.01'],
            'category' => ['nullable','string','max:255'],
            'reference' => ['nullable','string','max:255'],
            'notes' => ['nullable','string','max:5000'],
            'attachment' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        $data['currency'] = $cashbox->currency;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('finance/attachments', 'public');
        }

        $transaction->update($data);

        return redirect()->route('cashboxes.transactions.index',$cashbox)->with('success','تم تحديث الحركة.');
    }

    public function destroy(Cashbox $cashbox, CashboxTransaction $transaction)
    {
        abort_unless($transaction->cashbox_id === $cashbox->id, 404);
        $transaction->delete();

        return redirect()->route('cashboxes.transactions.index',$cashbox)->with('success','تم حذف الحركة.');
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

        return redirect()->back()->with('success','تم ترحيل الحركة (Posted).');
    }
}
