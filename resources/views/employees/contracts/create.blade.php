@extends('layouts.app')
@section('title','إضافة عقد')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة عقد — {{ $employee->full_name }}</h5>

    <form method="POST" action="{{ route('employees.contracts.store',$employee) }}">
      @csrf
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ البداية</label>
          <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ النهاية</label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">نوع العقد</label>
          <select name="contract_type" class="form-select" required>
            @foreach(['full_time','part_time','freelance','hourly'] as $t)
              <option value="{{ $t }}" @selected(old('contract_type')==$t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="{{ old('currency','USD') }}">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">راتب ثابت</label>
          <input name="salary_amount" class="form-control" value="{{ old('salary_amount') }}">
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">أجر ساعة</label>
          <input name="hour_rate" class="form-control" value="{{ old('hour_rate') }}">
        </div>
        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
        <a href="{{ route('employees.contracts.index',$employee) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection
