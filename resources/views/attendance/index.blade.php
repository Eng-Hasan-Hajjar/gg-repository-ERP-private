@extends('layouts.app')
@php($activeModule='attendance')
@section('title','الدوام والحضور')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">الدوام والحضور</h4>
    <div class="text-muted fw-semibold">سجل الدخول/الخروج + تأخير + ساعات</div>
  </div>
</div>


<form method="POST" action="{{ route('attendance.generateWeek') }}" class="d-flex gap-2 flex-wrap mb-3">
  @csrf
  <input type="date" name="week_start" class="form-control" required value="{{ now()->startOfWeek()->format('Y-m-d') }}">
  <button class="btn btn-namaa fw-bold">
    <i class="bi bi-magic"></i> توليد سجلات الأسبوع
  </button>
</form>




<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/كود">
      </div>

      <div class="col-6 col-md-2">
        <select name="branch_id" class="form-select">
          <option value="">الفرع (الكل)</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="employee_id" class="form-select">
          <option value="">الموظف (الكل)</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(request('employee_id')==$e->id)>{{ $e->full_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          @foreach(['scheduled','present','late','absent','off','leave'] as $s)
            <option value="{{ $s }}" @selected(request('status')==$s)>{{ $s }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-1">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
      </div>
      <div class="col-6 col-md-1">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
      </div>

      <div class="col-12 col-md-12 d-grid">
        <button class="btn btn-dark fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>التاريخ</th>
          <th>الموظف</th>
          <th class="hide-mobile">الفرع</th>
          <th class="hide-mobile">الشيفت</th>
          <th class="hide-mobile">دخول</th>
          <th class="hide-mobile">خروج</th>
          <th class="hide-mobile">تأخير</th>
          <th class="hide-mobile">ساعات</th>
          <th >حالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($records as $r)
          <tr>
            <td class="fw-bold">{{ $r->work_date->format('Y-m-d') }}</td>
            <td>{{ $r->employee->full_name }}</td>
            <td class="hide-mobile">{{ $r->employee->branch->name ?? '-' }}</td>
            <td class="hide-mobile">
              @if($r->shift)
                <span class="badge bg-light text-dark border">
                  {{ $r->shift->name }} ({{ $r->shift->start_time }}-{{ $r->shift->end_time }})
                </span>
              @else
                -
              @endif
            </td>
            <td class="hide-mobile">{{ $r->check_in_at?->format('H:i') ?? '-' }}</td>
            <td class="hide-mobile">{{ $r->check_out_at?->format('H:i') ?? '-' }}</td>
            <td class="hide-mobile"><span class="badge bg-warning text-dark">{{ $r->late_minutes }} د</span></td>
            <td class="hide-mobile"><span class="badge bg-info text-dark">{{ round($r->worked_minutes/60,2) }} س</span></td>
            <td>
              <span class="badge bg-{{ in_array($r->status,['late'])?'danger':(in_array($r->status,['present'])?'success':'secondary') }}">
                {{ $r->status }}
              </span>
            </td>

            <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
              @if(!$r->check_in_at && $r->status!='off')
                <form method="POST" action="{{ route('attendance.checkin',$r) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-in-right"></i> دخول</button>
                </form>
              @endif

              @if($r->check_in_at && !$r->check_out_at)
                <form method="POST" action="{{ route('attendance.checkout',$r) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-left"></i> خروج</button>
                </form>
              @endif

              <a class="btn btn-sm btn-outline-dark" href="{{ route('attendance.edit',$r) }}">
                <i class="bi bi-pencil"></i> تعديل
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="10" class="text-center text-muted py-4">لا يوجد سجلات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $records->links() }}
</div>
@endsection
