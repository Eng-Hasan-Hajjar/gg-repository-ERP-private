<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{



    public function index(Request $request)
    {
        $q = LeaveRequest::query()->with(['employee.branch', 'approver']);

        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {

            $employee = Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            if ($employee) {
                $q->whereHas('employee', function ($x) use ($employee) {
                    $x->where('branch_id', $employee->branch_id);
                });
            }
        }

        if ($request->filled('status'))
            $q->where('status', $request->status);
        if ($request->filled('type'))
            $q->where('type', $request->type);
        if ($request->filled('employee_id'))
            $q->where('employee_id', $request->employee_id);

        if ($request->filled('from'))
            $q->whereDate('start_date', '>=', $request->from);
        if ($request->filled('to'))
            $q->whereDate('start_date', '<=', $request->to);

        return view('leaves.index', [
            'leaves' => $q->latest()->paginate(20)->withQueryString(),
            'employees' => Employee::orderBy('full_name')->get(),
        ]);
    }

    /*
    public function index(Request $request)
    {
        $q = LeaveRequest::query()->with(['employee.branch','approver']);

        if ($request->filled('status')) $q->where('status',$request->status);
        if ($request->filled('type')) $q->where('type',$request->type);
        if ($request->filled('employee_id')) $q->where('employee_id',$request->employee_id);

        if ($request->filled('from')) $q->whereDate('start_date','>=',$request->from);
        if ($request->filled('to'))   $q->whereDate('start_date','<=',$request->to);

        return view('leaves.index', [
            'leaves' => $q->latest()->paginate(20)->withQueryString(),
            'employees' => Employee::orderBy('full_name')->get(),
        ]);
    }

    public function create()
    {
        return view('leaves.create', [
            'employees' => Employee::orderBy('full_name')->get(),
        ]);
    }
*/

    public function create()
    {
        $user = auth()->user();

        // super admin يرى الجميع
        if ($user->hasRole('super_admin')) {

            $employees = Employee::orderBy('full_name')->get();

        } else {

            $employee = Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            // إذا كان المستخدم موظف عادي
            if (!$user->hasPermission('manage_leaves')) {

                // يظهر اسمه فقط
                $employees = collect([$employee]);

            } else {

                // مدير الفرع يرى موظفي فرعه
                $employees = Employee::where('branch_id', $employee?->branch_id)
                    ->orderBy('full_name')
                    ->get();
            }
        }

        return view('leaves.create', [
            'employees' => $employees
        ]);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', 'in:leave,permission'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['status'] = 'pending';
        $leave = LeaveRequest::create($data);

        return redirect()->route('leaves.show', $leave)->with('success', 'تم إرسال الطلب.');
    }

    public function show(LeaveRequest $leave)
    {
        return view('leaves.show', ['leave' => $leave->load(['employee.branch', 'approver'])]);
    }



    public function destroy(LeaveRequest $leave)
{
    $leave->delete();

    return redirect()
        ->route('leaves.index')
        ->with('success','تم حذف طلب الإجازة.');
}



    public function approve(Request $request, LeaveRequest $leave)
    {
        $data = $request->validate(['admin_note' => ['nullable', 'string', 'max:5000']]);

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_note' => $data['admin_note'] ?? null,
        ]);

        return back()->with('success', 'تمت الموافقة على الطلب.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $data = $request->validate(['admin_note' => ['required', 'string', 'max:5000']]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_note' => $data['admin_note'],
        ]);

        return back()->with('success', 'تم رفض الطلب.');
    }
}
