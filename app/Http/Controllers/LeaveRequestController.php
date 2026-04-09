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

        // جلب الموظف المرتبط بالمستخدم
        $employee = Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        // ================================
        // 🔐 التحكم بالصلاحيات
        // ================================
        if (!$user->hasRole('super_admin')) {

            // موظف عادي → يرى طلباته فقط
            if (!$user->hasPermission('manage_leaves')) {

                if ($employee) {
                    $q->where('employee_id', $employee->id);
                }

            } else {

                // مدير → يرى طلبات الفرع
                if ($employee) {
                    $q->whereHas('employee', function ($x) use ($employee) {
                        $x->where('branch_id', $employee->branch_id);
                    });
                }

            }
        }

        // ================================
        // 🔍 الفلاتر
        // ================================
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        if ($request->filled('employee_id')) {
            $q->where('employee_id', $request->employee_id);
        }

        if ($request->filled('from')) {
            $q->whereDate('start_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $q->whereDate('start_date', '<=', $request->to);
        }

        // ================================
        // 👥 قائمة الموظفين (للفلتر)
        // ================================
        if ($user->hasRole('super_admin') || $user->hasPermission('manage_leaves')) {

            // المدير يرى الكل
            $employees = Employee::orderBy('full_name')->get();

        } else {

            // الموظف يرى نفسه فقط
            $employees = $employee ? collect([$employee]) : collect();
        }

        return view('leaves.index', [
            'leaves' => $q->latest()->paginate(20)->withQueryString(),
            'employees' => $employees,
        ]);
    }

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
            ->with('success', 'تم حذف طلب الإجازة.');
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
