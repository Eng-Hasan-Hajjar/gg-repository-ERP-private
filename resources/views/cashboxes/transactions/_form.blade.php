@csrf

<div class="row g-3">

  {{-- ── التاريخ ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="trx_date" class="form-control" required
           value="{{ old('trx_date', now()->format('Y-m-d')) }}" readonly
           style="background-color: #f8f9fa; cursor: not-allowed;">
    <small class="text-muted d-block mt-1">التاريخ يُسجل تلقائيًا بتاريخ اليوم</small>
  </div>

  {{-- ── النوع ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select" required id="transaction-type">
      <option value="in"       @selected(old('type', $transaction->type ?? '') == 'in')>مقبوض</option>
      <option value="out"      @selected(old('type', $transaction->type ?? '') == 'out')>مدفوع</option>
      <option value="transfer" @selected(old('type', $transaction->type ?? '') == 'transfer')>مناقلة</option>
      <option value="exchange" @selected(old('type', $transaction->type ?? '') == 'exchange')>تصريف</option>
    </select>
  </div>

  {{-- ── الصندوق الوجهة (مناقلة) ── --}}
  <div class="col-12 col-md-3" id="transfer-to-cashbox" style="display:none;">
    <label class="form-label fw-bold">الصندوق الوجهة</label>
    <select name="to_cashbox_id" class="form-select" id="to-cashbox-select">
      <option value="">— اختر الصندوق —</option>
      @foreach(\App\Models\Cashbox::where('status','active')->where('id','!=',$cashbox->id)->orderBy('name')->get() as $cb)
        <option value="{{ $cb->id }}" @selected(old('to_cashbox_id', $transaction->to_cashbox_id ?? '') == $cb->id)>
          {{ $cb->name }} ({{ $cb->currency }})
        </option>
      @endforeach
    </select>
    <small class="text-muted">يجب أن يكون بنفس العملة إذا أمكن</small>
  </div>

  {{-- ── الصندوق الوجهة (تصريف) ── --}}
  <div class="col-12 col-md-3" id="exchange-to-cashbox" style="display:none;">
    <label class="form-label fw-bold">صندوق الوجهة (التصريف)</label>
    <select name="exchange_to_cashbox_id" class="form-select" id="exchange-to-cashbox-select">
      <option value="">— اختر الصندوق —</option>
      @foreach(\App\Models\Cashbox::where('status','active')->where('id','!=',$cashbox->id)->orderBy('name')->get() as $cb)
        <option value="{{ $cb->id }}"
                data-currency="{{ $cb->currency }}"
                @selected(old('exchange_to_cashbox_id', $transaction->exchange_to_cashbox_id ?? '') == $cb->id)>
          {{ $cb->name }} ({{ $cb->currency }})
        </option>
      @endforeach
    </select>
    <small class="text-muted">عملته يجب أن تطابق العملة الأجنبية المختارة</small>
  </div>

  {{-- ── حقول التصريف (exchange) ── --}}
  <div class="col-12 col-md-6" id="exchange-fields" style="display:none;">
    <div class="row g-2">
      <div class="col-6">
        <label class="form-label fw-bold">العملة الأجنبية</label>
        <select name="foreign_currency" class="form-select" id="foreign-currency-select">
          <option value="">— اختر —</option>
          @foreach(['USD','EUR','TRY','GBP','SAR','AED','JOD'] as $cur)
            @if($cur !== $cashbox->currency)
              <option value="{{ $cur }}" @selected(old('foreign_currency', $transaction->foreign_currency ?? '') == $cur)>
                {{ $cur }}
              </option>
            @endif
          @endforeach
        </select>
        <small class="text-muted">العملة المُصرَّفة</small>
      </div>
      <div class="col-6">
        <label class="form-label fw-bold">المبلغ الأجنبي</label>
        <input type="number" step="0.01" min="0" name="foreign_amount" class="form-control"
               value="{{ old('foreign_amount', $transaction->foreign_amount ?? '') }}"
               placeholder="مثال: 3000">
        <small class="text-muted">المبلغ بالعملة الأجنبية</small>
      </div>
    </div>
  </div>

  {{-- ── المبلغ ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold" id="amount-label">المبلغ ({{ $cashbox->currency }})</label>
    <input name="amount" type="number" step="0.01" min="0" class="form-control" required
           value="{{ old('amount', $transaction->amount ?? '') }}" placeholder="0.00">
    <small class="text-muted" id="amount-hint"></small>
  </div>

  {{-- ── التصنيف الرئيسي (منسدل) ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التصنيف الرئيسي</label>
    <select name="category" class="form-select" id="category-select" required>
      <option value="">— اختر التصنيف —</option>
      @foreach(\App\Models\CashboxTransaction::$CATEGORIES as $key => $label)
        <option value="{{ $key }}" @selected(old('category', $transaction->category ?? '') == $key)>
          {{ $label }}
        </option>
      @endforeach
    </select>
    <small class="text-muted">اختر التصنيف المناسب للحركة</small>
  </div>

  {{-- ── التصنيف الثانوي (نصي) ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">
      التصنيف الثانوي
      <span class="text-muted fw-normal">(اختياري)</span>
    </label>
    <input name="sub_category" class="form-control"
           value="{{ old('sub_category', $transaction->sub_category ?? '') }}"
           placeholder="تفصيل إضافي للتصنيف...">
    <small class="text-muted">مثال: قسط أول / راتب شهر أبريل</small>
  </div>

  {{-- ── المرجع ── --}}
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">مرجع</label>
    <input name="reference" class="form-control"
           value="{{ old('reference', $transaction->reference ?? '') }}"
           placeholder="رقم إيصال / ID / تحويل">
  </div>

  {{-- ── المرفق ── --}}
  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرفق (PDF/صورة)</label>
    <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    @if(isset($transaction) && $transaction->attachment_path)
      <div class="small mt-2">
        <span class="text-muted fw-semibold">المرفق الحالي:</span>
        <a class="fw-bold" target="_blank" href="{{ asset('storage/' . $transaction->attachment_path) }}">
          عرض المرفق
        </a>
      </div>
    @endif
  </div>

  {{-- ── ملاحظات ── --}}
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

<div class="mt-4 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('cashboxes.transactions.index', $cashbox) }}"
     class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  const typeSelect         = document.getElementById('transaction-type');
  const transferDiv        = document.getElementById('transfer-to-cashbox');
  const exchangeDiv        = document.getElementById('exchange-fields');
  const exchangeToCashboxDiv = document.getElementById('exchange-to-cashbox');
  const amountLabel        = document.getElementById('amount-label');
  const amountHint         = document.getElementById('amount-hint');
  const toCashboxSel       = document.getElementById('to-cashbox-select');
  const forCurSel          = document.getElementById('foreign-currency-select');
  const exchangeToCashboxSel = document.getElementById('exchange-to-cashbox-select');
  const currency           = '{{ $cashbox->currency }}';

  function handleTypeChange() {
    const val = typeSelect.value;

    // إخفاء الكل أولاً
    transferDiv.style.display          = 'none';
    exchangeDiv.style.display          = 'none';
    exchangeToCashboxDiv.style.display = 'none';

    // إلغاء required للكل
    if (toCashboxSel)         toCashboxSel.required         = false;
    if (forCurSel)            forCurSel.required            = false;
    if (exchangeToCashboxSel) exchangeToCashboxSel.required = false;

    amountLabel.textContent = `المبلغ (${currency})`;
    amountHint.textContent  = '';

    if (val === 'transfer') {
      transferDiv.style.display = 'block';
      if (toCashboxSel) toCashboxSel.required = true;
    }

    if (val === 'exchange') {
      exchangeDiv.style.display          = 'block';
      exchangeToCashboxDiv.style.display = 'block';
      if (forCurSel)            forCurSel.required            = true;
      if (exchangeToCashboxSel) exchangeToCashboxSel.required = true;
      amountLabel.textContent = `المبلغ المُصرَّف (${currency})`;
      amountHint.textContent  = `المبلغ المخصوم من هذا الصندوق بعملته (${currency})`;
    }
  }

  typeSelect.addEventListener('change', handleTypeChange);
  handleTypeChange(); // تطبيق فوري عند التحميل
});
</script>
@endpush