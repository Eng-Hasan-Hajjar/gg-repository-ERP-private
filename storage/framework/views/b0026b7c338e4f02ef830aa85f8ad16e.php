
<?php $__env->startSection('title','التقرير اليومي'); ?>

<?php $__env->startSection('content'); ?>

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-calendar-event"></i>
        التقرير اليومي
    </div>

    <div class="p-4">

        <form class="mb-4 d-flex gap-2">
            <input type="date" name="date" value="<?php echo e($date); ?>" class="form-control w-auto">
            <button class="btn btn-namaa btn-pill">
                عرض
            </button>
        </form>

        <div class="row g-3 mb-4">

            <?php $__currentLoopData = $totals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="col-md-4">
                    <div class="p-3 border rounded-4 h-100">

                        <div class="fw-bold mb-2">
                            <?php echo e($currency); ?>

                        </div>

                        <div class="text-success fw-bold">
                            المقبوض: <?php echo e(number_format($row['in'],2)); ?>

                        </div>

                        <div class="text-danger fw-bold">
                            المدفوع: <?php echo e(number_format($row['out'],2)); ?>

                        </div>

                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="small text-muted">
                    <tr>
                        <th>التاريخ</th>
                        <th>الطالب</th>
                        <th>الدبلومة</th>
                        <th>الصندوق</th>
                        <th>العملة</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($t->trx_date); ?></td>
                            <td>
                                <?php echo e(optional(optional($t->account)->accountable)->full_name ?? '-'); ?>

                            </td>
                            <td><?php echo e(optional($t->diploma)->name ?? '-'); ?></td>
                            <td><?php echo e($t->cashbox->name ?? '-'); ?></td>
                            <td><?php echo e($t->currency); ?></td>
                            <td class="fw-bold"><?php echo e(number_format($t->amount,2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/finance/reports/daily.blade.php ENDPATH**/ ?>