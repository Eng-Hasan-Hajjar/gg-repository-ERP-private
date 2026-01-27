<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Branch;
use App\Models\Diploma;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $branches  = Branch::all();
        $diplomas  = Diploma::all();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تخطّي StudentSeeder');
            return;
        }

        $students = [
            [
                'first_name' => 'أحمد',
                'last_name'  => 'الخطيب',
                'email'      => 'ahmad@student.test',
                'phone'      => '00963991111111',
                'mode'       => 'onsite',
            ],
            [
                'first_name' => 'محمد',
                'last_name'  => 'العلي',
                'email'      => 'mohammad@student.test',
                'phone'      => '00963992222222',
                'mode'       => 'online',
            ],
            [
                'first_name' => 'سارة',
                'last_name'  => 'حسن',
                'email'      => 'sara@student.test',
                'phone'      => '00963993333333',
                'mode'       => 'onsite',
            ],
            [
                'first_name' => 'نور',
                'last_name'  => 'عبدالله',
                'email'      => 'noor@student.test',
                'phone'      => '00963994444444',
                'mode'       => 'online',
            ],
        ];

        foreach ($students as $i => $s) {

            $branch  = $branches->random();
            $diploma = $diplomas->isNotEmpty() ? $diplomas->random() : null;

            Student::firstOrCreate(
                ['email' => $s['email']],
                [
                    'university_id'       => 'NMA-' . now()->format('Y') . '-' . Str::upper(Str::random(6)),
                    'first_name'          => $s['first_name'],
                    'last_name'           => $s['last_name'],
                    'full_name'           => $s['first_name'].' '.$s['last_name'],
                    'phone'               => $s['phone'],
                    'whatsapp'            => $s['phone'],
                    'email'               => $s['email'],
                    'branch_id'            => $branch->id,
                    'mode'                => $s['mode'],
                    'status'              => 'waiting',
                    'registration_status' => 'pending',
                    'is_confirmed'        => false,
                    'diploma_id'          => $diploma?->id,
                ]
            );
        }
    }
}
