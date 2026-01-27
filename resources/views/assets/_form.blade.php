@csrf
@if(isset($asset)) @method('PUT') @endif

<div class="row g-3">

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">اسم الأصل</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $asset->name ?? '') }}">
  </div>

  <div class="col-12 col-lg-3">
    <label class="form-label fw-bold">التصنيف</label>
    <select name="asset_category_id" class="form-select">
      <option value="">—</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(old('asset_category_id', $asset->asset_category_id ?? '')==$c->id)>
          {{ $c->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-lg-3">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $asset->branch_id ?? '')==$b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الوصف</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $asset->description ?? '') }}</textarea>
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">الحالة</label>
    <select name="condition" class="form-select" required>
      <option value="good" @selected(old('condition', $asset->condition ?? 'good')=='good')>جيد</option>
      <option value="maintenance" @selected(old('condition', $asset->condition ?? '')=='maintenance')>صيانة</option>
      <option value="out_of_service" @selected(old('condition', $asset->condition ?? '')=='out_of_service')>خارج الخدمة</option>
    </select>
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">تاريخ الشراء</label>
    <input type="date" name="purchase_date" class="form-control"
           value="{{ old('purchase_date', optional($asset->purchase_date ?? null)->format('Y-m-d')) }}">
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">تكلفة الشراء</label>
    <input type="number" step="0.01" name="purchase_cost" class="form-control"
           value="{{ old('purchase_cost', $asset->purchase_cost ?? '') }}">
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">العملة</label>
    <input name="currency" class="form-control" maxlength="3" placeholder="USD"
           value="{{ old('currency', $asset->currency ?? 'USD') }}">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">Serial / رقم السيريال</label>
    <input name="serial_number" class="form-control"
           value="{{ old('serial_number', $asset->serial_number ?? '') }}">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">الموقع داخل الفرع</label>
    <input name="location" class="form-control" placeholder="غرفة / مخزن / قاعة"
           value="{{ old('location', $asset->location ?? '') }}">
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">صورة الأصل (اختياري)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">

    @if(isset($asset) && $asset->photo_path)
      <div class="mt-2">
        <img src="{{ asset('storage/'.$asset->photo_path) }}" style="max-height:120px;border-radius:12px" class="border">
      </div>
    @endif
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
  <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">إلغاء</a>
</div>
