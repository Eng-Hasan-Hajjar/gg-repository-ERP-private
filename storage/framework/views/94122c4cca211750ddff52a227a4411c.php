
<?php $__env->startSection('title','الأرباح حسب البرنامج'); ?>

<?php $__env->startSection('content'); ?>

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-graph-up"></i>
        الأرباح حسب البرنامج
    </div>

    <div class="p-4">

        <?php $__empty_1 = true; $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="glass-card mb-4 p-4">

                <h5 class="fw-bold mb-4">
                    <?php echo e($data['diploma']->name ?? '-'); ?>

                </h5>

                <div class="row g-3">

                    <?php $__currentLoopData = $data['currencies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-md-4">
                            <div class="p-3 border rounded-4 h-100">

                                <div class="text-muted small mb-1">
                                    العملة
                                </div>

                                <div class="fw-bold fs-5">
                                    <?php echo e($currency); ?>

                                </div>

                                <hr>

                                <div class="fw-bold fs-4 text-success">
                                    <?php echo e(number_format($amount,2)); ?>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="alert alert-warning mb-0">
                لا يوجد بيانات
            </div>

        <?php endif; ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/finance/reports/profit.blade.php ENDPATH**/ ?>