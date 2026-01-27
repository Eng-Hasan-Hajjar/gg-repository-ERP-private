<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeePayout;

class EmployeePayoutController extends Controller
{
 public function index(Employee $employee, Request $request)
{
    $query = $employee->payouts()->orderByDesc('payout_date');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $payouts = $query->get();

    // نحتاج أيضاً المجموعات كلها للملخص (بدون فلترة)
    $employee->load('payouts');

    return view('employees.payouts.index', compact('employee','payouts'));
}


    public function create(Employee $employee)
    {
        return view('employees.payouts.create', compact('employee'));
    }

    public function store(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'payout_date' => ['required','date'],
            'amount'      => ['required','numeric','min:0'],
            'currency'    => ['required','string','size:3'],
            'status'      => ['required','in:pending,paid'],
            'reference'   => ['nullable','string','max:255'],
            'notes'       => ['nullable','string','max:3000'],
        ]);

        $employee->payouts()->create($data);

        return redirect()->route('employees.payouts.index', $employee)->with('success','تم إضافة مستحق.');
    }

    
public function edit(Employee $employee, EmployeePayout $payout)
{
    abort_unless($payout->employee_id === $employee->id, 404);
    return view('employees.payouts.edit', compact('employee','payout'));
}

public function update(Request $request, Employee $employee, EmployeePayout $payout)
{
    abort_unless($payout->employee_id === $employee->id, 404);

    $data = $request->validate([
        'payout_date' => ['required','date'],
        'amount'      => ['required','numeric','min:0'],
        'currency'    => ['required','string','size:3'],
        'status'      => ['required','in:pending,paid'],
        'reference'   => ['nullable','string','max:255'],
        'notes'       => ['nullable','string','max:3000'],
    ]);

    $payout->update($data);

    return redirect()->route('employees.payouts.index', $employee)->with('success','تم تحديث المستحق.');
}

public function destroy(Employee $employee, EmployeePayout $payout)
{
    abort_unless($payout->employee_id === $employee->id, 404);

    $payout->delete();

    return redirect()->route('employees.payouts.index', $employee)->with('success','تم حذف المستحق.');
}


public function markPaid(Employee $employee, EmployeePayout $payout)
{
    abort_unless($payout->employee_id === $employee->id, 404);

    if ($payout->status !== 'paid') {
        $payout->update([
            'status' => 'paid',
        ]);
    }

    return redirect()
        ->route('employees.payouts.index', $employee)
        ->with('success', 'تم تحويل المستحق إلى "مدفوع".');
}


}
