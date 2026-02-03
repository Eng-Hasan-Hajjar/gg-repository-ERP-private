<?php
// app/Http/Controllers/StudentController.php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Branch;
use App\Models\Diploma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;

class StudentController extends Controller
{
  public function index(Request $request)
  {
    $q = Student::query()->with(['branch','diplomas','profile','crmInfo']);

    if ($request->filled('branch_id')) $q->where('branch_id', $request->branch_id);

    if ($request->filled('diploma_id')) {
      $did = $request->diploma_id;
      $q->whereHas('diplomas', fn($x)=>$x->where('diplomas.id',$did));
    }

    if ($request->filled('status')) $q->where('status', $request->status);
    if ($request->filled('registration_status')) $q->where('registration_status', $request->registration_status);

    if ($request->filled('search')) {
      $s = trim($request->search);
      $q->where(fn($x)=>$x
        ->where('full_name','like',"%$s%")
        ->orWhere('university_id','like',"%$s%")
        ->orWhere('phone','like',"%$s%")
      );
    }

    // شؤون الطلاب: فقط المثبتين
    $q->where('is_confirmed', true);

    return view('students.index', [
      'students' => $q->latest()->paginate(15)->withQueryString(),
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function create()
  {
    return view('students.create', [
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function store(StudentStoreRequest $request)
  {
    $data = $request->validated();

    // شؤون الطلاب: مثبت افتراضياً
    $data['registration_status'] = 'confirmed';
    $data['is_confirmed'] = true;
    $data['confirmed_at'] = now();
    $data['university_id'] = $this->generateUniversityId();

    $student = DB::transaction(function () use ($data, $request) {

      $student = Student::create($data);

      // profile
     // ✅ إنشاء/تحديث Profile + رفع ملفات
        $profileData = $request->input('profile', []);

        // تعبئة الاسم بالعربي تلقائياً إذا لم يُرسل
        if (empty($profileData['arabic_full_name'] ?? null)) {
            $profileData['arabic_full_name'] = $student->full_name;
        }

        // ✅ Uploads
        $uploadsMap = [
        'photo' => ['col'=>'photo_path', 'dir'=>'students/photos'],
        'info_file' => ['col'=>'info_file_path', 'dir'=>'students/info_files'],
        'identity_file' => ['col'=>'identity_file_path', 'dir'=>'students/identity_files'],
        'attendance_certificate' => ['col'=>'attendance_certificate_path', 'dir'=>'students/attendance_certificates'],
        'certificate_pdf' => ['col'=>'certificate_pdf_path', 'dir'=>'students/certificates/pdf'],
        'certificate_card' => ['col'=>'certificate_card_path', 'dir'=>'students/certificates/card'],
        ];

        foreach ($uploadsMap as $key => $cfg) {
            if ($request->hasFile("profile.$key")) {
                $profileData[$cfg['col']] = $request->file("profile.$key")->store($cfg['dir'], 'public');
            }
        }

        // لا تنشئ Profile فارغ بدون بيانات
        if (!empty(array_filter($profileData))) {
            $student->profile()->updateOrCreate(
                ['student_id' => $student->id],
                $profileData
            );
        }


      // crm
      $crmData = $request->input('crm', []);
      if (!empty(array_filter($crmData))) {
        $student->crmInfo()->updateOrCreate(
          ['student_id'=>$student->id],
          $crmData + ['converted_at'=>now()]
        );
      }

      // diplomas multi
      $diplomaIds = $request->input('diploma_ids', []);
      if (!empty($diplomaIds)) {
        $sync = [];
        foreach ($diplomaIds as $i=>$id) {
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

    return redirect()->route('students.show',$student)->with('success','تم إنشاء الطالب مع التفاصيل بنجاح.');
  }

public function show(Student $student)
{
    $student->load(['branch','diplomas','profile','crmInfo']);

    $p = $student->profile;

    // WhatsApp link (digits only)
    $waDigits = $student->whatsapp ? preg_replace('/\D+/', '', $student->whatsapp) : null;
    $waLink   = $waDigits ? "https://wa.me/{$waDigits}" : null;

    // Files flags + urls
    $files = [
        'photo' => [
            'exists' => (bool)($p?->photo_path),
            'url'    => $p?->photo_path ? asset('storage/'.$p->photo_path) : null,
        ],
        'info' => [
            'exists' => (bool)($p?->info_file_path),
            'url'    => $p?->info_file_path ? asset('storage/'.$p->info_file_path) : null,
        ],
        'identity' => [
            'exists' => (bool)($p?->identity_file_path),
            'url'    => $p?->identity_file_path ? asset('storage/'.$p->identity_file_path) : null,
        ],
        'attendance' => [
            'exists' => (bool)($p?->attendance_certificate_path),
            'url'    => $p?->attendance_certificate_path ? asset('storage/'.$p->attendance_certificate_path) : null,
        ],
        'certificate_pdf' => [
            'exists' => (bool)($p?->certificate_pdf_path),
            'url'    => $p?->certificate_pdf_path ? asset('storage/'.$p->certificate_pdf_path) : null,
        ],
        'certificate_card' => [
            'exists' => (bool)($p?->certificate_card_path),
            'url'    => $p?->certificate_card_path ? asset('storage/'.$p->certificate_card_path) : null,
        ],
    ];

    return view('students.show', [
        'student' => $student,
        'p'       => $p,
        'waLink'  => $waLink,
        'files'   => $files,
    ]);
}


  public function edit(Student $student)
  {
    $student->load(['diplomas','profile','crmInfo']);

    return view('students.edit', [
      'student' => $student,
      'branches' => Branch::orderBy('name')->get(),
      'diplomas' => Diploma::orderBy('name')->get(),
    ]);
  }

  public function update(StudentUpdateRequest $request, Student $student)
  {
    $data = $request->validated();
    unset($data['registration_status']);

    DB::transaction(function () use ($request,$student,$data) {

      $student->update($data);

      $profileData = $request->input('profile', []);
      if (!empty($profileData)) {
        $student->profile()->updateOrCreate(['student_id'=>$student->id], $profileData);
      }

      $crmData = $request->input('crm', []);
      if (!empty($crmData)) {
        $student->crmInfo()->updateOrCreate(['student_id'=>$student->id], $crmData);
      }

      $diplomaIds = $request->input('diploma_ids', null);
      if (is_array($diplomaIds)) {
        $sync = [];
        foreach ($diplomaIds as $i=>$id) {
          $sync[$id] = [
            'is_primary'  => $i === 0,
            'enrolled_at' => now()->toDateString(),
            'status'      => 'active',
          ];
        }
        $student->diplomas()->sync($sync);
      }
    });

    return redirect()->route('students.show',$student)->with('success','تم تحديث الطالب بنجاح.');
  }

  public function destroy(Student $student)
  {
    $student->delete();
    return redirect()->route('students.index')->with('success','تم حذف الطالب.');
  }

  private function generateUniversityId(): string
  {
    do {
      $id = 'NMA-'.now()->format('Y').'-'.Str::upper(Str::random(6));
    } while (Student::where('university_id',$id)->exists());

    return $id;
  }
}
