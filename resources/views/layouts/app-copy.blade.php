<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Namaa ERP')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

  <!-- Bootstrap Icons (للأيقونات داخل الكروت) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root{
      --namaa-blue: #0ea5e9;
      --namaa-green:#10b981;
      --namaa-ink:  #0b1220;
      --namaa-muted:#64748b;
    }

    body{
      font-family: "Cairo", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
      background:
        radial-gradient(1000px 600px at 15% 10%, rgba(16,185,129,.10), transparent 60%),
        radial-gradient(1000px 600px at 85% 15%, rgba(14,165,233,.10), transparent 55%),
        linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
      color: var(--namaa-ink);
    }

    /* Navbar */
    .navbar.namaa-nav{
      background: linear-gradient(90deg, rgba(14,165,233,.96) 0%, rgba(16,185,129,.96) 100%) !important;
      box-shadow: 0 12px 30px rgba(2, 6, 23, .10);
    }
    .navbar .navbar-brand{
      font-weight: 800;
      letter-spacing: .3px;
    }

    /* Hero header inside main */
    .namaa-hero{
      background: rgba(255,255,255,.75);
      border: 1px solid rgba(226,232,240,.9);
      backdrop-filter: blur(8px);
      border-radius: 18px;
      padding: 18px 18px;
      box-shadow: 0 20px 60px rgba(2,6,23,.08);
    }
    .namaa-hero h1{
      font-size: 1.25rem;
      font-weight: 900;
      margin: 0;
    }
    .namaa-hero p{
      margin: 6px 0 0 0;
      color: var(--namaa-muted);
      font-weight: 600;
      line-height: 1.8;
    }

    /* Module Cards */
    .module-card{
      border: 1px solid rgba(226,232,240,.9);
      border-radius: 18px;
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      background: rgba(255,255,255,.85);
      box-shadow: 0 14px 40px rgba(2,6,23,.08);
      height: 100%;
    }
    .module-card:hover{
      transform: translateY(-4px);
      box-shadow: 0 24px 80px rgba(2,6,23,.14);
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
    .grad-primary{ background: linear-gradient(135deg, var(--namaa-blue), var(--namaa-green)); }
    .grad-blue{ background: linear-gradient(135deg, #38bdf8, #2563eb); }
    .grad-green{ background: linear-gradient(135deg, #34d399, #059669); }
    .grad-amber{ background: linear-gradient(135deg, #fbbf24, #f97316); }
    .grad-purple{ background: linear-gradient(135deg, #a78bfa, #6366f1); }
    .grad-rose{ background: linear-gradient(135deg, #fb7185, #ef4444); }
    .grad-slate{ background: linear-gradient(135deg, #94a3b8, #334155); }

    .module-title{
      font-weight: 900;
      margin: 0;
      font-size: 1.05rem;
    }
    .module-sub{
      margin: 0;
      color: var(--namaa-muted);
      font-weight: 600;
      font-size: .92rem;
      line-height: 1.7;
    }

    .module-body{
      padding: 12px 16px 16px 16px;
    }

    .module-actions{
      padding: 0 16px 16px 16px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .btn-namaa{
      border: 0;
      font-weight: 800;
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
      font-weight: 800;
      border: 1px solid rgba(226,232,240,.95);
      background: rgba(255,255,255,.85);
      color: var(--namaa-ink);
    }
    .btn-soft:hover{
      border-color: rgba(14,165,233,.35);
      background: rgba(255,255,255,.95);
    }

    /* small badge */
    .chip{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 800;
      background: rgba(16,185,129,.10);
      color: #0f766e;
      border: 1px solid rgba(16,185,129,.18);
    }

    /* spacing helpers */
    .section-title{
      font-weight: 900;
      margin: 0;
      font-size: 1.05rem;
    }
    .section-note{
      color: var(--namaa-muted);
      font-weight: 600;
      margin: 0;
      line-height: 1.8;
      font-size: .95rem;
    }
  </style>
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark namaa-nav">
  <div class="container py-1">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('students.index') }}">
      <i class="bi bi-grid-fill"></i>
      Namaa ERP
    </a>


    <div class="navbar-nav me-auto">
      <a class="nav-link" href="{{ route('students.index') }}">الطلاب</a>
      <a class="nav-link" href="{{ route('diplomas.index') }}">الدبلومات</a>
      <a class="nav-link" href="{{ route('branches.index') }}">الفروع</a>
    </div>


    <div class="ms-auto d-flex align-items-center gap-3">
      <span class="t xt-white-50 small fw-semibold">
        {{ auth()->user()->name ?? '' }}
      </span>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-sm btn-outline-light fw-bold rounded-pill px-3">تسجيل خروج</button>
      </form>
    </div>


  </div>
</nav>

<main class="container py-4">






  
  {{-- Your page content --}}
  @include('partials.flash')
  @yield('content')

  <div class="text-center mt-5 small text-secondary fw-semibold">
    © {{ date('Y') }} نماء أكاديمي — جميع الحقوق محفوظة
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
