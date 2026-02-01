<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::orderBy('name')->get();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تخطّي EmployeeSeeder');
            return;
        }

        // تاريخ بداية الدوام (منطقي: بداية الشهر الحالي)
        $defaultEffective = now()->startOfMonth()->toDateString();

        // اختر فروع بشكل مرتب (أول 4 فروع إن وجدوا)
        $b1 = $branches[0] ?? $branches->random();
        $b2 = $branches[1] ?? $branches->random();
        $b3 = $branches[2] ?? $branches->random();
        $b4 = $branches[3] ?? $branches->random();

        $items = [
            // ===== Trainers =====
            [
                'full_name' => 'م. أحمد مصطفى',
                'type'      => 'trainer',
                'job_title' => 'مدرب Laravel',
                'email'     => 'trainer1@namaa.test',
                'phone'     => '00963995555111',
                'branch_id' => $b1->id,
                'schedule_mode' => 'weekly',
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'مدرب دوام أسبوعي ثابت (صباحي/مسائي حسب الجداول).',
            ],
            [
                'full_name' => 'م. محمد علي',
                'type'      => 'trainer',
                'job_title' => 'مدرب شبكات',
                'email'     => 'trainer2@namaa.test',
                'phone'     => '00963995555222',
                'branch_id' => $b2->id,
                'schedule_mode' => 'weekly',
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'مدرب دوام أسبوعي ثابت. مناسب للتقويم الشهري.',
            ],

            // ===== Employees =====
            [
                'full_name' => 'نور الدين حسن',
                'type'      => 'employee',
                'job_title' => 'شؤون طلاب',
                'email'     => 'employee1@namaa.test',
                'phone'     => '00963995555333',
                'branch_id' => $b3->id,
                'schedule_mode' => 'weekly',
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'موظف إداري دوام أسبوعي قياسي (أحد-خميس).',
            ],
            [
                'full_name' => 'سارة محمود',
                'type'      => 'employee',
                'job_title' => 'إدارة مالية',
                'email'     => 'employee2@namaa.test',
                'phone'     => '00963995555444',
                'branch_id' => $b4->id,
                'schedule_mode' => 'custom', // مثال على دوام متغير
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'دوام مرن (Custom) بسبب طبيعة العمل/الإغلاق المالي.',
            ],

            // ===== إضافات لطيفة لتشوف تنوع أكبر =====
            [
                'full_name' => 'ليلى عثمان',
                'type'      => 'employee',
                'job_title' => 'استقبال',
                'email'     => 'employee3@namaa.test',
                'phone'     => '00963995555666',
                'branch_id' => $b1->id,
                'schedule_mode' => 'weekly',
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'استقبال دوام أسبوعي ثابت.',
            ],
            [
                'full_name' => 'م. خالد حميد',
                'type'      => 'trainer',
                'job_title' => 'مدرب Python',
                'email'     => 'trainer3@namaa.test',
                'phone'     => '00963995555777',
                'branch_id' => $b2->id,
                'schedule_mode' => 'custom',
                'schedule_effective_from' => $defaultEffective,
                'notes' => 'مدرب حسب الدورات (Custom) وقد يتغير أسبوعياً.',
            ],
        ];

        foreach ($items as $i) {
            Employee::updateOrCreate(
                ['email' => $i['email']], // مفتاح ثابت
                [
                    'code'      => $this->makeCode($i['type']),
                    'full_name' => $i['full_name'],
                    'type'      => $i['type'],
                    'phone'     => $i['phone'],
                    'email'     => $i['email'],
                    'branch_id' => $i['branch_id'],
                    'job_title' => $i['job_title'],
                    'status'    => 'active',
                    'notes'     => $i['notes'] ?? null,

                    // ✅ حقول الدوام الجديدة
                    'schedule_mode' => $i['schedule_mode'] ?? 'weekly',
                    'schedule_effective_from' => $i['schedule_effective_from'] ?? null,
                ]
            );
        }

        $this->command->info('✅ تم Seed للموظفين مع بيانات دوام نظامية (schedule_mode + effective_from).');
    }

    private function makeCode(string $type): string
    {
        // مثال: TR-2026-AB123 أو EMP-2026-XY456
        $prefix = $type === 'trainer' ? 'TR' : 'EMP';
        return $prefix . '-' . now()->format('Y') . '-' . Str::upper(Str::random(5));
    }
}
