<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceRecordSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->get();

        if ($employees->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد موظفين، تخطّي AttendanceRecordSeeder');
            return;
        }

        $daysBack = 45; // آخر 45 يوم لتغطي التقويم + التقارير
        $today = now()->startOfDay();

        foreach ($employees as $emp) {
            for ($i = $daysBack; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $weekday0 = (int) $date->dayOfWeek; // 0..6

                // schedule للموظف لهذا اليوم
                $schedule = EmployeeSchedule::where('employee_id', $emp->id)
                    ->where('weekday', $weekday0)
                    ->first();

                // يوم عطلة أو بدون شيفت
                if (!$schedule || empty($schedule->work_shift_id)) {
                    AttendanceRecord::updateOrCreate(
                        ['employee_id' => $emp->id, 'work_date' => $date->toDateString()],
                        [
                            'work_shift_id' => null,
                            'status' => 'off',
                            'check_in_at' => null,
                            'check_out_at' => null,
                            'late_minutes' => 0,
                            'worked_minutes' => 0,
                            'notes' => 'Off day (seed)',
                        ]
                    );
                    continue;
                }

                $shift = WorkShift::find($schedule->work_shift_id);
                if (!$shift) continue;

                // وقت الشيفت
                $start = Carbon::parse($date->toDateString() . ' ' . $shift->start_time);
                $end   = Carbon::parse($date->toDateString() . ' ' . $shift->end_time);

                // نماذج حالات متنوعة:
                // - كل 12 يوم: غياب
                // - كل 18 يوم: إجازة
                // - كل 4 أيام: تأخير
                // - غير ذلك: حضور طبيعي
                $mode = 'present';

                if ($i % 18 === 0) $mode = 'leave';
                elseif ($i % 12 === 0) $mode = 'absent';
                elseif ($i % 4 === 0) $mode = 'late';

                if ($mode === 'leave') {
                    AttendanceRecord::updateOrCreate(
                        ['employee_id' => $emp->id, 'work_date' => $date->toDateString()],
                        [
                            'work_shift_id' => $shift->id,
                            'status' => 'leave',
                            'check_in_at' => null,
                            'check_out_at' => null,
                            'late_minutes' => 0,
                            'worked_minutes' => 0,
                            'notes' => 'Leave day (seed)',
                        ]
                    );
                    continue;
                }

                if ($mode === 'absent') {
                    AttendanceRecord::updateOrCreate(
                        ['employee_id' => $emp->id, 'work_date' => $date->toDateString()],
                        [
                            'work_shift_id' => $shift->id,
                            'status' => 'absent',
                            'check_in_at' => null,
                            'check_out_at' => null,
                            'late_minutes' => 0,
                            'worked_minutes' => 0,
                            'notes' => 'Absent (seed)',
                        ]
                    );
                    continue;
                }

                // present/late
                $lateMin = ($mode === 'late') ? random_int(5, 35) : random_int(0, 3);
                $checkIn = $start->copy()->addMinutes($lateMin);

                // خروج طبيعي + بعض الاختلاف
                $earlyLeave = (random_int(1, 10) === 1); // 10% خروج مبكر
                $overtime   = (random_int(1, 8) === 1);  // 12.5% وقت إضافي

                $checkOut = $end->copy();
                if ($earlyLeave) $checkOut->subMinutes(random_int(10, 45));
                if ($overtime)   $checkOut->addMinutes(random_int(10, 60));

                $worked = max(0, $checkOut->diffInMinutes($checkIn));

                AttendanceRecord::updateOrCreate(
                    ['employee_id' => $emp->id, 'work_date' => $date->toDateString()],
                    [
                        'work_shift_id' => $shift->id,
                        'status' => ($lateMin > 0 && $lateMin >= 5) ? 'late' : 'present',
                        'check_in_at' => $checkIn,
                        'check_out_at' => $checkOut,
                        'late_minutes' => ($lateMin >= 5) ? $lateMin : 0,
                        'worked_minutes' => $worked,
                        'notes' => 'Auto seeded record',
                    ]
                );
            }
        }
    }
}
