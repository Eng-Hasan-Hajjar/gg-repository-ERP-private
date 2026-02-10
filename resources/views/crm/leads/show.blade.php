@extends('layouts.app')
@section('title','CRM - تفاصيل العميل المحتمل')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">{{ $lead->full_name }}</h4>
<div class="text-muted small">
  المرحلة: <b>{{ $stage_ar }}</b>
  —
  حالة التسجيل: <b>{{ $registration_ar }}</b>
</div>
  </div>

  <div class="d-flex gap-2">
    <a class="btn btn-outline-dark" href="{{ route('leads.edit',$lead) }}">تعديل</a>

    @if($lead->registration_status === 'pending')
      <form method="POST" action="{{ route('leads.convert',$lead) }}">
        @csrf
        <button class="btn btn-success fw-bold">تحويل إلى طالب</button>
      </form>
    @endif
  </div>
</div>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <h6 class="fw-bold">الدبلومات</h6>
    @foreach($lead->diplomas as $d)
      <span class="badge bg-light text-dark border">{{ $d->name }}</span>
    @endforeach
    <hr>
    <div class="row g-2">
      <div class="col-md-4"><b>الهاتف:</b> {{ $lead->phone ?? '-' }}</div>
      <div class="col-md-4"><b>واتساب:</b> {{ $lead->whatsapp ?? '-' }}</div>
      <div class="col-md-4"><b>الفرع:</b> {{ $lead->branch->name ?? '-' }}</div>
      <div class="col-md-4"><b>السكن:</b> {{ $lead->residence ?? '-' }}</div>
      <div class="col-md-4"><b>العمر:</b> {{ $lead->age ?? '-' }}</div>
<div class="col-md-4"><b>المصدر:</b> {{ $source_ar }}</div>
      <div class="col-12"><b>الاحتياج:</b> {{ $lead->need ?? '-' }}</div>
      <div class="col-12"><b>ملاحظات:</b> {{ $lead->notes ?? '-' }}</div>
    </div>

    <div class="col-md-4">
  <b>مسؤول التواصل:</b> 
  {{ $lead->creator->name ?? $lead->creator->email ?? '-' }}
</div>
<div class="col-md-4"><b>العمل:</b> {{ $lead->job ?? '-' }}</div>
<div class="col-md-4"><b>البلد:</b> {{ $lead->country ?? '-' }}</div>
<div class="col-md-4"><b>المحافظة:</b> {{ $lead->province ?? '-' }}</div>
<div class="col-md-4"><b>الدراسة:</b> {{ $lead->study ?? '-' }}</div>

  </div>
</div>



{{-- Followups --}}
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h6 class="fw-bold mb-3">المتابعات</h6>

    <form class="row g-2 mb-3" method="POST" action="{{ route('leads.followups.store',$lead) }}">
      @csrf
      <div class="col-md-3">
        <input type="date" name="followup_date" class="form-control" value="{{ old('followup_date') }}">
      </div>
      <div class="col-md-3">
        <input name="result" class="form-control" placeholder="نتيجة المتابعة" value="{{ old('result') }}">
      </div>
      <div class="col-md-6">
        <input name="notes" class="form-control" placeholder="ملاحظات" value="{{ old('notes') }}">
      </div>
      <div class="col-12 d-grid">
        <button class="btn btn-primary fw-bold">إضافة متابعة</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead class="table-light">
          <tr><th>تاريخ</th><th>نتيجة</th><th>ملاحظات</th></tr>
        </thead>
        <tbody>
          @forelse($lead->followups as $f)
            <tr>
              <td>{{ $f->followup_date?->format('Y-m-d') ?? '-' }}</td>
              <td>{{ $f->result ?? '-' }}</td>
              <td>{{ $f->notes ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-muted text-center py-3">لا يوجد متابعات</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
