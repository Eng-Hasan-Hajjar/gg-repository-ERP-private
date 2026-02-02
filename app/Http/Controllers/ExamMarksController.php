<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamMarksUpdateRequest;
use App\Models\Exam;
use App\Models\ExamComponentResult;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamMarksController extends Controller
{
    public function edit(Exam $exam, Request $request)
    {
        $exam->load(['branch','diploma','trainer','components']);

        if ($exam->components->isEmpty()) {
            return redirect()
                ->route('exams.components.index', $exam)
                ->with('error','لا يمكن إدخال الدرجات قبل تعريف مكونات الامتحان.');
        }

// إذا ما في طلاب مسجلين → وجهه لاختيار الطلاب
if ($exam->students()->count() === 0) {
    return redirect()
        ->route('exams.students.edit', $exam)
        ->with('error','حدد أولاً الطلاب المنتسبين لهذا الامتحان.');
}

$studentsQ = $exam->students()->getQuery()->with(['branch','diploma']);


        if ($request->filled('search')) {
            $s = trim($request->search);
            $studentsQ->where(function($x) use ($s){
                $x->where('full_name','like',"%$s%")
                  ->orWhere('university_id','like',"%$s%");
            });
        }

        // خيار: فتح شاشة مخصصة لطالب واحد
        if ($request->filled('student_id')) {
            $studentsQ->where('id', $request->student_id);
        }

        $students = $studentsQ->orderBy('full_name')->get();

        // نتائج موجودة: component_id + student_id
        $existing = ExamComponentResult::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('exam_component_id', $exam->components->pluck('id'))
            ->get()
            ->groupBy(fn($r) => $r->student_id);

        return view('exams.marks_edit', compact('exam','students','existing'));
    }

    public function update(ExamMarksUpdateRequest $request, Exam $exam)
    {
        $exam->load('components');

        $rows = $request->validated()['rows'];

        foreach ($rows as $row) {
            $studentId = $row['student_id'];

            // حفظ درجات المكونات
            foreach ($row['components'] as $c) {
                // حماية: تأكد component belongs to exam
                abort_unless($exam->components->contains('id', $c['component_id']), 422, 'Invalid component.');

                ExamComponentResult::updateOrCreate(
                    ['exam_component_id' => $c['component_id'], 'student_id' => $studentId],
                    [
                        'score' => $c['score'] ?? null,
                        'notes' => $c['notes'] ?? null,
                        'entered_by' => auth()->id(),
                    ]
                );
            }

            // إعادة حساب المحصلة النهائية وتحديث exam_results (كـ Cache)
            [$finalScore, $status] = $this->computeFinal($exam, $studentId);

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $studentId],
                [
                    'score' => $finalScore, // هنا صارت المحصلة النهائية
                    'status' => $status,
                    'entered_by' => auth()->id(),
                    'notes' => null,
                ]
            );
        }

        return redirect()
            ->route('exams.show', $exam)
            ->with('success','تم حفظ درجات المكونات وحساب المحصلة النهائية.');
    }

    private function computeFinal(Exam $exam, int $studentId): array
    {
        $components = $exam->components;

        $results = ExamComponentResult::query()
            ->where('student_id', $studentId)
            ->whereIn('exam_component_id', $components->pluck('id'))
            ->get()
            ->keyBy('exam_component_id');

        $total = 0.0;

        foreach ($components as $comp) {
            $r = $results->get($comp->id);

            // إذا مكوّن مطلوب وما عليه درجة => اعتبر not_set
            if ($comp->is_required && (!$r || $r->score === null)) {
                return [null, 'not_set'];
            }

            if (!$r || $r->score === null || (float)$comp->max_score <= 0) {
                continue; // مكوّن غير محسوب إن لم يُدخل
            }

            $ratio = (float)$r->score / (float)$comp->max_score; // 0..1
            $total += $ratio * (float)$comp->weight; // weight-based (0..100)
        }

        // total هو نسبة/100
        $final = round($total, 2);

        // حالة النجاح/الرسوب حسب pass_score
        if ($exam->pass_score !== null) {
            $status = ($final >= (float)$exam->pass_score) ? 'passed' : 'failed';
        } else {
            $status = 'not_set';
        }

        return [$final, $status];
    }
}
