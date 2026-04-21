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
}
