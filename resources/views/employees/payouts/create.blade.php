@extends('layouts.app')
@section('title','إضافة مستحق')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1">إضافة مستحق — {{ $employee->full_name }}</h5>
        <div class="text-muted fw-semibold">كود: <code>{{ $employee->code }}</code></div>
      </div>
      <a href="{{ route('employees.payouts.index',$employee) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('employees.payouts.store',$employee) }}">
      @csrf

      <div class="row g-3">
        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">تاريخ المستحق</label>
          <input type="date" name="payout_date" class="form-control" required value="{{ old('payout_date', now()->format('Y-m-d')) }}">
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">المبلغ</label>
          <input name="amount" class="form-control" required value="{{ old('amount') }}" placeholder="مثال: 150">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="{{ old('currency','USD') }}" placeholder="USD">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">الحالة</label>
          <select name="status" class="form-select" required>
            <option value="pending" @selected(old('status','pending')=='pending')>معلق</option>
            <option value="paid" @selected(old('status')=='paid')>مدفوع</option>
          </select>
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label fw-bold">مرجع/إيصال</label>
          <input name="reference" class="form-control" value="{{ old('reference') }}" placeholder="رقم إيصال">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control" placeholder="تفاصيل المستحق...">{{ old('notes') }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-check2-circle"></i> حفظ
        </button>
        <a href="{{ route('employees.payouts.index',$employee) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
      </div>
    </form>
  </div>
</div>
@endsection
