@extends('layouts.app')
@php($activeModule = 'attendance')
@section('title', 'الدوام والحضور')

@section('content')
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">الدوام والحضور</h4>
      <div class="text-muted fw-semibold">سجل الدخول/الخروج + تأخير + ساعات</div>
    </div>
  </div>


@if(auth()->user()?->hasRole('super_admin'))
  <form method="POST" action="{{ route('attendance.generateWeek') }}" class="card border-0 shadow-sm mb-3">
    @csrf

    <div class="card-body py-2">

      <div class="row align-items-end g-2">

        <div class="col-md-3">
          <label class="fw-bold small mb-1">بداية الأسبوع</label>

          <input type="date" name="week_start" class="form-control" required
            value="{{ now()->startOfWeek()->format('Y-m-d') }}">
        </div>

        <div class="col-md-auto">

          <button class="btn btn-namaa fw-bold mt-3">
            <i class="bi bi-magic"></i>
            توليد سجلات الأسبوع
          </button>

        </div>

      </div>

    </div>

  </form>


  <div class="mb-2 text-muted small">
    عدد السجلات: {{ $records->total() }}
  </div>


  <div class="d-flex flex-wrap gap-2 mt-2">

    <a href="{{ route('attendance.index', [
    'from' => now()->startOfWeek()->toDateString(),
    'to' => now()->endOfWeek()->toDateString()
  ]) }}" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-calendar-week"></i>
      هذا الأسبوع
    </a>

    <a href="{{ route('attendance.index', [
    'from' => now()->startOfMonth()->toDateString(),
    'to' => now()->endOfMonth()->toDateString()
  ]) }}" class="btn btn-sm btn-outline-success">
      <i class="bi bi-calendar-month"></i>
      هذا الشهر
    </a>

    <a href="{{ route('attendance.index', [
    'from' => now()->subMonths(3)->startOfMonth()->toDateString(),
    'to' => now()->endOfMonth()->toDateString()
  ]) }}" class="btn btn-sm btn-outline-dark">
      <i class="bi bi-calendar-range"></i>
      آخر 3 أشهر
    </a>

  </div>




  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-6 col-md-2">
          <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/كود">
        </div>

        <div class="col-6 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">الفرع (الكل)</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="employee_id" class="form-select">
            <option value="">الموظف (الكل)</option>
            @foreach($employees as $e)
              <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->full_name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            @foreach(['scheduled', 'present', 'late', 'absent', 'off', 'leave'] as $s)
              <option value="{{ $s }}" @selected(request('status') == $s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-6 col-md-2">
          <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>

        <div class="col-6 col-md-6 d-grid">
          <button class="btn btn-namaa fw-bold">
            <i class="bi bi-funnel"></i>
            تطبيق الفلتر
          </button>
        </div>

        <div class="col-6 col-md-6 d-grid">
          <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-x-circle"></i>
            تنظيف
          </a>
        </div>




      </div>
    </div>
  </form>

@endif

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>التاريخ</th>
            <th>الموظف</th>
            <th class="hide-mobile">الفرع</th>
            <th class="hide-mobile">دخول</th>
            <th class="hide-mobile">خروج</th>
            <th class="hide-mobile">تأخير</th>
            <th class="hide-mobile">ساعات</th>
            <th>حالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            <tr>
              <td class="fw-bold">{{ $r->work_date->format('Y-m-d') }}</td>
              <td>{{ $r->employee->full_name }}</td>
              <td class="hide-mobile">{{ $r->employee->branch->name ?? '-' }}</td>

              <td class="hide-mobile">{{ $r->check_in_at?->format('H:i') ?? '-' }}</td>
              <td class="hide-mobile">{{ $r->check_out_at?->format('H:i') ?? '-' }}</td>
              <td class="hide-mobile"><span class="badge bg-warning text-dark">{{ $r->late_minutes }} د</span></td>
              <td class="hide-mobile"><span class="badge bg-info text-dark">{{ round($r->worked_minutes / 60, 2) }} س</span>
              </td>
              <td>
                <span class="badge bg-{{ $r->status_color }}">
                  {{ $r->status_label }}
                </span>
              </td>

              <td class="text-end d-flex gap-1 justify-content-end flex-wrap">

                @if(auth()->user()?->hasPermission('mark_attendance'))
                  @if(!$r->check_in_at && $r->status != 'off')
                    <form method="POST" action="{{ route('attendance.checkin', $r) }}">
                      @csrf
                      <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i> دخول</button>
                    </form>
                  @endif

                  @if($r->check_in_at && !$r->check_out_at)
                    <form method="POST" action="{{ route('attendance.checkout', $r) }}">
                      @csrf
                      <button class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-left"></i> خروج</button>
                    </form>
                  @endif
                @endif


              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">لا يوجد سجلات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $records->links() }}
  </div>
@endsection