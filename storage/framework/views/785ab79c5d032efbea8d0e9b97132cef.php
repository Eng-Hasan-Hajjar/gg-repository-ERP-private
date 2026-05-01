<?php echo csrf_field(); ?>

<div class="row g-3">

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="trx_date" class="form-control" required
           value="<?php echo e(old('trx_date', now()->format('Y-m-d'))); ?>" readonly
           style="background-color: #f8f9fa; cursor: not-allowed;">
    <small class="text-muted d-block mt-1">التاريخ يُسجل تلقائيًا بتاريخ اليوم</small>
  </div>

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select" required id="transaction-type">
      <option value="in"       <?php if(old('type', $transaction->type ?? '') == 'in'): echo 'selected'; endif; ?>>مقبوض</option>
      <option value="out"      <?php if(old('type', $transaction->type ?? '') == 'out'): echo 'selected'; endif; ?>>مدفوع</option>
      <option value="transfer" <?php if(old('type', $transaction->type ?? '') == 'transfer'): echo 'selected'; endif; ?>>مناقلة</option>
      <option value="exchange" <?php if(old('type', $transaction->type ?? '') == 'exchange'): echo 'selected'; endif; ?>>تصريف</option>
    </select>
  </div>

  
  <div class="col-12 col-md-3" id="transfer-to-cashbox" style="display:none;">
    <label class="form-label fw-bold">الصندوق الوجهة</label>
    <select name="to_cashbox_id" class="form-select" id="to-cashbox-select">
      <option value="">— اختر الصندوق —</option>
      <?php $__currentLoopData = \App\Models\Cashbox::where('status','active')->where('id','!=',$cashbox->id)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($cb->id); ?>" <?php if(old('to_cashbox_id', $transaction->to_cashbox_id ?? '') == $cb->id): echo 'selected'; endif; ?>>
          <?php echo e($cb->name); ?> (<?php echo e($cb->currency); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <small class="text-muted">يجب أن يكون بنفس العملة إذا أمكن</small>
  </div>

  
  <div class="col-12 col-md-3" id="exchange-to-cashbox" style="display:none;">
    <label class="form-label fw-bold">صندوق الوجهة (التصريف)</label>
    <select name="exchange_to_cashbox_id" class="form-select" id="exchange-to-cashbox-select">
      <option value="">— اختر الصندوق —</option>
      <?php $__currentLoopData = \App\Models\Cashbox::where('status','active')->where('id','!=',$cashbox->id)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($cb->id); ?>"
                data-currency="<?php echo e($cb->currency); ?>"
                <?php if(old('exchange_to_cashbox_id', $transaction->exchange_to_cashbox_id ?? '') == $cb->id): echo 'selected'; endif; ?>>
          <?php echo e($cb->name); ?> (<?php echo e($cb->currency); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <small class="text-muted">عملته يجب أن تطابق العملة الأجنبية المختارة</small>
  </div>

  
  <div class="col-12 col-md-6" id="exchange-fields" style="display:none;">
    <div class="row g-2">
      <div class="col-6">
        <label class="form-label fw-bold">العملة الأجنبية</label>
        <select name="foreign_currency" class="form-select" id="foreign-currency-select">
          <option value="">— اختر —</option>
          <?php $__currentLoopData = ['USD','EUR','TRY','GBP','SAR','AED','JOD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($cur !== $cashbox->currency): ?>
              <option value="<?php echo e($cur); ?>" <?php if(old('foreign_currency', $transaction->foreign_currency ?? '') == $cur): echo 'selected'; endif; ?>>
                <?php echo e($cur); ?>

              </option>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <small class="text-muted">العملة المُصرَّفة</small>
      </div>
      <div class="col-6">
        <label class="form-label fw-bold">المبلغ الأجنبي</label>
        <input type="number" step="0.01" min="0" name="foreign_amount" class="form-control"
               value="<?php echo e(old('foreign_amount', $transaction->foreign_amount ?? '')); ?>"
               placeholder="مثال: 3000">
        <small class="text-muted">المبلغ بالعملة الأجنبية</small>
      </div>
    </div>
  </div>

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold" id="amount-label">المبلغ (<?php echo e($cashbox->currency); ?>)</label>
    <input name="amount" type="number" step="0.01" min="0" class="form-control" required
           value="<?php echo e(old('amount', $transaction->amount ?? '')); ?>" placeholder="0.00">
    <small class="text-muted" id="amount-hint"></small>
  </div>

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التصنيف الرئيسي</label>
    <select name="category" class="form-select" id="category-select">
      <option value="">— اختر التصنيف —</option>
      <?php $__currentLoopData = \App\Models\CashboxTransaction::$CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($key); ?>" <?php if(old('category', $transaction->category ?? '') == $key): echo 'selected'; endif; ?>>
          <?php echo e($label); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <small class="text-muted">اختر التصنيف المناسب للحركة</small>
  </div>

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">
      التصنيف الثانوي
      <span class="text-muted fw-normal">(اختياري)</span>
    </label>
    <input name="sub_category" class="form-control"
           value="<?php echo e(old('sub_category', $transaction->sub_category ?? '')); ?>"
           placeholder="تفصيل إضافي للتصنيف...">
    <small class="text-muted">مثال: قسط أول / راتب شهر أبريل</small>
  </div>

  
  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">مرجع</label>
    <input name="reference" class="form-control"
           value="<?php echo e(old('reference', $transaction->reference ?? '')); ?>"
           placeholder="رقم إيصال / ID / تحويل">
  </div>

  
  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرفق (PDF/صورة)</label>
    <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    <?php if(isset($transaction) && $transaction->attachment_path): ?>
      <div class="small mt-2">
        <span class="text-muted fw-semibold">المرفق الحالي:</span>
        <a class="fw-bold" target="_blank" href="<?php echo e(asset('storage/' . $transaction->attachment_path)); ?>">
          عرض المرفق
        </a>
      </div>
    <?php endif; ?>
  </div>

  
  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" rows="3" class="form-control"
              placeholder="تفاصيل إضافية..."><?php echo e(old('notes', $transaction->notes ?? '')); ?></textarea>
  </div>

</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="mt-4 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="<?php echo e(route('cashboxes.transactions.index', $cashbox)); ?>"
     class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>

<?php $__env->startPush('scripts'); ?>
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
  const currency           = '<?php echo e($cashbox->currency); ?>';

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
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/transactions/_form.blade.php ENDPATH**/ ?>