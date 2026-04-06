<?php $__currentLoopData = ['success','error']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if(session($k)): ?>
    <div class="alert alert-<?php echo e($k==='success' ? 'success' : 'danger'); ?> alert-dismissible fade show" role="alert">
      <?php echo e(session($k)); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/partials/flash.blade.php ENDPATH**/ ?>