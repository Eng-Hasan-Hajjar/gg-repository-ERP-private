
<?php ($activeModule = 'attendance'); ?>
<?php $__env->startSection('title', 'طلبات الإجازات'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-center mb-3">

    <div>
      <h4 class="mb-0 fw-bold">طلبات الإجازات/الأذونات</h4>
      <div class="text-muted fw-semibold">مراجعة الطلبات والموافقة/الرفض</div>
    </div>

    <?php if(auth()->user()?->hasPermission('create_leaves')): ?>
      <a href="<?php echo e(route('leaves.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-circle"></i> طلب جديد
      </a>
    <?php endif; ?>
  </div>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-6 col-md-3">
          <select name="employee_id" class="form-select">
            <option value="">الموظف (الكل)</option>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($e->id); ?>" <?php if(request('employee_id') == $e->id): echo 'selected'; endif; ?>><?php echo e($e->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>


        <div class="col-6 col-md-2">
          <select name="type" class="form-select">
            <option value="">النوع (الكل)</option>
            <option value="leave" <?php if(request('type') == 'leave'): echo 'selected'; endif; ?>>إجازة</option>
            <option value="permission" <?php if(request('type') == 'permission'): echo 'selected'; endif; ?>>إذن</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <?php $__currentLoopData = ['pending' => 'معلق', 'approved' => 'مقبول', 'rejected' => 'مرفوض']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($k); ?>" <?php if(request('status') == $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control">
        </div>
        <div class="col-6 col-md-2">
          <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control">
        </div>

        <div class="col-6 col-md-1 d-grid">
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
            <th>الموظف</th>
            <th>النوع</th>
            <th class="hide-mobile">من</th>
            <th class="hide-mobile">إلى</th>
            <th>الحالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($l->id); ?></td>
              <td class="fw-bold"><?php echo e($l->employee?->full_name ?? '-'); ?></td>
              <td><?php echo e($l->type == 'leave' ? 'إجازة' : 'إذن'); ?></td>
              <td class="hide-mobile"><?php echo e($l->start_date->format('Y-m-d')); ?></td>
              <td class="hide-mobile"><?php echo e($l->end_date?->format('Y-m-d') ?? '-'); ?></td>
              <td>
                <span
                  class="badge bg-<?php echo e($l->status == 'approved' ? 'success' : ($l->status == 'rejected' ? 'danger' : 'secondary')); ?>">
                  <?php echo e($l->status == 'pending' ? 'معلّق' : ($l->status == 'approved' ? 'مقبول' : 'مرفوض')); ?>

                </span>
              </td>
              <td class="text-end d-flex gap-1 justify-content-end flex-wrap">

                <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('leaves.show', $l)); ?>"><i class="bi bi-eye"></i>
                  عرض</a>





                <?php if(auth()->user()?->hasPermission('delete_leaves')): ?>

                  <form method="POST" action="<?php echo e(route('leaves.destroy', $l)); ?>" class="d-inline"
                    onsubmit="return confirm('هل أنت متأكد من حذف طلب الإجازة؟')">

                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <button class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i> حذف
                    </button>

                  </form>

                <?php endif; ?>
              </td>



            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">لا يوجد طلبات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3"><?php echo e($leaves->links()); ?></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/leaves/index.blade.php ENDPATH**/ ?>