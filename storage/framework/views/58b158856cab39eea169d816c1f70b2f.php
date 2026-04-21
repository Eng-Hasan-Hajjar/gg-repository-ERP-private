
<?php $__env->startSection('title','تقرير الدبلومات'); ?>

<?php $__env->startSection('content'); ?>

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-mortarboard"></i>
        إجمالي دفعات الطلاب لكل دبلومة
    </div>

    <div class="p-4">

        <?php $__empty_1 = true; $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="glass-card mb-4 p-4">

                <h5 class="fw-bold mb-4">
                    <?php echo e($data['diploma']->name ?? '-'); ?>

                </h5>

                <div class="row g-3">

                    <?php $__currentLoopData = $data['currencies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-md-4">
                            <div class="p-3 border rounded-4 h-100">

                                <div class="text-muted small mb-1">
                                    العملة
                                </div>

                                <div class="fw-bold fs-5">
                                    <?php echo e($currency); ?>

                                </div>

                                <hr>

                                <div class="fw-bold fs-4">
                                    <?php echo e(number_format($row['total_amount'],2)); ?>

                                </div>

                                <div class="text-muted small">
                                    عدد الدفعات: <?php echo e($row['total_payments']); ?>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="alert alert-warning mb-0">
                لا يوجد بيانات حالياً
            </div>

        <?php endif; ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/finance/reports/diplomas.blade.php ENDPATH**/ ?>