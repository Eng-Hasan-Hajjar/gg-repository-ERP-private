
<?php $__env->startSection('title','تعديل أصل'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل الأصل</h5>
    <form method="POST" action="<?php echo e(route('assets.update',$asset)); ?>" enctype="multipart/form-data">
      <?php echo $__env->make('assets._form', ['asset'=>$asset], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/assets/edit.blade.php ENDPATH**/ ?>