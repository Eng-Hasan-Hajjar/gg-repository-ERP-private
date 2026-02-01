<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EmployeeScheduleOverride;

use App\Models\EmployeeSchedule;   
use App\Models\WorkShift;       

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


    $shifts = WorkShift::orderBy('name')->get();
$weekdays = [
  0=>'الأحد', 1=>'الإثنين', 2=>'الثلاثاء', 3=>'الأربعاء',
  4=>'الخميس', 5=>'الجمعة', 6=>'السبت'
];

        return view('employees.create', [
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'shifts' => $shifts,
             'scheduleMap' => [], // create
             'weekdays' => $weekdays,
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
               'schedule' => ['array'],
        'schedule.*' => ['nullable','integer','exists:work_shifts,id'],
        ]);

     


      



        $data['code'] = $this->generateEmployeeCode();

        $employee = Employee::create($data);

          // حفظ جدول الأسبوع
        $schedule = $request->input('schedule', []);
        foreach(range(0,6) as $wd){
            EmployeeSchedule::updateOrCreate(
                ['employee_id'=>$employee->id, 'weekday'=>$wd],
                ['work_shift_id'=> $schedule[$wd] ?? null]
            );
        }

        // ربط الدبلومات
        $employee->diplomas()->sync($data['diploma_ids'] ?? []);

        return redirect()->route('employees.show', $employee)->with('success','تم إنشاء المدرب/الموظف بنجاح.');
    }

    public function show(Employee $employee)
    {

    
        $employee->load(['branch','diplomas','contracts','payouts'
            ,'schedules.shift',  
            // جدول الأسبوع
            ]);

                // آخر 30 يوم Overrides (أو خليها شهرين حسب ما بدك)
        $overrides = EmployeeScheduleOverride::with('shift')
            ->where('employee_id', $employee->id)
            ->whereBetween('work_date', [now()->subDays(30)->toDateString(), now()->addDays(30)->toDateString()])
            ->orderBy('work_date')
            ->get();

        $weekdays = [
            0=>'الأحد', 1=>'الإثنين', 2=>'الثلاثاء', 3=>'الأربعاء',
            4=>'الخميس', 5=>'الجمعة', 6=>'السبت'
        ];

        // map: weekday => shift (model) أو null
        $scheduleMap = $employee->schedules->keyBy('weekday');






        return view('employees.show', compact('employee','weekdays','scheduleMap','overrides'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('diplomas');

        $shifts = WorkShift::orderBy('name')->get();

    $scheduleMap = $employee->schedules()
        ->pluck('work_shift_id','weekday')
        ->toArray();
  $weekdays = [
      0=>'الأحد', 1=>'الإثنين', 2=>'الثلاثاء', 3=>'الأربعاء',
      4=>'الخميس', 5=>'الجمعة', 6=>'السبت'
    ];
        return view('employees.edit', [
            'employee' => $employee,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
               'shifts' => $shifts,
        'scheduleMap' => $scheduleMap,
            'weekdays' => $weekdays,
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
            'schedule' => ['array'],
            'schedule.*' => ['nullable','integer','exists:work_shifts,id'],
        ]);

        $employee->update($data);

    

        $schedule = $request->input('schedule', []);
        foreach(range(0,6) as $wd){
            $employee->schedules()->updateOrCreate(
                ['weekday'=>$wd],
                ['work_shift_id'=> $schedule[$wd] ?? null]
            );
        }


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
