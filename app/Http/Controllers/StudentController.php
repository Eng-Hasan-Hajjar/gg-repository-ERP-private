<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Branch;
use App\Models\Student;
use App\Models\Diploma;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
public function index(Request $request)
{
    $q = Student::query()->with(['branch','diplomas','profile','crmInfo']);

    if ($request->filled('branch_id')) {
        $q->where('branch_id', $request->branch_id);
    }

    // ✅ فلترة دبلومة عبر pivot
    if ($request->filled('diploma_id')) {
        $diplomaId = $request->diploma_id;
        $q->whereHas('diplomas', function ($x) use ($diplomaId) {
            $x->where('diplomas.id', $diplomaId);
        });
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
              ->orWhere('phone','like',"%$s%");
        });
    }

    // ✅ فقط المثبتين
    $q->where('is_confirmed', true);

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

    $data['registration_status'] = 'confirmed';
    $data['is_confirmed'] = true;
    $data['confirmed_at'] = now();
    $data['university_id'] = $this->generateUniversityId();

    $student = DB::transaction(function () use ($data, $request) {

        $student = Student::create($data);

        // ✅ Profile
        $profileData = $request->input('profile', []);
        if (!empty(array_filter($profileData))) {
            $student->profile()->updateOrCreate(
                ['student_id' => $student->id],
                $profileData
            );
        }

        // ✅ CRM Info
        $crmData = $request->input('crm', []);
        if (!empty(array_filter($crmData))) {
            $student->crmInfo()->updateOrCreate(
                ['student_id' => $student->id],
                $crmData + ['converted_at' => now()]
            );
        }

        // ✅ الدبلومات (Multi)
        // إذا أنت عامل بالـ form: name="diploma_ids[]"
        $diplomaIds = $request->input('diploma_ids', []);
        if (!empty($diplomaIds)) {
            $sync = [];
            foreach ($diplomaIds as $i => $id) {
                $sync[$id] = [
                    'is_primary'  => $i === 0,
                    'enrolled_at' => now()->toDateString(),
                    'status'      => 'active',
                ];
            }
            $student->diplomas()->sync($sync);
        }

        return $student;
    });

    return redirect()
        ->route('students.show', $student)
        ->with('success', 'تم إنشاء الطالب مع التفاصيل بنجاح.');
}


    

    public function show(Student $student)
    {
      //  $this->authorizeByPermission('students.view');

       $student->load(['branch','profile','diplomas','crmInfo']);


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

    unset($data['registration_status']); // كما عندك

    DB::transaction(function () use ($request, $student, $data) {

        $student->update($data);

        // ✅ Profile update
        $profileData = $request->input('profile', []);
        if (!empty($profileData)) {
            $student->profile()->updateOrCreate(
                ['student_id' => $student->id],
                $profileData
            );
        }

        // ✅ CRM update
        $crmData = $request->input('crm', []);
        if (!empty($crmData)) {
            $student->crmInfo()->updateOrCreate(
                ['student_id' => $student->id],
                $crmData
            );
        }

        // ✅ diplomas sync (optional)
        $diplomaIds = $request->input('diploma_ids', null);
        if (is_array($diplomaIds)) {
            $sync = [];
            foreach ($diplomaIds as $i => $id) {
                $sync[$id] = [
                    'is_primary'  => $i === 0,
                    'enrolled_at' => now()->toDateString(),
                    'status'      => 'active',
                ];
            }
            $student->diplomas()->sync($sync);
        }
    });

    return redirect()
        ->route('students.show', $student)
        ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
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
