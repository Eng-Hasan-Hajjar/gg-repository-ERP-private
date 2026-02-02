@extends('layouts.app')
@section('title','إدخال درجات المكونات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">درجات المكونات — {{ $exam->title }}</h4>
    <div class="text-muted small">
      فرع: {{ $exam->branch->name }} — دبلومة: {{ $exam->diploma->name }}
    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.show',$exam) }}">
    <i class="bi bi-arrow-return-right"></i> رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث طالب">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-dark fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="{{ route('exams.marks.update',$exam) }}">
  @csrf
  @method('PUT')

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            @foreach($exam->components as $comp)
              <th style="min-width:160px">
                {{ $comp->title }}
                <div class="small text-muted">Max: {{ $comp->max_score }} | W: {{ $comp->weight }}</div>
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse($students as $i => $s)
            @php($stuRes = $existing[$s->id] ?? collect())
            <tr>
              <td class="fw-semibold">{{ $s->full_name }}</td>
              <td><code>{{ $s->university_id }}</code></td>

              <input type="hidden" name="rows[{{ $i }}][student_id]" value="{{ $s->id }}">

              @foreach($exam->components as $j => $comp)
                @php($r = $stuRes->firstWhere('exam_component_id', $comp->id))
                <td>
                  <input type="hidden" name="rows[{{ $i }}][components][{{ $j }}][component_id]" value="{{ $comp->id }}">

                  <input type="number" step="0.01"
                    name="rows[{{ $i }}][components][{{ $j }}][score]"
                    value="{{ old("rows.$i.components.$j.score", $r->score ?? '') }}"
                    class="form-control mb-1"
                    placeholder="0..{{ $comp->max_score }}">

                  <input
                    name="rows[{{ $i }}][components][{{ $j }}][notes]"
                    value="{{ old("rows.$i.components.$j.notes", $r->notes ?? '') }}"
                    class="form-control"
                    placeholder="ملاحظة (اختياري)">
                </td>
              @endforeach
            </tr>
          @empty
            <tr><td colspan="{{ 2 + $exam->components->count() }}" class="text-center text-muted py-4">لا يوجد طلاب</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-3">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <button class="btn btn-primary fw-bold px-4">
        <i class="bi bi-save"></i> حفظ + حساب المحصلة
      </button>
    </div>
  </div>
</form>
@endsection
