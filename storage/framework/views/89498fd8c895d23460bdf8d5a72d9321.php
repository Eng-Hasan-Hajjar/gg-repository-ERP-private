
<?php ($activeModule = 'users'); ?>

<?php $__env->startSection('title', 'إدارة الأدوار'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <div>
      <h4 class="mb-0 fw-bold">إدارة الأدوار والصلاحيات</h4>
      <div class="text-muted small">بحث متقدم + فلاتر ذكية</div>
    </div>
    <?php if(auth()->user()?->hasPermission('manage_roles')): ?>
      <a class="btn btn-primary rounded-pill px-4 fw-bold" href="<?php echo e(route('admin.roles.create')); ?>">
        <i class="bi bi-shield-plus"></i> دور جديد
      </a>
    <?php endif; ?>
  </div>

  <form class="card card-body border-0 shadow-sm mb-3" method="GET">
    <div class="row g-2">

      <div class="col-12 col-md-6">
        <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث باسم الدور أو الوصف">
      </div>

      <div class="col-8 col-md-4">
        <select name="has_users" class="form-select">
          <option value="">كل الأدوار</option>
          <option value="yes" <?php if(request('has_users') == 'yes'): echo 'selected'; endif; ?>>
            لديها مستخدمون
          </option>
          <option value="no" <?php if(request('has_users') == 'no'): echo 'selected'; endif; ?>>
            بلا مستخدمين
          </option>
        </select>
      </div>

      <div class="col-4 col-md-2 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th>الاسم المعروض</th>
            <th class="hide-mobile">الوصف</th>
            <th>المستخدمون</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>

        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($r->id); ?></td>
              <td class="fw-semibold"><?php echo e($r->label); ?></td>
              <td class="hide-mobile"><?php echo e($r->description ?: '-'); ?></td>
              <td>
                <span class="badge bg-secondary">
                  <?php echo e($r->users_count); ?>

                </span>
              </td>

              <td class="text-end">


                <a class="btn btn-sm btn-outline-success" href="<?php echo e(route('admin.roles.show', $r)); ?>">
                  <i class="bi bi-eye"></i> تفاصيل
                </a>

                <?php if(auth()->user()?->hasPermission('assign_permissions')): ?>
                  <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.roles.edit', $r)); ?>">
                    <i class="bi bi-pencil"></i> تعديل
                  </a>
                <?php endif; ?>

                <?php if(auth()->user()?->hasPermission('manage_roles')): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('admin.roles.users', $r)); ?>">
                    <i class="bi bi-people"></i> مستخدمون
                  </a>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('admin.roles.clone', $r)); ?>" class="d-inline">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-copy"></i> نسخ
                  </button>
                </form>

                <?php if($r->name !== 'super_admin' && $r->users_count == 0): ?>
                  <form method="POST" action="<?php echo e(route('admin.roles.destroy', $r)); ?>" class="d-inline"
                    onsubmit="return confirm('هل أنت متأكد؟')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                <?php endif; ?>

              </td>

            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                لا يوجد أدوار
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <?php echo e($roles->links()); ?>

  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>