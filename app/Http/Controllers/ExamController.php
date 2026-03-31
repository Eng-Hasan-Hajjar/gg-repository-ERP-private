<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamStoreRequest;
use App\Http\Requests\ExamUpdateRequest;
use App\Models\Branch;
use App\Models\Diploma;
use App\Models\Employee;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\ExamResult;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $q = Exam::query()->with(['branch', 'diploma', 'trainer']);

        if ($request->filled('branch_id'))
            $q->where('branch_id', $request->branch_id);
        if ($request->filled('diploma_id'))
            $q->where('diploma_id', $request->diploma_id);
        if ($request->filled('trainer_id'))
            $q->where('trainer_id', $request->trainer_id);
        if ($request->filled('type'))
            $q->where('type', $request->type);

        if ($request->filled('from'))
            $q->whereDate('exam_date', '>=', $request->from);
        if ($request->filled('to'))
            $q->whereDate('exam_date', '<=', $request->to);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('title', 'like', "%$s%")->orWhere('code', 'like', "%$s%");
            });
        }

        return view('exams.index', [
            'exams' => $q->latest('exam_date')->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
            'trainers' => Employee::orderBy('full_name')->get(),
        ]);
    }

    /*   public function create()
       {
           return view('exams.create', [
               'branches' => Branch::orderBy('name')->get(),
               'diplomas' => Diploma::orderBy('name')->get(),
               'trainers' => Employee::orderBy('full_name')->get(),
           ]);
       }
   */

    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            $branches = Branch::orderBy('name')->get();
        } else {
            $branches = Branch::where('id', $user->employee?->branch_id)->get();
        }

        return view('exams.create', [
            'branches' => $branches,
            'diplomas' => Diploma::orderBy('name')->get(),
            'trainers' => Employee::trainers()
                ->where('branch_id', auth()->user()->employee?->branch_id)
                ->orderBy('full_name')
                ->get(),
        ]);
    }

    public function store(ExamStoreRequest $request)
    {

        $data = $request->validated();

        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {
            $data['branch_id'] = $user->employee?->branch_id;
        }

        $exam = Exam::create($data);


        //  $exam = Exam::create($request->validated());

        return redirect()
            ->route('exams.show', $exam)
            ->with('success', 'تم إنشاء الامتحان بنجاح.');
    }
    /*
        public function show(Exam $exam)
        {
            $exam->load(['branch','diploma','trainer','results.student.branch','results.student.diploma']);

            return view('exams.show', compact('exam'));
        }
    */
    public function show(Exam $exam)
    {
        $exam->load(['branch', 'diploma', 'trainer', 'results.student', 'results.student.branch', 'results.student.diploma']);

        $total = $exam->results
            ->whereNotIn('status', ['absent', 'excused'])
            ->count();

        $passed = $exam->results
            ->where('status', 'passed')
            ->count();

        //$total   = $exam->results->count();
        $passed = $exam->results->where('status', 'passed')->count();
        $failed = $exam->results->where('status', 'failed')->count();

        $successRate = $total > 0
            ? round(($passed / $total) * 100)
            : 0;

        return view('exams.show', compact(
            'exam',
            'total',
            'passed',
            'failed',
            'successRate'
        ));
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
    /*
        public function update(ExamUpdateRequest $request, Exam $exam)
        {
            $exam->update($request->validated());

            return redirect()
                ->route('exams.show', $exam)
                ->with('success','تم تحديث الامتحان.');
        }
    */


public function update(ExamUpdateRequest $request, Exam $exam)
{
    $data = $request->validated();

    $user = auth()->user();

    if (!$user->hasRole('super_admin')) {
        $data['branch_id'] = $user->employee?->branch_id;
    }

    $exam->update($data);

    return redirect()
        ->route('exams.show', $exam)
        ->with('success', 'تم تعديل الامتحان بنجاح');
}


/*
    public function update(Request $request, Exam $exam)
    {
        foreach ($request->statuses ?? [] as $studentId => $status) {

            $score = $request->scores[$studentId] ?? null;

            // إذا لم يتم اختيار أي حالة → تجاهل
            if (!$status) {
                continue;
            }

            // لو الحالة absent أو excused → لا يوجد درجة
            if (in_array($status, ['absent', 'excused'])) {
                $score = null;
            }

            // لو الحالة passed/failed ولم يدخل درجة → تجاهل
            if (in_array($status, ['passed', 'failed']) && $score === null) {
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
                    'entered_by' => auth()->id(),
                ]
            );
        }

        return redirect()
            ->route('exams.results.edit', $exam)
            ->with('success', 'تم حفظ النتائج بنجاح');
    }


    */  


    
public function destroy(Exam $exam)
{
    // تحقق إذا يوجد نتائج (طلاب)
    if ($exam->results()->exists()) {
        return redirect()
            ->route('exams.index')
            ->with('error', 'لا يمكن حذف الامتحان لأنه يحتوي على طلاب');
    }

    $exam->delete();

    return redirect()
        ->route('exams.index')
        ->with('success', 'تم حذف الامتحان بنجاح');
}
}
