@extends('layouts.app')
@section('title','عرض أصل')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">{{ $asset->name }}</h4>
    <div class="text-muted">
      التاغ: <code>{{ $asset->asset_tag }}</code>
      — الحالة:
      <span class="badge {{ $asset->condition_badge_class }}">{{ $asset->condition_label }}</span>
    </div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
       @if(auth()->user()->hasPermission('edit_assets'))
    <a href="{{ route('assets.edit',$asset) }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
      <i class="bi bi-pencil"></i> تعديل
    </a>
    @endif
    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">
      رجوع
    </a>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-7">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">تفاصيل الأصل</h6>

        <div class="row g-2 small">
          <div class="col-6"><b>التصنيف:</b> {{ $asset->category->name ?? '-' }}</div>
          <div class="col-6"><b>الفرع:</b> {{ $asset->branch->name ?? '-' }}</div>
          <div class="col-6"><b>السيريال:</b> {{ $asset->serial_number ?? '-' }}</div>
          <div class="col-6"><b>الموقع:</b> {{ $asset->location ?? '-' }}</div>
          <div class="col-6"><b>تاريخ الشراء:</b> {{ $asset->purchase_date?->format('Y-m-d') ?? '-' }}</div>
          <div class="col-6"><b>التكلفة:</b> {{ $asset->purchase_cost ?? '-' }} {{ $asset->currency }}</div>
          <div class="col-12"><b>الوصف:</b> {{ $asset->description ?? '-' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-5">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">صورة الأصل</h6>

        @if($asset->photo_path)
          <img src="{{ asset('storage/'.$asset->photo_path) }}" class="w-100 border"
               style="border-radius:16px;object-fit:cover;max-height:320px">
        @else
          <div class="text-muted">لا يوجد صورة.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
