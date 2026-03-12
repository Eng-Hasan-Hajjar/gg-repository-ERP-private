<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Cashbox;
use Illuminate\Http\Request;

class CashboxController extends Controller
{
    public function index(Request $request)
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
        return view('cashboxes.create', [
            'branches' => Branch::orderBy('name')->get(),
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




        return view('cashboxes.show', compact('cashbox', 'transactions', 'postedIn', 'postedOut'));
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
}
