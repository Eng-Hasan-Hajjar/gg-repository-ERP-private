@extends('layouts.app')
@php($isDashboard = true)

@section('title', 'لوحة التحكم')

@section('dashboard')
<style>
  .dash-hero {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    border-radius: 18px; padding: 28px 32px;
    color: #fff; margin-bottom: 20px;
    position: relative; overflow: hidden;
  }
  .dash-hero::before {
    content: ''; position: absolute;
    top: -40%; left: -10%; width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(99,102,241,.15), transparent 70%);
    border-radius: 50%;
  }
  .dash-hero::after {
    content: ''; position: absolute;
    bottom: -30%; right: -5%; width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(16,185,129,.1), transparent 70%);
    border-radius: 50%;
  }
  .dash-hero h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; position: relative; }
  .dash-date { font-size: 13px; opacity: .6; position: relative; }
  .dash-role {
    font-size: 12px; background: rgba(99,102,241,.3);
    padding: 3px 12px; border-radius: 12px;
    display: inline-block; margin-top: 6px; position: relative;
  }
  .dash-hero .chips { display: flex; gap: 8px; flex-wrap: wrap; position: relative; }
  .chip-item {
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    padding: 4px 14px; border-radius: 20px;
    font-size: 12px; color: rgba(255,255,255,.8);
  }
  .live-bar {
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px; padding: 10px 16px;
    display: flex; flex-wrap: wrap; gap: 16px; align-items: center;
    margin-top: 16px; position: relative;
  }
  .live-item {
    display: flex; align-items: center; gap: 7px;
    font-size: 13px; font-weight: 800; color: rgba(255,255,255,.85);
  }
  .live-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,.3); flex-shrink: 0;
  }
  .live-badge {
    background: rgba(255,255,255,.15); border-radius: 8px;
    padding: 2px 10px; font-size: 13px; font-weight: 900; color: #fff;
  }

  /* ── Quick Stats ── */
  .quick-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
  @media(max-width:991px) { .quick-stats { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:767px) {
    .live-bar { gap: 10px; padding: 10px 12px; }
    .live-item { font-size: 11px; }
    .live-badge { font-size: 11px; padding: 2px 7px; }
    .dash-hero { padding: 18px 16px; }
    .dash-hero h1 { font-size: 16px; }
  }
  @media(max-width:400px) {
    .quick-stats { grid-template-columns: 1fr 1fr; gap: 8px; }
    .qs-card { padding: 10px 8px; }
    .qs-val { font-size: 15px; }
  }

  .qs-card {
    background: #fff; border-radius: 14px; padding: 16px 18px;
    box-shadow: 0 1px 8px rgba(0,0,0,.04);
    display: flex; align-items: center; gap: 12px;
    transition: transform .15s, box-shadow .15s;
    border-right: 4px solid transparent;
    text-decoration: none; color: inherit;
  }
  .qs-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.08); color: inherit; }
  .qs-card.qs-warn   { border-right-color: #f59e0b; }
  .qs-card.qs-green  { border-right-color: #10b981; }
  .qs-card.qs-blue   { border-right-color: #3b82f6; }
  .qs-card.qs-purple { border-right-color: #8b5cf6; }
  .qs-card.qs-red    { border-right-color: #ef4444; }
  .qs-card.qs-teal   { border-right-color: #0d9488; }
  .qs-card.qs-amber  { border-right-color: #d97706; }
  .qs-card.qs-sky    { border-right-color: #0284c7; }
  .qs-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
  }
  .qs-icon.warn   { background: #fef3c7; color: #d97706; }
  .qs-icon.green  { background: #d1fae5; color: #059669; }
  .qs-icon.blue   { background: #dbeafe; color: #2563eb; }
  .qs-icon.purple { background: #ede9fe; color: #7c3aed; }
  .qs-icon.red    { background: #fee2e2; color: #dc2626; }
  .qs-icon.teal   { background: #ccfbf1; color: #0f766e; }
  .qs-icon.amber  { background: #fef3c7; color: #b45309; }
  .qs-icon.sky    { background: #e0f2fe; color: #0369a1; }
  .qs-val   { font-size: 20px; font-weight: 900; color: #1e293b; line-height: 1.1; }
  .qs-label { font-size: 11px; color: #94a3b8; margin-top: 2px; }

  /* ── CRM alert ── */
  .crm-alert-card {
    background: linear-gradient(135deg, rgba(239,68,68,.08), rgba(245,158,11,.06));
    border: 1px solid rgba(239,68,68,.2); border-right: 4px solid #ef4444;
    border-radius: 14px; padding: 14px 18px;
    display: flex; align-items: center; gap: 14px;
    text-decoration: none; transition: transform .15s; flex: 1;
  }
  .crm-alert-card:hover { transform: translateY(-1px); }
  .crm-alert-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: rgba(239,68,68,.12); color: #dc2626;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .crm-alert-num { font-size: 24px; font-weight: 900; color: #dc2626; line-height: 1; }
  .crm-alert-lbl { font-size: 13px; font-weight: 800; color: #7f1d1d; }
  .crm-alert-sub { font-size: 12px; color: #94a3b8; margin-top: 2px; }

  /* ── Module cards ── */
  .module-card { border-radius: 16px !important; transition: transform .2s, box-shadow .2s; }
  .module-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }

  /* ── Stats mini ── */
  .stats-mini {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 8px; padding: 12px 16px;
    background: #f8fafc; border-radius: 12px; margin: 8px 16px 12px;
  }
  .sm-item { text-align: center; }
  .sm-val  { font-size: 20px; font-weight: 800; line-height: 1.2; }
  .sm-label {
    font-size: 11px; color: #94a3b8;
    display: flex; align-items: center; justify-content: center;
    gap: 3px; margin-top: 2px;
  }

  /* ── Progress ── */
  .prog-wrap { padding: 0 16px 12px; }
  .prog-label {
    display: flex; justify-content: space-between;
    font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;
  }
  .prog-bar { height: 6px; border-radius: 10px; background: #e2e8f0; overflow: hidden; }
  .prog-fill { height: 100%; border-radius: 10px; transition: width .6s ease; }

  /* ── Section Divider ── */
  .section-divider { display: flex; align-items: center; gap: 12px; margin: 24px 0 14px; }
  .section-divider .sd-line { flex: 1; height: 1px; background: #e2e8f0; }
  .section-divider .sd-title { font-size: 13px; font-weight: 700; color: #64748b; white-space: nowrap; }

  .toggle-section-btn {
    display: flex; align-items: center; gap: 6px;
    background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px;
    padding: 4px 10px; font-size: 12px; font-weight: 600; color: #64748b;
    cursor: pointer; transition: background .15s, color .15s;
    white-space: nowrap; user-select: none;
  }
  .toggle-section-btn:hover { background: #e2e8f0; color: #1e293b; }
  .toggle-section-btn .toggle-icon { transition: transform .3s ease; font-size: 11px; }
  .toggle-section-btn.collapsed .toggle-icon { transform: rotate(-90deg); }

  .collapsible-section {
    overflow: hidden;
    transition: max-height .4s ease, opacity .3s ease;
    max-height: 2000px; opacity: 1;
  }
  .collapsible-section.section-hidden { max-height: 0; opacity: 0; }

  .section-mini-stats { display: none; align-items: center; gap: 12px; flex-wrap: wrap; padding: 6px 0; }
  .section-mini-stats.visible { display: flex; }
  .sms-item {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700; color: #475569;
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 8px; padding: 3px 10px;
  }

  /* ══════════════════════════════════════════
     تنبيهات قابلة للإخفاء — التصميم الصحيح
     زر × خارج التنبيه في نفس الصف
  ══════════════════════════════════════════ */
  .dismissible-alert-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }
  .dismissible-alert-row .alert-inner { flex: 1; min-width: 0; }

  .dismiss-x {
    flex-shrink: 0;
    width: 30px; height: 30px; border-radius: 50%;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; color: #94a3b8;
    cursor: pointer;
    transition: background .15s, color .15s, border-color .15s;
  }
  .dismiss-x:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

  /* ── شريط ملخص التنبيهات ── */
  .alerts-summary-bar {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
    padding: 10px 16px; box-shadow: 0 1px 4px rgba(0,0,0,.05);
    margin-bottom: 16px;
  }
  .alerts-summary-bar .sum-title { font-size: 13px; font-weight: 700; color: #475569; }
  .alert-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
  }
  .alert-chip.red   { background: #fee2e2; color: #dc2626; }
  .alert-chip.amber { background: #fef3c7; color: #d97706; }
  .alert-chip.blue  { background: #dbeafe; color: #2563eb; }
  .alert-chip.teal  { background: #ccfbf1; color: #0f766e; }
  #show-all-alerts {
    margin-right: auto;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    border-radius: 8px; font-size: 12px; font-weight: 600;
    color: #475569; padding: 4px 12px; cursor: pointer;
    transition: background .15s;
  }
  #show-all-alerts:hover { background: #e2e8f0; color: #1e293b; }
</style>

{{-- ══════════ HERO ══════════ --}}
<div class="dash-hero">
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
    <div>
      <h1>لوحة التحكم — نظام نماء أكاديمي</h1>
      <div class="dash-date">{{ now()->locale('ar')->translatedFormat('l d F Y') }}</div>
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
  <div class="live-bar">
    <div class="live-item"><span class="live-dot"></span><span>النظام يعمل</span></div>
    <div class="live-item">
      <i class="bi bi-people-fill" style="color:rgba(255,255,255,.6);font-size:14px"></i>
      <span class="live-badge">{{ $onlineUsers }}</span><span>متصل الآن</span>
    </div>
    <div class="live-item">
      <i class="bi bi-activity" style="color:rgba(255,255,255,.6);font-size:14px"></i>
      <span>آخر نشاط:</span><span class="live-badge">{{ $lastActivityAr }}</span>
    </div>
    <div class="live-item">
      <i class="bi bi-box-arrow-in-right" style="color:rgba(255,255,255,.6);font-size:14px"></i>
      <span class="live-badge">{{ $todayLogins }}</span><span>دخول اليوم</span>
    </div>
    <div class="live-item" style="margin-right:auto;font-size:11px;opacity:.55;">
      <i class="bi bi-clock"></i> {{ now()->locale('ar')->format('H:i') }}
    </div>
  </div>
</div>

{{-- ══════════ شريط ملخص التنبيهات ══════════ --}}
<div id="alerts-summary-bar-wrap" style="display:none;">
  <div class="alerts-summary-bar">
    <i class="bi bi-bell-fill" style="color:#f59e0b;font-size:15px;"></i>
    <span class="sum-title">تنبيهات مخفية:</span>
    <div class="d-flex gap-2 flex-wrap">
      @if(auth()->user()?->hasPermission('view_leads') && $urgentLeads > 0)
        <span class="alert-chip red"><i class="bi bi-exclamation-triangle-fill"></i> {{ $urgentLeads }} عميل عاجل</span>
      @endif
      @if($pendingMessages > 0)
        <span class="alert-chip amber"><i class="bi bi-envelope-fill"></i> {{ $pendingMessages }} رسالة معلقة</span>
      @endif
      @if($studentsNeedUpdate > 0)
        <span class="alert-chip blue"><i class="bi bi-bell-fill"></i> {{ $studentsNeedUpdate }} طالب للمتابعة</span>
      @endif
      @if($studentsNeedVerification > 0)
        <span class="alert-chip amber"><i class="bi bi-person-exclamation"></i> {{ $studentsNeedVerification }} للمراجعة</span>
      @endif
      @if(isset($pendingLeaves) && $pendingLeaves > 0)
        <span class="alert-chip teal"><i class="bi bi-calendar-x-fill"></i> {{ $pendingLeaves }} إجازة معلقة</span>
      @endif
    </div>
    <button id="show-all-alerts"><i class="bi bi-eye me-1"></i> إظهار التنبيهات</button>
  </div>
</div>

{{-- ══════════ CRM URGENT ══════════ --}}
@if(auth()->user()?->hasPermission('view_leads') && $urgentLeads > 0)
  <div class="dismissible-alert-row" id="alert-crm">
    <div class="alert-inner">
      <a href="{{ route('leads.index') }}" class="crm-alert-card">
        <div class="crm-alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div>
          <div class="crm-alert-num">{{ $urgentLeads }}</div>
          <div class="crm-alert-lbl">عميل محتمل بدون متابعة منذ أكثر من 48 ساعة</div>
          <div class="crm-alert-sub">اضغط للانتقال إلى CRM ومتابعة العملاء</div>
        </div>
        <i class="bi bi-arrow-left-circle-fill ms-auto" style="font-size:22px;color:#ef4444;opacity:.6;"></i>
      </a>
    </div>
    <button class="dismiss-x" data-alert="alert-crm" title="إخفاء">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
@endif

{{-- ══════════ QUICK STATS ══════════ --}}
@if(auth()->user()?->hasPermission('view_dashboard'))
  <div class="section-divider" style="margin-top:0;margin-bottom:12px;">
    <span class="sd-title"><i class="bi bi-bar-chart-fill me-1"></i> الأرقام السريعة</span>
    <div class="sd-line"></div>
    <div class="section-mini-stats" id="mini-quick">
      <div class="sms-item"><i class="bi bi-mortarboard text-primary"></i> {{ $studentStats['total'] }} طالب</div>
      <div class="sms-item"><i class="bi bi-person-check text-success"></i> {{ $attendanceStats['present_today'] }} حاضر</div>
      <div class="sms-item"><i class="bi bi-check2-square" style="color:#8b5cf6"></i> {{ $highlights['alerts']['today_tasks'] }} مهام</div>
    </div>
    <button class="toggle-section-btn" data-target="section-quick" data-key="dash_quick">
      <i class="bi bi-chevron-down toggle-icon"></i><span class="btn-lbl">إخفاء</span>
    </button>
  </div>
  <div class="collapsible-section" id="section-quick">
    <div class="quick-stats">
      <div class="qs-card qs-blue">
        <div class="qs-icon blue"><i class="bi bi-mortarboard-fill"></i></div>
        <div><div class="qs-val">{{ $studentStats['total'] }}</div><div class="qs-label">إجمالي الطلاب</div></div>
      </div>
      <div class="qs-card qs-green">
        <div class="qs-icon green"><i class="bi bi-cash-coin"></i></div>
        <div><div class="qs-val">{{ number_format($todayStats['financial_amount'],0) }}</div><div class="qs-label">إيرادات اليوم</div></div>
      </div>
      <div class="qs-card qs-warn">
        <div class="qs-icon warn"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="qs-val">{{ $highlights['alerts']['pending_leaves'] }}</div><div class="qs-label">إجازات معلقة</div></div>
      </div>
      <div class="qs-card qs-purple">
        <div class="qs-icon purple"><i class="bi bi-check2-square"></i></div>
        <div><div class="qs-val">{{ $highlights['alerts']['today_tasks'] }}</div><div class="qs-label">مهام اليوم</div></div>
      </div>
      <div class="qs-card qs-teal">
        <div class="qs-icon teal"><i class="bi bi-person-check-fill"></i></div>
        <div><div class="qs-val">{{ $attendanceStats['present_today'] }}</div><div class="qs-label">حاضر اليوم</div></div>
      </div>
      <div class="qs-card qs-red">
        <div class="qs-icon red"><i class="bi bi-person-x-fill"></i></div>
        <div><div class="qs-val">{{ $attendanceStats['absent_today'] }}</div><div class="qs-label">غائب اليوم</div></div>
      </div>
      <div class="qs-card qs-sky">
        <div class="qs-icon sky"><i class="bi bi-person-plus-fill"></i></div>
        <div><div class="qs-val">{{ $todayStats['new_students'] }}</div><div class="qs-label">طلاب جدد اليوم</div></div>
      </div>
      <div class="qs-card qs-amber">
        <div class="qs-icon amber"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div><div class="qs-val">{{ $taskStats['overdue'] }}</div><div class="qs-label">مهام متأخرة</div></div>
      </div>
    </div>
  </div>
@endif

{{-- ══════════ تنبيه الرسائل والمتابعة ══════════ --}}
@if($pendingMessages > 0 || $studentsNeedUpdate > 0)
  <div class="dismissible-alert-row" id="alert-messages">
    <div class="alert-inner">
      <div class="row g-3 mb-0">
        @if($pendingMessages > 0)
          <div class="col-12 col-md-6">
            <a href="{{ route('students.index', ['has_message' => 1]) }}" class="text-decoration-none">
              <div class="alert alert-warning d-flex align-items-center gap-3 mb-0 shadow-sm"
                style="border-radius:12px;border-right:5px solid #f59e0b;">
                <div style="font-size:2rem;">📩</div>
                <div>
                  <div class="fw-bold">{{ $pendingMessages }} طالب لديه رسالة معلقة</div>
                  <div class="small text-muted">اضغط لعرض قائمة الطلاب ذوي الرسائل المعلقة</div>
                </div>
                <i class="bi bi-chevron-left ms-auto"></i>
              </div>
            </a>
          </div>
        @endif
        @if($studentsNeedUpdate > 0)
          <div class="col-12 col-md-6">
            <a href="{{ route('students.index', ['needs_update' => 1]) }}" class="text-decoration-none">
              <div class="alert alert-info d-flex align-items-center gap-3 mb-0 shadow-sm"
                style="border-radius:12px;border-right:5px solid #3b82f6;">
                <div style="font-size:2rem;">🔔</div>
                <div>
                  <div class="fw-bold">{{ $studentsNeedUpdate }} طالب لم يتم تحديث بياناتهم منذ 7 أيام</div>
                  <div class="small text-muted">اضغط لعرض الطلاب الذين يحتاجون متابعة</div>
                </div>
                <i class="bi bi-chevron-left ms-auto"></i>
              </div>
            </a>
          </div>
        @endif
      </div>
    </div>
    <button class="dismiss-x" data-alert="alert-messages" title="إخفاء">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
@endif

{{-- ══════════ تنبيه التحقق ══════════ --}}
@if($studentsNeedVerification > 0)
  <div class="dismissible-alert-row" id="alert-verify">
    <div class="alert-inner">
      <div class="d-flex align-items-center gap-2 px-3 py-2"
        style="background:rgba(245,158,11,.08);border-right:4px solid #f59e0b;border-radius:10px;">
        <i class="bi bi-person-exclamation" style="color:#f59e0b;font-size:1.2rem;flex-shrink:0;"></i>
        <div class="flex-grow-1">
          <span class="fw-bold" style="color:#92400e;">{{ $studentsNeedVerification }}</span>
          <span class="small text-muted"> طالب يحتاج مراجعة بياناته (اسم لاتيني / ميلاد / وثيقة)</span>
        </div>
        <a href="{{ route('students.index', ['needs_verification' => 1]) }}"
          class="btn btn-sm flex-shrink-0"
          style="background:#f59e0b;color:#fff;border-radius:8px;font-size:.78rem;">مراجعة</a>
      </div>
    </div>
    <button class="dismiss-x" data-alert="alert-verify" title="إخفاء">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
@endif

{{-- ══════════ تنبيه الإجازات ══════════ --}}
@if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('manager_attendance') || auth()->user()?->hasPermission('view_attendance'))
  @if($pendingLeaves > 0)
    <div class="dismissible-alert-row" id="alert-leaves">
      <div class="alert-inner">
        <div class="d-flex align-items-center gap-3 px-3 py-2"
          style="background:rgba(14,165,233,.08);border-right:4px solid #0ea5e9;border-radius:12px;">
          <i class="bi bi-calendar-x-fill fs-4" style="color:#0284c7;flex-shrink:0;"></i>
          <div class="flex-grow-1">
            <span class="fw-bold" style="color:#0c4a6e;">{{ $pendingLeaves }} طلب إجازة</span>
            <span class="small text-muted"> بانتظار المراجعة والموافقة</span>
          </div>
          <a href="{{ route('leaves.index', ['status' => 'pending']) }}"
            class="btn btn-sm fw-bold flex-shrink-0"
            style="background:#0ea5e9;color:#fff;border-radius:8px;">مراجعة الطلبات</a>
        </div>
      </div>
      <button class="dismiss-x" data-alert="alert-leaves" title="إخفاء">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  @endif
@endif

{{-- ══════════ أحداث الأسبوع ══════════ --}}
@if($upcomingEvents->count())
  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold border-0 d-flex justify-content-between align-items-center pt-3">
          <span><i class="bi bi-calendar-event text-primary"></i> أحداث الأسبوع القادم</span>
          <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-calendar3"></i> عرض التقويم
          </a>
        </div>
        <div class="card-body p-0">
          @foreach($upcomingEvents as $ev)
            <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:34px;height:34px;background:{{ $ev->color }}22;">
                <i class="bi {{ $eventTypes[$ev->type]['icon'] }}" style="color:{{ $ev->color }};font-size:14px;"></i>
              </div>
              <div>
                <div class="fw-bold" style="font-size:13px;">{{ $ev->title }}</div>
                <div class="text-muted" style="font-size:11px;">
                  {{ $ev->start_date->format('d/m/Y') }}
                  @if($ev->start_time) — {{ $ev->start_time }} @endif
                  • {{ $eventTypes[$ev->type]['label'] }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endif

{{-- ══════════════════════════════════════════
     القسم الأول: العمليات الأساسية
══════════════════════════════════════════ --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-grid-3x3-gap me-1"></i> العمليات الأساسية</span>
  <div class="sd-line"></div>
  <div class="section-mini-stats" id="mini-ops">
    <div class="sms-item"><i class="bi bi-mortarboard text-primary"></i> {{ $studentStats['total'] }} طالب</div>
    <div class="sms-item"><i class="bi bi-cash-coin text-success"></i> {{ number_format($todayStats['financial_amount'],0) }} إيرادات</div>
    <div class="sms-item"><i class="bi bi-headset" style="color:#ef4444"></i> {{ $leadStats['total'] }} عميل</div>
  </div>
  <button class="toggle-section-btn" data-target="section-ops" data-key="dash_ops">
    <i class="bi bi-chevron-down toggle-icon"></i><span class="btn-lbl">إخفاء</span>
  </button>
</div>
<div class="collapsible-section" id="section-ops">
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
            <p class="section-note">عرض إحصائيات سريعة وتصفية متقدمة حسب الفرع والفترة.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ $dashboardStats['total_students'] }}</div><div class="sm-label"><i class="bi bi-mortarboard"></i> الطلاب</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ number_format($dashboardStats['revenue_today'],0) }}</div><div class="sm-label"><i class="bi bi-cash-coin"></i> إيرادات</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $dashboardStats['active_employees'] }}</div><div class="sm-label"><i class="bi bi-person-check"></i> موظف</div></div>
            <div class="sm-item"><div class="sm-val text-danger">{{ $dashboardStats['overdue_tasks'] }}</div><div class="sm-label"><i class="bi bi-exclamation-circle"></i> متأخرة</div></div>
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
            <div class="sm-item"><div class="sm-val text-danger">{{ $leadStats['total'] }}</div><div class="sm-label"><i class="bi bi-people"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $leadStats['new'] }}</div><div class="sm-label"><i class="bi bi-star"></i> جديد</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $leadStats['followup'] }}</div><div class="sm-label"><i class="bi bi-arrow-repeat"></i> متابعة</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $leadStats['converted'] }}</div><div class="sm-label"><i class="bi bi-check2-circle"></i> تحويل</div></div>
          </div>
          <div class="prog-wrap">
            <div class="prog-label"><span>نسبة التحويل</span><span>{{ $convRate }}%</span></div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $convRate }}%;background:#10b981;"></div></div>
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $studentStats['total'] }}</div><div class="sm-label"><i class="bi bi-mortarboard"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $studentStats['confirmed'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> مثبّت</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $studentStats['pending'] }}</div><div class="sm-label"><i class="bi bi-hourglass-split"></i> معلّق</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $studentStats['today_new'] }}</div><div class="sm-label"><i class="bi bi-plus-circle"></i> جدد اليوم</div></div>
          </div>
          <div class="prog-wrap">
            <div class="prog-label"><span>نسبة التثبيت</span><span>{{ $confRate }}%</span></div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $confRate }}%;background:#3b82f6;"></div></div>
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $examStats['total'] }}</div><div class="sm-label"><i class="bi bi-journal"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $examStats['upcoming'] }}</div><div class="sm-label"><i class="bi bi-calendar-event"></i> قادم</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $examStats['done'] }}</div><div class="sm-label"><i class="bi bi-check2-all"></i> منتهي</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $examStats['this_month'] }}</div><div class="sm-label"><i class="bi bi-calendar-month"></i> هذا الشهر</div></div>
          </div>
          <div class="module-actions">
            <a href="{{ route('exams.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الامتحانات</a>
            <a href="{{ route('exams.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة امتحان</a>
          </div>
        </div>
      </div>
    @endif

    {{-- الصناديق --}}
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $cashboxStats['total'] }}</div><div class="sm-label"><i class="bi bi-safe"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $cashboxStats['active'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> نشط</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $cashboxStats['today_trx'] }}</div><div class="sm-label"><i class="bi bi-arrow-left-right"></i> حركات</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ number_format($cashboxStats['today_amount'],0) }}</div><div class="sm-label"><i class="bi bi-currency-dollar"></i> مبلغ اليوم</div></div>
          </div>
          <div class="module-actions">
            <a href="{{ route('cashboxes.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح النظام المالي</a>
            <a href="{{ route('cashboxes.index', ['status' => 'active']) }}" class="btn btn-soft w-100 w-sm-auto">الصناديق النشطة</a>
          </div>
        </div>
      </div>
    @endif

    {{-- الذمم --}}
    @if(auth()->user()?->hasPermission('view_debts'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon" style="background:linear-gradient(135deg,#fde68a,#f59e0b);color:#92400e;">
              <i class="bi bi-wallet2 fs-3"></i>
            </div>
            <div>
              <p class="module-title">الذمم وكشف الحسابات</p>
              <p class="module-sub">ذمم الطلاب — كشف حساب شامل لكل الحركات</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">متابعة الذمم المالية للطلاب، وكشف حساب تفصيلي مع تصدير Excel.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-danger">{{ $debtStats['has_debt'] }}</div><div class="sm-label"><i class="bi bi-exclamation-circle"></i> عليهم ذمة</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $debtStats['paid'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> مسدّد</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ number_format($debtStats['total_remaining'],0) }}</div><div class="sm-label"><i class="bi bi-currency-dollar"></i> إجمالي الذمم</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $debtStats['total_students'] }}</div><div class="sm-label"><i class="bi bi-people"></i> إجمالي</div></div>
          </div>
          <div class="module-actions grid-2">
            <a href="{{ route('debts.index') }}" class="btn btn-namaa w-100 w-sm-auto"><i class="bi bi-wallet2"></i> الذمم المالية</a>
            @if(auth()->user()?->hasPermission('view_account_statement'))
              <a href="{{ route('accounts.statement.index') }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-receipt-cutoff"></i> كشف الحسابات</a>
            @endif
          </div>
        </div>
      </div>
    @endif

    {{-- الدوام --}}
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
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-success">{{ $attendanceStats['present_today'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> حاضر</div></div>
            <div class="sm-item"><div class="sm-val text-danger">{{ $attendanceStats['absent_today'] }}</div><div class="sm-label"><i class="bi bi-x-circle"></i> غائب</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $attendanceStats['pending_leaves'] }}</div><div class="sm-label"><i class="bi bi-hourglass-split"></i> إجازات معلقة</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $attendanceStats['approved_leaves'] }}</div><div class="sm-label"><i class="bi bi-calendar-check"></i> إجازات قادمة</div></div>
          </div>
          <div class="module-actions grid-2">
            <a href="{{ route('attendance.calendar') }}" class="btn btn-namaa w-100">التقويم</a>
            <a href="{{ route('attendance.index') }}" class="btn btn-namaa w-100">فتح الدوام</a>
            @if(auth()->user()?->hasPermission('export_attendance_reports'))
              <a href="{{ route('attendance.reports') }}" class="btn btn-soft w-100">تقارير الدوام</a>
            @endif
            @if(auth()->user()?->hasPermission('view_leaves'))
              <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary fw-bold w-100 position-relative">
                طلبات الإجازات
                @if($pendingLeaves > 0)
                  <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size:.7rem;">{{ $pendingLeaves }}</span>
                @endif
              </a>
            @endif
          </div>
          <div style="padding:0 16px 10px;">
            <div class="small fw-bold text-muted mb-2">
              <i class="bi bi-calendar-day"></i> دوام اليوم — {{ now()->locale('ar')->translatedFormat('l d/m') }}
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('attendance.index', ['from'=>now()->toDateString(),'to'=>now()->toDateString(),'type'=>'employee']) }}"
                class="btn fw-bold flex-fill"
                style="background:rgba(14,165,233,.1);color:#0369a1;border:1px solid rgba(14,165,233,.25);border-radius:10px;font-size:12px;">
                <i class="bi bi-person-badge"></i><br>دوام الموظفين
              </a>
              <a href="{{ route('attendance.index', ['from'=>now()->toDateString(),'to'=>now()->toDateString(),'type'=>'trainer']) }}"
                class="btn fw-bold flex-fill"
                style="background:rgba(16,185,129,.1);color:#065f46;border:1px solid rgba(16,185,129,.25);border-radius:10px;font-size:12px;">
                <i class="bi bi-mortarboard"></i><br>دوام المدربين
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- التقويم --}}
    @if(auth()->user()?->hasPermission('view_calendar'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;">
              <i class="bi bi-calendar3 fs-3"></i>
            </div>
            <div>
              <p class="module-title">التقويم والأحداث</p>
              <p class="module-sub">جلسات — حملات — أعياد ميلاد — تذكيرات</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">إدارة الأحداث والمواعيد المهمة مع تنبيهات تلقائية.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ $calendarStats['total'] ?? 0 }}</div><div class="sm-label"><i class="bi bi-calendar3"></i> هذا الشهر</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $calendarStats['upcoming'] ?? 0 }}</div><div class="sm-label"><i class="bi bi-calendar-check"></i> قادمة</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $calendarStats['today'] ?? 0 }}</div><div class="sm-label"><i class="bi bi-calendar-day"></i> اليوم</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $calendarStats['passed'] ?? 0 }}</div><div class="sm-label"><i class="bi bi-calendar-x"></i> منتهية</div></div>
          </div>
          @if(isset($upcomingEvents2) && $upcomingEvents2->count())
            <div style="padding:0 16px 12px;">
              <div style="font-size:11px;font-weight:700;color:#64748b;margin-bottom:6px;"><i class="bi bi-clock"></i> أقرب حدث</div>
              <div class="d-flex align-items-center gap-2 p-2 rounded"
                style="background:{{ $next->color }}15;border-right:3px solid {{ $next->color }};">
                <i class="bi {{ $eventTypes2[$next->type]['icon'] ?? 'bi-calendar' }}" style="color:{{ $next->color }};font-size:16px;"></i>
                <div>
                  <div style="font-size:12px;font-weight:800;color:#1e293b;">{{ $next->title }}</div>
                  <div style="font-size:11px;color:#94a3b8;">{{ $next->start_date->format('d/m/Y') }} {{ $next->start_time ? '— '.$next->start_time : '' }}</div>
                </div>
              </div>
            </div>
          @endif
          <div class="module-actions">
            <a href="{{ route('calendar.index') }}" class="btn btn-namaa w-100 w-sm-auto"><i class="bi bi-calendar3"></i> فتح التقويم</a>
            @if(auth()->user()?->hasPermission('create_events'))
              <a href="{{ route('calendar.index') }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-plus-circle"></i> إضافة حدث</a>
            @endif
          </div>
        </div>
      </div>
    @endif

  </div>
</div>{{-- /section-ops --}}

{{-- ══════════════════════════════════════════
     القسم الثاني: الموارد البشرية
══════════════════════════════════════════ --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-person-gear me-1"></i> الموارد البشرية والإدارة</span>
  <div class="sd-line"></div>
  <div class="section-mini-stats" id="mini-hr">
    <div class="sms-item"><i class="bi bi-mortarboard text-primary"></i> {{ $hrStats['trainers'] }} مدرب</div>
    <div class="sms-item"><i class="bi bi-person-badge text-secondary"></i> {{ $hrStats['employees'] }} موظف</div>
    <div class="sms-item"><i class="bi bi-list-check" style="color:#8b5cf6"></i> {{ $taskStats['total'] }} مهمة</div>
  </div>
  <button class="toggle-section-btn" data-target="section-hr" data-key="dash_hr">
    <i class="bi bi-chevron-down toggle-icon"></i><span class="btn-lbl">إخفاء</span>
  </button>
</div>
<div class="collapsible-section" id="section-hr">
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
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ $taskStats['total'] }}</div><div class="sm-label"><i class="bi bi-list-check"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $taskStats['todo'] }}</div><div class="sm-label"><i class="bi bi-hourglass-split"></i> قيد التنفيذ</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $taskStats['done'] }}</div><div class="sm-label"><i class="bi bi-check2-all"></i> منجز</div></div>
            <div class="sm-item"><div class="sm-val text-danger">{{ $taskStats['overdue'] }}</div><div class="sm-label"><i class="bi bi-exclamation-circle"></i> متأخر</div></div>
          </div>
          <div class="prog-wrap">
            <div class="prog-label"><span>نسبة الإنجاز</span><span>{{ $doneRate }}%</span></div>
            <div class="prog-bar"><div class="prog-fill" style="width:{{ $doneRate }}%;background:#10b981;"></div></div>
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

    {{-- مجموعات الرؤية --}}
    @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasPermission('manage_roles'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-teal"><i class="bi bi-diagram-2-fill fs-3"></i></div>
            <div>
              <p class="module-title">مجموعات الرؤية</p>
              <p class="module-sub">تحكم بمن يرى مهام وتقارير من</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">تحديد صلاحيات الرؤية بشكل دقيق — كل مدير يرى فقط تقارير ومهام الموظفين المضافين لمجموعته.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ \App\Models\VisibilityGroup::count() }}</div><div class="sm-label"><i class="bi bi-diagram-2"></i> مجموعة</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ \DB::table('visibility_group_employee')->where('role_in_group','manager')->count() }}</div><div class="sm-label"><i class="bi bi-person-check"></i> مدير</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ \DB::table('visibility_group_employee')->where('role_in_group','member')->count() }}</div><div class="sm-label"><i class="bi bi-people"></i> عضو</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ \DB::table('visibility_group_employee')->distinct('employee_id')->count('employee_id') }}</div><div class="sm-label"><i class="bi bi-person-badge"></i> موظف مُدار</div></div>
          </div>
          <div class="module-actions">
            <a href="{{ route('admin.visibility-groups.index') }}" class="btn btn-namaa w-100 w-sm-auto"><i class="bi bi-diagram-2-fill"></i> إدارة المجموعات</a>
            <a href="{{ route('admin.visibility-groups.create') }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-plus-circle"></i> مجموعة جديدة</a>
          </div>
        </div>
      </div>
    @endif

    {{-- الموظفين --}}
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $hrStats['trainers'] }}</div><div class="sm-label"><i class="bi bi-mortarboard"></i> مدرب</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $hrStats['employees'] }}</div><div class="sm-label"><i class="bi bi-person-badge"></i> موظف</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $hrStats['active_trainers'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> مدرب نشط</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $hrStats['active_employees'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> موظف نشط</div></div>
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

    {{-- الأمان --}}
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
            <div class="sm-item"><div class="sm-val text-success">{{ $onlineUsers }}</div><div class="sm-label"><i class="bi bi-circle-fill text-success" style="font-size:6px"></i> متصلون</div></div>
            <div class="sm-item"><div class="sm-val text-primary">{{ $totalUsers }}</div><div class="sm-label"><i class="bi bi-people-fill"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $todayLogins }}</div><div class="sm-label"><i class="bi bi-box-arrow-in-right"></i> دخول اليوم</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $totalUsers - $onlineUsers }}</div><div class="sm-label"><i class="bi bi-circle"></i> غير متصل</div></div>
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
</div>{{-- /section-hr --}}

{{-- ══════════════════════════════════════════
     القسم الثالث: البنية التحتية
══════════════════════════════════════════ --}}
<div class="section-divider">
  <span class="sd-title"><i class="bi bi-buildings me-1"></i> البنية التحتية والبرامج</span>
  <div class="sd-line"></div>
  <div class="section-mini-stats" id="mini-infra">
    <div class="sms-item"><i class="bi bi-box-seam text-warning"></i> {{ $assetStats['total'] }} أصل</div>
    <div class="sms-item"><i class="bi bi-building" style="color:#ef4444"></i> {{ $branchStats['total'] }} فرع</div>
    <div class="sms-item"><i class="bi bi-mortarboard-fill" style="color:#84cc16"></i> {{ $diplomaStats['total'] }} دبلومة</div>
  </div>
  <button class="toggle-section-btn" data-target="section-infra" data-key="dash_infra">
    <i class="bi bi-chevron-down toggle-icon"></i><span class="btn-lbl">إخفاء</span>
  </button>
</div>
<div class="collapsible-section" id="section-infra">
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $assetStats['total'] }}</div><div class="sm-label"><i class="bi bi-box-seam"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $assetStats['good'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> جيد</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $assetStats['maintenance'] }}</div><div class="sm-label"><i class="bi bi-wrench"></i> صيانة</div></div>
            <div class="sm-item"><div class="sm-val text-danger">{{ $assetStats['retired'] }}</div><div class="sm-label"><i class="bi bi-x-circle"></i> خارج</div></div>
          </div>
          <div class="module-actions">
            <a href="{{ route('assets.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الأصول</a>
            <a href="{{ route('asset-categories.index') }}" class="btn btn-soft w-100 w-sm-auto">تصنيفات الأصول</a>
          </div>
        </div>
      </div>
    @endif

    {{-- طلبات اللوجستيات --}}
    @if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasPermission('submit_asset_request'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-yellow"><i class="bi bi-send-plus fs-3"></i></div>
            <div>
              <p class="module-title">طلبات اللوجستيات</p>
              <p class="module-sub">طلبات الشراء والإصلاح — مراجعة المدير</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">تقديم طلبات شراء أصول جديدة أو إصلاح أصول موجودة، ومتابعة حالة الطلب.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-warning">{{ $assetRequestStats['pending'] }}</div><div class="sm-label"><i class="bi bi-hourglass-split"></i> قيد المراجعة</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $assetRequestStats['approved'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> مقبول</div></div>
            <div class="sm-item"><div class="sm-val text-danger">{{ $assetRequestStats['rejected'] }}</div><div class="sm-label"><i class="bi bi-x-circle"></i> مرفوض</div></div>
            <div class="sm-item"><div class="sm-val text-primary">{{ $assetRequestStats['total'] }}</div><div class="sm-label"><i class="bi bi-list-check"></i> إجمالي</div></div>
          </div>
          <div class="module-actions grid-2">
            @if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasRole('super_admin'))
              <a href="{{ route('asset-requests.index') }}" class="btn btn-namaa w-100 w-sm-auto position-relative">
                <i class="bi bi-inbox"></i> إدارة الطلبات
                @if($assetRequestStats['pending'] > 0)
                  <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">{{ $assetRequestStats['pending'] }}</span>
                @endif
              </a>
            @endif
            @if(auth()->user()?->hasPermission('submit_asset_request'))
              <a href="{{ route('asset-requests.create') }}" class="btn btn-soft w-100 w-sm-auto"><i class="bi bi-send-plus"></i> تقديم طلب</a>
            @endif
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
            <div class="sm-item"><div class="sm-val text-danger">{{ $branchStats['total'] }}</div><div class="sm-label"><i class="bi bi-building"></i> فرع</div></div>
            <div class="sm-item"><div class="sm-val text-primary">{{ $branchStats['students'] }}</div><div class="sm-label"><i class="bi bi-mortarboard"></i> طالب</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $branchStats['employees'] }}</div><div class="sm-label"><i class="bi bi-person-badge"></i> موظف</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $branchStats['assets'] }}</div><div class="sm-label"><i class="bi bi-box-seam"></i> أصل</div></div>
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
            <div class="sm-item"><div class="sm-val text-primary">{{ $diplomaStats['total'] }}</div><div class="sm-label"><i class="bi bi-mortarboard"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $diplomaStats['active'] }}</div><div class="sm-label"><i class="bi bi-check-circle"></i> نشط</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $diplomaStats['online'] }}</div><div class="sm-label"><i class="bi bi-wifi"></i> أونلاين</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $diplomaStats['onsite'] }}</div><div class="sm-label"><i class="bi bi-geo-alt"></i> حضوري</div></div>
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
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ $programStats['total'] }}</div><div class="sm-label"><i class="bi bi-diagram-3"></i> مُدارة</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $programStats['online'] }}</div><div class="sm-label"><i class="bi bi-wifi"></i> أونلاين</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $programStats['onsite'] }}</div><div class="sm-label"><i class="bi bi-building"></i> حضوري</div></div>
            <div class="sm-item"><div class="sm-val text-secondary">{{ $programStats['inactive'] }}</div><div class="sm-label"><i class="bi bi-pause-circle"></i> غير نشط</div></div>
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
          <div class="stats-mini">
            <div class="sm-item"><div class="sm-val text-primary">{{ $mediaStats['total'] }}</div><div class="sm-label"><i class="bi bi-megaphone"></i> إجمالي</div></div>
            <div class="sm-item"><div class="sm-val text-warning">{{ $mediaStats['pending'] }}</div><div class="sm-label"><i class="bi bi-hourglass-split"></i> قيد التنفيذ</div></div>
            <div class="sm-item"><div class="sm-val text-success">{{ $mediaStats['done'] }}</div><div class="sm-label"><i class="bi bi-check2-circle"></i> منجز</div></div>
            <div class="sm-item"><div class="sm-val text-info">{{ $mediaStats['this_month'] }}</div><div class="sm-label"><i class="bi bi-calendar-month"></i> هذا الشهر</div></div>
          </div>
          <div class="module-actions">
            <a href="{{ route('media.index') }}" class="btn btn-namaa w-100">فتح قسم الميديا</a>
          </div>
          <div class="module-actions">
            <a href="{{ route('media.publish.create') }}" class="btn btn-namaa w-100"><i class="bi bi-plus-lg"></i> إضافة سجل نشر</a>
          </div>
        </div>
      </div>
    @endif

    {{-- إعدادات النظام --}}
    @if(auth()->user()?->hasRole('super_admin'))
      <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-slate"><i class="bi bi-gear-fill fs-3"></i></div>
            <div>
              <p class="module-title">إعدادات النظام</p>
              <p class="module-sub">المظهر، الألوان، وضبط الإشعارات</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">تخصيص مظهر النظام، ضبط الألوان، وتحديد مدد تنبيهات متابعة العملاء.</p>
          </div>
          <div class="stats-mini">
            <div class="sm-item">
              <div class="sm-val">
                <i class="bi bi-{{ \App\Models\SystemSetting::get('theme_mode','light') === 'dark' ? 'moon-stars-fill text-primary' : 'sun-fill text-warning' }}" style="font-size:1.4rem;"></i>
              </div>
              <div class="sm-label">وضع العرض</div>
            </div>
            <div class="sm-item">
              <div class="sm-val">
                <span style="display:inline-block;width:28px;height:28px;border-radius:50%;background:{{ \App\Models\SystemSetting::get('primary_color','#0ea5e9') }};"></span>
              </div>
              <div class="sm-label">اللون الرئيسي</div>
            </div>
            <div class="sm-item">
              <div class="sm-val text-danger fw-bold">{{ \App\Models\SystemSetting::get('alert_followup_hours',48) }}س</div>
              <div class="sm-label">تنبيه عاجل</div>
            </div>
            <div class="sm-item">
              <div class="sm-val text-warning fw-bold">{{ \App\Models\SystemSetting::get('alert_warning_hours',24) }}س</div>
              <div class="sm-label">تنبيه تحذيري</div>
            </div>
          </div>
          <div class="module-actions">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-namaa w-100 fw-bold"><i class="bi bi-sliders"></i> فتح الإعدادات</a>
          </div>
        </div>
      </div>
    @endif

  </div>
</div>{{-- /section-infra --}}

@if(session('asset_request_success'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'success', title: 'تم إرسال الطلب',
        text: '{{ session('asset_request_success') }}',
        confirmButtonText: 'ممتاز', confirmButtonColor: '#0ea5e9',
        timer: 4000, timerProgressBar: true,
      });
    });
  </script>
@endif

{{-- ══════════════════════════════════════════
     JavaScript الكامل
══════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

  var PREFIX     = 'namaa_dash_';
  var ALERTS_KEY = 'namaa_hidden_alerts';

  /* ══════════════════════════════
     1) طي / توسيع الأقسام
  ══════════════════════════════ */
  document.querySelectorAll('.toggle-section-btn').forEach(function (btn) {
    var targetId   = btn.dataset.target;
    var storageKey = PREFIX + btn.dataset.key;
    var section    = document.getElementById(targetId);
    var miniId     = targetId.replace('section-', 'mini-');
    var mini       = document.getElementById(miniId);
    var lbl        = btn.querySelector('.btn-lbl');

    if (localStorage.getItem(storageKey) === 'hidden') {
      collapseSection(section, mini, btn, lbl);
    }

    btn.addEventListener('click', function () {
      if (section.classList.contains('section-hidden')) {
        expandSection(section, mini, btn, lbl);
        localStorage.setItem(storageKey, 'visible');
      } else {
        collapseSection(section, mini, btn, lbl);
        localStorage.setItem(storageKey, 'hidden');
      }
    });
  });

  function collapseSection(section, mini, btn, lbl) {
    section.classList.add('section-hidden');
    btn.classList.add('collapsed');
    if (lbl) lbl.textContent = 'إظهار';
    if (mini) mini.classList.add('visible');
  }

  function expandSection(section, mini, btn, lbl) {
    section.classList.remove('section-hidden');
    btn.classList.remove('collapsed');
    if (lbl) lbl.textContent = 'إخفاء';
    if (mini) mini.classList.remove('visible');
  }

  /* ══════════════════════════════
     2) إخفاء التنبيهات
  ══════════════════════════════ */
  function getHidden() {
    try { return JSON.parse(localStorage.getItem(ALERTS_KEY) || '[]'); } catch(e) { return []; }
  }
  function saveHidden(arr) { localStorage.setItem(ALERTS_KEY, JSON.stringify(arr)); }

  function updateSummaryBar() {
    var all = document.querySelectorAll('.dismissible-alert-row');
    var bar = document.getElementById('alerts-summary-bar-wrap');
    if (!bar || all.length === 0) return;
    var allHidden = true;
    all.forEach(function(el) { if (el.style.display !== 'none') allHidden = false; });
    bar.style.display = allHidden ? 'block' : 'none';
  }

  /* تطبيق الحالة المحفوظة عند التحميل */
  getHidden().forEach(function(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });
  updateSummaryBar();

  /* أزرار × الإخفاء */
  document.querySelectorAll('.dismiss-x').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      var id = btn.dataset.alert;
      var el = document.getElementById(id);
      if (!el) return;

      el.style.transition    = 'opacity .25s ease, max-height .35s ease, margin .3s ease';
      el.style.overflow      = 'hidden';
      el.style.maxHeight     = el.offsetHeight + 'px';
      el.style.opacity       = '1';
      requestAnimationFrame(function() {
        el.style.opacity      = '0';
        el.style.maxHeight    = '0';
        el.style.marginBottom = '0';
      });
      setTimeout(function() {
        el.style.display = 'none';
        var arr = getHidden();
        if (!arr.includes(id)) arr.push(id);
        saveHidden(arr);
        updateSummaryBar();
      }, 360);
    });
  });

  /* زر إظهار الكل */
  var showAllBtn = document.getElementById('show-all-alerts');
  if (showAllBtn) {
    showAllBtn.addEventListener('click', function() {
      document.querySelectorAll('.dismissible-alert-row').forEach(function(el) {
        el.style.display      = '';
        el.style.opacity      = '';
        el.style.maxHeight    = '';
        el.style.marginBottom = '';
        el.style.overflow     = '';
        el.style.transition   = '';
      });
      saveHidden([]);
      updateSummaryBar();
    });
  }

});
</script>

@endsection