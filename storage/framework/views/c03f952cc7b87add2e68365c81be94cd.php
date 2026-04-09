
<?php ($activeModule='tasks'); ?>
<?php $__env->startSection('title','تعديل مهمة'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-pencil"></i> تعديل: <?php echo e($task->title); ?></h5>
    <form method="POST" action="<?php echo e(route('tasks.update',$task)); ?>">
      <?php echo $__env->make('tasks._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/tasks/edit.blade.php ENDPATH**/ ?>