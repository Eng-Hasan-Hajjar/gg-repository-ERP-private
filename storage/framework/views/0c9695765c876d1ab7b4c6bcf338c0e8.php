
<?php ($activeModule='attendance'); ?>
<?php $__env->startSection('title','تعديل سجل الدوام'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-1">تعديل سجل الدوام</h5>
    <div class="text-muted fw-semibold mb-3">
      <?php echo e($record->employee->full_name); ?> — التاريخ: <b><?php echo e($record->work_date->format('Y-m-d')); ?></b>
    </div>

    <form method="POST" action="<?php echo e(route('attendance.update',$record)); ?>">
      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الشيفت</label>
          <select name="work_shift_id" class="form-select">
            <option value="">—</option>
            <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($s->id); ?>" <?php if(old('work_shift_id',$record->work_shift_id)==$s->id): echo 'selected'; endif; ?>>
                <?php echo e($s->name); ?> (<?php echo e($s->start_time); ?>-<?php echo e($s->end_time); ?>)
              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-4">
          <label class="form-label fw-bold">توقيت الدخول</label>
          <input type="datetime-local" name="check_in_at" class="form-control"
                 value="<?php echo e(old('check_in_at', $record->check_in_at?->format('Y-m-d\TH:i') ?? '')); ?>">
        </div>

        <div class="col-6 col-md-4">
          <label class="form-label fw-bold">توقيت الخروج</label>
          <input type="datetime-local" name="check_out_at" class="form-control"
                 value="<?php echo e(old('check_out_at', $record->check_out_at?->format('Y-m-d\TH:i') ?? '')); ?>">
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">الحالة</label>
          <select name="status" class="form-select" required>
            <?php $__currentLoopData = ['scheduled','present','late','absent','off','leave']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($s); ?>" <?php if(old('status',$record->status)==$s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" rows="3" class="form-control"><?php echo e(old('notes',$record->notes)); ?></textarea>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex flex-wrap gap-2">
        <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
        <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\attendance\edit.blade.php ENDPATH**/ ?>