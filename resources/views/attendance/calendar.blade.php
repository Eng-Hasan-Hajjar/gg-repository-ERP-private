@extends('layouts.app')
@php($activeModule = 'attendance')

@section('title','تقويم الدوام')

@push('styles')
<style>
  /* ===============================
     Calendar Responsive UI
     =============================== */
  .calendar-wrap { border-radius: 18px; overflow: hidden; }
  .calendar-toolbar .btn { border-radius: 14px; font-weight: 900; }
  .calendar-toolbar .btn i { vertical-align: -2px; }

  /* Table behavior */
  .calendar-table { table-layout: fixed; }
  .calendar-table thead th{
    position: sticky; top: 0; z-index: 6;
    background: #f8fafc;
    white-space: nowrap;
  }

.calendar-table th.emp-col,
.calendar-table td.emp-col{
  position: sticky;
  right: 0; /* RTL */
  z-index: 4;
}
.calendar-table thead th.emp-col{
  z-index: 6;
  background: #f8fafc;
}

  /* Cell */
  .cell{
    width: 34px; min-width: 34px; height: 32px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 10px;
    font-weight: 900;
    font-size: .85rem;
    letter-spacing: .2px;
  }

  /* Status colors */
  .st-present { background: rgba(16,185,129,.14); color: #065f46; border: 1px solid rgba(16,185,129,.25); }
  .st-late    { background: rgba(245,158,11,.16); color: #7c2d12; border: 1px solid rgba(245,158,11,.28); }
  .st-absent  { background: rgba(239,68,68,.14);  color: #7f1d1d; border: 1px solid rgba(239,68,68,.25); }
  .st-leave   { background: rgba(59,130,246,.14); color: #1e3a8a; border: 1px solid rgba(59,130,246,.25); }
  .st-off     { background: rgba(148,163,184,.18); color: #0f172a; border: 1px solid rgba(148,163,184,.28); }
  .st-sched   { background: rgba(99,102,241,.12); color: #312e81; border: 1px solid rgba(99,102,241,.22); }

  /* Hover */
  .calendar-table tbody tr:hover td{ background: rgba(2,6,23,.02); }

  /* Legend chips */
  .legend-chip{
    display:inline-flex; align-items:center; gap:8px;
    padding:8px 12px; border-radius:999px;
    border:1px solid rgba(226,232,240,.95);
    background: rgba(255,255,255,.85);
    font-weight: 900;
    font-size: .9rem;
    white-space: nowrap;
  }
  .dot{ width:12px; height:12px; border-radius:999px; display:inline-block; }



  /* عرض عمود الموظف + منع تكسير الحروف */
.calendar-table .emp-col{
  min-width: 260px;     /* الأساس على الشاشات الكبيرة */
  width: 260px;
  max-width: 360px;

  background: #fff;     /* لأن العمود sticky */
  white-space: normal;  /* يسمح بسطرين/ثلاثة */
  word-break: keep-all; /* يمنع تكسير الحروف العربية */
  overflow-wrap: break-word; /* يكسر على حدود الكلمات عند الحاجة */
  vertical-align: middle;
  padding: 10px 12px;
}

/* تمييز العمود المثبّت بصريًا (اختياري لكنه يعطي احترافية) */
.calendar-table td.emp-col,
.calendar-table th.emp-col{
  box-shadow: -10px 0 18px rgba(2,6,23,.06);
}

/* تحسين النص داخل العمود */
.emp-name{ line-height: 1.35; }
.emp-branch{ line-height: 1.25; }

/* Responsive: على الموبايل خفّف عرض العمود */
@media (max-width: 991.98px){
  .calendar-table .emp-col{
    min-width: 200px;
    width: 200px;
  }
}
@media (max-width: 575.98px){
  .calendar-table .emp-col{
    min-width: 170px;
    width: 170px;
    padding: 8px 10px;
  }
  .emp-name{ font-size: .95rem; }
  .emp-branch{ font-size: .78rem; }
}




  /* Responsive tweaks */
  @media (max-width: 575.98px){
    .cell{ width: 30px; min-width: 30px; height: 28px; font-size:.78rem; border-radius: 9px; }
    .calendar-table th:first-child, .calendar-table td:first-child{ min-width: 180px !important; }
  }
</style>
@endpush

@section('content')

{{-- ===============================
   Header + Actions (Responsive)
   =============================== --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="fw-bold mb-1">تقويم الدوام الشهري</h4>
    <div class="text-muted small">عرض سريع لحالات الدوام حسب اليوم — قابل للتمرير على الموبايل</div>
  </div>

  <div class="calendar-toolbar d-flex flex-wrap gap-2 w-100 w-lg-auto justify-content-start justify-content-lg-end">
    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
      <i class="bi bi-arrow-return-right"></i> رجوع
    </a>

    {{-- على الشاشات الصغيرة: Dropdown للتصدير بدل زحمة أزرار --}}
    <div class="dropdown d-sm-none w-100">
      <button class="btn btn-namaa w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-download"></i> تصدير
      </button>
      <ul class="dropdown-menu w-100">
        <li>
          <a class="dropdown-item"
             href="{{ route('attendance.calendar.exportExcel', request()->query()) }}">
            <i class="bi bi-file-earmark-excel text-success"></i> تصدير Excel
          </a>
        </li>
        <li>
          <a class="dropdown-item"
             href="{{ route('attendance.calendar.exportPdf', request()->query()) }}">
            <i class="bi bi-file-earmark-pdf text-danger"></i> تصدير PDF
          </a>
        </li>
      </ul>
    </div>

    {{-- على الشاشات المتوسطة+: أزرار منفصلة --}}
    <a class="btn btn-success d-none d-sm-inline-flex"
       href="{{ route('attendance.calendar.exportExcel', request()->query()) }}">
      <i class="bi bi-file-earmark-excel"></i> Excel
    </a>

    <a class="btn btn-danger d-none d-sm-inline-flex"
       href="{{ route('attendance.calendar.exportPdf', request()->query()) }}">
      <i class="bi bi-file-earmark-pdf"></i> PDF
    </a>
  </div>
</div>

{{-- ===============================
   Filters Card (Responsive Grid)
   =============================== --}}
<form class="card mb-3" method="GET" action="{{ route('attendance.calendar') }}">
  <div class="card-body">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-4 col-lg-3">
        <label class="form-label fw-bold mb-1">الشهر</label>
        <input type="month" name="month" value="{{ $month }}" class="form-control">
      </div>

      <div class="col-12 col-md-4 col-lg-3">
        <label class="form-label fw-bold mb-1">الفرع</label>
        <select name="branch_id" class="form-select">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(($filters['branch_id'] ?? '') == $b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-4 col-lg-3">
        <button class="btn btn-namaa w-100">
          <i class="bi bi-funnel"></i> تطبيق
        </button>
      </div>

      <div class="col-12 col-lg-3 text-muted small d-flex align-items-center gap-2">
        <i class="bi bi-info-circle"></i>
        <span>تلميح: اضغط/مرّر لرؤية الحالة.</span>
      </div>
    </div>
  </div>
</form>

{{-- ===============================
   Table (Responsive + Scroll)
   =============================== --}}
<div class="calendar-wrap border">
  <div class="table-responsive" style="max-height: 68vh;">
    <table class="table table-bordered align-middle mb-0 calendar-table">
      <thead class="table-light">
        <tr>
          <th class="emp-col">الموظف</th>

          @foreach($days as $date)
            <th class="text-center" style="min-width:40px">
              {{ \Carbon\Carbon::parse($date)->day }}
            </th>
          @endforeach
        </tr>
      </thead>

      <tbody>
        @forelse($employees as $emp)
          <tr>
       <td class="emp-col">
  <div class="fw-bold emp-name">{{ $emp->full_name }}</div>
  <div class="small text-muted emp-branch">{{ $emp->branch->name ?? '-' }}</div>
</td>

            @foreach($days as $date)
              @php($status = $recordsMap[$emp->id][$date] ?? null)
              @php($css = $statusCssMap[$status] ?? '')

              <td class="text-center">
                <span class="cell {{ $css }}"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="{{ $status ? $status : 'لا يوجد سجل' }}">
                  {{ $letterMap[$status] ?? '-' }}
                </span>
              </td>
            @endforeach
          </tr>
        @empty
          <tr>
            <td colspan="{{ 1 + count($days) }}" class="text-center text-muted py-4">
              لا يوجد موظفون مطابقون للتصفية الحالية.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ===============================
   Legend (Responsive)
   =============================== --}}
<div class="d-flex flex-wrap gap-2 mt-3">
  <span class="legend-chip"><span class="dot" style="background:#10b981"></span> P = حضور</span>
  <span class="legend-chip"><span class="dot" style="background:#f59e0b"></span> L = تأخير</span>
  <span class="legend-chip"><span class="dot" style="background:#ef4444"></span> A = غياب</span>
  <span class="legend-chip"><span class="dot" style="background:#3b82f6"></span> V = إجازة</span>
  <span class="legend-chip"><span class="dot" style="background:#94a3b8"></span> O = عطلة</span>
  <span class="legend-chip"><span class="dot" style="background:#6366f1"></span> S = مجدول</span>
</div>

@push('scripts')
<script>
  // Tooltips (Bootstrap)
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
  });
</script>
@endpush

@endsection
