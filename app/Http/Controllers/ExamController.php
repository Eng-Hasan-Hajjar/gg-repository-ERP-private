<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamStoreRequest;
use App\Http\Requests\ExamUpdateRequest;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Employee;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $q = Exam::query()->with(['branch','diploma','trainer']);

        if ($request->filled('branch_id'))  $q->where('branch_id', $request->branch_id);
        if ($request->filled('diploma_id')) $q->where('diploma_id', $request->diploma_id);
        if ($request->filled('trainer_id')) $q->where('trainer_id', $request->trainer_id);
        if ($request->filled('type'))       $q->where('type', $request->type);

        if ($request->filled('from')) $q->whereDate('exam_date','>=',$request->from);
        if ($request->filled('to'))   $q->whereDate('exam_date','<=',$request->to);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($x) use ($s){
                $x->where('title','like',"%$s%")->orWhere('code','like',"%$s%");
            });
        }

        return view('exams.index', [
            'exams' => $q->latest('exam_date')->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'trainers' => Employee::orderBy('full_name')->get(),
        ]);
    }

    public function create()
    {
        return view('exams.create', [
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'trainers' => Employee::orderBy('full_name')->get(),
        ]);
    }

    public function store(ExamStoreRequest $request)
    {
        $exam = Exam::create($request->validated());

        return redirect()
            ->route('exams.show', $exam)
            ->with('success','تم إنشاء الامتحان بنجاح.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['branch','diploma','trainer','results.student.branch','results.student.diploma']);

        return view('exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('exams.edit', [
            'exam' => $exam,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'trainers' => Employee::orderBy('full_name')->get(),
        ]);
    }

    public function update(ExamUpdateRequest $request, Exam $exam)
    {
        $exam->update($request->validated());

        return redirect()
            ->route('exams.show', $exam)
            ->with('success','تم تحديث الامتحان.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('exams.index')
            ->with('success','تم حذف الامتحان.');
    }
}
