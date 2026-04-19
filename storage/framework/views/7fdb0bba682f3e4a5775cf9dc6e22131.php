
<?php $__env->startSection('title','لوحة المالية'); ?>

<?php $__env->startSection('content'); ?>

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-speedometer2"></i>
        لوحة التحكم المالية
    </div>

    <div class="p-4">

        <h5 class="fw-bold mb-3">إجمالي شامل</h5>

        <div class="row g-3">

            <?php $__currentLoopData = $totalTotals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="col-md-4">

                    <div class="glass-card p-3 text-center">

                        <div class="fw-bold mb-2">
                            <?php echo e($currency); ?>

                        </div>

                        <div class="text-success fw-bold">
                            دخل: <?php echo e(number_format($row['in'],2)); ?>

                        </div>

                        <div class="text-danger fw-bold">
                            مصاريف: <?php echo e(number_format($row['out'],2)); ?>

                        </div>

                        <hr>

                        <div class="fw-bold fs-5">
                            الصافي: <?php echo e(number_format($row['in'] - $row['out'],2)); ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/finance/dashboard.blade.php ENDPATH**/ ?>