@extends('layouts.app')
@php($activeModule='attendance')
@section('title','طلب إجازة/إذن')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> طلب إجازة/إذن</h5>

    <form method="POST" action="{{ route('leaves.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الموظف</label>
          <select name="employee_id" class="form-select" required>
            @foreach($employees as $e)
              <option value="{{ $e->id }}" @selected(old('employee_id')==$e->id)>{{ $e->full_name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">النوع</label>
          <select name="type" class="form-select" required>
            <option value="leave" @selected(old('type')=='leave')>إجازة</option>
            <option value="permission" @selected(old('type')=='permission')>إذن</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">من</label>
          <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">إلى</label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">السبب</label>
          <textarea name="reason" rows="3" class="form-control">{{ old('reason') }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">إرسال</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection
