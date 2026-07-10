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

        // ✅ فلتر "طلابي فقط" — يُضاف فوق الـ GlobalScope
        if ($request->boolean('my_only')) {
            $q->where('created_by', $user->id);
        }

        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }

        if ($request->filled('diploma_id')) {
            $q->whereHas('diplomas', fn($x) => $x->where('diplomas.id', $request->diploma_id));
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('registration_status')) {
            $q->where('registration_status', $request->registration_status);
        }

        // ✅ فلتر نوع الطالب (حضوري/أونلاين)
        if ($request->filled('mode')) {
            $q->where('mode', $request->mode);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(
                fn($x) => $x
                    ->where('full_name', 'like', "%$s%")
                    ->orWhere('university_id', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%")
                    ->orWhere('whatsapp', 'like', "%$s%")
            );
        }

        if ($request->filled('has_message')) {
            $q->whereHas(
                'profile',
                fn($p) =>
                $p->whereNotNull('message_to_send')->where('message_to_send', '!=', '')
            );
        }

        if ($request->filled('needs_update')) {
            $q->where('updated_at', '<=', now()->subDays(7))->where('status', 'active');
        }

        if ($request->boolean('needs_verification')) {
            $q->whereHas('profile', function ($p) {
                $p->where(function ($inner) {
                    $inner->whereNull('arabic_full_name')
                        ->orWhere('arabic_full_name', '')
                        ->orWhereNull('birth_date')
                        ->orWhereNull('national_id')
                        ->orWhere('national_id', '');
                });
            });
        }

        // ✅ فلتر الجنسية
        if ($request->filled('nationality')) {
            $q->whereHas('profile', fn($p) => $p->where('nationality', $request->nationality));
        }

        // ✅ فلتر مصدر CRM
        if ($request->filled('crm_source')) {
            $q->whereHas('crmInfo', fn($c) => $c->where('source', $request->crm_source));
        }

        // ✅ فلتر مرحلة CRM
        if ($request->filled('crm_stage')) {
            $q->whereHas('crmInfo', fn($c) => $c->where('stage', $request->crm_stage));
        }

        // ✅ فلتر مستوى اللغة (عبر pivot الدبلومات)
        if ($request->filled('language_level')) {
            $q->whereHas('diplomas', fn($d) => $d->wherePivot('language_level', $request->language_level));
        }

        // ✅ فلتر اتفاق الشهادة (عبر pivot الدبلومات)
        if ($request->filled('certificate_agreement')) {
            $q->whereHas('diplomas', fn($d) => $d->wherePivot('certificate_agreement', $request->certificate_agreement));
        }

        // ✅ فلتر نطاق تاريخ الإضافة
        if ($request->filled('date_from')) {
            $q->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->whereDate('created_at', '<=', $request->date_to);
        }

        // ✅ فلتر نطاق العلامة الامتحانية
        if ($request->filled('exam_score_min')) {
            $q->whereHas('profile', fn($p) => $p->where('exam_score', '>=', $request->exam_score_min));
        }
        if ($request->filled('exam_score_max')) {
            $q->whereHas('profile', fn($p) => $p->where('exam_score', '<=', $request->exam_score_max));
        }

        // ✅ الترتيب
        $sortBy  = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['created_at', 'full_name', 'university_id', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        // ✅ إحصائيات سريعة — نفس الـ query بدون pagination
        $statsQuery = clone $q;
        $totalCount     = (clone $statsQuery)->count();
        $confirmedCount = (clone $statsQuery)->where('registration_status', 'confirmed')->count();
        $pendingCount   = (clone $statsQuery)->where('registration_status', 'pending')->count();
        $myCount        = (clone $statsQuery)->where('created_by', $user->id)->count();

        $students = $q->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        $labels = $this->studentArabicLabels();
        $currentUserId = $user->id;
        $isAdmin = $user->hasRole('super_admin')
            || $user->hasRole('manager_student_affairs')
            || $user->hasPermission('view_all_students');

        $students->getCollection()->transform(function ($s) use ($labels, $currentUserId, $isAdmin) {
            $s->status_ar       = $labels['student_status'][$s->status] ?? '-';
            $s->registration_ar = $labels['registration_status'][$s->registration_status] ?? '-';
            $s->mode_ar         = $labels['mode'][$s->mode] ?? '-';
            $s->is_readonly     = !$isAdmin && ($s->created_by !== $currentUserId);
            return $s;
        });

        $showMyOnly = !$user->hasRole('super_admin')
            && !$user->hasRole('manager_student_affairs')
            && !$user->hasPermission('view_all_students');

        $needsVerificationCount = \App\Models\StudentProfile::query()
            ->where(function ($q) {
                $q->whereNull('arabic_full_name')
                    ->orWhere('arabic_full_name', '')
                    ->orWhereNull('birth_date')
                    ->orWhereNull('national_id')
                    ->orWhere('national_id', '');
            })
            ->whereHas(
                'student',
                fn($sq) =>
                $sq->where('registration_status', 'confirmed')
            )
            ->count();

        // ✅ قائمة الجنسيات المستخدمة فعلياً (لتفادي قائمة ضخمة غير مستخدمة)
        $usedNationalities = \App\Models\StudentProfile::query()
            ->whereNotNull('nationality')
            ->where('nationality', '!=', '')
            ->distinct()
            ->orderBy('nationality')
            ->pluck('nationality');




            $activeFilters = $this->buildActiveFilters(
    $request,
    \App\Models\Diploma::with('branch')->orderBy('name')->get(),
    \App\Models\Branch::orderBy('name')->get(),
    $labels['student_status'],
    $labels['registration_status'],
    $labels['mode'],
    $labels['crm_source'],
    $labels['crm_stage']
);


        return view('students.index', [
            'students'            => $students,
            'branches'            => \App\Models\Branch::orderBy('name')->get(),
            'diplomas'            => \App\Models\Diploma::with('branch')->orderBy('name')->get(),
            'labels'              => $labels,
            'statusOptions'       => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions'         => $labels['mode'],
            'crmSourceOptions'    => $labels['crm_source'],
            'crmStageOptions'     => $labels['crm_stage'],
            'nationalities'       => $usedNationalities,
            // ✅ إحصائيات
            'totalCount'              => $totalCount,
            'confirmedCount'          => $confirmedCount,
            'pendingCount'            => $pendingCount,
            'myCount'                 => $myCount,
            'showMyOnly'              => $showMyOnly,
            'needsVerificationCount'  => $needsVerificationCount,
            'sortBy'                  => $sortBy,
            'sortDir'                 => $sortDir,
            'activeFilters' => $activeFilters,
        ]);
    }

    /**
     * بناء قائمة الفلاتر النشطة المعروضة كـ chips فوق الجدول.
     * تُحسب بالكامل في الكونترولر لتفادي أي مشاكل ترتيب في Blade.
     */
    private function buildActiveFilters(Request $request, $diplomas, $branches, array $statusOptions, array $registrationOptions, array $modeOptions, array $crmSourceOptions, array $crmStageOptions): array
    {
        $activeFilters = [];

        if ($request->filled('search')) {
            $activeFilters[] = ['key' => 'search', 'label' => 'بحث: ' . $request->search];
        }

        if ($request->filled('diploma_id')) {
            $selDip = $diplomas->firstWhere('id', $request->diploma_id);
            if ($selDip) {
                $activeFilters[] = ['key' => 'diploma_id', 'label' => 'دبلومة: ' . $selDip->name . ' (' . $selDip->code . ')'];
            }
        }

        if ($request->filled('branch_id')) {
            $selBranch = $branches->firstWhere('id', $request->branch_id);
            if ($selBranch) {
                $activeFilters[] = ['key' => 'branch_id', 'label' => 'فرع: ' . $selBranch->name];
            }
        }

        if ($request->filled('status')) {
            $activeFilters[] = ['key' => 'status', 'label' => 'حالة: ' . ($statusOptions[$request->status] ?? $request->status)];
        }

        if ($request->filled('registration_status')) {
            $activeFilters[] = ['key' => 'registration_status', 'label' => 'تسجيل: ' . ($registrationOptions[$request->registration_status] ?? $request->registration_status)];
        }

        if ($request->filled('mode')) {
            $activeFilters[] = ['key' => 'mode', 'label' => 'نوع: ' . ($modeOptions[$request->mode] ?? $request->mode)];
        }

        if ($request->filled('nationality')) {
            $activeFilters[] = ['key' => 'nationality', 'label' => 'جنسية: ' . $request->nationality];
        }

        if ($request->filled('crm_source')) {
            $activeFilters[] = ['key' => 'crm_source', 'label' => 'مصدر: ' . ($crmSourceOptions[$request->crm_source] ?? $request->crm_source)];
        }

        if ($request->filled('crm_stage')) {
            $activeFilters[] = ['key' => 'crm_stage', 'label' => 'مرحلة: ' . ($crmStageOptions[$request->crm_stage] ?? $request->crm_stage)];
        }

        if ($request->filled('language_level')) {
            $activeFilters[] = ['key' => 'language_level', 'label' => 'لغة: ' . $request->language_level];
        }

        if ($request->filled('certificate_agreement')) {
            $activeFilters[] = ['key' => 'certificate_agreement', 'label' => 'اتفاق شهادة: ' . $request->certificate_agreement];
        }

        if ($request->filled('date_from')) {
            $activeFilters[] = ['key' => 'date_from', 'label' => 'من: ' . $request->date_from];
        }

        if ($request->filled('date_to')) {
            $activeFilters[] = ['key' => 'date_to', 'label' => 'إلى: ' . $request->date_to];
        }

        if ($request->filled('exam_score_min')) {
            $activeFilters[] = ['key' => 'exam_score_min', 'label' => 'علامة من: ' . $request->exam_score_min];
        }

        if ($request->filled('exam_score_max')) {
            $activeFilters[] = ['key' => 'exam_score_max', 'label' => 'علامة إلى: ' . $request->exam_score_max];
        }

        if ($request->boolean('has_message')) {
            $activeFilters[] = ['key' => 'has_message', 'label' => 'لديه رسالة'];
        }

        if ($request->boolean('needs_update')) {
            $activeFilters[] = ['key' => 'needs_update', 'label' => 'يحتاج تحديث'];
        }

        if ($request->boolean('needs_verification')) {
            $activeFilters[] = ['key' => 'needs_verification', 'label' => 'يحتاج مراجعة'];
        }

        return $activeFilters;
    }

  public function create()  
    {
        $labels = $this->studentArabicLabels();
        $user = auth()->user();

        if ($user->hasRole('super_admin') || $user->hasRole('manager_student_affairs') ) {

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
            'student' => new Student(),
            'branches' => $branches,
            'diplomas' => Diploma::with('branch')->orderBy('name')->get(),
            'studentDiplomas' => collect(),
            'studentDiplomasJson' => '[]',
            'crm' => [],        // ✅ أضف هذا
            'profile' => [],        // ✅ أضف هذا
            'statusOptions' => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions' => $labels['mode'],
        ]);
        /*
                  return view('students.create', [
                      'student' => new Student(),   // ✅ مهم جدًا
                      'branches' => $branches,
                      'diplomas' => Diploma::orderBy('name')->get(),

                      'statusOptions' => $labels['student_status'],
                      'registrationOptions' => $labels['registration_status'],
                      'modeOptions' => $labels['mode'],
                  ]);*/
    }


    public function store(StudentStoreRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $data['created_by'] = auth()->id();

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
        $data['certificate_agreement'] = $request->certificate_agreement ?? null;



        
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




            $crmData = $request->input('crm', []);
            $crmData = $request->input('crm', []);

            // احذف القيم الفارغة
            $crmData = array_filter($crmData, fn($v) => $v !== null && $v !== '');

            if (!empty($crmData)) {

                // احفظ تاريخ أول تواصل القديم إذا لم يُرسل
                if (!isset($crmData['first_contact_date'])) {
                    $existing = optional($student->crmInfo)->first_contact_date;
                    if ($existing) {
                        $crmData['first_contact_date'] = $existing;
                    }
                }

                // ✅ دائماً أضف قيم افتراضية للـ enum — سواء insert أو update
                $crmData['source'] = $crmData['source'] ?? $student->crmInfo?->source ?? 'other';
                $crmData['stage'] = $crmData['stage'] ?? $student->crmInfo?->stage ?? 'registered';

                $student->crmInfo()->updateOrCreate(
                    ['student_id' => $student->id],
                    $crmData
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
        //$student->load(['branch', 'diplomas.branch', 'profile', 'crmInfo.creator', 'diplomaCertificates']);
        $results = \App\Models\ExamResult::with(['exam.diploma'])
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

        // تعريب حالة الدبلومة
        $diplomaStatusMap = [
            'active' => 'مستمر',
            'waiting' => 'قيد الانتظار',
            'finished' => 'منتهي',
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
                $in = $items->where('type', 'in')->sum('amount');
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
                    $q2->whereHas(
                        'lead',
                        fn($lq) =>
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

            $plan->paid = $paid;
            $plan->remaining = $plan->total_amount - $paid;
        }

        $plansByDiploma = $paymentPlans->keyBy('diploma_id');

        // هل الخطة قادمة من Lead (قراءة فقط في بعض الحالات)؟
        $planFromLead = $paymentPlans->filter(fn($p) => !is_null($p->lead_id))->keyBy('diploma_id');

        // ══ متغيرات محسوبة للـ View (بدون @php في Blade) ══
        $activeModule = 'students';

        $diplomaStatusLabels = [
            'active'                 => 'مستمر في الدراسة',
            'waiting'                => 'قيد الانتظار',
            'withdrawn'              => 'منسحب',
            'failed'                 => 'راسب',
            'absent_exam'            => 'لم يتقدّم للامتحان',
            'certificate_delivered'  => 'تم تسليم الشهادة',
            'certificate_waiting'    => 'بانتظار الشهادة',
            'registration_ended'     => 'انتهى التسجيل',
            'dismissed'              => 'فُصل الطالب',
            'frozen'                 => 'تم تجميد القيد الدراسي',
        ];

        $diplomaFilesMap = [];
        foreach ($student->diplomas as $d) {
            $diplomaFilesMap[$d->id] = [
                ['path' => $d->pivot->attendance_certificate_path, 'label' => 'شهادة الحضور', 'icon' => 'bi-file-earmark-check'],
                ['path' => $d->pivot->certificate_pdf_path,        'label' => 'الشهادة PDF',   'icon' => 'bi-file-earmark-pdf'],
                ['path' => $d->pivot->certificate_card_path,       'label' => 'كرت الشهادة',   'icon' => 'bi-file-earmark-image'],
            ];
        }

        $profileDocuments = [
            ['key' => 'info', 'label' => 'ملف المعلومات', 'btnClass' => 'btn-outline-primary', 'icon' => 'bi-file-earmark-text'],
        ];

        $activeCashboxes = \App\Models\Cashbox::where('status', 'active')
            ->where('branch_id', $student->branch_id)
            ->get();

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
            'planFromLead',
            'activeModule',
            'diplomaStatusLabels',
            'diplomaFilesMap',
            'profileDocuments',
            'activeCashboxes'
        ));
    }


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
                // 'paid' => 'مدفوع',
                'withdrawn' => 'منسحب',
                'failed' => 'راسب',
                'absent_exam' => 'لم يتقدّم للامتحان',
                'certificate_delivered' => 'تم تسليم الشهادة',
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




        if ($user->hasRole('super_admin') || $user->hasRole('manager_student_affairs') ) {

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

        $student->load(['diplomas.branch', 'profile', 'crmInfo']);

        $studentDiplomasJson = $student->diplomas->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'code' => $d->code,
            'branch_id' => $d->branch_id,
        ])->toJson();




        return view('students.edit', [
            'student' => $student,
            'branches' => $branches,
            'diplomas' => Diploma::with('branch')->orderBy('name')->get(),
            'studentDiplomas' => $student->diplomas,
            'studentDiplomasJson' => $studentDiplomasJson,
            'crm' => old('crm', $student->crmInfo?->toArray() ?? []),    // ✅
            'profile' => old('profile', $student->profile?->toArray() ?? []), // ✅
            'statusOptions' => $labels['student_status'],
            'registrationOptions' => $labels['registration_status'],
            'modeOptions' => $labels['mode'],
        ]);


    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        $data = $request->validated();



        // ✅ تأكد من وجود قيمة للحقل certificate_agreement
        $data['certificate_agreement'] = $request->certificate_agreement ?? null;



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
                    'certificate_agreement' => $data['certificate_agreement'] ?? null,
                    'language_level' => $data['language_level'] ?? null,
                    // ✅ هل تم إعطاء المنحة؟ (يحددها قسم شؤون الطلاب)
                    'grant_given' => isset($data['grant_given']),
                ]);
            }








            $this->saveProfileWithUploads($student, $request);

            $profileData = $request->input('profile', []);
            if (!empty($profileData)) {
                $student->profile()->updateOrCreate(['student_id' => $student->id], $profileData);
            }

            $crmData = $request->input('crm', []);

            // ✅ احذف القيم الفارغة
            $crmData = array_filter($crmData, fn($v) => $v !== null && $v !== '');

            if (!empty($crmData)) {

                // ✅ احفظ تاريخ أول تواصل القديم
                if (!isset($crmData['first_contact_date'])) {
                    $existing = optional($student->crmInfo)->first_contact_date;
                    if ($existing)
                        $crmData['first_contact_date'] = $existing;
                }

                // ✅ قيم افتراضية للـ enum دائماً
                $crmData['source'] = $crmData['source'] ?? $student->crmInfo?->source ?? 'other';
                $crmData['stage'] = $crmData['stage'] ?? $student->crmInfo?->stage ?? 'registered';

                $student->crmInfo()->updateOrCreate(
                    ['student_id' => $student->id],
                    $crmData
                );
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






    public function modalFinancial(Student $student)
    {
        $student->load(['diplomas']);

        $financial = $student->financialAccount?->load('transactions.cashbox');

        // ✅ نفس الاستعلام الموجود في show()
        $paymentPlans = \App\Models\PaymentPlan::where(function ($q) use ($student) {
            $q->where('student_id', $student->id)
                ->orWhere(function ($q2) use ($student) {
                    $q2->whereHas(
                        'lead',
                        fn($lq) =>
                        $lq->where('student_id', $student->id)
                    );
                });
        })
            ->with(['installments', 'diploma'])
            ->get();

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
            $plan->paid = $paid;
            $plan->remaining = $plan->total_amount - $paid;
        }

        return view('students.modals.financial', compact('student', 'paymentPlans', 'financial'));
    }

    public function modalExams(Student $student)
    {
        $results = \App\Models\ExamResult::with(['exam.diploma'])
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('students.modals.exams', compact('student', 'results'));
    }


}