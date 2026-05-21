
<?php $__env->startSection('title','إضافة عقد'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة عقد — <?php echo e($employee->full_name); ?></h5>

    <form method="POST" action="<?php echo e(route('employees.contracts.store',$employee)); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ البداية</label>
          <input type="date" name="start_date" class="form-control" required value="<?php echo e(old('start_date')); ?>">
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">تاريخ النهاية</label>
          <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">نوع العقد</label>
          <select name="contract_type" class="form-select" required>
            <?php $__currentLoopData = ['full_time','part_time','freelance','hourly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($t); ?>" <?php if(old('contract_type')==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">العملة</label>
          <input name="currency" class="form-control" required value="<?php echo e(old('currency','USD')); ?>">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">راتب ثابت</label>
          <input name="salary_amount" class="form-control" value="<?php echo e(old('salary_amount')); ?>">
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">أجر ساعة</label>
          <input name="hour_rate" class="form-control" value="<?php echo e(old('hour_rate')); ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control"><?php echo e(old('notes')); ?></textarea>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
        <a href="<?php echo e(route('employees.contracts.index',$employee)); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/employees/contracts/create.blade.php ENDPATH**/ ?>