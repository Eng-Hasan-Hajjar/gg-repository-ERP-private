@extends('layouts.app')
@php($activeModule='finance')
@section('title','تعديل حركة')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil"></i> تعديل حركة</h5>
        <div class="text-muted fw-semibold">
          {{ $cashbox->name }} — <code>{{ $cashbox->code }}</code> — {{ $cashbox->currency }}
          — الحركة #{{ $transaction->id }}
        </div>
      </div>
      <a href="{{ route('cashboxes.transactions.index',$cashbox) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('cashboxes.transactions.update',[$cashbox,$transaction]) }}" enctype="multipart/form-data">
      @method('PUT')
      @csrf

<div class="row g-3">



  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="trx_date" class="form-control" required value="{{ now()->format('Y-m-d') }}" readonly
      style="background-color: #f8f9fa; cursor: not-allowed;">
    <small class="text-muted d-block mt-1">التاريخ يُسجل تلقائيًا بتاريخ اليوم</small>
  </div>


<!-- ... باقي الحقول ... -->

<div class="col-12 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <input type="text" class="form-control" value="{{ $transaction->display_type ?? ($transaction->type == 'transfer' ? 'مناقلة' : ($transaction->type == 'in' ? 'مقبوض' : 'مدفوع')) }}" readonly>
    <small class="text-muted">النوع لا يمكن تعديله بعد الإنشاء</small>
</div>

<!-- حقل الصندوق الوجهة – يظهر فقط إذا كانت الحركة مناقلة من الأساس -->
@if($transaction->type === 'transfer')
    <div class="col-12 col-md-3">
        <label class="form-label fw-bold">الصندوق الوجهة (تعديل)</label>
        <select name="to_cashbox_id" class="form-select" required>
            <option value="">— اختر صندوقًا جديدًا —</option>
            @foreach(\App\Models\Cashbox::where('status', 'active')
                        ->where('id', '!=', $cashbox->id)
                        ->orderBy('name')->get() as $cb)
                <option value="{{ $cb->id }}" @selected(old('to_cashbox_id', $transaction->to_cashbox_id ?? '') == $cb->id)>
                    {{ $cb->name }} ({{ $cb->currency }})
                </option>
            @endforeach
        </select>
        <small class="text-muted">يمكنك تغيير الصندوق الوجهة فقط</small>
    </div>
@endif

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">المبلغ ({{ $cashbox->currency }})</label>
    <input name="amount" class="form-control" required value="{{ old('amount', $transaction->amount ?? '') }}"
      placeholder="0.00">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">تصنيف</label>
    <input name="category" class="form-control" value="{{ old('category', $transaction->category ?? '') }}"
      placeholder="مثال: قسط / راتب / إيجار">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرجع</label>
    <input name="reference" class="form-control" value="{{ old('reference', $transaction->reference ?? '') }}"
      placeholder="رقم إيصال / ID / تحويل">
  </div>

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
  <a href="{{ route('cashboxes.transactions.index', $cashbox) }}"
    class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>





    </form>
  </div>
</div>















@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('transaction-type');
    const transferDiv = document.getElementById('transfer-to-cashbox');

    function toggleTransferField() {
        if (typeSelect.value === 'transfer') {
            transferDiv.style.display = 'block';
            document.getElementById('to-cashbox-select').required = true;
        } else {
            transferDiv.style.display = 'none';
            document.getElementById('to-cashbox-select').required = false;
        }
    }

    typeSelect.addEventListener('change', toggleTransferField);
    
    // تشغيل مرة أولى (في حالة التعديل)
    toggleTransferField();
});
</script>
@endpush









@endsection
