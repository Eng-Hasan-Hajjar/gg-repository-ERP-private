@extends('layouts.app')
@section('title','ملف المدرب/الموظف')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">{{ $employee->full_name }}</h4>
    <div class="text-muted fw-semibold">
      كود: <code>{{ $employee->code }}</code>
      — النوع: <b>{{ $employee->type=='trainer'?'مدرب':'موظف' }}</b>
      — الفرع: <b>{{ $employee->branch->name ?? '-' }}</b>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('employees.edit',$employee) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
      <i class="bi bi-pencil"></i> تعديل
    </a>

    <a href="{{ route('employees.contracts.index',$employee) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-file-earmark-text"></i> العقود
    </a>

    <a href="{{ route('employees.payouts.index',$employee) }}" class="btn btn-primary rounded-pill px-4 fw-bold">
      <i class="bi bi-cash-coin"></i> المستحقات
    </a>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">البيانات الأساسية</h6>

        <div class="row g-2 small">
          <div class="col-6"><b>الهاتف:</b> {{ $employee->phone ?? '-' }}</div>
          <div class="col-6"><b>الإيميل:</b> {{ $employee->email ?? '-' }}</div>
          <div class="col-6"><b>المسمى:</b> {{ $employee->job_title ?? '-' }}</div>
          <div class="col-6">
            <b>الحالة:</b>
            <span class="badge bg-{{ $employee->status=='active'?'success':'secondary' }}">
              {{ $employee->status=='active'?'نشط':'غير نشط' }}
            </span>
          </div>

          <div class="col-12 mt-2">
            <b>الدبلومات:</b>
            @if($employee->diplomas->count())
              @foreach($employee->diplomas as $d)
                <span class="badge bg-light text-dark border">{{ $d->name }} ({{ $d->code }})</span>
              @endforeach
            @else
              -
            @endif
          </div>

          <div class="col-12 mt-2">
            <b>ملاحظات:</b> {{ $employee->notes ?? '-' }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">ملخص سريع</h6>


        <hr class="my-4">

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <h6 class="fw-bold mb-0">برنامج الدوام الأسبوعي</h6>

      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-sm btn-outline-primary"
           href="{{ route('attendance.calendar', ['employee_id'=>$employee->id, 'month'=> now()->format('Y-m')]) }}">
          <i class="bi bi-calendar3"></i> عرض التقويم لهذا الموظف
        </a>

        <a class="btn btn-sm btn-outline-dark"
           href="{{ route('attendance.index', ['employee_id'=>$employee->id]) }}">
          <i class="bi bi-funnel"></i> سجلات الدوام (فلترة)
        </a>
      </div>
    </div>

    <div class="row g-2 small">
      <div class="col-12 col-md-6">
        <b>نمط الدوام:</b>
        <span class="badge bg-{{ $employee->schedule_mode=='weekly'?'primary':'warning' }}">
          {{ $employee->schedule_mode=='weekly' ? 'أسبوعي ثابت' : 'Custom/مرن' }}
        </span>
      </div>
      <div class="col-12 col-md-6">
        <b>ساري من:</b> {{ $employee->schedule_effective_from ?? '-' }}
      </div>
    </div>

    <div class="table-responsive mt-3">
      <table class="table table-sm table-bordered align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>اليوم</th>
            <th>الشيفت</th>
            <th>الوقت</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody>
          @foreach($weekdays as $wd => $label)
            @php($row = $scheduleMap[$wd] ?? null)
            @php($shift = $row?->shift)
            <tr>
              <td class="fw-bold">{{ $label }}</td>
              <td>{{ $shift?->name ?? 'OFF' }}</td>
              <td>
                @if($shift)
                  {{ $shift->start_time }} - {{ $shift->end_time }}
                @else
                  -
                @endif
              </td>
              <td>
                @if($shift)
                  <span class="badge bg-success">دوام</span>
                @else
                  <span class="badge bg-secondary">عطلة</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Overrides --}}
    <hr class="my-3">
    <h6 class="fw-bold mb-2">استثناءات/تعديلات على الدوام (Overrides)</h6>
    @if($overrides->count())
      <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>التاريخ</th>
              <th>الشيفت</th>
              <th>الوقت</th>
              <th>السبب</th>
            </tr>
          </thead>
          <tbody>
            @foreach($overrides as $o)
              <tr>
                <td class="fw-bold">{{ $o->work_date->format('Y-m-d') }}</td>
                <td>{{ $o->shift?->name ?? 'OFF' }}</td>
                <td>
                  @if($o->shift)
                    {{ $o->shift->start_time }} - {{ $o->shift->end_time }}
                  @else
                    -
                  @endif
                </td>
                <td>{{ $o->reason ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-muted small">لا يوجد استثناءات ضمن الفترة الحالية.</div>
    @endif

  </div>
</div>



        <div class="row g-2 small">
          <div class="col-6"><b>عدد العقود:</b> {{ $employee->contracts->count() }}</div>
          <div class="col-6"><b>عدد المستحقات:</b> {{ $employee->payouts->count() }}</div>
          <div class="col-12">
            <b>آخر مستحق:</b>
            @php($last = $employee->payouts->sortByDesc('payout_date')->first())
            {{ $last ? ($last->payout_date->format('Y-m-d').' — '.$last->amount.' '.$last->currency.' ('.$last->status.')') : '-' }}
          </div>
        </div>

        <hr>

        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('employees.contracts.create',$employee) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> إضافة عقد
          </a>
          <a href="{{ route('employees.payouts.create',$employee) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-plus-circle"></i> إضافة مستحق
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
