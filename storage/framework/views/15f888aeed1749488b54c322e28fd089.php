
<?php ($activeModule = 'reports'); ?>

<?php $__env->startSection('title','تنبيهات النظام'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <div class="page-title">تنبيهات النظام</div>
    <div class="page-subtitle">
        تحليل ذكي لحالة النظام اعتمادًا على البيانات الفعلية.
    </div>
</div>

<div class="clean-card">

    <?php $__empty_0 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>

        <div class="alert alert-<?php echo e($a['type']); ?> d-flex align-items-center gap-2">
            <i class="bi <?php echo e($a['icon']); ?>"></i>
            <?php echo e($a['message']); ?>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>

        <div class="text-success fw-bold">
            <i class="bi bi-check-circle"></i>
            لا توجد أي مخاطر حالياً — النظام يعمل بشكل طبيعي
        </div>

    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\reports\system-alerts.blade.php ENDPATH**/ ?>