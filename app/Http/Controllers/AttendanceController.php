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
        $q = AttendanceRecord::query()->with(['employee.branch', 'shift']);

        $user = auth()->user();

        if (!$user->hasRole('super_admin') && !$user->hasRole('manager_attendance')) {
            $employee = Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            if ($employee) {
                $q->where('employee_id', $employee->id);
                $q->whereDate('work_date', now()->toDateString());
            }
        }

        if ($request->filled('branch_id')) {
            $q->whereHas('employee', fn($x) => $x->where('branch_id', $request->branch_id));
        }
        if ($request->filled('employee_id')) {
            $q->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $q->whereDate('work_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('work_date', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->whereHas('employee', function ($x) use ($s) {
                $x->where('full_name', 'like', "%$s%")
                    ->orWhere('code', 'like', "%$s%");
            });
        }

        $activeModule = 'attendance';
        return view('attendance.index', [
            'records'    => $q->latest('work_date')->paginate(20)->withQueryString(),
            'branches'   => Branch::orderBy('name')->get(),
            'employees'  => Employee::orderBy('full_name')->get(),
            'activeModule',
        ]);
    }

    // ══════════════════════════════════════════
    // توليد سجل اليوم لموظف
    // ══════════════════════════════════════════
    public function createForToday(Employee $employee)
    {
        $date     = now()->toDateString();
        $weekday  = now()->dayOfWeekIso;
        $weekday0 = $weekday % 7;

        $schedule = EmployeeSchedule::where('employee_id', $employee->id)
            ->where('weekday', $weekday0)
            ->first();
        $shiftId = $schedule?->work_shift_id;

        AttendanceRecord::firstOrCreate(
            ['employee_id' => $employee->id, 'work_date' => $date],
            [
                'work_shift_id'  => $shiftId,
                'status'         => $shiftId ? 'scheduled' : 'off',
                'late_minutes'   => 0,
                'worked_minutes' => 0,
                'break_minutes'  => 0,
            ]
        );

        return redirect()->route('attendance.index')
            ->with('success', 'تم تجهيز سجل دوام اليوم.');
    }

    // ══════════════════════════════════════════
    // تسجيل الدخول — مع حساب التأخير من الوردية
    // ══════════════════════════════════════════
public function checkIn(AttendanceRecord $record)
{
    if ($record->check_in_at) {
        return back()->with('error', 'تم تسجيل الدخول مسبقاً.');
    }

    $now    = now();
    $late   = 0;
    $status = 'present';

    // ✅ أولاً: جرب من WorkShift إن وُجد
    $startTime = null;

    if ($record->shift && $record->shift->start_time) {
        $startTime = $record->shift->start_time;
    } else {
        // ✅ ثانياً: جلب وقت البداية من EmployeeSchedule مباشرةً
        $dayOfWeek = $record->work_date->dayOfWeek; // 0=Sunday, 6=Saturday

        $schedule = \App\Models\EmployeeSchedule::where('employee_id', $record->employee_id)
            ->where('weekday', $dayOfWeek)
            ->where('is_off', false)
            ->first();

        if ($schedule && $schedule->start_time) {
            $startTime = $schedule->start_time;
        }
    }

    if ($startTime) {
        $shiftStart = Carbon::parse(
            $record->work_date->format('Y-m-d') . ' ' . $startTime
        );

        $grace = 10; // دقائق السماح

        if ($now->greaterThan($shiftStart->copy()->addMinutes($grace))) {
            $late   = (int) $shiftStart->diffInMinutes($now);
            $status = 'late';
        }
    }

    $record->update([
        'check_in_at'  => $now,
        'late_minutes' => $late,
        'status'       => in_array($record->status, ['off', 'leave']) ? $record->status : $status,
    ]);

    return back()->with('success', 'تم تسجيل الدخول.' . ($late > 0 ? ' تأخير: ' . $late . ' دقيقة.' : ''));
}

    // ══════════════════════════════════════════
    // تسجيل الخروج — مع حساب صافي الساعات
    // ══════════════════════════════════════════
    public function checkOut(AttendanceRecord $record)
    {
        if (!$record->check_in_at) {
            return back()->with('error', 'لا يمكن تسجيل الخروج بدون دخول.');
        }
        if ($record->check_out_at) {
            return back()->with('error', 'تم تسجيل الخروج مسبقاً.');
        }

        $now          = now();
        $breakMinutes = (int) ($record->break_minutes ?? 0);

        // إذا كان في استراحة، ننهيها أولاً
        if ($record->is_on_break && $record->break_start_at) {
            $extraBreak   = (int) $record->break_start_at->diffInMinutes($now);
            $breakMinutes = $breakMinutes + $extraBreak;
        }

        // ✅ إجمالي الوقت من الدخول حتى الخروج
        $totalMinutes = (int) $record->check_in_at->diffInMinutes($now);

        // ✅ صافي الساعات = إجمالي - وقت الاستراحة
        $workedMinutes = max(0, $totalMinutes - $breakMinutes);

        $record->update([
            'check_out_at'   => $now,
            'worked_minutes' => $workedMinutes,
            'break_end_at'   => $record->is_on_break ? $now : $record->break_end_at,
            'break_minutes'  => $breakMinutes,
        ]);

        $hours   = floor($workedMinutes / 60);
        $minutes = $workedMinutes % 60;

        return back()->with('success', "تم تسجيل الخروج. صافي الساعات: {$hours}س {$minutes}د");
    }

    // ══════════════════════════════════════════
    // بدء الاستراحة
    // ══════════════════════════════════════════
    public function breakStart(AttendanceRecord $record)
    {
        if (!$record->check_in_at) {
            return back()->with('error', 'يجب تسجيل الدخول أولاً.');
        }
        if ($record->check_out_at) {
            return back()->with('error', 'لا يمكن بدء استراحة بعد تسجيل الخروج.');
        }
        if ($record->is_on_break) {
            return back()->with('error', 'أنت بالفعل في استراحة.');
        }

        $record->update([
            'break_start_at' => now(),
            'break_end_at'   => null,
        ]);

        return back()->with('success', 'تم بدء الاستراحة.');
    }

    // ══════════════════════════════════════════
    // إنهاء الاستراحة
    // ══════════════════════════════════════════
    public function breakEnd(AttendanceRecord $record)
    {
        if (!$record->is_on_break) {
            return back()->with('error', 'لا توجد استراحة نشطة.');
        }

        $now          = now();
        $breakMinutes = (int) $record->break_start_at->diffInMinutes($now);
        $totalBreak   = (int) $record->break_minutes + $breakMinutes;

        $record->update([
            'break_end_at'  => $now,
            'break_minutes' => $totalBreak,
        ]);

        return back()->with('success', 'تم إنهاء الاستراحة. المدة: ' . $breakMinutes . ' دقيقة.');
    }

    // ══════════════════════════════════════════
    // تعديل يدوي
    // ══════════════════════════════════════════
    public function edit(AttendanceRecord $record)
    {
        return view('attendance.edit', [
            'record' => $record->load(['employee', 'shift']),
            'shifts' => WorkShift::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, AttendanceRecord $record)
    {
        $data = $request->validate([
            'work_shift_id' => ['nullable', 'exists:work_shifts,id'],
            'check_in_at'   => ['nullable', 'date'],
            'check_out_at'  => ['nullable', 'date', 'after_or_equal:check_in_at'],
            'break_minutes' => ['nullable', 'integer', 'min:0'],
            'status'        => ['required', 'in:scheduled,present,late,absent,off,leave'],
            'notes'         => ['nullable', 'string', 'max:5000'],
        ]);

        // إعادة حساب worked_minutes
        $worked = 0;
        if (!empty($data['check_in_at']) && !empty($data['check_out_at'])) {
            $totalMin    = Carbon::parse($data['check_in_at'])->diffInMinutes(Carbon::parse($data['check_out_at']));
            $breakMin    = (int) ($data['break_minutes'] ?? 0);
            $worked      = max(0, $totalMin - $breakMin);
        }
        $data['worked_minutes'] = $worked;
        $data['break_minutes']  = $data['break_minutes'] ?? 0;

        $record->update($data);

        return redirect()->route('attendance.index')
            ->with('success', 'تم تحديث سجل الدوام.');
    }

    // ══════════════════════════════════════════
    // حفظ الملاحظات
    // ══════════════════════════════════════════
    public function updateNotes(Request $request, AttendanceRecord $record)
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:5000']
        ]);

        $record->update(['notes' => $data['notes']]);

        return response()->json([
            'success' => true,
            'notes'   => $record->notes
        ]);
    }
}