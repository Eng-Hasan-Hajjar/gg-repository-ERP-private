
<?php ($activeModule = 'attendance'); ?>
<?php $__env->startSection('title', 'تفاصيل طلب'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">طلب #<?php echo e($leave->id); ?></h4>
      <div class="text-muted fw-semibold">
        <?php echo e($leave->employee->full_name); ?> — <?php echo e($leave->type == 'leave' ? 'إجازة' : 'إذن'); ?>

        — من <?php echo e($leave->start_date->format('Y-m-d')); ?>

        إلى <?php echo e($leave->end_date?->format('Y-m-d') ?? $leave->start_date->format('Y-m-d')); ?>

      </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-4"><b>الحالة:</b>
          <span
            class="badge bg-<?php echo e($leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'secondary')); ?>">
            <?php echo e($leave->status); ?>

          </span>
        </div>
        <div class="col-12 col-md-8"><b>السبب:</b> <?php echo e($leave->reason ?? '-'); ?></div>

        <div class="col-12 mt-2"><b>ملاحظة الأدمن:</b> <?php echo e($leave->admin_note ?? '-'); ?></div>
      </div>

      <?php if($leave->status === 'pending'): ?>
        <hr>
        <div class="row g-2">
          <div class="col-12 col-lg-6">
            <?php if(auth()->user()?->hasPermission('approve_leaves')): ?>
              <form method="POST" action="<?php echo e(route('leaves.approve', $leave)); ?>">
                <?php echo csrf_field(); ?>
                <label class="form-label fw-bold">ملاحظة (اختياري)</label>
                <textarea name="admin_note" rows="2" class="form-control mb-2"></textarea>
                <button class="btn btn-success rounded-pill px-4 fw-bold">
                  <i class="bi bi-check2-circle"></i> موافقة
                </button>
              </form>
            <?php endif; ?>
          </div>

          <div class="col-12 col-lg-6">
            <?php if(auth()->user()?->hasPermission('reject_leaves')): ?>
              <form method="POST" action="<?php echo e(route('leaves.reject', $leave)); ?>">
                <?php echo csrf_field(); ?>
                <label class="form-label fw-bold">سبب الرفض (إلزامي)</label>
                <textarea name="admin_note" rows="2" class="form-control mb-2" required></textarea>
                <button class="btn btn-danger rounded-pill px-4 fw-bold">
                  <i class="bi bi-x-circle"></i> رفض
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/leaves/show.blade.php ENDPATH**/ ?>