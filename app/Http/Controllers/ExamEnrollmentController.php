<?php



namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamEnrollmentController extends Controller
{
    public function edit(Exam $exam, Request $request)
    {
        $exam->load(['branch','diploma','students']);

        $studentsQ = Student::query()
            ->where('branch_id', $exam->branch_id)
            ->where('diploma_id', $exam->diploma_id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $studentsQ->where(function($x) use ($s){
                $x->where('full_name','like',"%$s%")
                  ->orWhere('university_id','like',"%$s%");
            });
        }

        $students = $studentsQ->orderBy('full_name')->get();

        // الطلاب المحددين مسبقاً
        $selectedIds = $exam->students->pluck('id')->all();

        return view('exams.students', compact('exam','students','selectedIds'));
    }

    public function update(Exam $exam, Request $request)
    {
        $ids = $request->input('student_ids', []);
        $ids = array_values(array_filter($ids));

        // Sync على pivot
        $syncData = [];
        foreach ($ids as $id) {
            $syncData[$id] = [
                'status' => 'registered',
                'registered_at' => now(),
            ];
        }

        $exam->students()->sync($syncData);

        return redirect()
            ->route('exams.show', $exam)
            ->with('success','تم حفظ الطلاب المنتسبين للامتحان.');
    }
}
