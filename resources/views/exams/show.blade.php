@extends('layouts.app')
@section('title','تفاصيل الامتحان')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="fw-bold mb-0">{{ $exam->title }}</h4>
    <div class="text-muted small">
      {{ $exam->exam_date?->format('Y-m-d') ?? '-' }} — {{ $exam->type }} — كود: {{ $exam->code ?? '-' }}
    </div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-outline-dark fw-bold rounded-pill px-4" href="{{ route('exams.components.index',$exam) }}">
  <i class="bi bi-sliders"></i> مكونات الامتحان
</a>


<a class="btn btn-outline-dark fw-bold rounded-pill px-4"
   href="{{ route('exams.students.edit',$exam) }}">
  <i class="bi bi-people"></i> طلاب الامتحان
</a>

<a class="btn btn-namaa fw-bold rounded-pill px-4" href="{{ route('exams.marks.edit',$exam) }}">
  <i class="bi bi-pencil-square"></i> إدخال الدرجات
</a>

    <a class="btn btn-namaa fw-bold rounded-pill px-4" href="{{ route('exams.results.edit',$exam) }}">
      <i class="bi bi-pencil-square"></i> إدخال/تعديل الدرجات
    </a>
    <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.index') }}">
      <i class="bi bi-arrow-return-right"></i> رجوع
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-md-3"><b>الدبلومة:</b> {{ $exam->diploma->name ?? '-' }}</div>
      <div class="col-md-3"><b>الفرع:</b> {{ $exam->branch->name ?? '-' }}</div>
      <div class="col-md-3"><b>المدرب:</b> {{ $exam->trainer->full_name ?? '-' }}</div>
      <div class="col-md-3"><b>الحد الأعلى:</b> {{ $exam->max_score }}</div>
    </div>
    @if($exam->notes)
      <hr>
      <div class="text-muted">{{ $exam->notes }}</div>
    @endif
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h6 class="fw-bold mb-3">النتائج المدخلة</h6>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th>الدرجة</th>
            <th>الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($exam->results as $r)
            <tr>
              <td class="fw-semibold">
                <a href="{{ route('students.show',$r->student) }}" class="text-decoration-none">
                  {{ $r->student->full_name }}
                </a>
              </td>
              <td><code>{{ $r->student->university_id }}</code></td>
              <td>{{ $r->score ?? '-' }}</td>
              <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
              <td>{{ $r->notes ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">لا يوجد نتائج بعد</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
