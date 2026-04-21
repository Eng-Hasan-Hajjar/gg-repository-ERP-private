
<?php $__env->startSection('title','تعديل طالب'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل الطالب</h5>
    <form method="POST" enctype="multipart/form-data" action="<?php echo e(route('students.update',$student)); ?>" novalidate>
      <?php echo $__env->make('students._form',['student'=>$student], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/edit.blade.php ENDPATH**/ ?>