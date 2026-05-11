
<?php $__env->startSection('title', 'عقود'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0 fw-bold">العقود</h4>
      <div class="text-muted fw-semibold"><?php echo e($employee->full_name); ?> — <code><?php echo e($employee->code); ?></code></div>
    </div>
    <?php if(auth()->user()?->hasPermission('manage_contracts')): ?>
      <a href="<?php echo e(route('employees.contracts.create', $employee)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-circle"></i> إضافة عقد
      </a>
    <?php endif; ?>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>بداية</th>
            <th>نهاية</th>
            <th>نوع</th>
            <th>راتب</th>
            <th>ساعة</th>
            <th>عملة</th>
            <th class="text-end">إجراءات</th>

          </tr>
        </thead>
        <tbody>
          <?php $__empty_0 = true; $__currentLoopData = $employee->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <tr>
              <td><?php echo e($c->id); ?></td>
              <td><?php echo e($c->start_date?->format('Y-m-d')); ?></td>
              <td><?php echo e($c->end_date?->format('Y-m-d') ?? '-'); ?></td>
              <td><?php echo e($c->contract_type); ?></td>
              <td><?php echo e($c->salary_amount ?? '-'); ?></td>
              <td><?php echo e($c->hour_rate ?? '-'); ?></td>
              <td><?php echo e($c->currency); ?></td>
              <td class="text-end">
                <?php if(auth()->user()?->hasPermission('manage_contracts')): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('employees.contracts.edit', [$employee, $c])); ?>">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <form class="d-inline" method="POST" action="<?php echo e(route('employees.contracts.destroy', [$employee, $c])); ?>"
                    onsubmit="return confirm('حذف العقد؟');">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                <?php endif; ?>
              </td>

            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">لا يوجد عقود</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\employees\contracts\index.blade.php ENDPATH**/ ?>