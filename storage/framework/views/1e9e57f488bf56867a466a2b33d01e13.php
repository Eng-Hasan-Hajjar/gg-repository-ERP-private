
<?php $__env->startSection('title','CRM - تعديل عميل محتمل'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل العميل المحتمل</h5>
    <form method="POST" action="<?php echo e(route('leads.update',$lead)); ?>">
      <?php echo $__env->make('crm.leads._form',['lead'=>$lead], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/edit.blade.php ENDPATH**/ ?>