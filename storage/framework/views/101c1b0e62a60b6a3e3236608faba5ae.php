
<?php $__env->startSection('title','CRM - عميل محتمل جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة عميل محتمل</h5>
    <form method="POST" action="<?php echo e(route('leads.store')); ?>">
      <?php echo $__env->make('crm.leads._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/create.blade.php ENDPATH**/ ?>