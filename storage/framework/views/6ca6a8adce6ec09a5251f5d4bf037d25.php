
<?php ($activeModule = 'programs'); ?>

<?php $__env->startSection('title', 'إدارة البرامج'); ?>

<?php $__env->startSection('content'); ?>

   <div class="d-flex justify-content-between align-items-center mb-3">

<h4 class="fw-bold">

<?php if(request('type') == 'online'): ?>
إدارة البرامج الأونلاين

<?php elseif(request('type') == 'onsite'): ?>
إدارة البرامج الحضورية

<?php else: ?>
إدارة جميع البرامج
<?php endif; ?>

</h4>

</div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الدبلومة</th>
                        <th>السعر</th>
                        <th>الحملة</th>
                        <th class="text-end">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($r->id); ?></td>
                            <td><?php echo e($r->diploma->name); ?></td>
                            <td><?php echo e($r->price ?? '-'); ?></td>
                            <td>
                                <?php echo e($r->campaign_start ?? '-'); ?>

                            </td>
                            <td class="text-end">
                                <a href="<?php echo e(route('programs.management.edit', $r->diploma_id)); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    إدارة
                                </a>


                                <a href="<?php echo e(route('programs.management.show', $r->diploma)); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    لوحة البرنامج
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                لا يوجد بيانات
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/programs_management/index.blade.php ENDPATH**/ ?>