@extends('layouts.app')
@php($activeModule='attendance')
@section('title','تفاصيل طلب')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
  <div>
    <h4 class="mb-1 fw-bold">طلب #{{ $leave->id }}</h4>
    <div class="text-muted fw-semibold">
      {{ $leave->employee->full_name }} — {{ $leave->type=='leave'?'إجازة':'إذن' }}
      — من {{ $leave->start_date->format('Y-m-d') }}
      إلى {{ $leave->end_date?->format('Y-m-d') ?? $leave->start_date->format('Y-m-d') }}
    </div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4"><b>الحالة:</b>
        <span class="badge bg-{{ $leave->status=='approved'?'success':($leave->status=='rejected'?'danger':'secondary') }}">
          {{ $leave->status }}
        </span>
      </div>
      <div class="col-12 col-md-8"><b>السبب:</b> {{ $leave->reason ?? '-' }}</div>

      <div class="col-12 mt-2"><b>ملاحظة الأدمن:</b> {{ $leave->admin_note ?? '-' }}</div>
    </div>

    @if($leave->status === 'pending')
      <hr>
      <div class="row g-2">
        <div class="col-12 col-lg-6">
          <form method="POST" action="{{ route('leaves.approve',$leave) }}">
            @csrf
            <label class="form-label fw-bold">ملاحظة (اختياري)</label>
            <textarea name="admin_note" rows="2" class="form-control mb-2"></textarea>
            <button class="btn btn-success rounded-pill px-4 fw-bold">
              <i class="bi bi-check2-circle"></i> موافقة
            </button>
          </form>
        </div>

        <div class="col-12 col-lg-6">
          <form method="POST" action="{{ route('leaves.reject',$leave) }}">
            @csrf
            <label class="form-label fw-bold">سبب الرفض (إلزامي)</label>
            <textarea name="admin_note" rows="2" class="form-control mb-2" required></textarea>
            <button class="btn btn-danger rounded-pill px-4 fw-bold">
              <i class="bi bi-x-circle"></i> رفض
            </button>
          </form>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
