@extends('layouts.app')
@section('title','ملف الطالب')

@section('content')
<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <h4 class="fw-bold mb-1">{{ $student->full_name }}</h4>
    <div class="text-muted small">
      رقم جامعي: <code>{{ $student->university_id }}</code> —
      الفرع: <b>{{ $student->branch->name ?? '-' }}</b>
    </div>

    <div class="mt-2">
      <b>الدبلومات:</b>
      @foreach($student->diplomas as $d)
        <span class="badge bg-light text-dark border">{{ $d->name }}</span>
      @endforeach
    </div>
  </div>
</div>

@if($student->crmInfo)
  <div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
      <h6 class="fw-bold mb-2">بيانات CRM (الاستشارات)</h6>
      <div class="row g-2">
        <div class="col-md-4"><b>أول تواصل:</b> {{ $student->crmInfo->first_contact_date?->format('Y-m-d') ?? '-' }}</div>
        <div class="col-md-4"><b>السكن:</b> {{ $student->crmInfo->residence ?? '-' }}</div>
        <div class="col-md-4"><b>العمر:</b> {{ $student->crmInfo->age ?? '-' }}</div>
        <div class="col-md-4"><b>المصدر:</b> {{ $student->crmInfo->source }}</div>
        <div class="col-md-4"><b>المرحلة:</b> {{ $student->crmInfo->stage }}</div>
        <div class="col-12"><b>الاحتياج:</b> {{ $student->crmInfo->need ?? '-' }}</div>
      </div>
    </div>
  </div>
@endif

<div class="card shadow-sm border-0">
  <div class="card-body">
    <h6 class="fw-bold mb-2">الملف التفصيلي</h6>
    @if(!$student->profile)
      <div class="text-muted">لا يوجد ملف تفصيلي بعد.</div>
    @else
      <div class="row g-2">
        <div class="col-md-6"><b>الاسم بالعربي:</b> {{ $student->profile->arabic_full_name ?? '-' }}</div>
        <div class="col-md-3"><b>الجنسية:</b> {{ $student->profile->nationality ?? '-' }}</div>
        <div class="col-md-3"><b>تولد:</b> {{ $student->profile->birth_date?->format('Y-m-d') ?? '-' }}</div>
        <div class="col-md-6"><b>العنوان:</b> {{ $student->profile->address ?? '-' }}</div>
        <div class="col-md-6"><b>ملاحظات:</b> {{ $student->profile->notes ?? '-' }}</div>
      </div>
    @endif
  </div>
</div>
@endsection
