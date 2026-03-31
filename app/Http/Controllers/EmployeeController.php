<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EmployeeScheduleOverride;
use App\Models\User;
use App\Models\EmployeeSchedule;
use App\Models\WorkShift;
use Illuminate\Support\Facades\Hash;




/*


 ملحوظة معمارية مهمة

الآن يمكنك في أي مكان استخدام:

auth()->user()->employee

وبالتالي:

عرض مهامه

عرض جدوله

عرض حضوره

عرض تقاريره

النظام أصبح متكامل 🔥



* /
 */
class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = Employee::query()->with(['branch', 'diplomas']);

        if ($request->filled('type'))
            $q->where('type', $request->type);
        if ($request->filled('status'))
            $q->where('status', $request->status);
        if ($request->filled('branch_id'))
            $q->where('branch_id', $request->branch_id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('full_name', 'like', "%$s%")
                    ->orWhere('code', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        return view('employees.index', [
            'employees' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function create()
    {


        $weekdays = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت'
        ];

        return view('employees.create', [
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),

            'scheduleMap' => [], // create
            'weekdays' => $weekdays,
            'users' => User::doesntHave('employee')->orderBy('name')->get(), // 👈 أضف هذا
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:trainer,employee'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'diploma_ids' => ['nullable', 'array'],
            'diploma_ids.*' => ['exists:diplomas,id'],
            'user_id' => ['nullable', 'exists:users,id', 'unique:employees,user_id'],

            // ← تعديل: لم نعد نتحقق من shift id
            'schedule' => ['nullable', 'array'],
            'schedule.*.is_off' => ['nullable'],
            'schedule.*.start' => ['nullable', 'date_format:H:i'],
            'schedule.*.end' => ['nullable', 'date_format:H:i', 'after:schedule.*.start'],
        ]);

        $data['code'] = $this->generateEmployeeCode();

        $employee = Employee::create($data);

        // ← حفظ جدول الدوام الجديد
        $this->saveSchedule($employee, $request->input('schedule', []));

        $employee->diplomas()->sync($data['diploma_ids'] ?? []);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم إنشاء المدرب/الموظف بنجاح.');
    }


    public function show(Employee $employee)
    {

        $employee->load([
            'branch',
            'diplomas',
            'contracts',
            'payouts',
            'schedules'
        ]);

        $weekdays = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        // تجهيز جدول الدوام للعرض
        $scheduleMap = [];

        foreach ($weekdays as $wd => $label) {

            $schedule = $employee->schedules->firstWhere('weekday', $wd);

            $scheduleMap[$wd] = [
                'is_off' => $schedule ? (bool) $schedule->is_off : true,
                'start' => $schedule && $schedule->start_time
                    ? substr($schedule->start_time, 0, 5)
                    : '',
                'end' => $schedule && $schedule->end_time
                    ? substr($schedule->end_time, 0, 5)
                    : '',
            ];
        }

        // هذا المتغير الذي سبب الخطأ
        //  $overrides = collect();
        $overrides = EmployeeScheduleOverride::where('employee_id', $employee->id)
            ->latest('work_date')
            ->take(20)
            ->get();
        return view('employees.show', compact(
            'employee',
            'weekdays',
            'scheduleMap',
            'overrides'
        ));
    }


    public function edit(Employee $employee)
    {
        $employee->load('diplomas');

        $weekdays = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        // بناء scheduleMap بالشكل الجديد: weekday => ['start', 'end', 'is_off']
        $scheduleMap = [];

        foreach (range(0, 6) as $wd) {

            $schedule = $employee->schedules->firstWhere('weekday', $wd);

            $scheduleMap[$wd] = [
                'is_off' => old(
                    "schedule.$wd.is_off",
                    $schedule ? (bool) $schedule->is_off : true
                ),

                'start' => old(
                    "schedule.$wd.start",
                    $schedule && $schedule->start_time
                    ? substr($schedule->start_time, 0, 5)
                    : ''
                ),

                'end' => old(
                    "schedule.$wd.end",
                    $schedule && $schedule->end_time
                    ? substr($schedule->end_time, 0, 5)
                    : ''
                ),
            ];
        }
        $users = User::whereDoesntHave('employee')
            ->orWhere('id', $employee->user_id)
            ->orderBy('name')
            ->get();



        $overrides = [];
        return view('employees.edit', [
            'employee' => $employee,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'scheduleMap' => $scheduleMap,
            'weekdays' => $weekdays,
            'users' => $users,

        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:trainer,employee'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'diploma_ids' => ['nullable', 'array'],
            'diploma_ids.*' => ['exists:diplomas,id'],
            'user_id' => ['nullable', 'exists:users,id', 'unique:employees,user_id,' . $employee->id],

            'schedule' => ['nullable', 'array'],
            'schedule.*.is_off' => ['nullable'],
            'schedule.*.start' => ['nullable', 'date_format:H:i'],
            'schedule.*.end' => ['nullable', 'date_format:H:i'],
        ]);

        $employee->update($data);

        // ← حفظ الجدول الجديد
        $this->saveSchedule($employee, $request->input('schedule', []));

        $employee->diplomas()->sync($data['diploma_ids'] ?? []);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم تحديث البيانات بنجاح.');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'تم حذف السجل.');
    }

    private function generateEmployeeCode(): string
    {
        do {
            $code = 'EMP-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Employee::where('code', $code)->exists());

        return $code;
    }




    /**
     * حفظ جدول الدوام الأسبوعي للموظف (بدون شيفتات)
     */
    private function saveSchedule(Employee $employee, array $schedule): void
    {
        foreach (range(0, 6) as $wd) {
            $day = $schedule[$wd] ?? [];
            $isOff = !empty($day['is_off']);

            $employee->schedules()->updateOrCreate(
                ['weekday' => $wd],
                [
                    'start_time' => $isOff ? null : ($day['start'] ?? null),
                    'end_time' => $isOff ? null : ($day['end'] ?? null),
                    'is_off' => $isOff,
                ]
            );
        }
    }






    public function createUser(Employee $employee)
    {
        // منع إنشاء حساب إذا موجود مسبقاً
        if ($employee->user) {
            return back()->with('error', 'هذا الموظف لديه حساب بالفعل.');
        }

        // يجب أن يكون لديه بريد
        if (!$employee->email) {
            return back()->with('error', 'لا يمكن إنشاء حساب بدون بريد إلكتروني للموظف.');
        }

        // تأكد أن الإيميل غير مستخدم
        if (User::where('email', $employee->email)->exists()) {
            return back()->with('error', 'يوجد مستخدم مسجل بنفس البريد الإلكتروني.');
        }

        $password = Str::random(8);

        $user = User::create([
            'name' => $employee->full_name,
            'email' => $employee->email,
            'password' => Hash::make($password),
        ]);

        // ربط الموظف بالحساب
        $employee->update([
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'تم إنشاء الحساب بنجاح. كلمة المرور المؤقتة: ' . $password);
    }




}
