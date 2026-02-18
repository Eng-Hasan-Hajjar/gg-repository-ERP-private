@extends('layouts.app')
@section('title','درجات الامتحان')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">إدخال الدرجات — {{ $exam->title }}</h4>
    <div class="text-muted small">
      الفرع: {{ $exam->branch->name }} — الدبلومة: {{ $exam->diploma->name }} — الحد الأعلى: {{ $exam->max_score }}
    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.show',$exam) }}">
    <i class="bi bi-arrow-return-right"></i> رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/رقم جامعي">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-namaa fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('exams.results.update',$exam) }}">
  @csrf
  @method('PUT')

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th style="width:140px">الدرجة</th>
            <th style="width:160px">الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $i => $s)
            @php($r = $existing[$s->id] ?? null)
            <tr>
              <td class="fw-semibold">{{ $s->full_name }}</td>
              <td><code>{{ $s->university_id }}</code></td>

              <td>
                <input type="hidden" name="results[{{ $i }}][student_id]" value="{{ $s->id }}">
                <input type="number" step="0.01" name="results[{{ $i }}][score]" class="form-control"
                  value="{{ old("results.$i.score", $r->score ?? '') }}" placeholder="0..{{ $exam->max_score }}">
              </td>

              <td>
                <select name="results[{{ $i }}][status]" class="form-select">
                  @foreach(['not_set','passed','failed','absent','excused'] as $st)
                    <option value="{{ $st }}" @selected(old("results.$i.status", $r->status ?? 'not_set')==$st)>{{ $st }}</option>
                  @endforeach
                </select>
              </td>

              <td>
                <input name="results[{{ $i }}][notes]" class="form-control"
                  value="{{ old("results.$i.notes", $r->notes ?? '') }}" placeholder="اختياري">
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">لا يوجد طلاب مطابقون</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-3">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <button class="btn btn-primary fw-bold px-4">
        <i class="bi bi-save"></i> حفظ الدرجات
      </button>
    </div>
  </div>
</form>
@endsection
