<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $branch1 = Branch::first();
        $branch2 = Branch::skip(1)->first() ?? $branch1;
        $diploma1 = Diploma::first();
        $diploma2 = Diploma::skip(1)->first() ?? $diploma1;

        // المستخدم الأول (super_admin) والثاني (موظف عادي)
        $user1 = User::first();
        $user2 = User::skip(1)->first() ?? $user1;

        $students = [
            [
                'first_name' => 'أحمد',
                'last_name' => 'محمد الحسن',
                'full_name' => 'أحمد محمد الحسن',
                'phone' => '0501111001',
                'branch_id' => $branch1?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'onsite',
                'created_by' => $user1?->id,
                'diploma_id' => $diploma1?->id,
            ],
            [
                'first_name' => 'سارة',
                'last_name' => 'عبدالله الزهراني',
                'full_name' => 'سارة عبدالله الزهراني',
                'phone' => '0501111002',
                'branch_id' => $branch1?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'online',
                'created_by' => $user1?->id,
                'diploma_id' => $diploma1?->id,
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'علي الغامدي',
                'full_name' => 'محمد علي الغامدي',
                'phone' => '0501111003',
                'branch_id' => $branch1?->id,
                'status' => 'waiting',
                'registration_status' => 'pending',
                'mode' => 'onsite',
                'created_by' => $user1?->id,
                'diploma_id' => $diploma2?->id,
            ],
            [
                'first_name' => 'فاطمة',
                'last_name' => 'يوسف العمري',
                'full_name' => 'فاطمة يوسف العمري',
                'phone' => '0501111004',
                'branch_id' => $branch1?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'online',
                'created_by' => $user1?->id,
                'diploma_id' => $diploma2?->id,
            ],
            [
                'first_name' => 'خالد',
                'last_name' => 'إبراهيم السعدي',
                'full_name' => 'خالد إبراهيم السعدي',
                'phone' => '0501111005',
                'branch_id' => $branch2?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'onsite',
                'created_by' => $user1?->id,
                'diploma_id' => $diploma1?->id,
            ],
            [
                'first_name' => 'نورة',
                'last_name' => 'سعد القحطاني',
                'full_name' => 'نورة سعد القحطاني',
                'phone' => '0501111006',
                'branch_id' => $branch1?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'onsite',
                'created_by' => $user2?->id,
                'diploma_id' => $diploma1?->id,
            ],
            [
                'first_name' => 'عمر',
                'last_name' => 'عبدالرحمن الدوسري',
                'full_name' => 'عمر عبدالرحمن الدوسري',
                'phone' => '0501111007',
                'branch_id' => $branch1?->id,
                'status' => 'withdrawn',
                'registration_status' => 'confirmed',
                'mode' => 'online',
                'created_by' => $user2?->id,
                'diploma_id' => $diploma2?->id,
            ],
            [
                'first_name' => 'ريم',
                'last_name' => 'عبدالعزيز الشهري',
                'full_name' => 'ريم عبدالعزيز الشهري',
                'phone' => '0501111008',
                'branch_id' => $branch2?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'onsite',
                'created_by' => $user2?->id,
                'diploma_id' => $diploma1?->id,
            ],
            [
                'first_name' => 'يوسف',
                'last_name' => 'ناصر المطيري',
                'full_name' => 'يوسف ناصر المطيري',
                'phone' => '0501111009',
                'branch_id' => $branch2?->id,
                'status' => 'active',
                'registration_status' => 'confirmed',
                'mode' => 'online',
                'created_by' => $user2?->id,
                'diploma_id' => $diploma2?->id,
            ],
            [
                'first_name' => 'هند',
                'last_name' => 'سلطان الرشيدي',
                'full_name' => 'هند سلطان الرشيدي',
                'phone' => '0501111010',
                'branch_id' => $branch2?->id,
                'status' => 'waiting',
                'registration_status' => 'pending',
                'mode' => 'onsite',
                'created_by' => $user2?->id,
                'diploma_id' => $diploma1?->id,
            ],
        ];
        

        foreach ($students as $data) {
            $diplomaId = $data['diploma_id'];
            unset($data['diploma_id']);

            $data['university_id'] = 'NMA-' . now()->format('Y') . '-' . strtoupper(Str::random(6));
            $data['is_confirmed'] = $data['registration_status'] === 'confirmed';
            $data['confirmed_at'] = $data['is_confirmed'] ? now() : null;

            $student = Student::create($data);

            // ربط الدبلومة
            if ($diplomaId) {
                $student->diplomas()->attach($diplomaId, [
                    'is_primary' => true,
                    'enrolled_at' => now()->toDateString(),
                    'status' => 'active',
                ]);
            }

            // إنشاء Profile
            $student->profile()->create([
                'student_id' => $student->id,
                'arabic_full_name' => $student->full_name,
            ]);
        }
    }
}