<?php if($paymentPlans->isEmpty()): ?>
    <div class="alert alert-info">لا توجد خطط دفع مسجلة لهذا الطالب.</div>
<?php else: ?>
    <?php $__currentLoopData = $paymentPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0"><?php echo e($plan->diploma->name ?? 'دبلومة'); ?></h6>
                <span class="badge <?php echo e($plan->remaining > 0 ? 'bg-danger' : 'bg-success'); ?>">
                    <?php echo e($plan->remaining > 0 ? 'عليه ذمة' : 'مسدّد'); ?>

                </span>
            </div>
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="fw-bold text-primary"><?php echo e(number_format($plan->total_amount, 2)); ?></div>
                    <div class="text-muted small">الإجمالي</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold text-success"><?php echo e(number_format($plan->paid, 2)); ?></div>
                    <div class="text-muted small">المدفوع</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold <?php echo e($plan->remaining > 0 ? 'text-danger' : 'text-success'); ?>">
                        <?php echo e(number_format($plan->remaining, 2)); ?>

                    </div>
                    <div class="text-muted small">المتبقي</div>
                </div>
            </div>

            <?php if($plan->installments->count()): ?>
            <hr class="my-2">
            <div class="small">
                <div class="fw-bold mb-1">الأقساط:</div>
                <?php $__currentLoopData = $plan->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span><?php echo e($inst->due_date); ?></span>
                    <span><?php echo e(number_format($inst->amount, 2)); ?> <?php echo e($plan->currency); ?></span>
                    <span class="badge <?php echo e($inst->paid_at ? 'bg-success' : 'bg-warning text-dark'); ?>">
                        <?php echo e($inst->paid_at ? 'مدفوع' : 'معلّق'); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH C:\Users\engya\Desktop\مواقع الزبائن\namaa\laravel11-auth\resources\views/students/modals/financial.blade.php ENDPATH**/ ?>