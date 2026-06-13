@extends('layouts.app')
@section('title', 'تفاصيل الطلب')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">تفاصيل الطلب #{{ $assetRequest->id }}</h4>
    <div class="text-muted small">{{ $assetRequest->created_at->format('Y-m-d H:i') }}</div>
  </div>
  <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary rounded-pill">
    <i class="bi bi-arrow-right"></i> رجوع
  </a>
</div>

<div class="card border-0 shadow-sm" style="max-width:750px;">
  <div class="card-body">

    {{-- الحالة --}}
    <div class="mb-3">
      @if($assetRequest->status === 'pending')
        <span class="badge bg-warning text-dark fs-6">⏳ قيد المراجعة</span>
      @elseif($assetRequest->status === 'approved')
        <span class="badge bg-success fs-6">✅ مقبول</span>
      @else
        <span class="badge bg-danger fs-6">❌ مرفوض</span>
      @endif

      @if($assetRequest->priority === 'urgent')
        <span class="badge bg-danger me-1">🔴 عاجل</span>
      @elseif($assetRequest->priority === 'low')
        <span class="badge bg-secondary me-1">🔽 منخفضة</span>
      @else
        <span class="badge bg-secondary me-1">➖ عادية</span>
      @endif
    </div>

    <table class="table table-borderless">
      <tr>
        <th style="width:35%">نوع الطلب</th>
        <td>
          @if($assetRequest->type === 'purchase') 🛒 شراء
          @elseif($assetRequest->type === 'repair') 🔧 إصلاح
          @else 🚚 نقل
          @endif
        </td>
      </tr>
      <tr>
        <th>العنوان</th>
        <td>{{ $assetRequest->title }}</td>
      </tr>
      <tr>
        <th>مقدم الطلب</th>
        <td>{{ $assetRequest->user?->name }}</td>
      </tr>

      @if($assetRequest->type === 'transfer')
      <tr>
        <th>من فرع</th>
        <td>{{ $assetRequest->fromBranch?->name ?? '—' }}</td>
      </tr>
      <tr>
        <th>إلى فرع</th>
        <td>{{ $assetRequest->toBranch?->name ?? '—' }}</td>
      </tr>
      @else
      <tr>
        <th>الفرع</th>
        <td>{{ $assetRequest->branch?->name ?? '—' }}</td>
      </tr>
      @endif

      @if($assetRequest->asset)
      <tr>
        <th>الأصل المرتبط</th>
        <td>{{ $assetRequest->asset->name }} — <code>{{ $assetRequest->asset->asset_tag }}</code></td>
      </tr>
      @endif

      @if($assetRequest->description)
      <tr>
        <th>التفاصيل</th>
        <td>{{ $assetRequest->description }}</td>
      </tr>
      @endif

      @if($assetRequest->reviewed_by)
      <tr>
        <th>راجعه</th>
        <td>{{ $assetRequest->reviewer?->name }} — {{ $assetRequest->reviewed_at?->format('Y-m-d H:i') }}</td>
      </tr>
      @endif

      @if($assetRequest->manager_notes)
      <tr>
        <th>ملاحظات المدير</th>
        <td>{{ $assetRequest->manager_notes }}</td>
      </tr>
      @endif
    </table>

  </div>
</div>

@endsection