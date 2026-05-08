@extends('layouts.app')
@section('title', 'تقديم طلب لوجستي')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">تقديم طلب جديد</h4>
  <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary rounded-pill">رجوع</a>
</div>

<div class="card border-0 shadow-sm" style="max-width:700px;">
  <div class="card-body">
    <form method="POST" action="{{ route('asset-requests.store') }}">
      @csrf

      <div class="row g-3">

        <div class="col-md-6">
          <label class="fw-bold">نوع الطلب <span class="text-danger">*</span></label>
          <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="purchase" @selected(old('type')=='purchase')>🛒 طلب شراء</option>
            <option value="repair"   @selected(old('type')=='repair')>🔧 طلب إصلاح</option>
          </select>
          @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="fw-bold">الفرع</label>
          <select name="branch_id" class="form-select">
            <option value="">— اختر الفرع —</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(old('branch_id') == $b->id)>{{ $b->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <label class="fw-bold">عنوان الطلب <span class="text-danger">*</span></label>
          <input type="text" name="title" value="{{ old('title') }}"
                 class="form-control @error('title') is-invalid @enderror"
                 placeholder="مثال: شراء طابعة للفرع / إصلاح جهاز العرض" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label class="fw-bold">الأصل المرتبط (اختياري)</label>
          <select name="asset_id" class="form-select">
            <option value="">— للإصلاح: اختر الأصل —</option>
            @foreach($assets as $a)
              <option value="{{ $a->id }}" @selected(old('asset_id') == $a->id)>
                {{ $a->name }} — {{ $a->branch->name ?? '' }}
              </option>
            @endforeach
          </select>
          <small class="text-muted">للطلبات المتعلقة بأصل موجود مسبقاً</small>
        </div>

        <div class="col-12">
          <label class="fw-bold">التفاصيل</label>
          <textarea name="description" rows="4" class="form-control"
            placeholder="اشرح تفاصيل الطلب، المواصفات، أو سبب الإصلاح...">{{ old('description') }}</textarea>
        </div>

      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
      @endif

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-namaa px-5 fw-bold rounded-pill">
          <i class="bi bi-send"></i> تقديم الطلب
        </button>
        <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary rounded-pill">إلغاء</a>
      </div>

    </form>
  </div>
</div>

@endsection