@extends('layouts.app')
@php($isDashboard = true)

@section('title', 'لوحة التحكم')

@section('dashboard')



  {{-- Header / Intro --}}
  <div class="namaa-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <h1>لوحة التحكم — نظام نماء أكاديمي</h1>
        <p>

        </p>
      </div>



      {{-- الجزء الأيسر: ترحيب ذكي --}}
      <div>


        <p class="mt-2 mb-1">
          اليوم: {{ now()->locale('ar')->translatedFormat('l d F Y') }}
        </p>

        <p class="section-note">
          {{ auth()->user()->hasRole('super_admin')
    ? 'أنت تعمل بصلاحيات الإدارة العليا.'
    : 'أنت تعمل ضمن نطاق صلاحياتك المصرّح بها.' }}
        </p>
      </div>


      <div class="d-flex gap-2 flex-wrap">
        <span class="chip"><i class="bi bi-shield-lock"></i> نظام صلاحيات</span>
        <span class="chip"><i class="bi bi-graph-up-arrow"></i> تقارير</span>
        <span class="chip"><i class="bi bi-building"></i> فروع متعددة</span>
      </div>
    </div>
  </div>


  @if(auth()->user()?->hasPermission('view_dashboard'))

    <div class="row g-3 mb-4">

      {{-- تنبيهات اليوم --}}
      <div class="col-12 col-md-6 col-xl-3">
        <div class="module-card p-3">

          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-bell text-warning"></i>
            <p class="module-title mb-0">تنبيهات اليوم</p>
          </div>

          <p class="section-note">
            {{ $highlights['alerts']['pending_leaves'] }} طلب إجازة
            •
            {{ $highlights['alerts']['today_tasks'] }} مهام اليوم
          </p>

        </div>
      </div>


      {{-- المالية اليوم --}}
      <div class="col-12 col-md-6 col-xl-3">
        <div class="module-card p-3">

          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-cash-coin text-success"></i>
            <p class="module-title mb-0">المالية اليوم</p>
          </div>

          <p class="section-note">

            {{ $todayStats['financial_transactions'] }}
            حركة

            •

            {{ number_format($todayStats['financial_amount'], 2) }}

          </p>

        </div>
      </div>


      {{-- الطلاب --}}
      <div class="col-12 col-md-6 col-xl-3">
        <div class="module-card p-3">

          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-mortarboard text-primary"></i>
            <p class="module-title mb-0">نشاط الطلاب</p>
          </div>

          <p class="section-note">

            {{ $todayStats['new_students'] }}
            جدد

            •

            {{ $todayStats['confirmed_students'] }}
            تثبيت

          </p>

        </div>
      </div>


      {{-- نشاط النظام --}}
      <div class="col-12 col-md-6 col-xl-3">
        <div class="module-card p-3">

          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-activity text-info"></i>
            <p class="module-title mb-0">نشاط النظام</p>
          </div>

          <p class="section-note">

            {{ $highlights['activity']['count'] }}
            تعديل

            •

            {{ $highlights['activity']['last']
        ? \Carbon\Carbon::parse($highlights['activity']['last'])->diffForHumans()
        : '—'
                                            }}

          </p>

        </div>
      </div>

    </div>

  @endif


  {{-- Modules Grid --}}
  <div class="row g-3 g-lg-4 mb-4">


    @if(auth()->user()?->hasPermission('view_dashboard'))


      {{-- Dashboard / Reports --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-primary">
              <i class="bi bi-speedometer2 fs-3"></i>
            </div>
            <div>
              <p class="module-title">اللوحة الرئيسية والتقارير</p>
              <p class="module-sub">ملخص شامل — مؤشرات الأداء — تقارير مالية </p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              عرض إحصائيات سريعة، تقارير Excel/PDF، وتصفية متقدمة حسب الفرع والفترة.
            </p>
          </div>
          <div class="module-actions">

            @if(auth()->user()?->hasPermission('view_dashboard'))
              <a href="{{ route('reports.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح التقارير</a>
            @endif
            @if(auth()->user()?->hasPermission('view_executive_dashboard'))
              <a href="{{ route('reports.executive') }}" class="btn btn-namaa w-100 w-sm-auto">
                لوحة القيادة التنفيذية
              </a>
            @endif
          </div>
        </div>
      </div>
    @endif

{{-- ============================================================ --}}
    {{-- كارد CRM / العملاء المحتملين                                  --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_leads'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-red">
              <i class="bi bi-headset fs-3"></i>
            </div>
            <div>
              <p class="module-title">قسم الاستشارات والمبيعات (CRM)</p>
              <p class="module-sub">Leads — متابعة — تحويل العميل إلى طالب</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              إدارة العملاء المحتملين، مراحل المتابعة، مصادر العملاء، وتقارير التحويل والإيرادات المتوقعة.
            </p>
          </div>

          {{-- إحصائيات CRM --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-danger fs-5">{{ $leadStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-people"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ $leadStats['new'] }}</span><br>
              <span class="text-muted"><i class="bi bi-star"></i> جديد</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $leadStats['followup'] }}</span><br>
              <span class="text-muted"><i class="bi bi-arrow-repeat"></i> متابعة</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $leadStats['converted'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check2-circle"></i> تم التحويل</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('leads.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح CRM</a>
            @if(auth()->user()?->hasPermission('view_reports') || auth()->user()?->hasPermission('view_crm_reports'))
              <a href="{{ route('crm.reports.index') }}" class="btn btn-soft w-100 w-sm-auto">تقارير المبيعات</a>
            @endif
          </div>
        </div>
      </div>
    @endif


    {{-- ============================================================ --}}
    {{-- كارد الطلاب                                                   --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_students'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-blue">
              <i class="bi bi-people-fill fs-3"></i>
            </div>
            <div>
              <p class="module-title">الطلاب</p>
              <p class="module-sub">إدارة ملفات الطلاب — حالات التسجيل — الأقساط</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              إضافة/تعديل بيانات الطلاب، متابعة الحالة والدفعات، وبحث سريع حسب الاسم أو الرقم الجامعي.
            </p>
          </div>

          {{-- إحصائيات الطلاب --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $studentStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-mortarboard"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $studentStats['confirmed'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle"></i> مثبّت</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ $studentStats['pending'] }}</span><br>
              <span class="text-muted"><i class="bi bi-hourglass-split"></i> معلّق</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $studentStats['today_new'] }}</span><br>
              <span class="text-muted"><i class="bi bi-plus-circle"></i> جدد اليوم</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('students.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الطلاب</a>
            <a href="{{ route('students.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة طالب</a>
          </div>
        </div>
      </div>
    @endif


    {{-- ============================================================ --}}
    {{-- كارد الامتحانات                                               --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_exams'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-fuchsia">
              <i class="bi bi-journal-check fs-3"></i>
            </div>
            <div>
              <p class="module-title">قسم الامتحانات</p>
              <p class="module-sub">إدارة الامتحانات — تسجيل العلامات — نتائج</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              امتحانات حضورية/أونلاين، ربط الطلاب بالامتحان، احتساب النتيجة النهائية وإصدار التقارير.
            </p>
          </div>

          {{-- إحصائيات الامتحانات --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $examStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-journal"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ $examStats['upcoming'] }}</span><br>
              <span class="text-muted"><i class="bi bi-calendar-event"></i> قادم</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $examStats['done'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check2-all"></i> منتهي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $examStats['this_month'] }}</span><br>
              <span class="text-muted"><i class="bi bi-calendar-month"></i> هذا الشهر</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('exams.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الامتحانات</a>
            <a href="{{ route('exams.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة امتحان</a>
          </div>
        </div>
      </div>
    @endif


    {{-- ============================================================ --}}
    {{-- كارد الصناديق والحسابات المالية                                --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_cashboxes'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-teal">
              <i class="bi bi-cash-coin fs-3"></i>
            </div>
            <div>
              <p class="module-title">الصناديق والحسابات المالية</p>
              <p class="module-sub">مقبوض/مدفوع — عملات — أرصدة وتقارير</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              إدارة صناديق الفروع، تسجيل الحركات، رفع مرفقات، وسجل تدقيق للحركات المالية.
            </p>
          </div>

          {{-- إحصائيات الصناديق --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $cashboxStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-safe"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $cashboxStats['active'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle"></i> نشط</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $cashboxStats['today_trx'] }}</span><br>
              <span class="text-muted"><i class="bi bi-arrow-left-right"></i> حركات اليوم</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ number_format($cashboxStats['today_amount'], 0) }}</span><br>
              <span class="text-muted"><i class="bi bi-currency-dollar"></i> مبلغ اليوم</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('cashboxes.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح النظام المالي</a>
            <a href="{{ route('cashboxes.index', ['status' => 'active']) }}" class="btn btn-soft w-100 w-sm-auto">الصناديق النشطة</a>
          </div>
        </div>
      </div>
    @endif

    @if(auth()->user()?->hasPermission('view_attendance'))

      {{-- Attendance / Leaves --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-rose">
              <i class="bi bi-calendar2-week fs-3"></i>
            </div>
            <div>
              <p class="module-title">الدوام والإجازات</p>
              <p class="module-sub">حضور/انصراف — طلبات إجازة — تقارير</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              تقويم شهري، سجلات حضور يومية، تقارير ساعات/تأخير/غياب مع تصدير Excel/PDF.
            </p>
          </div>
          <div class="module-actions grid-2">
            <a href="{{ route('attendance.calendar') }}" class="btn btn-namaa w-100 w-sm-auto">التقويم</a>

            <a href="{{ route('attendance.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الدوام</a>

            <a href="{{ route('attendance.reports') }}" class="btn btn-soft w-100 w-sm-auto">تقارير الدوام</a>

            @if(auth()->user()?->hasPermission('view_leaves'))
              <a href="{{ route('leaves.index') }}" class="btn btn-soft w-100 w-sm-auto">طلبات الإجازات</a>
            @endif
          </div>

        </div>
      </div>


    @endif




    @if(auth()->user()?->hasPermission('view_tasks'))
      {{-- Tasks --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-slate">
              <i class="bi bi-check2-square fs-3"></i>
            </div>
            <div>
              <p class="module-title">مهام اليوم</p>
              <p class="module-sub">مهام يومية — مسؤوليات — أرشفة</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              إنشاء مهام حسب الفرع، متابعة حالة التنفيذ، وتقارير يومية وأرشفة تلقائية.
            </p>
          </div>
          <div class="module-actions grid-2">
            <a href="{{ route('tasks.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح المهام</a>

            @if(auth()->user()?->hasPermission('create_tasks'))
              <a href="{{ route('tasks.create') }}" class="btn btn-namaa w-100 w-sm-auto">إضافة مهمة</a>
            @endif

          </div>
          <div class="module-actions grid-2">

            <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="btn btn-soft w-100 w-sm-auto">مهام اليوم </a>

            <a href="{{ route('reports.task.index') }}" class="btn btn-soft w-100 w-sm-auto">

              <i class="bi bi-file-earmark-text"></i>
              تقارير المهام

            </a>

          </div>
        </div>
      </div>
    @endif
@if(auth()->user()?->hasPermission('view_employees'))
      {{-- HR / Trainers --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-violet">
              <i class="bi bi-person-badge-fill fs-3"></i>
            </div>
            <div>
              <p class="module-title">المدربين والموظفين</p>
              <p class="module-sub">ملفات — عقود — مستحقات — ارتباط بالدبلومات</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              ملف كامل للمدرب/الموظف، جدولة دفعات المدربين وربطها بالصناديق، وتقارير مالية متخصصة.
            </p>
          </div>

          {{-- إحصائيات الموارد البشرية مدمجة داخل الكارد --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $hrStats['trainers'] }}</span><br>
              <span class="text-muted"><i class="bi bi-mortarboard"></i> مدرب</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-secondary fs-5">{{ $hrStats['employees'] }}</span><br>
              <span class="text-muted"><i class="bi bi-person-badge"></i> موظف</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $hrStats['active_trainers'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle text-success"></i> مدرب نشط</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $hrStats['active_employees'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle text-success"></i> موظف نشط</span>
            </div>
          </div>

          <div class="module-actions grid-2">
            @if(auth()->user()?->hasPermission('manage_trainer'))
              <a href="{{ route('employees.index', ['type' => 'trainer']) }}" class="btn btn-namaa w-100 w-sm-auto">
                إدارة المدربين
              </a>
            @endif
            @if(auth()->user()?->hasPermission('manage_employees'))
              <a href="{{ route('employees.index', ['type' => 'employee']) }}" class="btn btn-namaa w-100 w-sm-auto">
                إدارة الموظفين
              </a>
            @endif
          </div>
          <div class="module-actions">
            <a href="{{ route('employees.index') }}" class="btn btn-soft w-100 w-sm-auto">فتح الموارد البشرية</a>
          </div>
        </div>
      </div>
    @endif













    {{-- ← إحصائيات المستخدمين --}}






    {{-- الأمان والمستخدمون والحوكمة --}}
    @if(auth()->user()?->hasPermission('manage_roles'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-blue">
              <i class="bi bi-shield-lock fs-3"></i>
            </div>
            <div>
              <p class="module-title">الأمان والمستخدمون والحوكمة</p>
              <p class="module-sub">حسابات الدخول — الأدوار — الصلاحيات</p>
            </div>
          </div>

          <div class="module-body">
            <p class="section-note">
              إدارة مستخدمي النظام، التحكم الكامل بالصلاحيات، ومراقبة كل التغييرات عبر سجل التدقيق.
            </p>
          </div>

          {{-- إحصائيات المستخدمين مدمجة داخل الكارد --}}
          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $onlineUsers }}</span><br>
              <span class="text-muted"><i class="bi bi-circle-fill text-success" style="font-size:6px"></i> متصلون</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $totalUsers }}</span><br>
              <span class="text-muted"><i class="bi bi-people-fill"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $todayLogins }}</span><br>
              <span class="text-muted"><i class="bi bi-box-arrow-in-right"></i> دخول اليوم</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-secondary fs-5">{{ $totalUsers - $onlineUsers }}</span><br>
              <span class="text-muted"><i class="bi bi-circle"></i> غير متصلين</span>
            </div>
          </div>

          <div class="module-actions grid-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-namaa w-100 w-sm-auto">
              إدارة المستخدمين
            </a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-namaa w-100 w-sm-auto">
              الأدوار والصلاحيات
            </a>
          </div>
          <div class="module-actions">
            <a href="{{ route('admin.audit.index') }}" class="btn btn-soft w-100 w-sm-auto">
              مركز التدقيق
            </a>
          </div>
        </div>
      </div>
    @endif







{{-- ============================================================ --}}
    {{-- كارد الأصول واللوجستيات                                       --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_assets'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-yellow">
              <i class="bi bi-box-seam fs-3"></i>
            </div>
            <div>
              <p class="module-title">اللوجستيات وإدارة الأصول</p>
              <p class="module-sub">أصول — مخزون — تصنيف حسب الفرع والحالة</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              تسجيل الأجهزة والمعدات، حالة الأصل (جيد/صيانة/خارج الخدمة)، وإدارة المخزون.
            </p>
          </div>

          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $assetStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-box-seam"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $assetStats['good'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle"></i> جيد</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ $assetStats['maintenance'] }}</span><br>
              <span class="text-muted"><i class="bi bi-wrench"></i> صيانة</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-danger fs-5">{{ $assetStats['retired'] }}</span><br>
              <span class="text-muted"><i class="bi bi-x-circle"></i> خارج الخدمة</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('assets.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الأصول</a>
            <a href="{{ route('asset-categories.index') }}" class="btn btn-soft w-100 w-sm-auto">تصنيفات الأصول</a>
          </div>
        </div>
      </div>
    @endif


    {{-- ============================================================ --}}
    {{-- كارد الفروع                                                   --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_branches'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-red">
              <i class="bi bi-building fs-3"></i>
            </div>
            <div>
              <p class="module-title">الفروع</p>
              <p class="module-sub">إعدادات الفروع — توزيع العمليات حسب الفرع</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              إدارة فروع نماء أكاديمي (ألمانيا، اسطنبول، مرسين...) وربط الطلاب والموظفين والأصول حسب الفرع.
            </p>
          </div>

          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-danger fs-5">{{ $branchStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-building"></i> فرع</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $branchStats['students'] }}</span><br>
              <span class="text-muted"><i class="bi bi-mortarboard"></i> طالب</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-secondary fs-5">{{ $branchStats['employees'] }}</span><br>
              <span class="text-muted"><i class="bi bi-person-badge"></i> موظف</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-warning fs-5">{{ $branchStats['assets'] }}</span><br>
              <span class="text-muted"><i class="bi bi-box-seam"></i> أصل</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('branches.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الفروع</a>
            @if(auth()->user()?->hasPermission('create_branches'))
              <a href="{{ route('branches.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة فرع</a>
            @endif
          </div>
        </div>
      </div>
    @endif


    {{-- ============================================================ --}}
    {{-- كارد الدبلومات                                                --}}
    {{-- ============================================================ --}}
    @if(auth()->user()?->hasPermission('view_diplomas'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-lime">
              <i class="bi bi-mortarboard-fill fs-3"></i>
            </div>
            <div>
              <p class="module-title">الدبلومات</p>
              <p class="module-sub">إعدادات الدبلومات — الربط بالطلاب والمدربين</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              تعريف الدبلومات داخل النظام، الأكواد الخاصة بها، وربطها بالطلاب والمدربين ضمن الموارد البشرية.
            </p>
          </div>

          <div class="row text-center small fw-bold mt-2 mb-2">
            <div class="col-6 col-md-3 mb-2">
              <span class="text-primary fs-5">{{ $diplomaStats['total'] }}</span><br>
              <span class="text-muted"><i class="bi bi-mortarboard"></i> إجمالي</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-success fs-5">{{ $diplomaStats['active'] }}</span><br>
              <span class="text-muted"><i class="bi bi-check-circle"></i> نشط</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-info fs-5">{{ $diplomaStats['online'] }}</span><br>
              <span class="text-muted"><i class="bi bi-wifi"></i> أونلاين</span>
            </div>
            <div class="col-6 col-md-3 mb-2">
              <span class="text-secondary fs-5">{{ $diplomaStats['onsite'] }}</span><br>
              <span class="text-muted"><i class="bi bi-geo-alt"></i> حضوري</span>
            </div>
          </div>

          <div class="module-actions">
            <a href="{{ route('diplomas.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الدبلومات</a>
            @if(auth()->user()?->hasPermission('create_diplomas'))
              <a href="{{ route('diplomas.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة دبلومة</a>
            @endif
          </div>
        </div>
      </div>
    @endif


    @if(auth()->user()?->hasPermission('view_program_management'))

      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-indigo">
              <i class="bi bi-diagram-3 fs-3"></i>
            </div>
            <div>
              <p class="module-title">إدارة البرامج</p>
              <p class="module-sub">متابعة شاملة لجميع أقسام البرنامج</p>
            </div>
          </div>

          <div class="module-body">
            <p class="section-note">
              متابعة قسم البرامج، الميديا، التسويق، الامتحانات وشؤون الطلاب لكل دبلومة.
            </p>
          </div>

          <div class="module-actions">
            <a href={{ route('programs.management.index') }} class="btn btn-namaa w-100">
              إدارة البرامج
            </a>
          </div>
        </div>
      </div>

    @endif




    @if(auth()->user()?->hasPermission('view_program_management'))

      {{-- Online Programs --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-dark">
              <i class="bi bi-diagram-3 fs-3"></i>
            </div>
            <div>
              <p class="module-title">إدارة البرامج الأونلاين</p>
              <p class="module-sub">متابعة الدبلومات التعليمية عن بُعد</p>
            </div>
          </div>

          <div class="module-body">
            <p class="section-note">
              إدارة البرامج الأونلاين، الامتحانات الرقمية، المحتوى الإلكتروني، وتتبع الأداء.
            </p>
          </div>

          <div class="module-actions">
            <a href="{{ route('programs.management.index', ['type' => 'online']) }}" class="btn btn-namaa w-100">
              فتح برامج الأونلاين
            </a>
          </div>
        </div>
      </div>



      {{-- Onsite Programs --}}
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-dark">
              <i class="bi bi-building fs-3"></i>
            </div>
            <div>
              <p class="module-title">إدارة البرامج الحضورية</p>
              <p class="module-sub">متابعة الدبلومات داخل الفروع</p>
            </div>
          </div>

          <div class="module-body">
            <p class="section-note">
              إدارة البرامج داخل الفروع، الجداول الصفية، الحضور، والامتحانات الحضورية.
            </p>
          </div>

          <div class="module-actions">
            <a href="{{ route('programs.management.index', ['type' => 'onsite']) }}" class="btn btn-namaa w-100">
              فتح البرامج الحضورية
            </a>
          </div>
        </div>
      </div>

    @endif






    @if(auth()->user()?->hasPermission('view_media_requests'))

      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-purple">
              <i class="bi bi-megaphone fs-3"></i>
            </div>
            <div>
              <p class="module-title">طلبات الميديا</p>
              <p class="module-sub">إدارة الطلبات وجدولة النشر</p>
            </div>
          </div>



          <div class="module-body">
            <p class="section-note">

            </p>
          </div>



          <div class="module-actions">
            <a href="{{ route('media.index') }}" class="btn btn-namaa w-100">
              فتح قسم الميديا
            </a>
          </div>
        </div>
      </div>

    @endif











    @if(auth()->user()?->hasRole('super_admin'))

      <div class="col-12 col-md-6 col-xl-4" hidden>

        <div class="module-card">

          <div class="module-head">

            <div class="module-icon grad-slate">
              <i class="bi bi-gear-fill fs-3"></i>
            </div>

            <div>
              <p class="module-title">إعدادات النظام</p>
              <p class="module-sub">
                النسخ الاحتياطية — حالة النظام — صيانة النظام
              </p>
            </div>

          </div>

          <div class="module-body">

            <p class="section-note">

              إدارة النسخ الاحتياطية للنظام ومراقبة حالة السيرفر وقاعدة البيانات.

            </p>

          </div>

          <div class="module-actions grid-2">

            <a href="{{ route('system.backup.index') }}" class="btn btn-namaa w-100">

              <i class="bi bi-database"></i>
              النسخ الاحتياطية

            </a>


            <a href="{{ route('system.health') }}" class="btn btn-soft w-100">

              <i class="bi bi-activity"></i>
              حالة النظام

            </a>

          </div>

        </div>

      </div>

    @endif



  </div>

@endsection