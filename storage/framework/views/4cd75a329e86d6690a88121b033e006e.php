
<?php ($activeModule='tasks'); ?>
<?php $__env->startSection('title','مهام اليوم'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">مهام اليوم</h4>
    <div class="text-muted fw-semibold">إسناد مهام + متابعة التنفيذ + حالات وأولويات</div>
  </div>

    <?php if(auth()->user()?->hasPermission('create_tasks')): ?>
  <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> إضافة مهمة
  </a>
  <?php endif; ?>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: عنوان/وصف">
      </div>

      <div class="col-6 col-md-2">
        <select name="branch_id" class="form-select">
          <option value="">الفرع (الكل)</option>
          <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id')==$b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="assigned_to" class="form-select">
          <option value="">المسند له (الكل)</option>
          <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($e->id); ?>" <?php if(request('assigned_to')==$e->id): echo 'selected'; endif; ?>><?php echo e($e->full_name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <?php $__currentLoopData = ['todo','in_progress','done','blocked','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($s); ?>" <?php if(request('status')==$s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="priority" class="form-select">
          <option value="">الأولوية (الكل)</option>
          <?php $__currentLoopData = ['low','medium','high','urgent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($p); ?>" <?php if(request('priority')==$p): echo 'selected'; endif; ?>><?php echo e($p); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-12 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="hide-mobile">#</th>
          <th>العنوان</th>
          <th class="hide-mobile">الفرع</th>
          <th>المسند له</th>
          <th class="hide-mobile">أولوية</th>
          <th>حالة</th>
          <th class="hide-mobile" >الاستحقاق</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="hide-mobile"><?php echo e($t->id); ?></td>
            <td class="fw-bold"><?php echo e($t->title); ?></td>
            <td class="hide-mobile"><?php echo e($t->branch->name ?? '-'); ?></td>
            <td><?php echo e($t->assignee->full_name ?? '-'); ?></td>
            <td class="hide-mobile"><span class="badge bg-light text-dark border"><?php echo e($t->priority); ?></span></td>
            <td>
              <span class="badge bg-<?php echo e($t->status=='done'?'success':($t->status=='blocked'?'danger':'secondary')); ?>">
                <?php echo e($t->status); ?>

              </span>
            </td>
            <td class="hide-mobile"><?php echo e($t->due_date?->format('Y-m-d') ?? '-'); ?></td>
            <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
                <?php if(auth()->user()?->hasPermission('view_tasks')): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('tasks.show',$t)); ?>"><i class="bi bi-eye"></i> عرض</a>
          <?php endif; ?>
            <?php if(auth()->user()?->hasPermission('edit_tasks')): ?>
              <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('tasks.edit',$t)); ?>"><i class="bi bi-pencil"></i> تعديل</a>
<?php endif; ?>
  <?php if(auth()->user()?->hasPermission('complete_tasks')): ?>
              <form method="POST" action="<?php echo e(route('tasks.quickStatus',$t)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="status" value="done">
                <button class="btn btn-sm btn-outline-success"><i class="bi bi-check2-circle"></i> تم</button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">لا يوجد مهام</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($tasks->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/tasks/index.blade.php ENDPATH**/ ?>