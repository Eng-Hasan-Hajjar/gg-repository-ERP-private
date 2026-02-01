<?php

namespace Database\Seeders;

use App\Models\WorkShift;
use Illuminate\Database\Seeder;

class WorkShiftSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'صباحي',
                'code' => 'AM',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'grace_minutes' => 10,
                'is_active' => true,
                'notes' => 'دوام صباحي افتراضي',
            ],
            [
                'name' => 'مسائي',
                'code' => 'PM',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'grace_minutes' => 10,
                'is_active' => true,
                'notes' => 'دوام مسائي افتراضي',
            ],
            [
                'name' => 'جزئي',
                'code' => 'PT',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'grace_minutes' => 5,
                'is_active' => true,
                'notes' => 'دوام جزئي (نصف يوم)',
            ],
        ];

        foreach ($items as $i) {
            WorkShift::updateOrCreate(
                ['code' => $i['code']], // 🔑 مفتاح عدم التضارب
                $i
            );
        }

        $this->command->info('✅ تم Seed لشيفتات الدوام بنجاح');
    }
}
