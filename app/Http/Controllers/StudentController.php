<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Branch;
use App\Models\Student;
use App\Models\Diploma;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $q = Student::query()->with('branch','diploma');

        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }


        if ($request->filled('diploma_id')) {
            $q->where('diploma_id', $request->diploma_id);
        }


        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('registration_status')) {
            $q->where('registration_status', $request->registration_status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($x) use ($s) {
                $x->where('full_name','like',"%$s%")
                  ->orWhere('university_id','like',"%$s%")
                  ->orWhere('phone','like',"%$s%")
                  ;
            });
        }

        return view('students.index', [
            'students' => $q->latest()->paginate(15)->withQueryString(),
            'branches' => Branch::orderBy('name')->get(),
              'diplomas' => Diploma::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
      //  $this->authorizeByPermission('students.create');

        return view('students.create', [
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),
        ]);
    }

    public function store(StudentStoreRequest $request)
    {
        $data = $request->validated();

        // enforce pending in initial creation
        $data['registration_status'] = 'pending';

        // university id
        $data['university_id'] = $this->generateUniversityId();

        $student = Student::create($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم إنشاء الطالب (بيانات أولية) بنجاح.');
    }

    public function show(Student $student)
    {
      //  $this->authorizeByPermission('students.view');

        $student->load(['branch','diploma','profile']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
       // $this->authorizeByPermission('students.update');

        return view('students.edit', [
            'student' => $student,
            'branches' => Branch::orderBy('name')->get(),
             'diplomas' => Diploma::orderBy('name')->get(),
        ]);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        $data = $request->validated();

        // لا تسمح بتغيير registration_status إلى confirmed من هنا (التثبيت له زر خاص)
        unset($data['registration_status']);

        $student->update($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب الأساسية.');
    }

    public function destroy(Student $student)
    {
      //  $this->authorizeByPermission('students.delete');

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'تم حذف الطالب.');
    }

    private function generateUniversityId(): string
    {
        do {
            $id = 'NMA-' . now()->format('Y') . '-' . str::upper(str::random(6));
        } while (Student::where('university_id', $id)->exists());

        return $id;
    }

    private function authorizeByPermission(string $perm): void
    {
        abort_unless(auth()->user()?->can($perm), 403);
    }
}
