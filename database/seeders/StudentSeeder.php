<?php

namespace Database\Seeders;

use App\Models\{
    Student, Branch, Diploma,
    StudentProfile, StudentCrmInfo
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $diplomas = Diploma::all();

        if ($branches->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد فروع، تم تخطي الطلاب');
            return;
        }

        for ($i = 1; $i <= 50; $i++) {

            $firstNames = ['أحمد','محمد','سارة','نور','علي','ريم','ياسر','لينا'];
            $lastNames  = ['الخطيب','العلي','حسن','عبدالله','المصري','الحموي'];

            $first = collect($firstNames)->random();
            $last  = collect($lastNames)->random();

            $student = Student::create([
                'university_id' => 'NMA-' . now()->format('Y') . '-' . Str::upper(Str::random(6)),
                'first_name' => $first,
                'last_name'  => $last,
                'full_name'  => "$first $last",
                'email'      => "student{$i}@test.local",
                'phone'      => '0096399' . rand(1000000, 9999999),
                'whatsapp'   => '0096399' . rand(1000000, 9999999),
                'branch_id'  => $branches->random()->id,
                'mode'       => rand(0,1) ? 'onsite' : 'online',
                'status'     => 'waiting',
                'registration_status' => 'pending',
                'is_confirmed' => false,
            ]);

            // Profile
            StudentProfile::create([
                'student_id' => $student->id,
                'arabic_full_name' => $student->full_name,
                'nationality' => 'Syrian',
                'birth_date' => now()->subYears(rand(18,30)),
                'address' => 'حلب',
                'exam_score' => rand(50, 100),
                'notes' => 'طالب افتراضي للاختبار',
            ]);
// CRM
$sources = ['ad','referral','social','website','expo','other'];
$stages  = ['new','follow_up','interested','registered','rejected','postponed'];

StudentCrmInfo::create([
    'student_id' => $student->id,
    'first_contact_date' => now()->subDays(rand(1,30))->toDateString(), // لأن الحقل date
    'residence' => 'حلب',
    'age' => rand(18,30),
    'organization' => 'Nama Academy',
    'source' => $sources[array_rand($sources)],     // ✅ قيم enum
    'stage'  => $stages[array_rand($stages)],       // ✅ قيم enum
    'need'   => 'يريد التسجيل في دبلومة مناسبة.',  // اختياري (عندك حقل need)
    'notes'  => 'CRM Auto Seed',
]);


            // Diplomas (pivot)
            if ($diplomas->isNotEmpty()) {
                $student->diplomas()->attach(
                    $diplomas->random()->id,
                    [
                        'is_primary' => true,
                        'enrolled_at' => now(),
                        'status' => 'active',
                        'notes' => 'Auto seeded'
                    ]
                );
            }
        }

        $this->command->info('✅ تم إنشاء 50 طالب مع Profile و CRM و Diplomas');
    }
}
