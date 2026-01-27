@extends('layouts.app')
@php($activeModule='finance')
@section('title','ملف الصندوق')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">{{ $cashbox->name }}</h4>
    <div class="text-muted fw-semibold">
      كود: <code>{{ $cashbox->code }}</code>
      — فرع: <b>{{ $cashbox->branch->name ?? '-' }}</b>
      — عملة: <b>{{ $cashbox->currency }}</b>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('cashboxes.transactions.index',$cashbox) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-arrow-left-right"></i> الحركات
    </a>
    <a href="{{ route('cashboxes.edit',$cashbox) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
      <i class="bi bi-pencil"></i> تعديل
    </a>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">البيانات</h6>
        <div class="row g-2 small">
          <div class="col-6"><b>الحالة:</b>
            <span class="badge bg-{{ $cashbox->status=='active'?'success':'secondary' }}">
              {{ $cashbox->status=='active'?'نشط':'غير نشط' }}
            </span>
          </div>
          <div class="col-6"><b>الرصيد الافتتاحي:</b> {{ $cashbox->opening_balance }} {{ $cashbox->currency }}</div>
          <div class="col-12 mt-2"><b>الرصيد الحالي (posted):</b> {{ $cashbox->current_balance }} {{ $cashbox->currency }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">اختصارات</h6>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-sm btn-outline-primary" href="{{ route('cashboxes.transactions.create',$cashbox) }}">
            <i class="bi bi-plus-circle"></i> إضافة حركة
          </a>
          <a class="btn btn-sm btn-outline-success" href="{{ route('cashboxes.transactions.index',$cashbox) }}">
            <i class="bi bi-list-check"></i> عرض كل الحركات
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
