<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeContract;

use Illuminate\Http\Request;

class EmployeeContractController extends Controller
{
    public function index(Employee $employee)
    {
        $employee->load('contracts');
        return view('employees.contracts.index', compact('employee'));
    }

    public function create(Employee $employee)
    {
        return view('employees.contracts.create', compact('employee'));
    }

    public function store(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'start_date' => ['required','date'],
            'end_date'   => ['nullable','date','after_or_equal:start_date'],
            'contract_type' => ['required','in:full_time,part_time,freelance,hourly'],
            'salary_amount' => ['nullable','numeric','min:0'],
            'hour_rate'     => ['nullable','numeric','min:0'],
            'currency'      => ['required','string','size:3'],
            'notes'         => ['nullable','string','max:3000'],
        ]);

        $employee->contracts()->create($data);

        return redirect()->route('employees.contracts.index', $employee)->with('success','تم إضافة العقد.');
    }
    public function edit(Employee $employee, EmployeeContract $contract)
{
    abort_unless($contract->employee_id === $employee->id, 404);
    return view('employees.contracts.edit', compact('employee','contract'));
}

public function update(Request $request, Employee $employee, EmployeeContract $contract)
{
    abort_unless($contract->employee_id === $employee->id, 404);

    $data = $request->validate([
        'start_date' => ['required','date'],
        'end_date'   => ['nullable','date','after_or_equal:start_date'],
        'contract_type' => ['required','in:full_time,part_time,freelance,hourly'],
        'salary_amount' => ['nullable','numeric','min:0'],
        'hour_rate'     => ['nullable','numeric','min:0'],
        'currency'      => ['required','string','size:3'],
        'notes'         => ['nullable','string','max:3000'],
    ]);

    $contract->update($data);

    return redirect()
        ->route('employees.contracts.index', $employee)
        ->with('success','تم تحديث العقد بنجاح.');
}

public function destroy(Employee $employee, EmployeeContract $contract)
{
    abort_unless($contract->employee_id === $employee->id, 404);

    $contract->delete();

    return redirect()
        ->route('employees.contracts.index', $employee)
        ->with('success','تم حذف العقد.');
}


}
