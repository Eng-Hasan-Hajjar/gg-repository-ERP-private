
<?php ($activeModule='attendance'); ?>
<?php $__env->startSection('title','طلب إجازة/إذن'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> طلب إجازة/إذن</h5>

    <form method="POST" action="<?php echo e(route('leaves.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الموظف</label>
          <select name="employee_id" class="form-select" required>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($e->id); ?>" <?php if(old('employee_id')==$e->id): echo 'selected'; endif; ?>><?php echo e($e->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-bold">النوع</label>
          <select name="type" class="form-select" required>
            <option value="leave" <?php if(old('type')=='leave'): echo 'selected'; endif; ?>>إجازة</option>
            <option value="permission" <?php if(old('type')=='permission'): echo 'selected'; endif; ?>>إذن</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">من</label>
          <input type="date" name="start_date" class="form-control" required value="<?php echo e(old('start_date')); ?>">
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">إلى</label>
          <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">السبب</label>
          <textarea name="reason" rows="3" class="form-control"><?php echo e(old('reason')); ?></textarea>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">إرسال</button>
        <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/leaves/create.blade.php ENDPATH**/ ?>