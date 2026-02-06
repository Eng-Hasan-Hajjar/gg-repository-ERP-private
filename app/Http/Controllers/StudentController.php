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
use Illuminate\Support\Facades\Storage;
class StudentController extends Controller
{
public function index(Request $request)
{
    $q = Student::query()->with(['branch','diplomas','profile','crmInfo']);

    if ($request->filled('branch_id')) {
        $q->where('branch_id', $request->branch_id);
    }

    if ($request->filled('diploma_id')) {
        $did = $request->diploma_id;
        $q->whereHas('diplomas', fn($x)=>$x->where('diplomas.id',$did));
    }

    if ($request->filled('status')) {
        $q->where('status', $request->status);
    }

    if ($request->filled('registration_status')) {
        $q->where('registration_status', $request->registration_status);
    }

    if ($request->filled('search')) {
        $s = trim($request->search);
        $q->where(fn($x)=>$x
            ->where('full_name','like',"%$s%")
            ->orWhere('university_id','like',"%$s%")
            ->orWhere('phone','like',"%$s%")
        );
    }

    // ✅ فقط المثبتين
    $q->where('is_confirmed', true);

    $students = $q->latest()->paginate(15)->withQueryString();

    // ✅ خرائط التعريب (نفس التي عندك في show)
    $labels = $this->studentArabicLabels();

    // ✅ جهز خيارات الفلاتر بالعربي
    $statusOptions = $labels['student_status'];            // key=>arabic
    $registrationOptions = $labels['registration_status']; // key=>arabic
    $modeOptions = $labels['mode'];                        // key=>arabic

    // ✅ أضف لكل طالب قيم جاهزة للعرض بالعربي (بدون تعديل DB)
    $students->getCollection()->transform(function ($s) use ($labels) {
        $s->status_ar = $labels['student_status'][$s->status] ?? ($s->status ?? '-');
        $s->registration_ar = $labels['registration_status'][$s->registration_status] ?? ($s->registration_status ?? '-');
        $s->mode_ar = $labels['mode'][$s->mode] ?? ($s->mode ?? '-');
        return $s;
    });

    return view('students.index', [
        'students' => $students,
        'branches' => Branch::orderBy('name')->get(),
        'diplomas' => Diploma::orderBy('name')->get(),

        // ✅ للفلاتر
        'labels' => $labels,
        'statusOptions' => $statusOptions,
        'registrationOptions' => $registrationOptions,
        'modeOptions' => $modeOptions,
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
$this->saveProfileWithUploads($student, $request);
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

    $waDigits = $student->whatsapp ? preg_replace('/\D+/', '', $student->whatsapp) : null;
    $waLink   = $waDigits ? "https://wa.me/{$waDigits}" : null;

    $files = $this->buildProfileFiles($p);

    $diplomaFiles = $this->buildDiplomaFiles($student);

    $labels = $this->studentArabicLabels();

    $status_ar = $labels['student_status'][$student->status] ?? ($student->status ?? '-');
    $registration_ar = $labels['registration_status'][$student->registration_status] ?? '-';
    $mode_ar = $labels['mode'][$student->mode] ?? '-';

    $crm_source_ar = $student->crmInfo
        ? ($labels['crm_source'][$student->crmInfo->source] ?? '-')
        : '-';

    $crm_stage_ar = $student->crmInfo
        ? ($labels['crm_stage'][$student->crmInfo->stage] ?? '-')
        : '-';


        // ======= 🔹 تعريب حالة الدبلومة (خاص بالـ Pivot) =======
$diplomaStatusMap = [
    'active'   => 'نشط',
    'waiting'  => 'بانتظار',
    'finished' => 'منتهي',
];

$student->diplomas->transform(function ($d) use ($diplomaStatusMap) {
    $d->pivot->status_ar =
        $diplomaStatusMap[$d->pivot->status] ?? $d->pivot->status;

    return $d;
});





    return view('students.show', compact(
        'student','p','waLink','files',
        'diplomaFiles',
        'status_ar','registration_ar','mode_ar',
        'crm_source_ar','crm_stage_ar'
    ));
}

/**
 * ✅ تجهيز روابط الملفات + exists بشكل آمن حتى لو ما في بروفايل
 */
private function buildProfileFiles($p): array
{
    $disk = Storage::disk('public');

    $map = [
        'photo'            => $p?->photo_path,
        'info'             => $p?->info_file_path,
        'identity'         => $p?->identity_file_path,
        'attendance'       => $p?->attendance_certificate_path,
        'certificate_pdf'  => $p?->certificate_pdf_path,
        'certificate_card' => $p?->certificate_card_path,
    ];

    $out = [];
    foreach ($map as $key => $path) {
        $exists = ($path && $disk->exists($path));
        $out[$key] = [
            'path'   => $path,
            'exists' => $exists,
            'url'    => $exists ? $disk->url($path) : null,
        ];
    }

    return $out;
}

/**
 * ✅ خرائط التعريب (DB إنجليزي -> عرض عربي)
 */
private function studentArabicLabels(): array
{
    return [
        'mode' => [
            'onsite' => 'حضوري',
            'online' => 'أونلاين',
        ],

        'registration_status' => [
            'confirmed' => 'مثبت',
            'pending'   => 'بانتظار التأكيد',
            'canceled'  => 'ملغي',
        ],

        'student_status' => [
            'active'                => 'نشط',
            'waiting'               => 'بانتظار التأكيد',
            'paid'                  => 'مدفوع',
            'withdrawn'             => 'منسحب',
            'failed'                => 'راسب',
            'absent_exam'           => 'متغيب عن الامتحان',
            'certificate_delivered' => 'تم تسليم الشهادة',
            'certificate_waiting'   => 'بانتظار الشهادة',
            'registration_ended'    => 'انتهى التسجيل',
            'dismissed'             => 'مفصول',
            'frozen'                => 'مجمّد',
        ],

        'crm_source' => [
            'ad'       => 'إعلان مدفوع',
            'referral' => 'إحالة / توصية',
            'social'   => 'وسائل التواصل الاجتماعي',
            'website'  => 'الموقع الإلكتروني',
            'expo'     => 'معرض / فعالية',
            'other'    => 'أخرى',
        ],

        'crm_stage' => [
            'new'        => 'جديد',
            'follow_up'  => 'متابعة',
            'interested' => 'مهتم',
            'registered' => 'مسجل',
            'rejected'   => 'مرفوض',
            'postponed'  => 'مؤجل',
        ],
    ];
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

 
      

// ========== ✅ حفظ بيانات كل دبلومة (ملفات + حقول نصية) ==========
foreach ($request->input('diplomas', []) as $did => $data) {

    // 1) ملفات الدبلومة
    if ($request->hasFile("diplomas.$did.attendance_certificate")) {
        $path = $request->file("diplomas.$did.attendance_certificate")
            ->store("students/diplomas/{$student->id}", 'public');

        $student->diplomas()->updateExistingPivot($did, [
            'attendance_certificate_path' => $path,
            'has_attendance_certificate' => true,
        ]);
    }

    if ($request->hasFile("diplomas.$did.certificate_pdf")) {
        $path = $request->file("diplomas.$did.certificate_pdf")
            ->store("students/diplomas/{$student->id}", 'public');

        $student->diplomas()->updateExistingPivot($did, [
            'certificate_pdf_path' => $path,
        ]);
    }

    if ($request->hasFile("diplomas.$did.certificate_card")) {
        $path = $request->file("diplomas.$did.certificate_card")
            ->store("students/diplomas/{$student->id}", 'public');

        $student->diplomas()->updateExistingPivot($did, [
            'certificate_card_path' => $path,
        ]);
    }

    // 2) حفظ الحقول النصية + التاريخ + التقييم + التسليم
    $student->diplomas()->updateExistingPivot($did, [
        'status' => $data['status'] ?? 'active',
        'rating' => $data['rating'] ?? null,
        'notes'  => $data['notes'] ?? null,
        'ended_at' => $data['ended_at'] ?? null,
        'certificate_delivered' => isset($data['certificate_delivered']),
    ]);
}








$this->saveProfileWithUploads($student, $request);

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
        
        $student->diplomas()->syncWithoutDetaching($sync);

        //   $student->diplomas()->sync($sync);
      }
    });

/*

    foreach ($request->input('diplomas', []) as $did => $data) {
   $student->diplomas()->updateExistingPivot($did, [
      'status' => $data['status'] ?? 'active',
   ]);
}

*/



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



  
private function saveProfileWithUploads(Student $student, \Illuminate\Http\Request $request): void
{
    $profileData = $request->input('profile', []);

    // الاسم بالعربي تلقائي إذا فارغ
    if (empty($profileData['arabic_full_name'] ?? null)) {
        $profileData['arabic_full_name'] = $student->full_name;
    }

    $uploadsMap = [
        'photo' => ['col'=>'photo_path', 'dir'=>'students/photos'],
        'info_file' => ['col'=>'info_file_path', 'dir'=>'students/info_files'],
        'identity_file' => ['col'=>'identity_file_path', 'dir'=>'students/identity_files'],
        'attendance_certificate' => ['col'=>'attendance_certificate_path', 'dir'=>'students/attendance_certificates'],
        'certificate_pdf' => ['col'=>'certificate_pdf_path', 'dir'=>'students/certificates/pdf'],
        'certificate_card' => ['col'=>'certificate_card_path', 'dir'=>'students/certificates/card'],
    ];

    $profile = $student->profile()->firstOrCreate(['student_id' => $student->id], []);

    foreach ($uploadsMap as $key => $cfg) {
        if ($request->hasFile("profile.$key")) {

            // حذف القديم عند الاستبدال
            $old = $profile->{$cfg['col']} ?? null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            $profileData[$cfg['col']] = $request->file("profile.$key")->store($cfg['dir'], 'public');
        }
    }

    // لا تعمل update إذا كله فاضي
    if (!empty(array_filter($profileData, fn($v) => $v !== null && $v !== ''))) {
        $profile->update($profileData);
    }
}




private function buildDiplomaFiles($student)
{
    $disk = Storage::disk('public');

    $out = [];

    foreach ($student->diplomas as $d) {

        $path = $d->pivot->attendance_certificate_path;

        $exists = ($path && $disk->exists($path));

        $out[$d->id] = [
            'exists' => $exists,
            'url' => $exists ? $disk->url($path) : null,
            'status' => $d->pivot->status,
            'has_attendance' => $d->pivot->has_attendance_certificate,
        ];
    }

    return $out;
}





}
