@csrf

<div class="row g-3">
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="trx_date" class="form-control" required
           value="{{ old('trx_date', isset($transaction) ? $transaction->trx_date?->format('Y-m-d') : now()->format('Y-m-d')) }}">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select" required>
      <option value="in"  @selected(old('type', $transaction->type ?? '')=='in')>مقبوض</option>
      <option value="out" @selected(old('type', $transaction->type ?? '')=='out')>مدفوع</option>
    </select>
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">المبلغ ({{ $cashbox->currency }})</label>
    <input name="amount" class="form-control" required
           value="{{ old('amount', $transaction->amount ?? '') }}" placeholder="0.00">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">تصنيف</label>
    <input name="category" class="form-control"
           value="{{ old('category', $transaction->category ?? '') }}" placeholder="مثال: قسط / راتب / إيجار">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرجع</label>
    <input name="reference" class="form-control"
           value="{{ old('reference', $transaction->reference ?? '') }}" placeholder="رقم إيصال / ID / تحويل">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرفق (PDF/صورة)</label>
    <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">

    @if(isset($transaction) && $transaction->attachment_path)
      <div class="small mt-2">
        <span class="text-muted fw-semibold">المرفق الحالي:</span>
        <a class="fw-bold" target="_blank" href="{{ asset('storage/'.$transaction->attachment_path) }}">
          عرض المرفق
        </a>
      </div>
    @endif
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" rows="3" class="form-control"
              placeholder="تفاصيل إضافية...">{{ old('notes', $transaction->notes ?? '') }}</textarea>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('cashboxes.transactions.index',$cashbox) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
