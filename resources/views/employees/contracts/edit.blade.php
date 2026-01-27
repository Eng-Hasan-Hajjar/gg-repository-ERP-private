@extends('layouts.app')
@section('title','تعديل عقد')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil"></i> تعديل عقد — {{ $employee->full_name }}</h5>
        <div class="text-muted fw-semibold">#{{ $contract->id }} — <code>{{ $employee->code }}</code></div>
      </div>
      <a href="{{ route('employees.contracts.index',$employee) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('employees.contracts.update',[$employee,$contract]) }}">
      @csrf @method('PUT')

      <div class="row g-3">
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ البداية</label>
          <input type="date" name="start_date" class="form-control" required
                 value="{{ old('start_date', $contract->start_date?->format('Y-m-d')) }}">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ النهاية</label>
          <input type="date" name="end_date" class="form-control"
                 value="{{ old('end_date', $contract->end_date?->format('Y-m-d')) }}">
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">نوع العقد</label>
          <select name="contract_type" class="form-select" required>
            @foreach(['full_time','part_time','freelance','hourly'] as $t)
              <option value="{{ $t }}" @selected(old('contract_type', $contract->contract_type)==$t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="{{ old('currency', $contract->currency) }}">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">راتب ثابت</label>
          <input name="salary_amount" class="form-control" value="{{ old('salary_amount', $contract->salary_amount) }}">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">أجر ساعة</label>
          <input name="hour_rate" class="form-control" value="{{ old('hour_rate', $contract->hour_rate) }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control">{{ old('notes', $contract->notes) }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold"><i class="bi bi-save"></i> حفظ</button>
        <a href="{{ route('employees.contracts.index',$employee) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
      </div>
    </form>
  </div>
</div>
@endsection
