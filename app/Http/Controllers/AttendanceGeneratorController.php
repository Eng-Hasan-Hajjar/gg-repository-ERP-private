<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceGeneratorController extends Controller
{
    public function generateWeek(Request $request)
    {
        $data = $request->validate([
            'week_start' => ['required', 'date'],
            'branch_id'  => ['nullable', 'integer', 'exists:branches,id'],
        ]);

        $start = Carbon::parse($data['week_start'])->startOfWeek();

        $employees = Employee::query()
            ->when(!empty($data['branch_id']), fn($q) => $q->where('branch_id', $data['branch_id']))
            ->where('status', 'active')
            ->with('schedules') // ← بدون scheduleOverrides لأننا أزلنا الشيفتات
            ->get();

        foreach ($employees as $emp) {
            // map: weekday => schedule row
            $weekly = $emp->schedules->keyBy('weekday');

            for ($d = 0; $d < 7; $d++) {
                $date    = $start->copy()->addDays($d)->toDateString();
                $weekday = Carbon::parse($date)->dayOfWeek; // 0=Sun .. 6=Sat

                $schedule = $weekly->get($weekday);

                // هل هذا اليوم عطلة؟
                $isOff = !$schedule || $schedule->is_off;

                // بناء وقت البداية والنهاية كـ timestamp كامل
                $scheduledStart = (!$isOff && $schedule->start_time)
                    ? Carbon::parse($date . ' ' . $schedule->start_time)
                    : null;

                $scheduledEnd = (!$isOff && $schedule->end_time)
                    ? Carbon::parse($date . ' ' . $schedule->end_time)
                    : null;

                AttendanceRecord::updateOrCreate(
                    ['employee_id' => $emp->id, 'work_date' => $date],
                    [
                        'scheduled_start' => $scheduledStart,
                        'scheduled_end'   => $scheduledEnd,
                        'work_shift_id'   => null, // لم نعد نستخدمه
                        'status'          => $isOff ? 'off' : 'scheduled',
                        'late_minutes'    => 0,
                        'worked_minutes'  => 0,
                    ]
                );
            }
        }

        return back()->with('success', 'تم توليد سجلات الأسبوع بنجاح.');
    }
}