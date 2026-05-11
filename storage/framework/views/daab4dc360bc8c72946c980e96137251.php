
<?php ($activeModule = 'finance'); ?>
<?php $__env->startSection('title', 'ذمة الطالب'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold"><i class="bi bi-person-vcard"></i> ذمة الطالب</h4>
      <div class="text-muted fw-semibold">
        <?php echo e($student->full_name); ?>

        — <code><?php echo e($student->university_id); ?></code>
        <?php if($student->phone): ?> — <?php echo e($student->phone); ?> <?php endif; ?>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="<?php echo e(route('debts.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
      
      <a href="<?php echo e(route('debts.student.excel', $student)); ?>" class="btn btn-success rounded-pill px-4 fw-bold">
        <i class="bi bi-file-earmark-excel"></i> تصدير Excel
      </a>
      <a href="<?php echo e(route('students.show', $student)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-person"></i> ملف الطالب
      </a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="text-muted small fw-semibold mb-1">إجمالي المستحق</div>
        <div class="fw-bold fs-5"><?php echo e(number_format($summaryTotal, 2)); ?></div>
        <div class="text-muted small"><?php echo e($summaryCurrency); ?></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="text-muted small fw-semibold mb-1">المدفوع</div>
        <div class="fw-bold fs-5 text-success"><?php echo e(number_format($summaryPaid, 2)); ?></div>
        <div class="text-muted small"><?php echo e($summaryCurrency); ?></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="text-muted small fw-semibold mb-1">المتبقي</div>
        <div class="fw-bold fs-5 <?php echo e($summaryRemaining > 0 ? 'text-danger' : 'text-success'); ?>">
          <?php echo e(number_format(abs($summaryRemaining), 2)); ?>

        </div>
        <div class="text-muted small"><?php echo e($summaryRemaining < 0 ? 'زيادة دفع' : $summaryCurrency); ?></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="text-muted small fw-semibold mb-1">نسبة السداد</div>
        <div class="fw-bold fs-5"><?php echo e($summaryPct); ?>%</div>
        <div class="progress mt-2" style="height:6px;">
          <div class="progress-bar bg-success" style="width:<?php echo e($summaryPct); ?>%"></div>
        </div>
      </div>
    </div>
  </div>

  <?php $__empty_0 = true; $__currentLoopData = $debtByDiploma; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header fw-bold d-flex justify-content-between align-items-center"
        style="background:linear-gradient(135deg,#11998e,#38ef7d); color:#fff;">
        <span>
          <i class="bi bi-mortarboard"></i>
          <?php echo e(optional($item['diploma'])->name ?? 'دبلومة غير معروفة'); ?>

        </span>
        <span class="badge bg-white text-dark px-3">
          <?php echo e($item['payment_type'] === 'full' ? 'دفعة كاملة' : 'دفعات'); ?>

        </span>
      </div>
      <div class="card-body">

        <div class="row g-2 mb-3">
          <div class="col-4 text-center">
            <div class="small text-muted">المستحق</div>
            <div class="fw-bold"><?php echo e(number_format($item['total'], 2)); ?> <?php echo e($item['currency']); ?></div>
          </div>
          <div class="col-4 text-center">
            <div class="small text-muted">المدفوع</div>
            <div class="fw-bold text-success"><?php echo e(number_format($item['paid'], 2)); ?> <?php echo e($item['currency']); ?></div>
          </div>
          <div class="col-4 text-center">
            <div class="small text-muted">المتبقي</div>
            <div class="fw-bold <?php echo e($item['remaining'] > 0 ? 'text-danger' : 'text-success'); ?>">
              <?php echo e(number_format(abs($item['remaining']), 2)); ?> <?php echo e($item['currency']); ?>

            </div>
          </div>
        </div>

        <div class="progress mb-3" style="height:8px;">
          <div class="progress-bar bg-success" style="width:<?php echo e($item['pct']); ?>%"></div>
        </div>

        <?php if($item['payment_type'] === 'installments' && $item['installments']->count() > 0): ?>
          <h6 class="fw-bold text-muted mb-2 small">الدفعات المجدولة</h6>
          <div class="table-responsive mb-3">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>المبلغ</th>
                  <th>تاريخ الاستحقاق</th>
                  <th>الحالة</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $item['installments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td class="fw-bold"><?php echo e(number_format($inst->amount, 2)); ?> <?php echo e($item['currency']); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($inst->due_date)->format('Y-m-d')); ?></td>
                    <td>
                      <span class="badge bg-<?php echo e($inst['inst_class']); ?>"><?php echo e($inst['inst_label']); ?></span>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <?php if($item['transactions']->count() > 0): ?>
          <h6 class="fw-bold text-muted mb-2 small">الدفعات المُسجَّلة في الصندوق</h6>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>التاريخ</th>
                  <th>المبلغ</th>
                  <th>الصندوق</th>
                  <th>مرجع</th>
                  <th>ملاحظات</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $item['transactions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td class="text-muted small"><?php echo e($trx->id); ?></td>
                    <td><?php echo e($trx->trx_date->format('Y-m-d')); ?></td>
                    <td class="fw-bold text-success">
                      <?php echo e(number_format($trx->amount, 2)); ?> <?php echo e($trx->currency); ?>

                    </td>
                    <td class="small"><?php echo e(optional($trx->cashbox)->name ?? '-'); ?></td>
                    <td class="small text-muted"><?php echo e($trx->reference ?? '-'); ?></td>
                    <td class="small text-muted"><?php echo e($trx->notes ?? '-'); ?></td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center text-muted small py-2">
            <i class="bi bi-inbox"></i> لا توجد دفعات مسجّلة لهذه الدبلومة
          </div>
        <?php endif; ?>

      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
    <div class="alert alert-info">لا توجد خطط دفع مسجّلة لهذا الطالب.</div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\debts\show.blade.php ENDPATH**/ ?>