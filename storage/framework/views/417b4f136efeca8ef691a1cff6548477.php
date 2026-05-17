
<?php ($activeModule = 'branches'); ?>
<?php $__env->startSection('title','الفروع'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <div>
    <h4 class="mb-1">إدارة الفروع</h4>
    <div class="text-muted small">إضافة وتعديل الفروع وربطها بالطلاب.</div>
  </div>
  <?php if(auth()->user()?->hasPermission('create_branches')): ?>
  <a class="btn btn-primary" href="<?php echo e(route('branches.create')); ?>">
    + إضافة فرع
  </a>
  <?php endif; ?>
</div>

<form class="card card-body mb-3" method="GET" action="<?php echo e(route('branches.index')); ?>">
  <div class="row g-2 align-items-end">
    <div class="col-md-9">
      <label class="form-label mb-1">بحث</label>
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
             placeholder="ابحث بالاسم أو الرمز">
    </div>
    <div class="col-md-3 d-grid">
      <button class="btn btn-namaa">تطبيق</button>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>اسم الفرع</th>
          <th>الرمز</th>
          <th>عدد الطلاب</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($b->id); ?></td>
            <td class="fw-semibold"><?php echo e($b->name); ?></td>
            <td><span class="badge text-bg-secondary"><?php echo e($b->code); ?></span></td>
            <td><span class="badge text-bg-light border"><?php echo e($b->students()->count()); ?></span></td>
            <td class="text-end">
              <div class="d-inline-flex gap-1">
                <?php if(auth()->user()?->hasPermission('edit_branches')): ?>
                <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('branches.edit', $b)); ?>" hidden>تعديل</a>
                <?php endif; ?>
                <?php if(auth()->user()?->hasPermission('delete_branches')): ?>
                <form method="POST" action="<?php echo e(route('branches.destroy', $b)); ?>"
                      onsubmit="return confirm('هل أنت متأكد من حذف الفرع؟');" hidden>
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger">حذف</button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">لا يوجد فروع</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($branches->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/branches/index.blade.php ENDPATH**/ ?>