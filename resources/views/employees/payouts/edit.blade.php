@extends('layouts.app')
@section('title','تعديل مستحق')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1">تعديل مستحق — {{ $employee->full_name }}</h5>
        <div class="text-muted fw-semibold">#{{ $payout->id }} — <code>{{ $employee->code }}</code></div>
      </div>
      <a href="{{ route('employees.payouts.index',$employee) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('employees.payouts.update',[$employee,$payout]) }}">
      @csrf @method('PUT')

      <div class="row g-3">
        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">تاريخ المستحق</label>
          <input type="date" name="payout_date" class="form-control" required value="{{ old('payout_date', $payout->payout_date?->format('Y-m-d')) }}">
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">المبلغ</label>
          <input name="amount" class="form-control" required value="{{ old('amount', $payout->amount) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="{{ old('currency', $payout->currency) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">الحالة</label>
          <select name="status" class="form-select" required>
            <option value="pending" @selected(old('status', $payout->status)=='pending')>معلق</option>
            <option value="paid" @selected(old('status', $payout->status)=='paid')>مدفوع</option>
          </select>
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label fw-bold">مرجع/إيصال</label>
          <input name="reference" class="form-control" value="{{ old('reference', $payout->reference) }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control">{{ old('notes', $payout->notes) }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold"><i class="bi bi-save"></i> حفظ</button>
        <a href="{{ route('employees.payouts.index',$employee) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
      </div>
    </form>
  </div>
</div>
@endsection
