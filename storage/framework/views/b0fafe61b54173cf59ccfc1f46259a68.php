
<?php $__env->startSection('title', 'المستحقات'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">المستحقات</h4>
      <div class="text-muted fw-semibold">
        <?php echo e($employee->full_name); ?> — <code><?php echo e($employee->code); ?></code>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      <a href="<?php echo e(route('employees.show', $employee)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع للملف
      </a>
      <?php if(auth()->user()?->hasPermission('manage_salaries')): ?>
        <a href="<?php echo e(route('employees.payouts.create', $employee)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة مستحق
        </a>
      <?php endif; ?>
    </div>
  </div>



  <form class="card border-0 shadow-sm mb-3" method="GET">
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">فلترة حسب الحالة</label>
          <select name="status" class="form-select">
            <option value="">الكل</option>
            <option value="pending" <?php if(request('status') == 'pending'): echo 'selected'; endif; ?>>معلّق</option>
            <option value="paid" <?php if(request('status') == 'paid'): echo 'selected'; endif; ?>>مدفوع</option>
          </select>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <a href="<?php echo e(route('employees.payouts.index', $employee)); ?>" class="btn btn-outline-secondary fw-bold">مسح
            الفلتر</a>
        </div>
      </div>
    </div>
  </form>




  <?php
    $paid = $employee->payouts->where('status', 'paid');
    $pending = $employee->payouts->where('status', 'pending');

    $sumPaidByCur = $paid->groupBy('currency')->map(fn($rows) => $rows->sum('amount'));
    $sumPendingByCur = $pending->groupBy('currency')->map(fn($rows) => $rows->sum('amount'));
  ?>

  <div class="row g-3 mb-3">
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0"><i class="bi bi-check2-circle text-success"></i> المدفوع</h6>
            <span class="badge bg-success"><?php echo e($paid->count()); ?></span>
          </div>
          <hr>
          <?php if($sumPaidByCur->count()): ?>
            <?php $__currentLoopData = $sumPaidByCur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur => $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="d-flex justify-content-between fw-semibold mb-1">
                <span><?php echo e($cur); ?></span>
                <span><?php echo e(number_format($sum, 2)); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <div class="text-muted fw-semibold">لا يوجد مدفوعات.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split text-warning"></i> المعلّق</h6>
            <span class="badge bg-warning text-dark"><?php echo e($pending->count()); ?></span>
          </div>
          <hr>
          <?php if($sumPendingByCur->count()): ?>
            <?php $__currentLoopData = $sumPendingByCur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur => $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="d-flex justify-content-between fw-semibold mb-1">
                <span><?php echo e($cur); ?></span>
                <span><?php echo e(number_format($sum, 2)); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <div class="text-muted fw-semibold">لا يوجد مستحقات معلّقة.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>التاريخ</th>
            <th>المبلغ</th>
            <th>العملة</th>
            <th>الحالة</th>
            <th>مرجع</th>
            <th>ملاحظات</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_0 = true; $__currentLoopData = $employee->payouts->sortByDesc('payout_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <tr>
              <td><?php echo e($p->id); ?></td>
              <td><?php echo e($p->payout_date?->format('Y-m-d')); ?></td>
              <td class="fw-bold"><?php echo e(number_format($p->amount, 2)); ?></td>
              <td><span class="badge bg-light text-dark border"><?php echo e($p->currency); ?></span></td>
              <td>
                <span class="badge bg-<?php echo e($p->status == 'paid' ? 'success' : 'warning text-dark'); ?>">
                  <?php echo e($p->status == 'paid' ? 'مدفوع' : 'معلق'); ?>

                </span>
              </td>
              <td><?php echo e($p->reference ?? '-'); ?></td>
              <td class="text-muted small"><?php echo e($p->notes ? \Illuminate\Support\Str::limit($p->notes, 60) : '-'); ?></td>
              <td class="text-end">
                <?php if(auth()->user()?->hasPermission('manage_salaries')): ?>
                  <?php if($p->status !== 'paid'): ?>
                    <form class="d-inline" method="POST" action="<?php echo e(route('employees.payouts.markPaid', [$employee, $p])); ?>">
                      <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                      <button class="btn btn-sm btn-outline-success" title="تحويل إلى مدفوع">
                        <i class="bi bi-check2-circle"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('employees.payouts.edit', [$employee, $p])); ?>">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form class="d-inline" method="POST" action="<?php echo e(route('employees.payouts.destroy', [$employee, $p])); ?>"
                    onsubmit="return confirm('حذف المستحق؟');">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد مستحقات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\employees\payouts\index.blade.php ENDPATH**/ ?>