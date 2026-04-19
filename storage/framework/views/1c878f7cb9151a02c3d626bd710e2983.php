
<?php ($activeModule='finance'); ?>
<?php $__env->startSection('title','تعديل حركة'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil"></i> تعديل حركة</h5>
        <div class="text-muted fw-semibold">
          <?php echo e($cashbox->name); ?> — <code><?php echo e($cashbox->code); ?></code> — <?php echo e($cashbox->currency); ?>

          — الحركة #<?php echo e($transaction->id); ?>

        </div>
      </div>
      <a href="<?php echo e(route('cashboxes.transactions.index',$cashbox)); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع
      </a>
    </div>

    <form method="POST" action="<?php echo e(route('cashboxes.transactions.update',[$cashbox,$transaction])); ?>" enctype="multipart/form-data">
      <?php echo method_field('PUT'); ?>
      <?php echo csrf_field(); ?>

<div class="row g-3">



  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="trx_date" class="form-control" required value="<?php echo e(now()->format('Y-m-d')); ?>" readonly
      style="background-color: #f8f9fa; cursor: not-allowed;">
    <small class="text-muted d-block mt-1">التاريخ يُسجل تلقائيًا بتاريخ اليوم</small>
  </div>


<!-- ... باقي الحقول ... -->

<div class="col-12 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <input type="text" class="form-control" value="<?php echo e($transaction->display_type); ?>" readonly>
    <small class="text-muted">النوع لا يمكن تعديله بعد الإنشاء</small>
</div>

<!-- حقل الصندوق الوجهة – يظهر فقط إذا كانت الحركة مناقلة من الأساس -->
<?php if($transaction->type === 'transfer'): ?>
    <div class="col-12 col-md-3">
        <label class="form-label fw-bold">الصندوق الوجهة (تعديل)</label>
        <select name="to_cashbox_id" class="form-select" required>
            <option value="">— اختر صندوقًا جديدًا —</option>
            <?php $__currentLoopData = \App\Models\Cashbox::where('status', 'active')
                        ->where('id', '!=', $cashbox->id)
                        ->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cb->id); ?>" <?php if(old('to_cashbox_id', $transaction->to_cashbox_id ?? '') == $cb->id): echo 'selected'; endif; ?>>
                    <?php echo e($cb->name); ?> (<?php echo e($cb->currency); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <small class="text-muted">يمكنك تغيير الصندوق الوجهة فقط</small>
    </div>
<?php endif; ?>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">المبلغ (<?php echo e($cashbox->currency); ?>)</label>
    <input name="amount" class="form-control" required value="<?php echo e(old('amount', $transaction->amount ?? '')); ?>"
      placeholder="0.00">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">تصنيف</label>
    <input name="category" class="form-control" value="<?php echo e(old('category', $transaction->category ?? '')); ?>"
      placeholder="مثال: قسط / راتب / إيجار">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">مرجع</label>
    <input name="reference" class="form-control" value="<?php echo e(old('reference', $transaction->reference ?? '')); ?>"
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

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="<?php echo e(route('cashboxes.transactions.index', $cashbox)); ?>"
    class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>





    </form>
  </div>
</div>















<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>









<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/transactions/edit.blade.php ENDPATH**/ ?>