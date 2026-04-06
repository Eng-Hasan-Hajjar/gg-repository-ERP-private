@extends('layouts.app')
@php($activeModule='attendance')
@section('title','تقارير الدوام')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">تقارير الدوام</h4>
    <div class="text-muted fw-semibold">ساعات/تأخير/غياب حسب فترة</div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    @if(auth()->user()?->hasPermission('export_attendance_reports'))
      <a class="btn btn-outline-success rounded-pill px-4 fw-bold"
         href="{{ route('attendance.reports.exportExcel', request()->all()) }}">
        <i class="bi bi-file-earmark-excel"></i> Excel
      </a>
      <a class="btn btn-outline-danger rounded-pill px-4 fw-bold"
         href="{{ route('attendance.reports.exportPdf', request()->all()) }}">
        <i class="bi bi-file-earmark-pdf"></i> PDF
      </a>
    @endif
    <a href="{{ route('attendance.calendar') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
      <i class="bi bi-calendar3"></i> التقويم
    </a>
  </div>
</div>

{{-- ── أزرار الفترة السريعة ── --}}
<div class="d-flex gap-2 mb-3 flex-wrap">
  <a href="{{ route('attendance.reports', array_merge(request()->except('period'), ['period'=>'daily'])) }}"
     class="btn btn-sm {{ request('period')=='daily' ? 'btn-primary' : 'btn-outline-secondary' }}">
    <i class="bi bi-calendar-day"></i> اليوم
  </a>
  <a href="{{ route('attendance.reports', array_merge(request()->except('period'), ['period'=>'weekly'])) }}"
     class="btn btn-sm {{ request('period')=='weekly' ? 'btn-primary' : 'btn-outline-secondary' }}">
    <i class="bi bi-calendar-week"></i> هذا الأسبوع
  </a>
  <a href="{{ route('attendance.reports', array_merge(request()->except('period'), ['period'=>'monthly'])) }}"
     class="btn btn-sm {{ request('period')=='monthly' ? 'btn-primary' : 'btn-outline-secondary' }}">
    <i class="bi bi-calendar-month"></i> هذا الشهر
  </a>
</div>

{{-- ── فورم الفلتر (يحتوي على كل الحقول) ── --}}
<form class="card border-0 shadow-sm mb-3" method="GET" action="{{ route('attendance.reports') }}">
  <div class="card-body">
    <div class="row g-2">

      <div class="col-6 col-md-2">
        <label class="form-label fw-bold">من</label>
        <input type="date" name="from" value="{{ request('from', $from) }}" class="form-control">
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label fw-bold">إلى</label>
        <input type="date" name="to" value="{{ request('to', $to) }}" class="form-control">
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label fw-bold">الفرع</label>
        <select name="branch_id" class="form-select">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-3">
        <label class="form-label fw-bold">الموظف</label>
        <select name="employee_id" class="form-select">
          <option value="">كل الموظفين</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->full_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-3 d-grid align-self-end">
        <button class="btn btn-namaa fw-bold">
          <i class="bi bi-funnel"></i> تطبيق
        </button>
      </div>

    </div>
  </div>
</form>

{{-- ── الجدول ── --}}
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الموظف</th>
          <th>الفرع</th>
          <th>أيام حضور</th>
          <th>أيام غياب</th>
          <th>أيام إجازة</th>
          <th>تأخير (د)</th>
          <th>استراحة (د)</th>
          <th>ساعات عمل</th>
          <th>صافي ساعات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td class="fw-bold">{{ $r->employee->full_name ?? '-' }}</td>
            <td>{{ $r->employee->branch->name ?? '-' }}</td>
            <td><span class="badge bg-success">{{ (int)$r->present_days }}</span></td>
            <td><span class="badge bg-dark">{{ (int)$r->absent_days }}</span></td>
            <td><span class="badge bg-warning text-dark">{{ (int)$r->leave_days }}</span></td>
            <td><span class="badge bg-danger">{{ (int)$r->late_minutes }}</span></td>
            <td><span class="badge bg-secondary">{{ (int)$r->break_minutes }}</span></td>
            <td class="fw-bold">{{ round($r->worked_minutes / 60, 2) }}</td>
            <td><span class="badge bg-info text-dark">{{ round(max(0, $r->worked_minutes - $r->break_minutes) / 60, 2) }}</span></td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection