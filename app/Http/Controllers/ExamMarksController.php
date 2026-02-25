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
        $exam->load(['branch','diploma','trainer']);


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
           
            ->get()
            ->groupBy(fn($r) => $r->student_id);

        return view('exams.marks_edit', compact('exam','students','existing'));
    }

    public function update(ExamMarksUpdateRequest $request, Exam $exam)
    {
      

        $rows = $request->validated()['rows'];

        foreach ($rows as $row) {
            $studentId = $row['student_id'];

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


}
