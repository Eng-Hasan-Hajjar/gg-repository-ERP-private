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
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تخطّي EmployeeSeeder');
            return;
        }

        $items = [
            [
                'full_name' => 'م. أحمد مصطفى',
                'type'      => 'trainer',
                'job_title'=> 'مدرب Laravel',
                'email'     => 'trainer1@namaa.test',
                'phone'     => '00963995555111',
            ],
            [
                'full_name' => 'م. محمد علي',
                'type'      => 'trainer',
                'job_title'=> 'مدرب شبكات',
                'email'     => 'trainer2@namaa.test',
                'phone'     => '00963995555222',
            ],
            [
                'full_name' => 'نور الدين حسن',
                'type'      => 'employee',
                'job_title'=> 'شؤون طلاب',
                'email'     => 'employee1@namaa.test',
                'phone'     => '00963995555333',
            ],
            [
                'full_name' => 'سارة محمود',
                'type'      => 'employee',
                'job_title'=> 'إدارة مالية',
                'email'     => 'employee2@namaa.test',
                'phone'     => '00963995555444',
            ],
        ];

        foreach ($items as $i) {
            Employee::firstOrCreate(
                ['email' => $i['email']],
                [
                    'code'      => 'EMP-' . now()->format('Y') . '-' . Str::upper(Str::random(5)),
                    'full_name' => $i['full_name'],
                    'type'      => $i['type'],
                    'phone'     => $i['phone'],
                    'email'     => $i['email'],
                    'branch_id' => $branches->random()->id,
                    'job_title' => $i['job_title'],
                    'status'    => 'active',
                    'notes'     => 'موظف/مدرب افتراضي للتجربة',
                ]
            );
        }
    }
}
