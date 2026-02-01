@extends('layouts.app')
@php($activeModule='attendance')
@section('title','طلبات الإجازات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0 fw-bold">طلبات الإجازات/الأذونات</h4>
    <div class="text-muted fw-semibold">مراجعة الطلبات والموافقة/الرفض</div>
  </div>
  <a href="{{ route('leaves.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> طلب جديد
  </a>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <select name="employee_id" class="form-select">
          <option value="">الموظف (الكل)</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(request('employee_id')==$e->id)>{{ $e->full_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="leave" @selected(request('type')=='leave')>إجازة</option>
          <option value="permission" @selected(request('type')=='permission')>إذن</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          @foreach(['pending'=>'معلق','approved'=>'مقبول','rejected'=>'مرفوض'] as $k=>$v)
            <option value="{{ $k }}" @selected(request('status')==$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
      </div>
      <div class="col-6 col-md-2">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
      </div>

      <div class="col-6 col-md-1 d-grid">
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
          <th>#</th>
          <th>الموظف</th>
          <th>النوع</th>
          <th>من</th>
          <th>إلى</th>
          <th>الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leaves as $l)
          <tr>
            <td>{{ $l->id }}</td>
            <td class="fw-bold">{{ $l->employee->full_name }}</td>
            <td>{{ $l->type=='leave'?'إجازة':'إذن' }}</td>
            <td>{{ $l->start_date->format('Y-m-d') }}</td>
            <td>{{ $l->end_date?->format('Y-m-d') ?? '-' }}</td>
            <td>
              <span class="badge bg-{{ $l->status=='approved'?'success':($l->status=='rejected'?'danger':'secondary') }}">
                {{ $l->status=='pending'?'معلّق':($l->status=='approved'?'مقبول':'مرفوض') }}
              </span>
            </td>
            <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('leaves.show',$l) }}"><i class="bi bi-eye"></i> عرض</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد طلبات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $leaves->links() }}</div>
@endsection
