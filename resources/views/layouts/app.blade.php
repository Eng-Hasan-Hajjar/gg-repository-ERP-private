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
      src:  url('/fonts/hacen-tunisia/Hacen-Tunisia.ttf') format('truetype');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }


    :root{
      --namaa-blue: #0ea5e9;
      --namaa-green:#10b981;
      --namaa-ink:  #0b1220;
      --namaa-muted:#64748b;

      --glass: rgba(255,255,255,.82);
      --border: rgba(226,232,240,.95);
      --shadow: 0 20px 60px rgba(2,6,23,.08);
      --shadow-2: 0 24px 80px rgba(2,6,23,.14);
    }


    body{
      font-family: 'Hacen Tunisia', system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
      background:
        radial-gradient(1000px 600px at 15% 10%, rgba(16,185,129,.10), transparent 60%),
        radial-gradient(1000px 600px at 85% 15%, rgba(14,165,233,.10), transparent 55%),
        linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
      color: var(--namaa-ink);
    }


    h1, h2, h3, h4, h5, h6,
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
    .navbar.namaa-nav{
      background: linear-gradient(90deg, rgba(14,165,233,.96) 0%, rgba(16,185,129,.96) 100%) !important;
      box-shadow: 0 12px 30px rgba(2, 6, 23, .10);
    }
    .navbar .navbar-brand{ font-weight: 900; letter-spacing: .3px; }

    /* Dashboard Hero */
    .namaa-hero{
      background: rgba(255,255,255,.75);
      border: 1px solid rgba(226,232,240,.9);
      backdrop-filter: blur(8px);
      border-radius: 18px;
      padding: 18px;
      box-shadow: var(--shadow);
    }
    .namaa-hero h1{ font-size: 1.25rem; font-weight: 900; margin: 0; }
    .namaa-hero p{ margin: 6px 0 0 0; color: var(--namaa-muted); font-weight: 700; line-height: 1.9; }

    .chip{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 900;
      background: rgba(16,185,129,.10);
      color: #0f766e;
      border: 1px solid rgba(16,185,129,.18);
      white-space: nowrap;
    }

    /* Module Cards (Dashboard only) */
    .module-card{
      border: 1px solid rgba(226,232,240,.9);
      border-radius: 18px;
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      background: rgba(255,255,255,.86);
      box-shadow: 0 14px 40px rgba(2,6,23,.08);
      height: 100%;
    }
    .module-card:hover{
      transform: translateY(-4px);
      box-shadow: var(--shadow-2);
      border-color: rgba(14,165,233,.35);
    }
    .module-head{
      padding: 16px 16px 0 16px;
      display: flex;
      gap: 12px;
      align-items: center;
    }
    .module-icon{
      width: 52px;
      height: 52px;
      border-radius: 16px;
      display: grid;
      place-items: center;
      color: #fff;
      flex: 0 0 auto;
      box-shadow: 0 16px 30px rgba(2,6,23,.14);
    }

    /* Gradients */
    .grad-primary{ background: linear-gradient(135deg, var(--namaa-blue), var(--namaa-green)); }
    .grad-blue{ background: linear-gradient(135deg, #38bdf8, #2563eb); }
    .grad-green{ background: linear-gradient(135deg, #34d399, #059669); }
    .grad-amber{ background: linear-gradient(135deg, #fbbf24, #f97316); }
    .grad-purple{ background: linear-gradient(135deg, #a78bfa, #6366f1); }
    .grad-rose{ background: linear-gradient(135deg, #fb7185, #ef4444); }
    .grad-slate{ background: linear-gradient(135deg, #94a3b8, #334155); }

    .module-title{ font-weight: 900; margin: 0; font-size: 1.05rem; }
    .module-sub{ margin: 0; color: var(--namaa-muted); font-weight: 700; font-size: .92rem; line-height: 1.7; }
    .module-body{ padding: 12px 16px 16px 16px; }
    .module-actions{ padding: 0 16px 16px 16px; display: flex; gap: 10px; flex-wrap: wrap; }

    .btn-namaa{
      border: 0;
      font-weight: 900;
      border-radius: 14px;
      padding: 10px 14px;
      background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
      color: #fff !important;
      box-shadow: 0 18px 35px rgba(16,185,129,.18), 0 16px 26px rgba(14,165,233,.14);
    }
    .btn-namaa:hover{ filter: brightness(.95); }

    .btn-soft{
      border-radius: 14px;
      padding: 10px 14px;
      font-weight: 900;
      border: 1px solid rgba(226,232,240,.95);
      background: rgba(255,255,255,.85);
      color: var(--namaa-ink);
    }
    .btn-soft:hover{
      border-color: rgba(14,165,233,.35);
      background: rgba(255,255,255,.95);
    }

    .section-note{
      color: var(--namaa-muted);
      font-weight: 700;
      margin: 0;
      line-height: 1.9;
      font-size: .95rem;
    }

    /* =======================================
       MODULE PAGES: Right Iconbar + Content
       ======================================= */
    .app-shell{
      display: grid;
      grid-template-columns: 1fr; /* mobile */
      gap: 14px;
      align-items: start;
    }

    /* Iconbar on RIGHT (requested) */
    .iconbar{
      display: none; /* hidden on dashboard */
      position: sticky;
      top: 92px;
      align-self: start;

      width: 64px;
      padding: 10px 8px;

      background: rgba(255,255,255,.78);
      border: 1px solid rgba(226,232,240,.95);
      border-radius: 18px;
      backdrop-filter: blur(10px);
      box-shadow: var(--shadow);
    }

    .iconbar a{
      display: grid;
      place-items: center;
      width: 44px;
      height: 44px;
      border-radius: 16px;
      margin: 8px auto;
      text-decoration: none;
      color: #fff;
      box-shadow: 0 14px 26px rgba(2,6,23,.12);
      transition: transform .15s ease, filter .15s ease;
    }
    .iconbar a:hover{ transform: translateY(-2px); filter: brightness(.95); }

    .iconbar a.active{
      outline: 3px solid rgba(14,165,233,.25);
      outline-offset: 2px;
    }

    /* Content surface */
    .content-surface{
      background: rgba(255,255,255,.78);
      border: 1px solid rgba(226,232,240,.95);
      border-radius: 18px;
      box-shadow: var(--shadow);
      padding: 16px;
    }

    /* Desktop layout: content left, iconbar right */
    @media (min-width: 992px){
      .app-shell{
        grid-template-columns: 1fr 64px;
      }
      .iconbar{ display: block; }
    }
/* في الشاشات الصغيرة يبقى زرًا واحدًا بالعرض الكامل */
@media (max-width: 767px){
  .module-actions.grid-2 {
    grid-template-columns: 1fr;
  }
}
    /* Mobile: iconbar becomes bottom dock (best UX) */
    @media (max-width: 991.98px){
      .iconbar{
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
      .iconbar a{
        margin: 0;
        width: 44px;
        height: 44px;
        border-radius: 16px;
        flex: 1 1 auto;
        max-width: 54px;
      }
      .app-shell{ grid-template-columns: 1fr; }
      .content-surface{ padding: 14px; }
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



      <div class="dropdown">

    <button class="btn btn-light position-relative" id="alertBell"
            data-bs-toggle="dropdown">

        <i class="bi bi-bell fs-5"></i>

        <span id="alertCount"
              class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
              style="display:none">
        </span>

    </button>

    <div class="dropdown-menu p-2" id="alertList" style="width:320px">
        <div class="text-muted text-center small">جاري تحميل التنبيهات...</div>
    </div>

</div>



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

      <aside class="iconbar" aria-label="Module icons">
        <a href="{{ route('dashboard') }}" class="grad-slate {{ $activeModule==='dashboard' ? 'active' : '' }}" title="لوحة التحكم">
          <i class="bi bi-grid-fill fs-6"></i>
        </a>

       @if(auth()->user()?->hasPermission('manage_roles'))

          <a href="{{ route('admin.audit.index') }}"
            class="grad-rose {{ $activeModule==='audit' ? 'active' : '' }}"
            title="سجل التدقيق">
            <i class="bi bi-journal-text fs-6"></i>
          </a>
      @endif
        @if(auth()->user()?->hasPermission('manage_roles'))

            <a href="{{ route('admin.roles.index') }}"
              class="grad-slate {{ $activeModule==='users' ? 'active' : '' }}"
              title="المستخدمين والصلاحيات">
              <i class="bi bi-person-gear fs-6"></i>
            </a>
       @endif







        <a href="{{ route('reports.index') }}" class="grad-green {{ $activeModule==='reports' ? 'active' : '' }}" title="التقارير والإحصائيات ">
          <i class="bi bi-bar-chart fs-6"></i> 
        </a>

 


        <a href="{{ route('students.index') }}" class="grad-blue {{ $activeModule==='students' ? 'active' : '' }}" title="الطلاب">
          <i class="bi bi-people-fill fs-6"></i>
        </a>
         <a href="{{ route('leads.index') }}" class="grad-green {{ $activeModule==='leads' ? 'active' : '' }}" title="CRM">
          <i class="bi bi-headset fs-3"></i>
        </a>

        <a href="{{ route('diplomas.index') }}" class="grad-purple {{ $activeModule==='diplomas' ? 'active' : '' }}" title="الدبلومات">
          <i class="bi bi-mortarboard-fill fs-6"></i>
        </a>

        <a href="{{ route('employees.index') }}"
          class="grad-primary {{ $activeModule==='employees' ? 'active' : '' }}"
          title="الموارد البشرية">
          <i class="bi bi-person-badge-fill fs-6"></i>
        </a>



        <a href="{{ route('branches.index') }}" class="grad-green {{ $activeModule==='branches' ? 'active' : '' }}" title="الفروع">
          <i class="bi bi-building fs-6"></i>
        </a>

    <a href="{{ route('assets.index') }}" class="grad-blue {{ $activeModule==='assets' ? 'active' : '' }}" title="الأصول">
          <i class="bi bi-box-seam fs-6"></i>
        </a>
    <a href="{{ route('asset-categories.index') }}" class="grad-purple {{ $activeModule==='asset-categories' ? 'active' : '' }}" title="تصنيفات الأصول">
          <i class="bi bi-tags fs-6"></i>
        </a>




    


        <a href="{{ route('cashboxes.index') }}"
          class="grad-amber {{ $activeModule==='finance' ? 'active' : '' }}"
          title="المالية والصناديق">
          <i class="bi bi-cash-coin fs-6"></i>
        </a>



        <a href="{{ route('attendance.index') }}" class="grad-rose {{ $activeModule==='attendance' ? 'active' : '' }}" title="الدوام">
  <i class="bi bi-calendar2-week fs-6"></i>
</a>

<a href="{{ route('tasks.index') }}" class="grad-slate {{ $activeModule==='tasks' ? 'active' : '' }}" title="المهام">
  <i class="bi bi-check2-square fs-6"></i>
</a>



<a href="{{ route('leaves.index') }}" class="grad-amber {{ $activeModule==='attendance' ? 'active' : '' }}" title="الإجازات">
  <i class="bi bi-clipboard2-check fs-6"></i>
</a>


<a href="{{ route('attendance.calendar') }}"
   class="grad-rose {{ $activeModule==='attendance' ? 'active' : '' }}"
   title="الدوام">
  <i class="bi bi-calendar2-week fs-6"></i>
</a>

<a href="{{ route('attendance.reports') }}"
   class="grad-slate {{ $activeModule==='attendance-reports' ? 'active' : '' }}"
   title="تقارير الدوام">
  <i class="bi bi-clipboard-data fs-6"></i>
</a>

        {{-- لاحقاً --}}
        


        <a href="#" class="grad-primary" title="التقارير (قريباً)">
          <i class="bi bi-graph-up-arrow fs-6"></i>
        </a>
      </aside>

    </div>

  @endif

  <div class="text-center mt-5 small text-secondary fw-semibold">
    © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




<script>
document.addEventListener('DOMContentLoaded', function(){

    fetch("{{ route('alerts.navbar') }}")
        .then(res => res.json())
        .then(data => {

            const count = document.getElementById('alertCount');
            const list  = document.getElementById('alertList');

            if(data.count > 0){
                count.style.display = 'inline-block';
                count.innerText = data.count;
            }

            if(data.alerts.length === 0){
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
