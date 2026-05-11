
<?php ($activeModule = 'reports'); ?>

<?php $__env->startSection('title','الإيرادات حسب الفرع'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <div class="page-title">الإيرادات حسب الفرع</div>
    <div class="page-subtitle">
        ملخص الإيرادات الفعلية المسجلة في النظام لكل فرع ضمن الفترة المحددة.
    </div>
</div>

<div class="clean-card">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>الفرع</th>
                <th>الإيرادات</th>
            </tr>
        </thead>

        <tbody>
        <?php $__empty_0 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <tr>
                <td><?php echo e($r['branch']); ?></td>

                <td>
                    <span class="stat-number">
                        <?php echo e(number_format($r['total'],2)); ?>

                    </span>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr>
                <td colspan="2" class="text-center text-muted">
                    لا توجد بيانات مالية ضمن الفترة المحددة
                </td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\reports\revenue-by-branch.blade.php ENDPATH**/ ?>