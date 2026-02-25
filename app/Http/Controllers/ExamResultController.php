<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
public function edit(Exam $exam, Request $request)
{
    $exam->load(['branch','diploma','trainer']);

    $studentsQ = \App\Models\Student::query()
        ->where('branch_id', $exam->branch_id)
        ->whereHas('diplomas', function ($q) use ($exam) {
            $q->where('diplomas.id', $exam->diploma_id);
        });

    if ($request->filled('search')) {
        $s = trim($request->search);
        $studentsQ->where(function($x) use ($s){
            $x->where('full_name','like',"%$s%")
              ->orWhere('university_id','like',"%$s%");
        });
    }

    if ($request->filled('student_id')) {
        $studentsQ->where('id', $request->student_id);
    }

    $students = $studentsQ->orderBy('full_name')->get();

    $existing = \App\Models\ExamResult::where('exam_id', $exam->id)
        ->get()
        ->keyBy('student_id');

    return view('exams.results_edit', compact('exam','students','existing'));
}
public function update(Request $request, Exam $exam)
{
    foreach ($request->rows ?? [] as $row) {

        if (!isset($row['student_id'])) {
            continue; // حماية إضافية
        }

        $studentId = $row['student_id'];
        $status    = $row['status'] ?? null;
        $score     = $row['score'] ?? null;
        $notes     = $row['notes'] ?? null;

        if (!$status) {
            continue;
        }

        if (in_array($status, ['absent','excused'])) {
            $score = null;
        }

        if (in_array($status, ['passed','failed']) && $score === null) {
            continue;
        }

        ExamResult::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $studentId,
            ],
            [
                'score' => $score,
                'status' => $status,
                'notes' => $notes,
                'entered_by' => auth()->id(),
            ]
        );
    }

    return redirect()
        ->route('exams.show', $exam)
        ->with('success','تم حفظ النتائج بنجاح.');
}
}