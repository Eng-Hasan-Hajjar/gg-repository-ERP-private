<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamComponent;
use App\Models\ExamComponentResult;
use App\Models\Student;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::query()->whereNotNull('diploma_id')->limit(20)->get();
        if ($students->isEmpty()) return;

        $sample = $students->first();

        $exam = Exam::create([
            'title' => 'Final Exam (Dynamic) - Sample',
            'code' => 'EXD-' . now()->format('Ymd'),
            'exam_date' => now()->toDateString(),
            'type' => 'final',
            'max_score' => 100,
            'pass_score' => 50,
            'diploma_id' => $sample->diploma_id,
            'branch_id' => $sample->branch_id,
            'trainer_id' => null,
            'notes' => 'Seeder demo dynamic exam',
        ]);
        $eligible = $students->where('diploma_id',$exam->diploma_id)
                     ->where('branch_id',$exam->branch_id);

        $exam->students()->syncWithPivotValues(
            $eligible->pluck('id')->all(),
            ['status'=>'registered','registered_at'=>now()]
        );


        // Components (weights sum 100)
        $components = collect([
            ['title'=>'مذاكرة 1','key'=>'memo1','max_score'=>20,'weight'=>10,'sort_order'=>1,'is_required'=>false],
            ['title'=>'عملي 1','key'=>'pr1','max_score'=>50,'weight'=>25,'sort_order'=>2,'is_required'=>true],
            ['title'=>'عملي 2','key'=>'pr2','max_score'=>50,'weight'=>25,'sort_order'=>3,'is_required'=>true],
            ['title'=>'مشروع','key'=>'project','max_score'=>100,'weight'=>20,'sort_order'=>4,'is_required'=>false],
            ['title'=>'امتحان نهائي','key'=>'final','max_score'=>100,'weight'=>20,'sort_order'=>5,'is_required'=>true],
        ])->map(fn($c) => $exam->components()->create($c));

        $targetStudents = $students
            ->where('diploma_id',$exam->diploma_id)
            ->where('branch_id',$exam->branch_id);

        foreach ($targetStudents as $s) {
            foreach ($components as $comp) {
                $score = rand(0, (int)$comp->max_score);

                ExamComponentResult::updateOrCreate(
                    ['exam_component_id'=>$comp->id,'student_id'=>$s->id],
                    ['score'=>$score,'notes'=>null,'entered_by'=>1]
                );
            }

            // Compute final cache (same logic as controller)
            $total = 0.0;
            foreach ($components as $comp) {
                $r = ExamComponentResult::where('exam_component_id',$comp->id)->where('student_id',$s->id)->first();
                if (!$r || $r->score === null || (float)$comp->max_score <= 0) continue;
                $total += ((float)$r->score / (float)$comp->max_score) * (float)$comp->weight;
            }
            $final = round($total, 2);

            $status = ($final >= 50) ? 'passed' : 'failed';

            ExamResult::updateOrCreate(
                ['exam_id'=>$exam->id,'student_id'=>$s->id],
                ['score'=>$final,'status'=>$status,'entered_by'=>1]
            );
        }
    }
}
