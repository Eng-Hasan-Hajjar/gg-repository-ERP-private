
<?php ($activeModule = 'users'); ?>

<?php $__env->startSection('title','تعديل مستخدم'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل المستخدم</h5>

    <form method="POST" action="<?php echo e(route('admin.users.update',$user)); ?>">
      <?php echo method_field('PUT'); ?>
      <?php echo $__env->make('admin.users._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>