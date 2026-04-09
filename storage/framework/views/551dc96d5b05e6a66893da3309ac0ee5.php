
<?php $__env->startSection('title','إضافة إضافة مورد بشري'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-person-plus"></i> إضافة مورد بشري</h5>
    <form method="POST" action="<?php echo e(route('employees.store')); ?>">
      <?php echo $__env->make('employees._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/employees/create.blade.php ENDPATH**/ ?>