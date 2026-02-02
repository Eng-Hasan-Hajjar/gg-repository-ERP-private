<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamResultsUpdateRequest;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    // صفحة إدخال/تعديل درجات الامتحان (قائمة طلاب الفرع+الدبلومة)
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

        $students = $studentsQ->orderBy('full_name')->get();

        // خرائط نتائج موجودة
        $existing = ExamResult::where('exam_id', $exam->id)->get()->keyBy('student_id');

        return view('exams.results_edit', compact('exam','students','existing'));
    }

    // حفظ جماعي
    public function update(ExamResultsUpdateRequest $request, Exam $exam)
    {
        $results = $request->validated()['results'];

        foreach ($results as $row) {
            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $row['student_id']],
                [
                    'score' => $row['score'] ?? null,
                    'status' => $row['status'],
                    'notes' => $row['notes'] ?? null,
                    'entered_by' => auth()->id(),
                ]
            );
        }

        return redirect()
            ->route('exams.show', $exam)
            ->with('success','تم حفظ درجات الامتحان بنجاح.');
    }

    // سجل امتحانات طالب (Transcript)
    public function studentTranscript(Student $student)
    {
        $student->load(['branch','diploma']);

        $results = ExamResult::query()
            ->with(['exam.branch','exam.diploma','exam.trainer'])
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('students.exams', compact('student','results'));
    }
}
