<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentExtraUpdateRequest;
use App\Models\Student;

class StudentExtraController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function edit(Student $student)
    {
        abort_unless(auth()->user()->can('students.extra.update'), 403);

        if (!$student->is_confirmed) {
            return redirect()
                ->route('students.show', $student)
                ->with('error', 'لا يمكن إضافة معلومات إضافية قبل تثبيت الطالب.');
        }

        $student->load('extra');

        return view('students.extra_edit', compact('student'));
    }

    public function update(StudentExtraUpdateRequest $request, Student $student)
    {
        if (!$student->is_confirmed) {
            abort(403, 'Student not confirmed.');
        }

        $extra = $student->extra()->firstOrCreate(['student_id' => $student->id], ['data' => []]);

        $payload = $request->validated()['data'] ?? [];
        $extra->update(['data' => $payload]);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث المعلومات الإضافية.');
    }
}
