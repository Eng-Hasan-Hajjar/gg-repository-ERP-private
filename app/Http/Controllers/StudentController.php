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
use App\Models\ExamResult;
class StudentController extends Controller
{


    public function index(Request $request)
    {
        $q = Student::query()->with(['branch', 'diplomas', 'profile', 'crmInfo']);

        $user = auth()->user();

        // 🔒 تحديد الفروع المسموحة
        if (!$user->hasRole('super_admin')) {

            $employee = $user->employee;

            $branchIds = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id
            ])->filter()->unique()->values()->all();

            if (!empty($branchIds)) {
                $q->whereIn('branch_id', $branchIds);
            }
        }

        // 🔎 فلاتر
        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }

        if ($request->filled('diploma_id')) {
            $did = $request->diploma_id;
            $q->whereHas('diplomas', fn($x) => $x->where('diplomas.id', $did));
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('registration_status')) {
            $q->where('registration_status', $request->registration_status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function ($x) use ($s) {
                $x->where('full_name', 'like', "%$s%")
                    ->orWhere('university_id', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        $students = $q->latest()->paginate(15)->withQueryString();

        // 🧠 تعريب
        $labels = $this->studentArabicLabels();

        $students->getCollection()->transform(function ($s) use ($labels) {
            $s->status_ar = $labels['student_status'][$s->status] ?? '-';
            $s->registration_ar = $labels['registration_status'][$s->registration_status] ?? '-';
            $s->mode_ar = $labels['mode'][$s->mode] ?? '-';
            return $s;
        });

        return view('students.index', [
            'students' => $students,
            'branches' => Branch::orderBy('name')->get(),
            'diplomas' => Diploma::orderBy('name')->get(),

            'labels' => $labels,
            'statusOptions' => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions' => $labels['mode'],
        ]);
    }
    /*
        public function index(Request $request)
        {
            $q = Student::query()->with(['branch', 'diplomas', 'profile', 'crmInfo']);

            $user = auth()->user();

            if (!$user->hasRole('super_admin')) {

                $branchId = $user->employee?->branch_id;



                $employee = $user->employee;

                $branchIds = collect([
                    $employee?->branch_id,
                    $employee?->secondary_branch_id
                ])->filter()->unique()->all();

                if (!$user->hasRole('super_admin') && !empty($branchIds)) {
                    $q->whereIn('branch_id', $branchIds);
                }

            }
            if ($request->filled('branch_id')) {
                $q->where('branch_id', $request->branch_id);
            }

            if ($request->filled('diploma_id')) {
                $did = $request->diploma_id;
                $q->whereHas('diplomas', fn($x) => $x->where('diplomas.id', $did));
            }

            if ($request->filled('status')) {
                $q->where('status', $request->status);
            }

            if ($request->filled('registration_status')) {
                $q->where('registration_status', $request->registration_status);
            }

            if ($request->filled('search')) {
                $s = trim($request->search);
                $q->where(
                    fn($x) => $x
                        ->where('full_name', 'like', "%$s%")
                        ->orWhere('university_id', 'like', "%$s%")
                        ->orWhere('phone', 'like', "%$s%")
                );
            }

            // ✅ فقط المثبتين
            //  $q->where('is_confirmed', true);

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

    */
    public function create()
    {
        $labels = $this->studentArabicLabels();
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {

            $branches = Branch::orderBy('name')->get();

        } else {

            $employee = $user->employee;

            $branchIds = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id
            ])->filter()->unique();

            $branches = Branch::whereIn('id', $branchIds)
                ->orderBy('name')
                ->get();
        }
        return view('students.create', [
            'student' => new Student(),   // ✅ مهم جدًا
            'branches' => $branches,
            'diplomas' => Diploma::orderBy('name')->get(),

            'statusOptions' => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions' => $labels['mode'],
        ]);
    }


    public function store(StudentStoreRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {

            $employee = $user->employee;

            $allowedBranches = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id
            ])->filter()->unique()->all();

            if (!in_array($data['branch_id'], $allowedBranches)) {
                abort(403);
            }
        }
        // شؤون الطلاب: مثبت افتراضياً
        $data['registration_status'] = 'confirmed';
        $data['is_confirmed'] = true;
        $data['confirmed_at'] = now();
        $data['university_id'] = $this->generateUniversityId();



          // ✅ تأكد من وجود قيمة للحقل certificate_agreement
    $data['certificate_agreement'] = $request->has('certificate_agreement') && $request->certificate_agreement == 1;



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
                'photo' => ['col' => 'photo_path', 'dir' => 'students/photos'],
                'info_file' => ['col' => 'info_file_path', 'dir' => 'students/info_files'],
                'identity_file' => ['col' => 'identity_file_path', 'dir' => 'students/identity_files'],
                'attendance_certificate' => ['col' => 'attendance_certificate_path', 'dir' => 'students/attendance_certificates'],
                'certificate_pdf' => ['col' => 'certificate_pdf_path', 'dir' => 'students/certificates/pdf'],
                'certificate_card' => ['col' => 'certificate_card_path', 'dir' => 'students/certificates/card'],
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
            /*
            $crmData = $request->input('crm', []);
            if (!empty(array_filter($crmData))) {
                $student->crmInfo()->updateOrCreate(
                    ['student_id' => $student->id],
                    $crmData + ['converted_at' => now()]
                );
            }
*/

            $crmData = $request->input('crm', []);
            if (!empty(array_filter($crmData))) {
                // قيم افتراضية للحقول الإجبارية في DB
                $crmData['source'] = $crmData['source'] ?? 'other';
                $crmData['stage'] = $crmData['stage'] ?? 'registered';

                $student->crmInfo()->updateOrCreate(
                    ['student_id' => $student->id],
                    $crmData + ['converted_at' => now()]
                );
            }



            // diplomas multi
            $diplomaIds = $request->input('diploma_ids', []);
            if (!empty($diplomaIds)) {
                $sync = [];
                foreach ($diplomaIds as $i => $id) {
                    $sync[$id] = [
                        'is_primary' => $i === 0,
                        'enrolled_at' => now()->toDateString(),
                        'status' => 'active',
                    ];
                }
                $student->diplomas()->sync($sync);
            }

            return $student;
        });

        return redirect()->route('students.show', $student)->with('success', 'تم إنشاء الطالب مع التفاصيل بنجاح.');
    }



    
public function show(Student $student)
{
    $student->load(['branch', 'diplomas', 'profile', 'crmInfo']);
 
    $results = \App\Models\ExamResult::with(['exam.diploma'])
        ->where('student_id', $student->id)
        ->get();
 
    $p       = $student->profile;
    $waDigits = $student->whatsapp ? preg_replace('/\D+/', '', $student->whatsapp) : null;
    $waLink  = $waDigits ? "https://wa.me/{$waDigits}" : null;
    $files   = $this->buildProfileFiles($p);
    $diplomaFiles = $this->buildDiplomaFiles($student);
    $labels  = $this->studentArabicLabels();
 
    $status_ar       = $labels['student_status'][$student->status]             ?? ($student->status ?? '-');
    $registration_ar = $labels['registration_status'][$student->registration_status] ?? '-';
    $mode_ar         = $labels['mode'][$student->mode]                         ?? '-';
 
    $crm_source_ar = $student->crmInfo
        ? ($labels['crm_source'][$student->crmInfo->source] ?? '-')
        : '-';
 
    $crm_stage_ar = $student->crmInfo
        ? ($labels['crm_stage'][$student->crmInfo->stage] ?? '-')
        : '-';
 
    // تعريب حالة الدبلومة
    $diplomaStatusMap = [
        'active'  => 'مستمر',
        'waiting' => 'قيد الانتظار',
        'finished'=> 'منتهي',
    ];
    $student->diplomas->transform(function ($d) use ($diplomaStatusMap) {
        $d->pivot->status_ar = $diplomaStatusMap[$d->pivot->status] ?? $d->pivot->status;
        return $d;
    });
 
    $financial = $student->financialAccount?->load('transactions.cashbox');
 
    // الرصيد حسب العملة
    $balancesByCurrency = [];
    if ($financial) {
        $transactions = $financial->transactions()
            ->with('cashbox')
            ->where('status', 'posted')
            ->get();
 
        $grouped = $transactions->groupBy(fn($trx) => $trx->cashbox->currency);
        foreach ($grouped as $currency => $items) {
            $in  = $items->where('type', 'in')->sum('amount');
            $out = $items->where('type', 'out')->sum('amount');
            $balancesByCurrency[$currency] = $in - $out;
        }
    }
 
    // ══════════════════════════════════════════════════════════════
    // خطط الدفع:
    // الأولوية: الخطة المنقولة من Lead (lead_id مرتبط بهذا الطالب)
    // ثم الخطط المرتبطة مباشرة بالطالب
    // ══════════════════════════════════════════════════════════════
    $paymentPlans = \App\Models\PaymentPlan::where(function ($q) use ($student) {
            $q->where('student_id', $student->id)
              ->orWhere(function ($q2) use ($student) {
                  // الخطط المرتبطة بـ Lead تحوّل إلى هذا الطالب
                  $q2->whereHas('lead', fn($lq) =>
                      $lq->where('student_id', $student->id)
                  );
              });
        })
        ->with(['installments', 'diploma'])
        ->get();
 
    // حساب المدفوع والمتبقي لكل خطة
    foreach ($paymentPlans as $plan) {
        $paid = $financial
            ? $financial->transactions()
                ->where('diploma_id', $plan->diploma_id)
                ->where('type', 'in')
                ->whereHas('cashbox', function ($q) use ($plan) {
                    $q->where('currency', $plan->currency);
                })
                ->sum('amount')
            : 0;
 
        $plan->paid      = $paid;
        $plan->remaining = $plan->total_amount - $paid;
    }
 
    $plansByDiploma = $paymentPlans->keyBy('diploma_id');
 
    // هل الخطة قادمة من Lead (قراءة فقط في بعض الحالات)؟
    $planFromLead = $paymentPlans->filter(fn($p) => !is_null($p->lead_id))->keyBy('diploma_id');
 
    return view('students.show', compact(
        'student',
        'p',
        'waLink',
        'files',
        'diplomaFiles',
        'results',
        'status_ar',
        'registration_ar',
        'mode_ar',
        'crm_source_ar',
        'crm_stage_ar',
        'financial',
        'balancesByCurrency',
        'paymentPlans',
        'plansByDiploma',
        'planFromLead'    // ← جديد: الخطط المنقولة من Lead
    ));
}
 


/*
    public function show(Student $student)
    {
        $student->load(['branch', 'diplomas', 'profile', 'crmInfo']);




        $results = ExamResult::with(['exam.diploma'])
            ->where('student_id', $student->id)
            ->get();



        $p = $student->profile;

        $waDigits = $student->whatsapp ? preg_replace('/\D+/', '', $student->whatsapp) : null;
        $waLink = $waDigits ? "https://wa.me/{$waDigits}" : null;

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
            'active' => 'مستمر',
            'waiting' => 'قيد الانتظار',
            'finished' => 'منتهي',
        ];

        $student->diplomas->transform(function ($d) use ($diplomaStatusMap) {
            $d->pivot->status_ar =
                $diplomaStatusMap[$d->pivot->status] ?? $d->pivot->status;

            return $d;
        });





        $financial = $student->financialAccount?->load('transactions.cashbox');





        // ===== تجميع الرصيد حسب العملة =====

        $balancesByCurrency = [];

        if ($financial) {

            $transactions = $financial->transactions()
                ->with('cashbox')
                ->where('status', 'posted')
                ->get();

            $grouped = $transactions->groupBy(fn($trx) => $trx->cashbox->currency);

            foreach ($grouped as $currency => $items) {

                $in = $items->where('type', 'in')->sum('amount');
                $out = $items->where('type', 'out')->sum('amount');

                $balancesByCurrency[$currency] = $in - $out;
            }
        }





        $paymentPlans = $student->paymentPlans()
            ->with(['installments', 'diploma'])
            ->get();


        $plansByDiploma = $paymentPlans->keyBy('diploma_id');


        $paidAmounts = [];

        foreach ($paymentPlans as $plan) {

            $paid = $student->financialAccount
                ? $student->financialAccount
                    ->transactions()
                    ->where('diploma_id', $plan->diploma_id)
                    ->where('type', 'in')
                    ->whereHas('cashbox', function ($q) use ($plan) {
                        $q->where('currency', $plan->currency);
                    })
                    ->sum('amount')
                : 0;

            $plan->paid = $paid;

            $remaining = max($plan->total_amount - $paid, 0);
            $plan->remaining = $plan->total_amount - $paid;
        }



        return view('students.show', compact(
            'student',
            'p',
            'waLink',
            'files',
            'diplomaFiles',
            'results',
            'status_ar',
            'registration_ar',
            'mode_ar',
            'crm_source_ar',
            'crm_stage_ar',
            'financial',
            'balancesByCurrency',
            'paymentPlans',

            'plansByDiploma'
        ));
    }
*/
    /**
     * ✅ تجهيز روابط الملفات + exists بشكل آمن حتى لو ما في بروفايل
     */
    private function buildProfileFiles($p): array
    {
        $disk = Storage::disk('public');

        $map = [
            'photo' => $p?->photo_path,
            'info' => $p?->info_file_path,
            'identity' => $p?->identity_file_path,
            'attendance' => $p?->attendance_certificate_path,
            'certificate_pdf' => $p?->certificate_pdf_path,
            'certificate_card' => $p?->certificate_card_path,
        ];

        $out = [];
        foreach ($map as $key => $path) {
            $exists = ($path && $disk->exists($path));
            $out[$key] = [
                'path' => $path,
                'exists' => $exists,
                'url' => $exists ? $disk->url($path) : null,
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
                'confirmed' => 'مُثبت',
                'pending' => 'قيد الانتظار',
                'canceled' => 'مُلغي',
            ],

            'student_status' => [
                'active' => 'مستمر في الدراسة',
                'waiting' => 'قيد الانتظار',
                'paid' => 'مدفوع',
                'withdrawn' => 'منسحب',
                'failed' => 'راسب',
                'absent_exam' => 'لم يتقدّم للامتحان',
                'certificate_delivered' => 'جرى تسليم الشهادة',
                'certificate_waiting' => 'الشهادة قيد الإصدار',
                'registration_ended' => 'انتهى التسجيل',
                'dismissed' => 'فُصل الطالب',
                'frozen' => 'تم تجميد القيد الدراسي',
            ],

            'crm_source' => [
                'ad' => 'إعلان مدفوع',
                'referral' => 'إحالة / توصية',
                'social' => 'وسائل التواصل الاجتماعي',
                'website' => 'الموقع الإلكتروني',
                'expo' => 'معرض / فعالية',
                'other' => 'أخرى',
            ],

            'crm_stage' => [
                'new' => 'جديد',
                'follow_up' => 'متابعة',
                'interested' => 'مهتم',
                'registered' => 'مسجل',
                'rejected' => 'مرفوض',
                'postponed' => 'مؤجل',
            ],
        ];
    }
    public function edit(Student $student)
    {
        $student->load(['diplomas', 'profile', 'crmInfo']);
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {

            $branches = Branch::orderBy('name')->get();

        } else {

            $employee = $user->employee;

            $branchIds = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id
            ])->filter()->unique();

            $branches = Branch::whereIn('id', $branchIds)
                ->orderBy('name')
                ->get();
        }
        // === مهم جدًا === جلب خرائط التعريب
        $labels = $this->studentArabicLabels();

        return view('students.edit', [
            'student' => $student,
            'branches' => $branches,
            'diplomas' => Diploma::orderBy('name')->get(),

            // 🔹 هذه هي التي كانت مفقودة لديك:
            'statusOptions' => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions' => $labels['mode'],
        ]);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        $data = $request->validated();



            // ✅ تأكد من وجود قيمة للحقل certificate_agreement
    $data['certificate_agreement'] = $request->has('certificate_agreement') && $request->certificate_agreement == 1;

    


        $user = auth()->user();

        if (!$user->hasRole('super_admin')) {

            $employee = $user->employee;

            $allowedBranches = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id
            ])->filter()->unique()->all();

            if (!in_array($data['branch_id'], $allowedBranches)) {
                abort(403);
            }
        }



        unset($data['registration_status']);

        DB::transaction(function () use ($request, $student, $data) {

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
                    'notes' => $data['notes'] ?? null,
                    'ended_at' => $data['ended_at'] ?? null,
                    'certificate_delivered' => isset($data['certificate_delivered']),
                ]);
            }








            $this->saveProfileWithUploads($student, $request);

            $profileData = $request->input('profile', []);
            if (!empty($profileData)) {
                $student->profile()->updateOrCreate(['student_id' => $student->id], $profileData);
            }

            $crmData = $request->input('crm', []);
            if (!empty($crmData)) {

                // لا تمسح تاريخ أول تواصل إذا لم يتم إرساله
                if (!isset($crmData['first_contact_date'])) {
                    $crmData['first_contact_date'] = optional($student->crmInfo)->first_contact_date;
                }



                $student->crmInfo()->updateOrCreate(['student_id' => $student->id], $crmData);
            }

            $diplomaIds = $request->input('diploma_ids', null);
            if (is_array($diplomaIds)) {
                $sync = [];
                foreach ($diplomaIds as $i => $id) {
                    $sync[$id] = [
                        'is_primary' => $i === 0,
                        'enrolled_at' => now()->toDateString(),
                        'status' => 'active',
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



        return redirect()->route('students.show', $student)->with('success', 'تم تحديث الطالب بنجاح.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب.');
    }



    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return back()->with('error', 'لم يتم تحديد أي طالب');
        }

        Student::whereIn('id', $ids)->delete();

        return back()->with('success', 'تم حذف الطلاب المحددين');
    }




    private function generateUniversityId(): string
    {
        do {
            $id = 'NMA-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (Student::where('university_id', $id)->exists());

        return $id;
    }




    private function saveProfileWithUploads(Student $student, Request $request): void
    {
        $profileData = $request->input('profile', []);

        // الاسم بالعربي تلقائي إذا فارغ
        if (empty($profileData['arabic_full_name'] ?? null)) {
            $profileData['arabic_full_name'] = $student->full_name;
        }

        $uploadsMap = [
            'photo' => ['col' => 'photo_path', 'dir' => 'students/photos'],
            'info_file' => ['col' => 'info_file_path', 'dir' => 'students/info_files'],
            'identity_file' => ['col' => 'identity_file_path', 'dir' => 'students/identity_files'],
            'attendance_certificate' => ['col' => 'attendance_certificate_path', 'dir' => 'students/attendance_certificates'],
            'certificate_pdf' => ['col' => 'certificate_pdf_path', 'dir' => 'students/certificates/pdf'],
            'certificate_card' => ['col' => 'certificate_card_path', 'dir' => 'students/certificates/card'],
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
