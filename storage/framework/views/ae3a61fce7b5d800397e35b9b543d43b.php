


<?php $__env->startSection('title', 'تقرير الحضور لشهر'); ?>

<?php $__env->startSection('content'); ?>


    <h3>
        تقرير الحضور لشهر
        <?php echo e(\Carbon\Carbon::parse(request('month', now()))->translatedFormat('F Y')); ?>

    </h3>



    <form method="GET" class="mb-3 d-flex gap-2">
        <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control" style="max-width:200px">

        <button class="btn btn-primary">عرض</button>

        <a hidden href="<?php echo e(route('reports.attendance.monthly.pdf', ['month' => $month])); ?>" class="btn btn-danger">
            تصدير PDF
        </a>

        <a href="<?php echo e(route('reports.attendance.monthly.excel', ['month' => $month])); ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> تصدير Excel
        </a>


    </form>

    <table class="table">
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>إجمالي الساعات</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                    $totalSeconds = 0;

                    for ($date = $start->copy(); $date <= $end; $date->addDay()) {
                        $totalSeconds += $user->workedSecondsOn($date);
                    }

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                ?>

                <tr>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($hours); ?> ساعة <?php echo e($minutes); ?> دقيقة</td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>







<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/reports/monthly.blade.php ENDPATH**/ ?>