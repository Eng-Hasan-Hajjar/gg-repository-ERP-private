<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceGeneratorController extends Controller
{
  
public function generateWeek(Request $request)
{
    $data = $request->validate([
        'week_start' => ['required','date'],
        'branch_id'  => ['nullable','integer','exists:branches,id'],
    ]);

    $start = Carbon::parse($data['week_start'])->startOfWeek(); // حسب إعداداتك
    $end   = $start->copy()->addDays(6);

    $employees = Employee::query()
        ->when(!empty($data['branch_id']), fn($q)=>$q->where('branch_id',$data['branch_id']))
        ->where('status','active')
        ->with(['schedules','scheduleOverrides'])
        ->get();

    foreach($employees as $emp){
        // map weekday => shift_id
        $weekly = $emp->schedules->pluck('work_shift_id','weekday')->toArray();
        // map date => shift_id (override)
        $over   = $emp->scheduleOverrides
                    ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
                    ->pluck('work_shift_id','work_date')
                    ->toArray();

        for($d=0;$d<7;$d++){
            $date = $start->copy()->addDays($d)->toDateString();
            $weekday = Carbon::parse($date)->dayOfWeek; // 0..6

            // الأولوية للاستثناء
            $shiftId = array_key_exists($date, $over)
                ? $over[$date]
                : ($weekly[$weekday] ?? null);

            // status: off أو scheduled
            $status = $shiftId ? 'scheduled' : 'off';

            AttendanceRecord::updateOrCreate(
                ['employee_id'=>$emp->id, 'work_date'=>$date],
                ['work_shift_id'=>$shiftId, 'status'=>$status]
            );
        }
    }

    return back()->with('success','تم توليد سجلات الأسبوع بنجاح.');
}
}
