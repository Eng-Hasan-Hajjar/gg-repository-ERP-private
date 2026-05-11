
<?php $__env->startSection('title', 'تعديل مجموعة الرؤية'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">تعديل: <?php echo e($visibilityGroup->name); ?></h4>
  <a href="<?php echo e(route('admin.visibility-groups.index')); ?>" class="btn btn-outline-secondary">رجوع</a>
</div>

<form method="POST" action="<?php echo e(route('admin.visibility-groups.update', $visibilityGroup)); ?>">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

  <div class="card border-0 shadow-sm mb-3">
    <div class="card-header fw-bold">معلومات المجموعة</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="fw-bold">اسم المجموعة</label>
          <input type="text" name="name" value="<?php echo e(old('name', $visibilityGroup->name)); ?>"
                 class="form-control">
        </div>
        <div class="col-md-6">
          <label class="fw-bold">ملاحظات</label>
          <input type="text" name="notes" value="<?php echo e(old('notes', $visibilityGroup->notes)); ?>"
                 class="form-control">
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">

    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header fw-bold d-flex align-items-center gap-2">
          <span class="badge bg-danger">مدير</span> المديرون
        </div>
        <div class="card-body" style="max-height:400px; overflow-y:auto;">
          <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check mb-1">
              <input type="checkbox" name="managers[]" value="<?php echo e($emp->id); ?>"
                     id="mgr_<?php echo e($emp->id); ?>" class="form-check-input"
                     <?php if(in_array($emp->id, old('managers', $managerIds))): echo 'checked'; endif; ?>>
              <label for="mgr_<?php echo e($emp->id); ?>" class="form-check-label">
                <?php echo e($emp->full_name); ?>

                <?php if($emp->branch): ?>
                  <small class="text-muted">(<?php echo e($emp->branch->name); ?>)</small>
                <?php endif; ?>
              </label>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header fw-bold d-flex align-items-center gap-2">
          <span class="badge bg-info text-dark">عضو</span> الأعضاء
        </div>
        <div class="card-body" style="max-height:400px; overflow-y:auto;">
          <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check mb-1">
              <input type="checkbox" name="members[]" value="<?php echo e($emp->id); ?>"
                     id="mem_<?php echo e($emp->id); ?>" class="form-check-input"
                     <?php if(in_array($emp->id, old('members', $memberIds))): echo 'checked'; endif; ?>>
              <label for="mem_<?php echo e($emp->id); ?>" class="form-check-label">
                <?php echo e($emp->full_name); ?>

                <?php if($emp->branch): ?>
                  <small class="text-muted">(<?php echo e($emp->branch->name); ?>)</small>
                <?php endif; ?>
              </label>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>

  </div>

  <div class="mt-4 text-end">
    <button class="btn btn-namaa px-5 fw-bold">
      <i class="bi bi-check-circle"></i> حفظ التعديلات
    </button>
  </div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\admin\visibility_groups\edit.blade.php ENDPATH**/ ?>