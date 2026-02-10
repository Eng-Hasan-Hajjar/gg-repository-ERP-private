@extends('layouts.app')
@section('title','CRM - العملاء المحتملين')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">قسم الاستشارات والمبيعات (CRM)</h4>
    <div class="text-muted small">Leads + Followups + Convert to Student</div>
  </div>
  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('leads.create') }}">
    <i class="bi bi-person-plus"></i> عميل محتمل جديد
  </a>
</div>

<form class="card card-body shadow-sm border-0 mb-3" method="GET" action="{{ route('leads.index') }}">
  <div class="row g-2">
    <div class="col-md-4">
      <input class="form-control" name="search" value="{{ request('search') }}" placeholder="بحث: الاسم / الهاتف / واتساب">
    </div>

    <div class="col-md-2">
      <select class="form-select" name="branch_id">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <select class="form-select" name="diploma_id">
        <option value="">كل الدبلومات</option>
        @foreach($diplomas as $d)
          <option value="{{ $d->id }}" @selected(request('diploma_id')==$d->id)>{{ $d->name }} ({{ $d->code }})</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
     <select class="form-select" name="stage">
        <option value="">المرحلة</option>
        @foreach($stageOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('stage')==$key)>
            {{ $label }}
          </option>
        @endforeach
      </select>

    </div>

    <div class="col-md-2">
      <select class="form-select" name="registration_status">
        <option value="">حالة التسجيل</option>
        @foreach($registrationOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('registration_status')==$key)>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-12 d-grid">
      <button class="btn btn-dark fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الاسم</th>
          <th>هاتف</th>
          <th>الفرع</th>
          <th>الدبلومات</th>
          <th>المرحلة</th>
          <th>الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leads as $l)
          <tr>
            <td>{{ $l->id }}</td>
            <td class="fw-bold">{{ $l->full_name }}</td>
            <td>{{ $l->phone ?? '-' }}</td>
            <td>{{ $l->branch->name ?? '-' }}</td>
            <td>
              @foreach($l->diplomas as $d)
                <span class="badge bg-light text-dark border">{{ $d->name }}</span>
              @endforeach
            </td>
            <td><span class="badge bg-info">{{ $l->stage_ar }}</span></td>

            <td>
              <span class="badge bg-{{ $l->registration_status==='pending'?'warning':($l->registration_status==='converted'?'success':'secondary') }}">
                {{ $l->registration_ar }}
              </span>
            </td>
            
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('leads.show',$l) }}">عرض</a>
              <a class="btn btn-sm btn-outline-dark" href="{{ route('leads.edit',$l) }}">تعديل</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $leads->links() }}</div>
@endsection
