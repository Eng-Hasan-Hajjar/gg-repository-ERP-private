@extends('layouts.app')
@section('title','مكونات الامتحان')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">مكونات الامتحان — {{ $exam->title }}</h4>
    <div class="text-muted small">مجموع الأوزان الحالي: <b>{{ $totalWeight }}</b> (يفضل = 100)</div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('exams.show',$exam) }}">
    <i class="bi bi-arrow-return-right"></i> رجوع
  </a>
</div>

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <form method="POST" action="{{ route('exams.components.store',$exam) }}">
      @csrf
      <div class="row g-2">
        <div class="col-md-4">
          <label class="form-label fw-bold">اسم المكوّن</label>
          <input name="title" class="form-control" required placeholder="مثال: عملي 1 / مشروع / مذاكرة 2">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Key (اختياري)</label>
          <input name="key" class="form-control" placeholder="practical1">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Max</label>
          <input name="max_score" type="number" step="0.01" class="form-control" value="100">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Weight</label>
          <input name="weight" type="number" step="0.01" class="form-control" value="0">
        </div>
        <div class="col-md-1">
          <label class="form-label fw-bold">ترتيب</label>
          <input name="sort_order" type="number" class="form-control" value="0">
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_required" value="1" id="req">
            <label class="form-check-label fw-bold" for="req">مطلوب</label>
          </div>
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary fw-bold">إضافة مكوّن</button>
        </div>
      </div>
    </form>

    @if($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الاسم</th>
          <th>Max</th>
          <th>Weight</th>
          <th>مطلوب؟</th>
          <th>ترتيب</th>
          <th class="text-end">إجراء</th>
        </tr>
      </thead>
      <tbody>
        @forelse($exam->components as $c)
          <tr>
            <td class="fw-bold">{{ $c->title }}</td>
            <td>{{ $c->max_score }}</td>
            <td>{{ $c->weight }}</td>
            <td>{!! $c->is_required ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
            <td>{{ $c->sort_order }}</td>
            <td class="text-end">
              <form method="POST" action="{{ route('exams.components.destroy',[$exam,$c]) }}" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف المكوّن؟')">
                  <i class="bi bi-trash"></i> حذف
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">لا يوجد مكونات بعد</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
