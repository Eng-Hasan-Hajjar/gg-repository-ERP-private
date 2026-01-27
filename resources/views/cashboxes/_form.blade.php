@csrf
@if(isset($cashbox)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">اسم الصندوق</label>
    <input name="name" class="form-control" required value="{{ old('name', $cashbox->name ?? '') }}">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">الكود</label>
    <input name="code" class="form-control" required value="{{ old('code', $cashbox->code ?? '') }}" placeholder="CB-IST-USD">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">العملة</label>
    <select name="currency" class="form-select" required>
      @foreach(['USD','TRY','EUR'] as $c)
        <option value="{{ $c }}" @selected(old('currency',$cashbox->currency ?? 'USD')==$c)>{{ $c }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$cashbox->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <option value="active" @selected(old('status',$cashbox->status ?? 'active')=='active')>نشط</option>
      <option value="inactive" @selected(old('status',$cashbox->status ?? '')=='inactive')>غير نشط</option>
    </select>
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">رصيد افتتاحي</label>
    <input name="opening_balance" class="form-control" value="{{ old('opening_balance', $cashbox->opening_balance ?? 0) }}">
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('cashboxes.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
