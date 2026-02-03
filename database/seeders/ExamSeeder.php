<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Diploma;
use App\Models\Branch;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // اختر دبلومة + فرع
        $diploma = Diploma::first();
        $branch  = Branch::first();

        if (!$diploma || !$branch) {
            $this->command->warn('⚠️ لا يوجد Diplomas أو Branches');
            return;
        }

        // اختر طلاب منتسبين لهذه الدبلومة (pivot)
        $students = Student::whereHas('diplomas', function ($q) use ($diploma) {
            $q->where('diplomas.id', $diploma->id);
        })->limit(20)->get();

        if ($students->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد طلاب مرتبطين بهذه الدبلومة');
            return;
        }

        // إنشاء امتحان
        $exam = Exam::create([
            'title'       => 'Final Exam - Seeder',
            'code'        => 'EX-' . now()->format('Ymd'),
            'exam_date'   => now()->toDateString(),
            'type'        => 'final',
            'max_score'   => 100,
            'pass_score'  => 50,
            'diploma_id'  => $diploma->id,
            'branch_id'   => $branch->id,
            'trainer_id'  => null,
            'notes'       => 'Seeder demo exam',
        ]);

        // تسجيل الطلاب + إدخال نتائج
        foreach ($students as $student) {

            // تسجيل الطالب في الامتحان
            $exam->registrations()->create([
                'student_id'   => $student->id,
                'status'       => 'registered',
                'registered_at'=> now(),
            ]);

            // نتيجة الامتحان
            ExamResult::updateOrCreate(
                [
                    'exam_id'    => $exam->id,
                    'student_id' => $student->id,
                ],
                [
                    'score'      => rand(40, 100),
                    'status'     => 'passed',
                    'entered_by' => 1,
                ]
            );
        }

        $this->command->info('✅ ExamSeeder تم بنجاح مع طلاب عبر pivot');
    }
}
