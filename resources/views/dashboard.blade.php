@extends('layouts.app')
@php($isDashboard = true)

@section('title', 'لوحة التحكم')

@section('dashboard')

<style>
  /* ───── Hero Section ───── */
  .dash-hero {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    border-radius: 18px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .dash-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(99,102,241,.15) 0%, transparent 70%);
    border-radius: 50%;
  }
  .dash-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    right: -5%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(16,185,129,.1) 0%, transparent 70%);
    border-radius: 50%;
  }
  .dash-hero h1 {
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 4px;
    position: relative;
  }
  .dash-hero .sub {
    font-size: 14px;
    opacity: .7;
    position: relative;
  }
  .dash-hero .chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    position: relative;
  }
  .dash-hero .chip-item {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    color: rgba(255,255,255,.8);
    backdrop-filter: blur(4px);
  }
  .dash-date {
    font-size: 13px;
    opacity: .6;
    position: relative;
  }
  .dash-role {
    font-size: 12px;
    background: rgba(99,102,241,.3);
    padding: 3px 12px;
    border-radius: 12px;
    display: inline-block;
    margin-top: 6px;
    position: relative;
  }

  /* ───── Quick Stats Row ───── */
  .quick-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }
  @media (max-width: 991px) {
    .quick-stats { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 575px) {
    .quick-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
  }
  .qs-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 1px 8px rgba(0,0,0,.04);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: transform .15s, box-shadow .15s;
    border-right: 4px solid transparent;
  }
  .qs-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
  }
  .qs-card.qs-warn   { border-right-color: #f59e0b; }
  .qs-card.qs-green  { border-right-color: #10b981; }
  .qs-card.qs-blue   { border-right-color: #3b82f6; }
  .qs-card.qs-purple { border-right-color: #8b5cf6; }
  .qs-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .qs-icon.warn   { background: #fef3c7; color: #d97706; }
  .qs-icon.green  { background: #d1fae5; color: #059669; }
  .qs-icon.blue   { background: #dbeafe; color: #2563eb; }
  .qs-icon.purple { background: #ede9fe; color: #7c3aed; }
  .qs-text .qs-val {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.2;
  }
  .qs-text .qs-label {
    font-size: 12px;
    color: #94a3b8;
  }

  /* ───── Module Cards Enhancement ───── */
  .module-card {
    border-radius: 16px !important;
    transition: transform .2s, box-shadow .2s;
  }
  .module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
  }

  /* ───── Stats Mini Grid inside cards ───── */
  .stats-mini {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 12px;
    margin: 8px 16px 12px;
  }
  .stats-mini .sm-item {
    text-align: center;
  }
  .stats-mini .sm-val {
    font-size: 20px;
    font-weight: 800;
    line-height: 1.2;
  }
  .stats-mini .sm-label {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    margin-top: 2px;
  }

  /* ───── Section Divider ───── */
  .section-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 28px 0 16px;
  }
  .section-divider .sd-line {
    flex: 1;
    height: 1px;
    background: #e2e8f0;
  }
  .section-divider .sd-title {
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
  }
</style>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- HERO                                                    --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="dash-hero">
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
    <div>
      <h1>لوحة التحكم — نظام نماء أكاديمي</h1>
      <div class="dash-date">اليوم: {{ now()->locale('ar')->translatedFormat('l d F Y') }}</div>
      <div class="dash-role">
        <i class="bi bi-shield-check"></i>
        {{ auth()->user()->hasRole('super_admin') ? 'صلاحيات الإدارة العليا' : 'صلاحيات مصرّح بها' }}
      </div>
    </div>
    <div class="chips">
      <span class="chip-item"><i class="bi bi-shield-lock"></i> نظام صلاحيات</span>
      <span class="chip-item"><i class="bi bi-graph-up-arrow"></i> تقارير</span>
      <span class="chip-item"><i class="bi bi-building"></i> فروع متعددة</span>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- QUICK STATS                                             --}}
{{-- ═══════════════════════════════════════════════════════ --}}
@if(auth()->user()?->hasPermission('view_dashboard'))
<div class="quick-stats">
  <div class="qs-card qs-warn">
    <div class="qs-icon warn"><i class="bi bi-bell"></i></div>
    <div class="qs-text">
      <div class="qs-val">{{ $highlights['alerts']['pending_leaves'] }} إجازة • {{ $highlights['alerts']['today_tasks'] }} مهمة</div>
      <div class="qs-label">تنبيهات اليوم</div>
    </div>
  </div>
  <div class="qs-card qs-green">
    <div class="qs-icon green"><i class="bi bi-cash-coin"></i></div>
    <div class="qs-text">
      <div class="qs-val">{{ $todayStats['financial_transactions'] }} حركة • {{ number_format($todayStats['financial_amount'], 0) }}</div>
      <div class="qs-label">المالية اليوم</div>
    </div>
  </div>
  <div class="qs-card qs-blue">
    <div class="qs-icon blue"><i class="bi bi-mortarboard"></i></div>
    <div class="qs-text">
      <div class="qs-val">{{ $todayStats['new_students'] }} جدد • {{ $todayStats['confirmed_students'] }} تثبيت</div>
      <div class="qs-label">نشاط الطلاب</div>
    </div>
  </div>
  <div class="qs-card qs-purple">
    <div class="qs-icon purple"><i class="bi bi-activity"></i></div>
    <div class="qs-text">
      <div class="qs-val">{{ $highlights['activity']['count'] }} تعديل • {{ $highlights['activity']['last'] ? \Carbon\Carbon::parse($highlights['activity']['last'])->diffForHumans() : '—' }}</div>
      <div class="qs-label">نشاط النظام</div>
    </div>
  </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- MAIN MODULES                                            --}}
{{-- ═══════════════════════════════════════════════════════ --}}

{{-- ── القسم الأول: العمليات الأساسية ── --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-grid-3x3-gap me-1"></i> العمليات الأساسية</span>
  <div class="sd-line"></div>
</div>

<div class="row g-3 g-lg-4 mb-2">

  {{-- التقارير --}}
  @if(auth()->user()?->hasPermission('view_dashboard'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-primary"><i class="bi bi-speedometer2 fs-3"></i></div>
        <div>
          <p class="module-title">اللوحة الرئيسية والتقارير</p>
          <p class="module-sub">ملخص شامل — مؤشرات الأداء — تقارير مالية</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">عرض إحصائيات سريعة، تقارير Excel/PDF، وتصفية متقدمة حسب الفرع والفترة.</p>
      </div>
      <div class="module-actions">
        <a href="{{ route('reports.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح التقارير</a>
        @if(auth()->user()?->hasPermission('view_executive_dashboard'))
          <a href="{{ route('reports.executive') }}" class="btn btn-soft w-100 w-sm-auto">لوحة القيادة التنفيذية</a>
        @endif
      </div>
    </div>
  </div>
  @endif

  {{-- CRM --}}
  @if(auth()->user()?->hasPermission('view_leads'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-red"><i class="bi bi-headset fs-3"></i></div>
        <div>
          <p class="module-title">قسم الاستشارات والمبيعات (CRM)</p>
          <p class="module-sub">Leads — متابعة — تحويل العميل إلى طالب</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة العملاء المحتملين، مراحل المتابعة، مصادر العملاء، وتقارير.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-danger">{{ $leadStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-people"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ $leadStats['new'] }}</div>
          <div class="sm-label"><i class="bi bi-star"></i> جديد</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $leadStats['followup'] }}</div>
          <div class="sm-label"><i class="bi bi-arrow-repeat"></i> متابعة</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $leadStats['converted'] }}</div>
          <div class="sm-label"><i class="bi bi-check2-circle"></i> تحويل</div>
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

  {{-- الطلاب --}}
  @if(auth()->user()?->hasPermission('view_students'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-blue"><i class="bi bi-people-fill fs-3"></i></div>
        <div>
          <p class="module-title">الطلاب</p>
          <p class="module-sub">إدارة ملفات الطلاب — حالات التسجيل — الأقساط</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إضافة/تعديل بيانات الطلاب، متابعة الحالة والدفعات، وبحث سريع.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $studentStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-mortarboard"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $studentStats['confirmed'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> مثبّت</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ $studentStats['pending'] }}</div>
          <div class="sm-label"><i class="bi bi-hourglass-split"></i> معلّق</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $studentStats['today_new'] }}</div>
          <div class="sm-label"><i class="bi bi-plus-circle"></i> جدد اليوم</div>
        </div>
      </div>
      <div class="module-actions">
        <a href="{{ route('students.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الطلاب</a>
        <a href="{{ route('students.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة طالب</a>
      </div>
    </div>
  </div>
  @endif

  {{-- الامتحانات --}}
  @if(auth()->user()?->hasPermission('view_exams'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-fuchsia"><i class="bi bi-journal-check fs-3"></i></div>
        <div>
          <p class="module-title">قسم الامتحانات</p>
          <p class="module-sub">إدارة الامتحانات — تسجيل العلامات — نتائج</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">امتحانات حضورية/أونلاين، ربط الطلاب بالامتحان، احتساب النتيجة.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $examStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-journal"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ $examStats['upcoming'] }}</div>
          <div class="sm-label"><i class="bi bi-calendar-event"></i> قادم</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $examStats['done'] }}</div>
          <div class="sm-label"><i class="bi bi-check2-all"></i> منتهي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $examStats['this_month'] }}</div>
          <div class="sm-label"><i class="bi bi-calendar-month"></i> هذا الشهر</div>
        </div>
      </div>
      <div class="module-actions">
        <a href="{{ route('exams.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الامتحانات</a>
        <a href="{{ route('exams.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة امتحان</a>
      </div>
    </div>
  </div>
  @endif

  {{-- الصناديق المالية --}}
  @if(auth()->user()?->hasPermission('view_cashboxes'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-teal"><i class="bi bi-cash-coin fs-3"></i></div>
        <div>
          <p class="module-title">الصناديق والحسابات المالية</p>
          <p class="module-sub">مقبوض/مدفوع — عملات — أرصدة وتقارير</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة صناديق الفروع، تسجيل الحركات، رفع مرفقات، وسجل تدقيق.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $cashboxStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-safe"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $cashboxStats['active'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> نشط</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $cashboxStats['today_trx'] }}</div>
          <div class="sm-label"><i class="bi bi-arrow-left-right"></i> حركات</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ number_format($cashboxStats['today_amount'], 0) }}</div>
          <div class="sm-label"><i class="bi bi-currency-dollar"></i> مبلغ اليوم</div>
        </div>
      </div>
      <div class="module-actions">
        <a href="{{ route('cashboxes.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح النظام المالي</a>
        <a href="{{ route('cashboxes.index', ['status' => 'active']) }}" class="btn btn-soft w-100 w-sm-auto">الصناديق النشطة</a>
      </div>
    </div>
  </div>
  @endif

  {{-- الدوام والإجازات --}}
  @if(auth()->user()?->hasPermission('view_attendance'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-rose"><i class="bi bi-calendar2-week fs-3"></i></div>
        <div>
          <p class="module-title">الدوام والإجازات</p>
          <p class="module-sub">حضور/انصراف — طلبات إجازة — تقارير</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">تقويم شهري، سجلات حضور يومية، تقارير ساعات/تأخير/غياب.</p>
      </div>
      <div class="module-actions grid-2">
        <a href="{{ route('attendance.calendar') }}" class="btn btn-namaa w-100 w-sm-auto">التقويم</a>
        <a href="{{ route('attendance.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الدوام</a>

        @if(auth()->user()?->hasPermission('export_attendance_reports'))
        <a href="{{ route('attendance.reports') }}" class="btn btn-soft w-100 w-sm-auto">تقارير الدوام</a>
         @endif
        @if(auth()->user()?->hasPermission('view_leaves'))
          <a href="{{ route('leaves.index') }}" class="btn btn-soft w-100 w-sm-auto">طلبات الإجازات</a>
        @endif
      </div>
    </div>
  </div>
  @endif

</div>

{{-- ── القسم الثاني: الموارد البشرية والإدارة ── --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-person-gear me-1"></i> الموارد البشرية والإدارة</span>
  <div class="sd-line"></div>
</div>

<div class="row g-3 g-lg-4 mb-2">

  {{-- المهام --}}
  @if(auth()->user()?->hasPermission('view_tasks'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-slate"><i class="bi bi-check2-square fs-3"></i></div>
        <div>
          <p class="module-title">مهام اليوم</p>
          <p class="module-sub">مهام يومية — مسؤوليات — أرشفة</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إنشاء مهام حسب الفرع، متابعة حالة التنفيذ، وتقارير يومية.</p>
      </div>
      <div class="module-actions grid-2">
        <a href="{{ route('tasks.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح المهام</a>
        @if(auth()->user()?->hasPermission('create_tasks'))
          <a href="{{ route('tasks.create') }}" class="btn btn-namaa w-100 w-sm-auto">إضافة مهمة</a>
        @endif
        <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="btn btn-soft w-100 w-sm-auto">مهام اليوم</a>
        <a href="{{ route('reports.task.index') }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-file-earmark-text"></i> تقارير المهام</a>
      </div>
    </div>
  </div>
  @endif

  {{-- المدربين والموظفين --}}
  @if(auth()->user()?->hasPermission('view_employees'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-violet"><i class="bi bi-person-badge-fill fs-3"></i></div>
        <div>
          <p class="module-title">المدربين والموظفين</p>
          <p class="module-sub">ملفات — عقود — مستحقات — ارتباط بالدبلومات</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">ملف كامل للمدرب/الموظف، جدولة دفعات وربطها بالصناديق.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $hrStats['trainers'] }}</div>
          <div class="sm-label"><i class="bi bi-mortarboard"></i> مدرب</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-secondary">{{ $hrStats['employees'] }}</div>
          <div class="sm-label"><i class="bi bi-person-badge"></i> موظف</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $hrStats['active_trainers'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> مدرب نشط</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $hrStats['active_employees'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> موظف نشط</div>
        </div>
      </div>
      <div class="module-actions grid-2">
        @if(auth()->user()?->hasPermission('manage_trainer'))
          <a href="{{ route('employees.index', ['type' => 'trainer']) }}" class="btn btn-namaa w-100 w-sm-auto">إدارة المدربين</a>
        @endif
        @if(auth()->user()?->hasPermission('manage_employees'))
          <a href="{{ route('employees.index', ['type' => 'employee']) }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الموظفين</a>
        @endif
      </div>
      <div class="module-actions">
        <a href="{{ route('employees.index') }}" class="btn btn-soft w-100 w-sm-auto">فتح الموارد البشرية</a>
      </div>
    </div>
  </div>
  @endif

  {{-- الأمان والمستخدمون --}}
  @if(auth()->user()?->hasPermission('manage_roles'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-blue"><i class="bi bi-shield-lock fs-3"></i></div>
        <div>
          <p class="module-title">الأمان والمستخدمون والحوكمة</p>
          <p class="module-sub">حسابات الدخول — الأدوار — الصلاحيات</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة مستخدمي النظام، التحكم الكامل بالصلاحيات، ومراقبة التغييرات.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-success">{{ $onlineUsers }}</div>
          <div class="sm-label"><i class="bi bi-circle-fill text-success" style="font-size:6px"></i> متصلون</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $totalUsers }}</div>
          <div class="sm-label"><i class="bi bi-people-fill"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $todayLogins }}</div>
          <div class="sm-label"><i class="bi bi-box-arrow-in-right"></i> دخول اليوم</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-secondary">{{ $totalUsers - $onlineUsers }}</div>
          <div class="sm-label"><i class="bi bi-circle"></i> غير متصل</div>
        </div>
      </div>
      <div class="module-actions grid-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة المستخدمين</a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-namaa w-100 w-sm-auto">الأدوار والصلاحيات</a>
      </div>
      <div class="module-actions">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-soft w-100 w-sm-auto">مركز التدقيق</a>
      </div>
    </div>
  </div>
  @endif

</div>

{{-- ── القسم الثالث: البنية التحتية والبرامج ── --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-buildings me-1"></i> البنية التحتية والبرامج</span>
  <div class="sd-line"></div>
</div>

<div class="row g-3 g-lg-4 mb-4">

  {{-- الأصول --}}
  @if(auth()->user()?->hasPermission('view_assets'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-yellow"><i class="bi bi-box-seam fs-3"></i></div>
        <div>
          <p class="module-title">اللوجستيات وإدارة الأصول</p>
          <p class="module-sub">أصول — مخزون — تصنيف حسب الفرع والحالة</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">تسجيل الأجهزة والمعدات، حالة الأصل، وإدارة المخزون.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $assetStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-box-seam"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $assetStats['good'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> جيد</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ $assetStats['maintenance'] }}</div>
          <div class="sm-label"><i class="bi bi-wrench"></i> صيانة</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-danger">{{ $assetStats['retired'] }}</div>
          <div class="sm-label"><i class="bi bi-x-circle"></i> خارج</div>
        </div>
      </div>
      <div class="module-actions">
        <a href="{{ route('assets.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الأصول</a>
        <a href="{{ route('asset-categories.index') }}" class="btn btn-soft w-100 w-sm-auto">تصنيفات الأصول</a>
      </div>
    </div>
  </div>
  @endif

  {{-- الفروع --}}
  @if(auth()->user()?->hasPermission('view_branches'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-red"><i class="bi bi-building fs-3"></i></div>
        <div>
          <p class="module-title">الفروع</p>
          <p class="module-sub">إعدادات الفروع — توزيع العمليات حسب الفرع</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة الفروع وربط الطلاب والموظفين والأصول حسب الفرع.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-danger">{{ $branchStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-building"></i> فرع</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $branchStats['students'] }}</div>
          <div class="sm-label"><i class="bi bi-mortarboard"></i> طالب</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-secondary">{{ $branchStats['employees'] }}</div>
          <div class="sm-label"><i class="bi bi-person-badge"></i> موظف</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-warning">{{ $branchStats['assets'] }}</div>
          <div class="sm-label"><i class="bi bi-box-seam"></i> أصل</div>
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

  {{-- الدبلومات --}}
  @if(auth()->user()?->hasPermission('view_diplomas'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-lime"><i class="bi bi-mortarboard-fill fs-3"></i></div>
        <div>
          <p class="module-title">الدبلومات</p>
          <p class="module-sub">إعدادات الدبلومات — الربط بالطلاب والمدربين</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">تعريف الدبلومات داخل النظام وربطها بالطلاب والمدربين.</p>
      </div>
      <div class="stats-mini">
        <div class="sm-item">
          <div class="sm-val text-primary">{{ $diplomaStats['total'] }}</div>
          <div class="sm-label"><i class="bi bi-mortarboard"></i> إجمالي</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-success">{{ $diplomaStats['active'] }}</div>
          <div class="sm-label"><i class="bi bi-check-circle"></i> نشط</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-info">{{ $diplomaStats['online'] }}</div>
          <div class="sm-label"><i class="bi bi-wifi"></i> أونلاين</div>
        </div>
        <div class="sm-item">
          <div class="sm-val text-secondary">{{ $diplomaStats['onsite'] }}</div>
          <div class="sm-label"><i class="bi bi-geo-alt"></i> حضوري</div>
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

  {{-- إدارة البرامج --}}
  @if(auth()->user()?->hasPermission('view_program_management'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-indigo"><i class="bi bi-diagram-3 fs-3"></i></div>
        <div>
          <p class="module-title">إدارة البرامج</p>
          <p class="module-sub">متابعة شاملة لجميع أقسام البرنامج</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">متابعة قسم البرامج، الميديا، التسويق، الامتحانات وشؤون الطلاب.</p>
      </div>
      <div class="module-actions grid-2">
        <a href="{{ route('programs.management.index') }}" class="btn btn-namaa w-100 w-sm-auto">كل البرامج</a>
        <a href="{{ route('programs.management.index', ['type' => 'online']) }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-wifi"></i> أونلاين</a>
        <a href="{{ route('programs.management.index', ['type' => 'onsite']) }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-building"></i> حضوري</a>
      </div>
    </div>
  </div>
  @endif

  {{-- طلبات الميديا --}}
  @if(auth()->user()?->hasPermission('view_media_requests'))
  <div class="col-12 col-md-6 col-xl-4">
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-purple"><i class="bi bi-megaphone fs-3"></i></div>
        <div>
          <p class="module-title">طلبات الميديا</p>
          <p class="module-sub">إدارة الطلبات وجدولة النشر</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة طلبات التصميم والمحتوى الرقمي وجدولة النشر عبر المنصات.</p>
      </div>
      <div class="module-actions">
        <a href="{{ route('media.index') }}" class="btn btn-namaa w-100">فتح قسم الميديا</a>
      </div>
    </div>
  </div>
  @endif

  {{-- إعدادات النظام --}}
  @if(auth()->user()?->hasRole('super_admin'))
  <div class="col-12 col-md-6 col-xl-4" hidden>
    <div class="module-card">
      <div class="module-head">
        <div class="module-icon grad-slate"><i class="bi bi-gear-fill fs-3"></i></div>
        <div>
          <p class="module-title">إعدادات النظام</p>
          <p class="module-sub">النسخ الاحتياطية — حالة النظام — صيانة</p>
        </div>
      </div>
      <div class="module-body">
        <p class="section-note">إدارة النسخ الاحتياطية ومراقبة حالة السيرفر وقاعدة البيانات.</p>
      </div>
      <div class="module-actions grid-2">
        <a href="{{ route('system.backup.index') }}" class="btn btn-namaa w-100"><i class="bi bi-database"></i> النسخ الاحتياطية</a>
        <a href="{{ route('system.health') }}" class="btn btn-soft w-100"><i class="bi bi-activity"></i> حالة النظام</a>
      </div>
    </div>
  </div>
  @endif

</div>

@endsection