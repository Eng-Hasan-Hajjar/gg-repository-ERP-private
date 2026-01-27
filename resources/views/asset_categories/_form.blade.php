@csrf
@if(isset($item)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-12 col-lg-8">
    <label class="form-label fw-bold">اسم التصنيف</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $item->name ?? '') }}">
  </div>

  <div class="col-12 col-lg-4">
    <label class="form-label fw-bold">الكود</label>
    <input name="code" class="form-control" required placeholder="IT / FURN / NET"
           value="{{ old('code', $item->code ?? '') }}">
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary rounded-pill fw-bold px-4">
    <i class="bi bi-save2"></i> حفظ
  </button>
  <a href="{{ route('asset-categories.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">إلغاء</a>
</div>
