
<?php $__env->startSection('title', 'مجموعات الرؤية'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">مجموعات الرؤية</h4>
    <div class="text-muted small">تحكم بمن يرى تقارير ومهام من</div>
  </div>
  <a href="<?php echo e(route('admin.visibility-groups.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> مجموعة جديدة
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>اسم المجموعة</th>
          <th class="text-center">عدد الأعضاء</th>
          <th>الملاحظات</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($g->id); ?></td>
            <td class="fw-bold"><?php echo e($g->name); ?></td>
            <td class="text-center">
              <span class="badge bg-primary"><?php echo e($g->employees_count); ?></span>
            </td>
            <td class="text-muted small"><?php echo e($g->notes ?? '—'); ?></td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.visibility-groups.edit', $g)); ?>"
                 class="btn btn-sm btn-outline-dark">
                <i class="bi bi-pencil"></i> تعديل
              </a>
              <form method="POST" action="<?php echo e(route('admin.visibility-groups.destroy', $g)); ?>"
                    class="d-inline" onsubmit="return confirm('حذف المجموعة؟')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">لا توجد مجموعات</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/admin/visibility_groups/index.blade.php ENDPATH**/ ?>