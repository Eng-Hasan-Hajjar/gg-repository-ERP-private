
<?php $__env->startSection('title','المعلومات الإضافية'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="mb-0">المعلومات الإضافية</h5>
        <div class="text-muted"><?php echo e($student->full_name); ?> — <code><?php echo e($student->university_id); ?></code></div>
      </div>
      <a class="btn btn-outline-secondary" href="<?php echo e(route('students.show',$student)); ?>">رجوع</a>
    </div>

    <form method="POST" action="<?php echo e(route('students.extra.update',$student)); ?>">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>

      <?php ($data = old('data', $student->extra->data ?? [])); ?>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">العنوان</label>
          <input class="form-control" name="data[address]" value="<?php echo e($data['address'] ?? ''); ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">تاريخ الميلاد</label>
          <input type="date" class="form-control" name="data[birth_date]" value="<?php echo e($data['birth_date'] ?? ''); ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">الجنسية</label>
          <input class="form-control" name="data[nationality]" value="<?php echo e($data['nationality'] ?? ''); ?>">
        </div>

        <div class="col-12">
          <label class="form-label">ملاحظات</label>
          <textarea class="form-control" rows="4" name="data[notes]"><?php echo e($data['notes'] ?? ''); ?></textarea>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="mt-3">
        <button class="btn btn-primary">حفظ المعلومات الإضافية</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\students\extra_edit.blade.php ENDPATH**/ ?>