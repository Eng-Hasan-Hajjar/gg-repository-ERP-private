<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\WorkShift;
use Illuminate\Database\Seeder;

class EmployeeScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->get();
        $shifts = WorkShift::all();

        if ($employees->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد موظفين، تخطّي EmployeeScheduleSeeder');
            return;
        }

        if ($shifts->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد شيفتات، شغّل WorkShiftSeeder أولاً');
            return;
        }

        $morning = $shifts->firstWhere('name', 'صباحي') ?? $shifts->first();
        $evening = $shifts->firstWhere('name', 'مسائي') ?? $shifts->last();
        $part    = $shifts->firstWhere('name', 'جزئي') ?? $shifts->first();

        foreach ($employees as $idx => $emp) {
            for ($weekday = 0; $weekday <= 6; $weekday++) {

                // عطلة الجمعة/السبت => work_shift_id = null
                if (in_array($weekday, [5, 6])) {
                    EmployeeSchedule::updateOrCreate(
                        ['employee_id' => $emp->id, 'weekday' => $weekday],
                        ['work_shift_id' => null]
                    );
                    continue;
                }

                // توزيع شيفتات متنوع للعرض
                $shiftId = match ($idx % 3) {
                    0 => $morning->id,
                    1 => $evening->id,
                    default => $part->id,
                };

                EmployeeSchedule::updateOrCreate(
                    ['employee_id' => $emp->id, 'weekday' => $weekday],
                    ['work_shift_id' => $shiftId]
                );
            }
        }

        $this->command->info('✅ تم Seed لجداول الدوام الأسبوعية بنجاح (EmployeeScheduleSeeder)');
    }
}
