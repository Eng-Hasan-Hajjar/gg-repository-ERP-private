<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" href="{{ asset('images/namaa-logo.png') }}">
  <title>@yield('title', 'Namaa ERP')</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/namaa-logo.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/namaa-logo.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/namaa-logo.png') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    @font-face {
      font-family: 'Hacen Tunisia';
      src: url('{{ asset('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf') }}') format('truetype');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }





    :root {
      --namaa-blue: #0ea5e9;
      --namaa-green: #10b981;
      --namaa-ink: #0b1220;
      --namaa-muted: #64748b;

      --glass: rgba(255, 255, 255, .82);
      --border: rgba(226, 232, 240, .95);
      --shadow: 0 20px 60px rgba(2, 6, 23, .08);
      --shadow-2: 0 24px 80px rgba(2, 6, 23, .14);





      /* =========================================================
   🎯 Reports Design Tokens (Namaa Identity)
   ========================================================= */

      --report-students: #0ea5e9;
      --report-revenue: #10b981;
      --report-growth: #6366f1;
      --report-alerts: #f59e0b;
      --report-crm: #ec4899;

      /* Soft Backgrounds */
      --report-students-soft: rgba(14, 165, 233, .08);
      --report-revenue-soft: rgba(16, 185, 129, .08);
      --report-growth-soft: rgba(99, 102, 241, .08);
      --report-alerts-soft: rgba(245, 158, 11, .10);
      --report-crm-soft: rgba(236, 72, 153, .08);





    }


    /* =========================================================
   🎯 Soft Buttons System (ERP Style)
   ========================================================= */

    .btn-soft-primary {
      background: var(--report-students-soft);
      color: var(--report-students);
      border: 1px solid rgba(14, 165, 233, .25);
    }

    .btn-soft-success {
      background: var(--report-revenue-soft);
      color: var(--report-revenue);
      border: 1px solid rgba(16, 185, 129, .25);
    }

    .btn-soft-warning {
      background: var(--report-alerts-soft);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, .35);
    }

    .btn-soft-purple {
      background: var(--report-growth-soft);
      color: var(--report-growth);
      border: 1px solid rgba(99, 102, 241, .25);
    }

    .btn-soft-pink {
      background: var(--report-crm-soft);
      color: var(--report-crm);
      border: 1px solid rgba(236, 72, 153, .25);
    }

    [class*="btn-soft-"] {
      font-weight: 900;
      border-radius: 14px;
      padding: 10px 16px;
      transition: .2s ease;
    }

    [class*="btn-soft-"]:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 25px rgba(2, 6, 23, .08);
    }







    body {
      font-family: 'Hacen Tunisia', sans-serif !important;
      letter-spacing: .2px;

      background:
        radial-gradient(1000px 600px at 15% 10%, rgba(16, 185, 129, .10), transparent 60%),
        radial-gradient(1000px 600px at 85% 15%, rgba(14, 165, 233, .10), transparent 55%),
        linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
      color: var(--namaa-ink);


    }

    input,
    select,
    textarea,
    button,
    .table,
    .badge,
    .dropdown-menu,
    .navbar,
    .card,
    .modal,
    .form-control {
      font-family: 'Hacen Tunisia', sans-serif !important;
    }





    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }

    .status-dot.online {
      background: #22c55e;
      box-shadow: 0 0 6px #22c55e;
    }

    .status-dot.offline {
      background: #ef4444;
    }




    /* ===== User Status Cell ===== */

    .user-cell {
      display: flex;
      align-items: center;
      gap: 8px;
      min-height: 28px;
    }

    /* الدائرة */
    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      flex-shrink: 0;
      position: relative;
      top: 1px;
    }

    /* Online */
    .status-dot.online {
      background: #22c55e;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, .15);
    }

    /* Offline */
    .status-dot.offline {
      background: #ef4444;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, .15);
    }

    /* اسم المستخدم */
    .user-name {
      white-space: nowrap;
    }







    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .navbar-brand,
    .module-title {
      font-family: 'Hacen Tunisia', sans-serif;
      font-weight: 900;
    }

    /* تخطيط احترافي للأزرار: صفّان × عمودان كحد أقصى */
    .module-actions.grid-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }


    /* Navbar */
    .navbar.namaa-nav {
      background: linear-gradient(90deg, rgba(14, 165, 233, .96) 0%, rgba(16, 185, 129, .96) 100%) !important;
      box-shadow: 0 12px 30px rgba(2, 6, 23, .10);
    }

    .navbar .navbar-brand {
      font-weight: 900;
      letter-spacing: .3px;
    }

    /* Dashboard Hero */
    .namaa-hero {
      background: rgba(255, 255, 255, .75);
      border: 1px solid rgba(226, 232, 240, .9);
      backdrop-filter: blur(8px);
      border-radius: 18px;
      padding: 18px;
      box-shadow: var(--shadow);
    }

    .namaa-hero h1 {
      font-size: 1.25rem;
      font-weight: 900;
      margin: 0;
    }

    .namaa-hero p {
      margin: 6px 0 0 0;
      color: var(--namaa-muted);
      font-weight: 700;
      line-height: 1.9;
    }

    .chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 900;
      background: rgba(16, 185, 129, .10);
      color: #0f766e;
      border: 1px solid rgba(16, 185, 129, .18);
      white-space: nowrap;
    }

    /* Module Cards (Dashboard only) */
    .module-card {
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 18px;
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      background: rgba(255, 255, 255, .86);
      box-shadow: 0 14px 40px rgba(2, 6, 23, .08);
      height: 100%;
    }

    .module-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-2);
      border-color: rgba(14, 165, 233, .35);
    }

    .module-head {
      padding: 16px 16px 0 16px;
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .module-icon {
      width: 52px;
      height: 52px;
      border-radius: 16px;
      display: grid;
      place-items: center;
      color: #fff;
      flex: 0 0 auto;
      box-shadow: 0 16px 30px rgba(2, 6, 23, .14);
    }

    /* Gradients */
    /* Primary Identity */
    .grad-primary {
      background: linear-gradient(135deg, var(--namaa-blue), var(--namaa-green));
    }

    /* Blues */
    .grad-blue {
      background: linear-gradient(135deg, #38bdf8, #2563eb);
    }

    .grad-sky {
      background: linear-gradient(135deg, #0ea5e9, #0284c7);
    }

    .grad-indigo {
      background: linear-gradient(135deg, #6366f1, #4338ca);
    }

    /* Greens */
    .grad-green {
      background: linear-gradient(135deg, #34d399, #059669);
    }

    .grad-emerald {
      background: linear-gradient(135deg, #10b981, #047857);
    }

    .grad-teal {
      background: linear-gradient(135deg, #2dd4bf, #0f766e);
    }

    /* Warm */
    .grad-amber {
      background: linear-gradient(135deg, #fbbf24, #f97316);
    }

    .grad-orange {
      background: linear-gradient(135deg, #fb923c, #ea580c);
    }

    .grad-yellow {
      background: linear-gradient(135deg, #fde047, #ca8a04);
    }

    /* Alerts */
    .grad-rose {
      background: linear-gradient(135deg, #fb7185, #e11d48);
    }

    .grad-red {
      background: linear-gradient(135deg, #ef4444, #b91c1c);
    }

    /* Purple Family */
    .grad-purple {
      background: linear-gradient(135deg, #a78bfa, #6366f1);
    }

    .grad-violet {
      background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    }

    .grad-fuchsia {
      background: linear-gradient(135deg, #e879f9, #c026d3);
    }

    /* Neutral ERP */
    .grad-slate {
      background: linear-gradient(135deg, #94a3b8, #334155);
    }

    .grad-gray {
      background: linear-gradient(135deg, #9ca3af, #4b5563);
    }

    /* Luxury / Analytics */
    .grad-cyan {
      background: linear-gradient(135deg, #22d3ee, #0891b2);
    }

    .grad-lime {
      background: linear-gradient(135deg, #84cc16, #4d7c0f);
    }

    .grad-dark {
      background: linear-gradient(135deg, #1e293b, #020617);
    }




    .module-title {
      font-weight: 900;
      margin: 0;
      font-size: 1.05rem;
    }

    .module-sub {
      margin: 0;
      color: var(--namaa-muted);
      font-weight: 700;
      font-size: .92rem;
      line-height: 1.7;
    }

    .module-body {
      padding: 12px 16px 16px 16px;
    }

    .module-actions {
      padding: 0 16px 16px 16px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }




    /* ============================
   Table KPI Style (Soft ERP)
============================ */

    .badge-namaa {
      background: rgba(14, 165, 233, .08);
      color: var(--namaa-blue);
      border: 1px solid rgba(14, 165, 233, .25);
      font-weight: 800;
      padding: 6px 10px;
      border-radius: 10px;
      font-size: .75rem;
    }



    .btn-namaa {
      border: 0;
      font-weight: 900;
      border-radius: 14px;
      padding: 10px 14px;
      background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
      color: #fff !important;
      box-shadow: 0 18px 35px rgba(16, 185, 129, .18), 0 16px 26px rgba(14, 165, 233, .14);
    }

    .btn-namaa:hover {
      filter: brightness(.95);
    }

    .btn-soft {
      border-radius: 14px;
      padding: 10px 14px;
      font-weight: 900;
      border: 1px solid rgba(226, 232, 240, .95);
      background: rgba(255, 255, 255, .85);
      color: var(--namaa-ink);
    }

    .btn-soft:hover {
      border-color: rgba(14, 165, 233, .35);
      background: rgba(255, 255, 255, .95);
    }

    .section-note {
      color: var(--namaa-muted);
      font-weight: 700;
      margin: 0;
      line-height: 1.9;
      font-size: .95rem;
    }

    /* =======================================
       MODULE PAGES: Right Iconbar + Content
       ======================================= */
    .app-shell {
      display: grid;
      grid-template-columns: 1fr;
      /* mobile */
      gap: 14px;
      align-items: start;
    }

    /* Iconbar on RIGHT (requested) */
    .iconbar {
      display: none;
      /* hidden on dashboard */
      position: sticky;
      top: 92px;
      align-self: start;

      width: 64px;
      padding: 10px 8px;

      background: rgba(255, 255, 255, .78);
      border: 1px solid rgba(226, 232, 240, .95);
      border-radius: 18px;
      backdrop-filter: blur(10px);
      box-shadow: var(--shadow);
    }

    .iconbar a {
      display: grid;
      place-items: center;
      width: 44px;
      height: 44px;
      border-radius: 16px;
      margin: 8px auto;
      text-decoration: none;
      color: #fff;
      box-shadow: 0 14px 26px rgba(2, 6, 23, .12);
      transition: transform .15s ease, filter .15s ease;
    }

    .iconbar a:hover {
      transform: translateY(-2px);
      filter: brightness(.95);
    }

    .iconbar a.active {
      outline: 3px solid rgba(14, 165, 233, .25);
      outline-offset: 2px;
    }

    /* Content surface */
    .content-surface {
      background: rgba(255, 255, 255, .78);
      border: 1px solid rgba(226, 232, 240, .95);
      border-radius: 18px;
      box-shadow: var(--shadow);
      padding: 16px;
    }

    /* Desktop layout: content left, iconbar right */
    @media (min-width: 992px) {
      .app-shell {
        grid-template-columns: 1fr 64px;
      }

      .iconbar {
        display: block;
      }
    }

    /* في الشاشات الصغيرة يبقى زرًا واحدًا بالعرض الكامل */
    @media (max-width: 767px) {
      .module-actions.grid-2 {
        grid-template-columns: 1fr;
      }
    }

    /* Mobile: iconbar becomes bottom dock (best UX) */
    @media (max-width: 991.98px) {
      .iconbar {
        display: flex;
        position: fixed;
        bottom: 14px;
        left: 14px;
        right: 14px;
        top: auto;

        width: auto;
        padding: 10px 10px;
        border-radius: 18px;
        z-index: 9999;

        justify-content: space-between;
        gap: 10px;
      }

      .iconbar a {
        margin: 0;
        width: 44px;
        height: 44px;
        border-radius: 16px;
        flex: 1 1 auto;
        max-width: 54px;
      }

      .app-shell {
        grid-template-columns: 1fr;
      }

      .content-surface {
        padding: 14px;
      }
    }








    /* =========================================================
   ✅ Desktop يبقى طبيعي 100%
   ========================================================= */

    @media (min-width: 769px) {

      .table {
        table-layout: auto;
        /* يرجع التمدد الطبيعي */
      }

      .table td,
      .table th {
        white-space: nowrap;
        /* يمنع التكسير في الديسكتوب */
      }

    }


    /* =========================================================
   ✅ Mobile Optimization فقط — لا يلمس الديسكتوب
   ========================================================= */

    @media (max-width: 768px) {

      .table-responsive {
        overflow-x: auto;
      }

      .table {
        table-layout: fixed;
        font-size: 13px;
      }

      .table td,
      .table th {
        padding: 6px 8px;
        white-space: normal;
        word-break: break-word;
      }

      /* اختصار البريد الطويل */
      .table td code {
        display: inline-block;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      /* تصغير الأزرار */
      .table .btn {
        padding: 4px 6px;
        font-size: 11px;
        margin-bottom: 2px;
      }

      /* ضبط الاسم */
      .user-name {
        max-width: 110px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

    }






    /* =========================================================
   📱 Mobile Table Cleanup (ERP Style)
   ========================================================= */

    @media (max-width:768px) {

      /* إخفاء الأعمدة غير المهمة */
      .hide-mobile {
        display: none !important;
      }

      /* تصغير الجدول */
      .table {
        font-size: 13px;
      }

      /* جعل الصف أنظف */
      .table td {
        vertical-align: middle;
      }

      /* عرض الإجراء الأساسي فقط */
      .actions-cell {
        white-space: nowrap;
        width: 1%;
      }

      .main-action {
        padding: 6px 10px;
        font-size: 12px;
      }
    }







    /* =========================================================
   📱 Dock Responsive Control
   ========================================================= */


    /* تابلت */
    @media (min-width:769px) and (max-width:1024px) {

      .hide-tablet {
        display: none !important;
      }
    }

    /* ديسكتوب فقط */
    @media (max-width:1024px) {
      .show-desktop-only {
        display: none !important;
      }
    }






    /* =========================================================
   📱 Reports Responsive Layout
   ========================================================= */
    /* =========================================
   ERP REPORTS GRID SYSTEM
========================================= */

    .reports-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 18px;
    }

    /* الكرت نفسه */
    .report-tile {
      position: relative;
      padding: 20px;
      border-radius: 18px;
      text-decoration: none;
      transition: .25s ease;
      overflow: hidden;
      border: 1px solid rgba(226, 232, 240, .9);
      background: #fff;
    }

    /* Hover احترافي */
    .report-tile:hover {
      transform: translateY(-6px);
      box-shadow: 0 25px 60px rgba(2, 6, 23, .12);
    }

    /* أيقونة كبيرة */
    .report-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin-bottom: 12px;
    }

    /* العنوان */
    .report-title {
      font-weight: 900;
      font-size: 1.05rem;
      margin-bottom: 4px;
    }

    /* الوصف */
    .report-desc {
      font-size: .85rem;
      color: #64748b;
    }

    /* خط زخرفي */
    .report-tile::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--namaa-blue), var(--namaa-green));
      opacity: .2;
    }


    @media (max-width:768px) {
      .reports-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width:480px) {
      .reports-grid {
        grid-template-columns: 1fr;
      }
    }





    @media (max-width:768px) {

      .stat-card {
        padding: 12px;
        border-radius: 14px;
      }

      .stat-value {
        font-size: 1.1rem;
      }

      .glass-card {
        padding: 14px !important;
      }
    }
  </style>

  @stack('styles')
</head>

@php
  // هل الصفحة Dashboard؟
  $isDashboard = $isDashboard ?? false;
  // لتفعيل تمييز الأيقونة الحالية
  $activeModule = $activeModule ?? '';
@endphp

<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark namaa-nav">
    <div class="container py-1">
      {{-- رابط الداشبورد --}}
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-fill"></i>
        Namaa ERP
      </a>

      <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white-50 small fw-semibold d-none d-md-inline">
          {{ auth()->user()->name ?? '' }}
        </span>


   @if(auth()->user()->hasRole('super_admin'))

        <div class="dropdown">

          <button class="btn btn-light position-relative" id="alertBell" data-bs-toggle="dropdown">

            <i class="bi bi-bell fs-5"></i>

            <span id="alertCount" class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
              style="display:none">
            </span>

          </button>

          <div class="dropdown-menu p-2" id="alertList" style="width:320px">
            <div class="text-muted text-center small">جاري تحميل التنبيهات...</div>
          </div>

        </div>
   @endif



        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-sm btn-outline-light fw-bold rounded-pill px-3">تسجيل خروج</button>
        </form>
      </div>
    </div>
  </nav>

  <main class="container py-4">

    {{-- ✅ Dashboard فقط: يعرض Hero + Cards --}}
    @if($isDashboard)

      @yield('dashboard')

    @else

      {{-- ✅ أي صفحة مودول: تظهر أيقونات يمين + محتوى يسار --}}
      <div class="app-shell">

        <section class="content-surface">
          @include('partials.flash')
          @yield('content')
        </section>



        @if(auth()->user()->hasRole('super_admin'))

          <aside class="iconbar" aria-label="Module icons">
            <a href="{{ route('dashboard') }}" class="grad-slate {{ $activeModule === 'dashboard' ? 'active' : '' }}"
              title="لوحة التحكم">
              <i class="bi bi-grid-fill fs-6"></i>
            </a>

            @if(auth()->user()?->hasPermission('manage_roles'))

              <a href="{{ route('admin.audit.index') }}" class="grad-rose {{ $activeModule === 'audit' ? 'active' : '' }}"
                title="سجل التدقيق">
                <i class="bi bi-journal-text fs-6"></i>
              </a>
            @endif
            @if(auth()->user()?->hasPermission('manage_roles'))

              <a href="{{ route('admin.roles.index') }}"
                class="grad-slate hide-mobile show-desktop-only {{ $activeModule === 'users' ? 'active' : '' }}"
                title="المستخدمين والصلاحيات">
                <i class="bi bi-person-gear fs-6"></i>
              </a>
            @endif







            <a href="{{ route('reports.index') }}" class="grad-green  {{ $activeModule === 'reports' ? 'active' : '' }}"
              title="التقارير والإحصائيات ">
              <i class="bi bi-bar-chart fs-6"></i>
            </a>




            <a href="{{ route('students.index') }}" class="grad-blue   {{ $activeModule === 'students' ? 'active' : '' }}"
              title="الطلاب">
              <i class="bi bi-people-fill fs-6"></i>
            </a>
            <a href="{{ route('leads.index') }}"
              class="grad-green  hide-mobile show-desktop-only {{ $activeModule === 'leads' ? 'active' : '' }}" title="CRM">
              <i class="bi bi-headset fs-3"></i>
            </a>

            <a href="{{ route('diplomas.index') }}"
              class="grad-purple  hide-mobile show-desktop-only {{ $activeModule === 'diplomas' ? 'active' : '' }}"
              title="الدبلومات">
              <i class="bi bi-mortarboard-fill fs-6"></i>
            </a>

            <a href="{{ route('employees.index') }}" class="grad-primary {{ $activeModule === 'employees' ? 'active' : '' }}"
              title="الموارد البشرية">
              <i class="bi bi-person-badge-fill fs-6"></i>
            </a>



            <a href="{{ route('branches.index') }}"
              class="grad-green  hide-mobile show-desktop-only {{ $activeModule === 'branches' ? 'active' : '' }}"
              title="الفروع">
              <i class="bi bi-building fs-6"></i>
            </a>

            <a href="{{ route('assets.index') }}"
              class="grad-blue  hide-mobile show-desktop-only {{ $activeModule === 'assets' ? 'active' : '' }}"
              title="الأصول">
              <i class="bi bi-box-seam fs-6"></i>
            </a>
            <a href="{{ route('asset-categories.index') }}"
              class="grad-purple  hide-mobile show-desktop-only {{ $activeModule === 'asset-categories' ? 'active' : '' }}"
              title="تصنيفات الأصول">
              <i class="bi bi-tags fs-6"></i>
            </a>







            <a href="{{ route('cashboxes.index') }}" class="grad-amber {{ $activeModule === 'finance' ? 'active' : '' }}"
              title="المالية والصناديق">
              <i class="bi bi-cash-coin fs-6"></i>
            </a>



            <a href="{{ route('attendance.index') }}" class="grad-rose {{ $activeModule === 'attendance' ? 'active' : '' }}"
              title="الدوام">
              <i class="bi bi-calendar2-week fs-6"></i>
            </a>

            <a href="{{ route('tasks.index') }}" class="grad-slate {{ $activeModule === 'tasks' ? 'active' : '' }}"
              title="المهام">
              <i class="bi bi-check2-square fs-6"></i>
            </a>



            <a href="{{ route('leaves.index') }}"
              class="grad-amber  hide-mobile show-desktop-only {{ $activeModule === 'attendance' ? 'active' : '' }}"
              title="الإجازات">
              <i class="bi bi-clipboard2-check fs-6"></i>
            </a>


            <a href="{{ route('attendance.calendar') }}"
              class="grad-rose  hide-mobile show-desktop-only {{ $activeModule === 'attendance' ? 'active' : '' }}"
              title="الدوام">
              <i class="bi bi-calendar2-week fs-6"></i>
            </a>

            <a href="{{ route('attendance.reports') }}"
              class="grad-slate  hide-mobile show-desktop-only {{ $activeModule === 'attendance-reports' ? 'active' : '' }}"
              title="تقارير الدوام">
              <i class="bi bi-clipboard-data fs-6"></i>
            </a>





          </aside>

        @endif


      </div>

    @endif

    <div class="text-center mt-5 small text-secondary fw-semibold">
      © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
    </div>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




  <script>
    document.addEventListener('DOMContentLoaded', function () {

      fetch("{{ route('alerts.navbar') }}")
        .then(res => res.json())
        .then(data => {

          const count = document.getElementById('alertCount');
          const list = document.getElementById('alertList');

          if (data.count > 0) {
            count.style.display = 'inline-block';
            count.innerText = data.count;
          }

          if (data.alerts.length === 0) {
            list.innerHTML =
              `<div class="text-success text-center">
                        لا توجد تنبيهات 🎉
                    </div>`;
            return;
          }

          let html = '';

          data.alerts.forEach(a => {
            html += `
                    <a href="${a.url}"
                       class="dropdown-item d-flex gap-2 align-items-start">
                        <i class="bi ${a.icon} text-${a.type}"></i>
                        <div>${a.message}</div>
                    </a>
                `;
          });

          list.innerHTML = html;
        });

    });
  </script>




  @stack('scripts')
</body>

</html>