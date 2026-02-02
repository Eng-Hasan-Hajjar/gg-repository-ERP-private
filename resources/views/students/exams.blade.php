@extends('layouts.app')
@section('title','امتحانات الطالب')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">سجل الامتحانات — {{ $student->full_name }}</h4>
    <div class="text-muted small">رقم جامعي: <code>{{ $student->university_id }}</code></div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('students.show',$student) }}">
    <i class="bi bi-arrow-return-right"></i> العودة لملف الطالب
  </a>
</div>

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
          <th>الدرجة</th>
          <th>الحالة</th>
        </tr>
      </thead>
      <tbody>
        @forelse($results as $r)
          <tr>
            <td>{{ $r->exam->exam_date?->format('Y-m-d') ?? '-' }}</td>
            <td>
              <a class="text-decoration-none fw-bold" href="{{ route('exams.show',$r->exam) }}">
                {{ $r->exam->title }}
              </a>
              <div class="small text-muted">{{ $r->exam->type }} — {{ $r->exam->code ?? '-' }}</div>
            </td>
            <td>{{ $r->exam->diploma->name ?? '-' }}</td>
            <td>{{ $r->exam->branch->name ?? '-' }}</td>
            <td>{{ $r->exam->trainer->full_name ?? '-' }}</td>
            <td>{{ $r->score ?? '-' }} / {{ $r->exam->max_score }}</td>
            <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد نتائج بعد</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
