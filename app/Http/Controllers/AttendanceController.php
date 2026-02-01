<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $q = AttendanceRecord::query()->with(['employee.branch','shift']);

        if ($request->filled('branch_id')) {
            $q->whereHas('employee', fn($x)=>$x->where('branch_id',$request->branch_id));
        }
        if ($request->filled('employee_id')) $q->where('employee_id',$request->employee_id);
        if ($request->filled('status')) $q->where('status',$request->status);

        if ($request->filled('from')) $q->whereDate('work_date','>=',$request->from);
        if ($request->filled('to'))   $q->whereDate('work_date','<=',$request->to);

        // بحث بالاسم/الكود
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->whereHas('employee', function($x) use ($s){
                $x->where('full_name','like',"%$s%")->orWhere('code','like',"%$s%");
            });
        }

        return view('attendance.index', [
            'records' => $q->latest('work_date')->paginate(20)->withQueryString(),
            'branches'=> Branch::orderBy('name')->get(),
            'employees'=> Employee::orderBy('full_name')->get(),
        ]);
    }

    // زر: توليد سجل اليوم لموظف (إذا لم يوجد)
    public function createForToday(Employee $employee)
    {
        $date = now()->toDateString();
        $weekday = now()->dayOfWeekIso; // 1..7
        $weekday0 = $weekday % 7; // 0..6 (Sun=0)

        $schedule = EmployeeSchedule::where('employee_id',$employee->id)->where('weekday',$weekday0)->first();
        $shiftId = $schedule?->work_shift_id;

        $record = AttendanceRecord::firstOrCreate(
            ['employee_id'=>$employee->id,'work_date'=>$date],
            [
                'work_shift_id'=>$shiftId,
                'status'=>$shiftId ? 'scheduled' : 'off',
                'late_minutes'=>0,
                'worked_minutes'=>0,
            ]
        );

        return redirect()->route('attendance.index')->with('success','تم تجهيز سجل دوام اليوم.');
    }

    // Check-in سريع
    public function checkIn(AttendanceRecord $record)
    {
        if ($record->check_in_at) return back()->with('error','تم تسجيل الدخول مسبقاً.');

        $now = now();
        $late = 0;
        $status = 'present';

        if ($record->shift) {
            $workDate = $record->work_date instanceof Carbon
    ? $record->work_date->toDateString()
    : Carbon::parse($record->work_date)->toDateString();

$shiftStart = Carbon::parse($workDate.' '.$record->shift->start_time);



            $grace = (int) $record->shift->grace_minutes;
            if ($now->greaterThan($shiftStart->copy()->addMinutes($grace))) {
                $late = max(0, $shiftStart->diffInMinutes($now));
                $status = 'late';
            }
        }

        $record->update([
            'check_in_at' => $now,
            'late_minutes' => $late,
            'status' => $record->status=='off' ? 'off' : $status,
        ]);

        return back()->with('success','تم تسجيل الدخول.');
    }

    // Check-out سريع + احتساب الساعات
    public function checkOut(AttendanceRecord $record)
    {
        if (!$record->check_in_at) return back()->with('error','لا يمكن تسجيل الخروج بدون دخول.');
        if ($record->check_out_at) return back()->with('error','تم تسجيل الخروج مسبقاً.');

        $now = now();
        $worked = $record->check_in_at->diffInMinutes($now);

        $record->update([
            'check_out_at' => $now,
            'worked_minutes' => $worked,
        ]);

        return back()->with('success','تم تسجيل الخروج.');
    }

    // تعديل يدوي
    public function edit(AttendanceRecord $record)
    {
        return view('attendance.edit', [
            'record'=>$record->load(['employee','shift']),
            'shifts'=> WorkShift::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, AttendanceRecord $record)
    {
        $data = $request->validate([
            'work_shift_id' => ['nullable','exists:work_shifts,id'],
            'check_in_at' => ['nullable','date'],
            'check_out_at' => ['nullable','date','after_or_equal:check_in_at'],
            'status' => ['required','in:scheduled,present,late,absent,off,leave'],
            'notes' => ['nullable','string','max:5000'],
        ]);

        // إعادة حساب worked_minutes
        $worked = 0;
        if (!empty($data['check_in_at']) && !empty($data['check_out_at'])) {
            $worked = Carbon::parse($data['check_in_at'])->diffInMinutes(Carbon::parse($data['check_out_at']));
        }
        $data['worked_minutes'] = $worked;

        $record->update($data);
        return redirect()->route('attendance.index')->with('success','تم تحديث سجل الدوام.');
    }
}
