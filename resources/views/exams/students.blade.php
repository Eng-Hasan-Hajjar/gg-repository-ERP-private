@extends('layouts.app')
@section('title','طلاب الامتحان')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">طلاب الامتحان — {{ $exam->title }}</h4>
    <div class="text-muted small">
      فرع: {{ $exam->branch->name }} — دبلومة: {{ $exam->diploma->name }}
    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.show',$exam) }}">
    رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/رقم جامعي">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-dark fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('exams.students.update',$exam) }}">
  @csrf
  @method('PUT')

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">اختيار</th>
            <th>الطالب</th>
            <th>رقم جامعي</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $s)
            <tr>
              <td>
                <input type="checkbox" name="student_ids[]"
                       value="{{ $s->id }}"
                       @checked(in_array($s->id, $selectedIds))>
              </td>
              <td class="fw-semibold">{{ $s->full_name }}</td>
              <td><code>{{ $s->university_id }}</code></td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted py-4">لا يوجد طلاب ضمن هذا الفرع/الدبلومة</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-3">
      <button class="btn btn-primary fw-bold px-4">
        حفظ الطلاب المنتسبين
      </button>
    </div>
  </div>
</form>
@endsection
