
<?php $__env->startSection('title','شكراً لكم'); ?>

<?php $__env->startSection('content'); ?>

<div class="card shadow-sm p-5 text-center">
    <div class="mb-3">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
    </div>
    <h3 class="text-success fw-bold">شكراً لتقديم طلبكم</h3>
    <p class="text-muted mt-2">تم استلام الطلب وسيتم التواصل معكم قريباً.</p>
    <a href="<?php echo e(route('media.public.form')); ?>" class="btn btn-namaa mt-3">
        <i class="bi bi-plus-lg"></i> تقديم طلب جديد
    </a>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('media.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\media\thanks.blade.php ENDPATH**/ ?>