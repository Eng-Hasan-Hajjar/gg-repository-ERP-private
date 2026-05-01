
<?php ($activeModule='finance'); ?>
<?php $__env->startSection('title','إضافة حركة'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-plus-circle"></i> إضافة حركة</h5>
        <div class="text-muted fw-semibold">
          <?php echo e($cashbox->name); ?> — <code><?php echo e($cashbox->code); ?></code> — <?php echo e($cashbox->currency); ?>

        </div>
      </div>
      <a href="<?php echo e(route('cashboxes.transactions.index',$cashbox)); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع
      </a>
    </div>

    <form method="POST" action="<?php echo e(route('cashboxes.transactions.store',$cashbox)); ?>" enctype="multipart/form-data">
      <?php echo $__env->make('cashboxes.transactions._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/transactions/create.blade.php ENDPATH**/ ?>