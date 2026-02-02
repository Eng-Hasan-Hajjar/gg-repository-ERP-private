@extends('layouts.app')
@php($activeModule='exams')
@section('title','الامتحانات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">الامتحانات</h4>
    <div class="text-muted small">فلترة حسب الفرع/الدبلومة/المدرب/الفترة</div>
  </div>
  <a class="btn btn-primary rounded-pill fw-bold px-4" href="{{ route('exams.create') }}">
    <i class="bi bi-plus-circle"></i> امتحان جديد
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('exams.index') }}">
  <div class="row g-2">
    <div class="col-12 col-md-3">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/كود">
    </div>

    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="diploma_id" class="form-select">
        <option value="">كل الدبلومات</option>
        @foreach($diplomas as $d)
          <option value="{{ $d->id }}" @selected(request('diploma_id')==$d->id)>{{ $d->name }} ({{ $d->code }})</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="trainer_id" class="form-select">
        <option value="">المدرب (الكل)</option>
        @foreach($trainers as $t)
          <option value="{{ $t->id }}" @selected(request('trainer_id')==$t->id)>{{ $t->full_name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-1">
      <input type="date" name="from" value="{{ request('from') }}" class="form-control">
    </div>
    <div class="col-6 col-md-1">
      <input type="date" name="to" value="{{ request('to') }}" class="form-control">
    </div>

    <div class="col-12 col-md-1 d-grid">
      <button class="btn btn-dark fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>التاريخ</th>
          <th>الامتحان</th>
          <th>الدبلومة</th>
          <th>الفرع</th>
          <th>المدرب</th>
          <th>الحد الأعلى</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($exams as $e)
          <tr>
            <td class="fw-bold">{{ $e->exam_date?->format('Y-m-d') ?? '-' }}</td>
            <td>
              <div class="fw-bold">{{ $e->title }}</div>
              <div class="small text-muted">{{ $e->code ?? '-' }} — {{ $e->type }}</div>
            </td>
            <td>{{ $e->diploma->name ?? '-' }}</td>
            <td>{{ $e->branch->name ?? '-' }}</td>
            <td>{{ $e->trainer->full_name ?? '-' }}</td>
            <td>{{ $e->max_score }}</td>
            <td class="text-end">
              @php($studentId = request('student_id'))

<a class="btn btn-sm btn-outline-primary" href="{{ route('exams.show',$e) }}">
  <i class="bi bi-eye"></i> عرض
</a>

<a class="btn btn-sm btn-outline-dark" href="{{ route('exams.edit',$e) }}">
  <i class="bi bi-pencil"></i> تعديل
</a>

@if($studentId)
  <a class="btn btn-sm btn-dark"
     href="{{ route('exams.marks.edit', $e).'?student_id='.$studentId }}">
     إدخال علامات هذا الطالب
  </a>
@else
  <a class="btn btn-sm btn-dark" href="{{ route('exams.marks.edit',$e) }}">
     إدخال الدرجات (جميع الطلاب)
  </a>
@endif

            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد امتحانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $exams->links() }}
</div>
@endsection
