
<?php $__env->startSection('title','تعديل دبلومة'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">تعديل دبلومة</h5>
      <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('diplomas.index')); ?>">رجوع</a>
    </div>

    <form method="POST" action="<?php echo e(route('diplomas.update', $diploma)); ?>" enctype="multipart/form-data">
      <?php echo $__env->make('diplomas._form', ['diploma' => $diploma], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/diplomas/edit.blade.php ENDPATH**/ ?>