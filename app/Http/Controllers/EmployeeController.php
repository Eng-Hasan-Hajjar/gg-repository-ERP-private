<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = Employee::query()->with(['branch','diplomas']);

        if ($request->filled('type')) $q->where('type', $request->type);
        if ($request->filled('status')) $q->where('status', $request->status);
        if ($request->filled('branch_id')) $q->where('branch_id', $request->branch_id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($x) use ($s){
                $x->where('full_name','like',"%$s%")
                  ->orWhere('code','like',"%$s%")
                  ->orWhere('phone','like',"%$s%");
            });
        }

        return view('employees.index', [
            'employees' => $q->latest()->paginate(15)->withQueryString(),
            'branches'  => Branch::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('employees.create', [
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'type'      => ['required','in:trainer,employee'],
            'phone'     => ['nullable','string','max:50'],
            'email'     => ['nullable','email','max:255'],
            'branch_id' => ['nullable','exists:branches,id'],
            'job_title' => ['nullable','string','max:255'],
            'status'    => ['required','in:active,inactive'],
            'notes'     => ['nullable','string','max:3000'],
            'diploma_ids' => ['nullable','array'],
            'diploma_ids.*' => ['exists:diplomas,id'],
        ]);

        $data['code'] = $this->generateEmployeeCode();

        $employee = Employee::create($data);

        // ربط الدبلومات
        $employee->diplomas()->sync($data['diploma_ids'] ?? []);

        return redirect()->route('employees.show', $employee)->with('success','تم إنشاء المدرب/الموظف بنجاح.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['branch','diplomas','contracts','payouts']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('diplomas');
        return view('employees.edit', [
            'employee' => $employee,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'type'      => ['required','in:trainer,employee'],
            'phone'     => ['nullable','string','max:50'],
            'email'     => ['nullable','email','max:255'],
            'branch_id' => ['nullable','exists:branches,id'],
            'job_title' => ['nullable','string','max:255'],
            'status'    => ['required','in:active,inactive'],
            'notes'     => ['nullable','string','max:3000'],
            'diploma_ids' => ['nullable','array'],
            'diploma_ids.*' => ['exists:diplomas,id'],
        ]);

        $employee->update($data);
        $employee->diplomas()->sync($data['diploma_ids'] ?? []);

        return redirect()->route('employees.show', $employee)->with('success','تم تحديث البيانات بنجاح.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success','تم حذف السجل.');
    }

    private function generateEmployeeCode(): string
    {
        do {
            $code = 'EMP-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Employee::where('code',$code)->exists());

        return $code;
    }
}
