
<?php $__env->startSection('title','إضافة مستحق'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1">إضافة مستحق — <?php echo e($employee->full_name); ?></h5>
        <div class="text-muted fw-semibold">كود: <code><?php echo e($employee->code); ?></code></div>
      </div>
      <a href="<?php echo e(route('employees.payouts.index',$employee)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع
      </a>
    </div>

    <form method="POST" action="<?php echo e(route('employees.payouts.store',$employee)); ?>">
      <?php echo csrf_field(); ?>

      <div class="row g-3">
        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">تاريخ المستحق</label>
          <input type="date" name="payout_date" class="form-control" required value="<?php echo e(old('payout_date', now()->format('Y-m-d'))); ?>">
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">المبلغ</label>
          <input name="amount" class="form-control" required value="<?php echo e(old('amount')); ?>" placeholder="مثال: 150">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="<?php echo e(old('currency','USD')); ?>" placeholder="USD">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">الحالة</label>
          <select name="status" class="form-select" required>
            <option value="pending" <?php if(old('status','pending')=='pending'): echo 'selected'; endif; ?>>معلق</option>
            <option value="paid" <?php if(old('status')=='paid'): echo 'selected'; endif; ?>>مدفوع</option>
          </select>
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label fw-bold">مرجع/إيصال</label>
          <input name="reference" class="form-control" value="<?php echo e(old('reference')); ?>" placeholder="رقم إيصال">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control" placeholder="تفاصيل المستحق..."><?php echo e(old('notes')); ?></textarea>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-check2-circle"></i> حفظ
        </button>
        <a href="<?php echo e(route('employees.payouts.index',$employee)); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\employees\payouts\create.blade.php ENDPATH**/ ?>