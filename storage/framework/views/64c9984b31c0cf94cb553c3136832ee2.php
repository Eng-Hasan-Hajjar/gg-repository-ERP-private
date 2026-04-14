
<?php $__env->startSection('title','طالب جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة طالب (شؤون الطلاب)</h5>
    <form method="POST" enctype="multipart/form-data" action="<?php echo e(route('students.store')); ?>" novalidate>
      <?php echo $__env->make('students._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/create.blade.php ENDPATH**/ ?>