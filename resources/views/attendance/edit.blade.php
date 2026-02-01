@extends('layouts.app')
@php($activeModule='attendance')
@section('title','تعديل سجل الدوام')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-1">تعديل سجل الدوام</h5>
    <div class="text-muted fw-semibold mb-3">
      {{ $record->employee->full_name }} — التاريخ: <b>{{ $record->work_date->format('Y-m-d') }}</b>
    </div>

    <form method="POST" action="{{ route('attendance.update',$record) }}">
      @csrf @method('PUT')

      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الشيفت</label>
          <select name="work_shift_id" class="form-select">
            <option value="">—</option>
            @foreach($shifts as $s)
              <option value="{{ $s->id }}" @selected(old('work_shift_id',$record->work_shift_id)==$s->id)>
                {{ $s->name }} ({{ $s->start_time }}-{{ $s->end_time }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-4">
          <label class="form-label fw-bold">توقيت الدخول</label>
          <input type="datetime-local" name="check_in_at" class="form-control"
                 value="{{ old('check_in_at', $record->check_in_at?->format('Y-m-d\TH:i') ?? '') }}">
        </div>

        <div class="col-6 col-md-4">
          <label class="form-label fw-bold">توقيت الخروج</label>
          <input type="datetime-local" name="check_out_at" class="form-control"
                 value="{{ old('check_out_at', $record->check_out_at?->format('Y-m-d\TH:i') ?? '') }}">
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الحالة</label>
          <select name="status" class="form-select" required>
            @foreach(['scheduled','present','late','absent','off','leave'] as $s)
              <option value="{{ $s }}" @selected(old('status',$record->status)==$s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control">{{ old('notes',$record->notes) }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection
