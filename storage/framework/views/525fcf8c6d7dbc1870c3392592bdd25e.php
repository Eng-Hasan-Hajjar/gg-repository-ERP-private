
<?php ($activeModule = 'reports'); ?>

<?php $__env->startSection('title','نمو الطلاب'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <div class="page-title">نمو عدد الطلاب</div>
    <div class="page-subtitle">
        يوضح هذا التقرير عدد الطلاب المسجلين في كل شهر بناءً على تاريخ التسجيل الفعلي.
    </div>
</div>

<div class="clean-card">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>الشهر</th>
                <th>عدد الطلاب المسجلين</th>
            </tr>
        </thead>
        <tbody>

        <?php $__empty_0 = true; $__currentLoopData = $growth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <tr>
                <td><?php echo e($row['month']); ?></td>

                <td>
                    <span class="stat-number">
                        <?php echo e($row['total']); ?>

                    </span>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr>
                <td colspan="2" class="text-center text-muted">
                    لا توجد بيانات تسجيل خلال الفترة المحددة
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\reports\students-growth.blade.php ENDPATH**/ ?>