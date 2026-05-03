
<?php $__env->startSection('title', ' الموارد البشرية'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold">الموارد البشرية</h4>
      <div class="text-muted fw-semibold">ملفات — عقود — مستحقات — ربط بالدبلومات</div>
    </div>

    
    <?php if(auth()->user()?->hasPermission('create_trainer')): ?>
      <a href="<?php echo e(route('employees.create', ['type' => 'trainer'])); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        إضافة مدرب
      </a>
    <?php endif; ?>
    <?php if(auth()->user()?->hasPermission('create_employees')): ?>
       <a href="<?php echo e(route('employees.create', ['type' => 'employee'])); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        إضافة موظف
      </a>
    <?php endif; ?>


  </div>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-3">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
            placeholder="بحث: الاسم / الكود / الهاتف">
        </div>



        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="active" <?php if(request('status') == 'active'): echo 'selected'; endif; ?>>نشط</option>
            <option value="inactive" <?php if(request('status') == 'inactive'): echo 'selected'; endif; ?>>غير نشط</option>
          </select>
        </div>

        <div class="col-12 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">الفرع (الكل)</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12 col-md-2 d-grid">
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
            <th class="hide-mobile">الكود</th>
            <th>الاسم</th>
            <th>النوع</th>
            <th>الفرع</th>
            <th>الحالة</th>
            <th>الحساب</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($e->id); ?></td>
              <td class="hide-mobile"><code><?php echo e($e->code); ?></code></td>
              <td class="fw-bold"><?php echo e($e->full_name); ?></td>
              <td>
                <span class="badge bg-<?php echo e($e->type == 'trainer' ? 'primary' : 'secondary'); ?>">
                  <?php echo e($e->type == 'trainer' ? 'مدرب' : 'موظف'); ?>

                </span>
              </td>
              <td><?php echo e($e->branch->name ?? '-'); ?></td>
              <td>
                <span class="badge bg-<?php echo e($e->status == 'active' ? 'success' : 'secondary'); ?>">
                  <?php echo e($e->status == 'active' ? 'نشط' : 'غير نشط'); ?>

                </span>
              </td>
              <td>
                <?php if($e->user): ?>
                  <span class="badge bg-success">مرتبط</span>
                <?php else: ?>
                  <span class="badge bg-light text-dark border">—</span>
                <?php endif; ?>
              </td>

              <td class="text-end">
                <?php if(auth()->user()?->hasPermission('view_employees')): ?>
                  <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('employees.show', $e)); ?>">
                    <i class="bi bi-eye"></i> عرض
                  </a>
                <?php endif; ?>
                <?php if(auth()->user()?->hasPermission('edit_employees')): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('employees.edit', $e)); ?>">
                    <i class="bi bi-pencil"></i> تعديل
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد بيانات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <?php echo e($employees->links()); ?>

  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/employees/index.blade.php ENDPATH**/ ?>