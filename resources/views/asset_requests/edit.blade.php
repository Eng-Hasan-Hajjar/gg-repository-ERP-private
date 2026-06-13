@extends('layouts.app')
@section('title', 'تعديل الطلب')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">تعديل الطلب #{{ $assetRequest->id }}</h4>
  <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary rounded-pill">رجوع</a>
</div>

<div class="card border-0 shadow-sm" style="max-width:700px;">
  <div class="card-body">
    <form method="POST" action="{{ route('asset-requests.update', $assetRequest) }}">
      @csrf
      @method('PUT')

      <div class="row g-3">

        {{-- نوع الطلب --}}
        <div class="col-md-6">
          <label class="fw-bold">نوع الطلب <span class="text-danger">*</span></label>
          <select name="type" id="request-type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="purchase" @selected(old('type', $assetRequest->type)=='purchase')>🛒 طلب شراء</option>
            <option value="repair"   @selected(old('type', $assetRequest->type)=='repair')>🔧 طلب إصلاح</option>
            <option value="transfer" @selected(old('type', $assetRequest->type)=='transfer')>🚚 طلب نقل</option>
          </select>
          @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- حقول طلب النقل --}}
        <div class="col-12" id="transfer-fields" style="{{ old('type', $assetRequest->type) === 'transfer' ? '' : 'display:none;' }}">
          <div class="row g-3 p-3 rounded-3" style="background:#f0f7ff; border: 1px solid #bbd6f5;">
            <div class="col-12">
              <div class="fw-bold text-primary mb-1"><i class="bi bi-arrow-left-right"></i> بيانات النقل</div>
            </div>
            <div class="col-md-6">
              <label class="fw-bold">من فرع <span class="text-danger">*</span></label>
              <select name="from_branch_id" class="form-select @error('from_branch_id') is-invalid @enderror">
                <option value="">— اختر الفرع —</option>
                @foreach($branches as $b)
                  <option value="{{ $b->id }}" @selected(old('from_branch_id', $assetRequest->from_branch_id) == $b->id)>{{ $b->name }}</option>
                @endforeach
              </select>
              @error('from_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="fw-bold">إلى فرع <span class="text-danger">*</span></label>
              <select name="to_branch_id" class="form-select @error('to_branch_id') is-invalid @enderror">
                <option value="">— اختر الفرع —</option>
                @foreach($branches as $b)
                  <option value="{{ $b->id }}" @selected(old('to_branch_id', $assetRequest->to_branch_id) == $b->id)>{{ $b->name }}</option>
                @endforeach
              </select>
              @error('to_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        {{-- الأولوية --}}
        <div class="col-md-6">
          <label class="fw-bold">الأولوية <span class="text-danger">*</span></label>
          <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
            <option value="normal"  @selected(old('priority', $assetRequest->priority) == 'normal')>➖ عادية</option>
            <option value="low"     @selected(old('priority', $assetRequest->priority) == 'low')>🔽 منخفضة</option>
            <option value="urgent"  @selected(old('priority', $assetRequest->priority) == 'urgent')>🔴 عاجل</option>
          </select>
          @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- الفرع --}}
        <div class="col-md-6">
          <label class="fw-bold">الفرع</label>
          <select name="branch_id" class="form-select">
            <option value="">— اختر الفرع —</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(old('branch_id', $assetRequest->branch_id) == $b->id)>{{ $b->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- عنوان الطلب --}}
        <div class="col-md-6">
          <label class="fw-bold">عنوان الطلب <span class="text-danger">*</span></label>
          <input type="text" name="title" value="{{ old('title', $assetRequest->title) }}"
                 class="form-control @error('title') is-invalid @enderror"
                 placeholder="مثال: شراء طابعة / إصلاح جهاز العرض" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- الأصل المرتبط --}}
        <div class="col-12">
          <label class="fw-bold">الأصل المرتبط (اختياري)</label>
          <select name="asset_id" class="form-select">
            <option value="">— للإصلاح: اختر الأصل —</option>
            @foreach($assets as $a)
              <option value="{{ $a->id }}" @selected(old('asset_id', $assetRequest->asset_id) == $a->id)>
                {{ $a->name }} — {{ $a->branch->name ?? '' }}
              </option>
            @endforeach
          </select>
          <small class="text-muted">للطلبات المتعلقة بأصل موجود مسبقاً</small>
        </div>

        {{-- التفاصيل --}}
        <div class="col-12">
          <label class="fw-bold">التفاصيل</label>
          <textarea name="description" rows="4" class="form-control"
            placeholder="اشرح تفاصيل الطلب، المواصفات، أو سبب الإصلاح...">{{ old('description', $assetRequest->description) }}</textarea>
        </div>

        {{-- تنبيه للطلبات العاجلة --}}
        <div class="col-12" id="urgentAlert" style="display:none;">
          <div class="alert alert-danger py-2 mb-0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>تنبيه:</strong> الطلبات العاجلة تُرسل إشعاراً فورياً لمدير اللوجستيات.
          </div>
        </div>

      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
      @endif

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-namaa px-5 fw-bold rounded-pill">
          <i class="bi bi-save"></i> حفظ التعديلات
        </button>
        <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary rounded-pill">إلغاء</a>
      </div>

    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prioritySelect = document.querySelector('select[name="priority"]');
    const urgentAlert    = document.getElementById('urgentAlert');

    function toggleAlert() {
        urgentAlert.style.display = prioritySelect.value === 'urgent' ? 'block' : 'none';
    }

    prioritySelect.addEventListener('change', toggleAlert);
    toggleAlert();
});

document.getElementById('request-type').addEventListener('change', function () {
    const transferFields = document.getElementById('transfer-fields');
    transferFields.style.display = this.value === 'transfer' ? '' : 'none';
});
</script>

@endsection