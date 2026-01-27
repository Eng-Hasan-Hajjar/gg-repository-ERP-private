@extends('layouts.app')
@section('title','المعلومات الإضافية')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="mb-0">المعلومات الإضافية</h5>
        <div class="text-muted">{{ $student->full_name }} — <code>{{ $student->university_id }}</code></div>
      </div>
      <a class="btn btn-outline-secondary" href="{{ route('students.show',$student) }}">رجوع</a>
    </div>

    <form method="POST" action="{{ route('students.extra.update',$student) }}">
      @csrf
      @method('PUT')

      @php($data = old('data', $student->extra->data ?? []))

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">العنوان</label>
          <input class="form-control" name="data[address]" value="{{ $data['address'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label">تاريخ الميلاد</label>
          <input type="date" class="form-control" name="data[birth_date]" value="{{ $data['birth_date'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label">الجنسية</label>
          <input class="form-control" name="data[nationality]" value="{{ $data['nationality'] ?? '' }}">
        </div>

        <div class="col-12">
          <label class="form-label">ملاحظات</label>
          <textarea class="form-control" rows="4" name="data[notes]">{{ $data['notes'] ?? '' }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="mt-3">
        <button class="btn btn-primary">حفظ المعلومات الإضافية</button>
      </div>
    </form>
  </div>
</div>
@endsection
