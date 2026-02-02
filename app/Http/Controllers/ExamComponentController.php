<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamComponentStoreRequest;
use App\Models\Exam;
use App\Models\ExamComponent;
use Illuminate\Http\Request;

class ExamComponentController extends Controller
{
    public function index(Exam $exam)
    {
        $exam->load('components');

        $totalWeight = $exam->components->sum('weight');

        return view('exams.components', compact('exam','totalWeight'));
    }

    public function store(ExamComponentStoreRequest $request, Exam $exam)
    {
        $data = $request->validated();
        $data['is_required'] = (bool) $request->boolean('is_required');

        $exam->components()->create($data);

        return back()->with('success','تمت إضافة مكوّن للامتحان.');
    }

    public function destroy(Exam $exam, ExamComponent $component)
    {
        abort_unless($component->exam_id === $exam->id, 404);

        $component->delete();

        return back()->with('success','تم حذف المكوّن.');
    }
}
