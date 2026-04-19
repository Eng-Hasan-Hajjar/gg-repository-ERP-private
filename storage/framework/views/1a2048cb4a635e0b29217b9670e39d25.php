
<?php ($activeModule = 'finance'); ?>

<?php $__env->startSection('title', 'الصناديق المالية'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold">الصناديق والحسابات المالية</h4>
      <div class="text-muted fw-semibold">إدارة الصناديق حسب الفرع والعملة + سجل الحركات</div>
    </div>


    

      <a href="<?php echo e(route('finance.dashboard')); ?>" class="btn btn-warning fw-bold">
        📊 لوحة المالية
      </a>

      <a href="<?php echo e(route('finance.reports.diplomas')); ?>" class="btn btn-outline-primary">
        📘 تقرير الدبلومات
      </a>

      <a href="<?php echo e(route('finance.reports.profit')); ?>" class="btn btn-outline-success">
        💰 أرباح البرامج
      </a>

      <a href="<?php echo e(route('finance.reports.daily')); ?>" class="btn btn-outline-dark">
        🗓 التقرير اليومي
      </a>




   



    <?php if(auth()->user()?->hasPermission('create_cashboxes')): ?>
        <a href="<?php echo e(route('cashboxes.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة صندوق
        </a>
      </div>
    <?php endif; ?>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-4">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: اسم/كود">
        </div>

        <div class="col-6 col-md-3">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="currency" class="form-select">
            <option value="">كل العملات</option>
            <?php $__currentLoopData = ['USD', 'TRY', 'EUR']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c); ?>" <?php if(request('currency') == $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="active" <?php if(request('status') == 'active'): echo 'selected'; endif; ?>>نشط</option>
            <option value="inactive" <?php if(request('status') == 'inactive'): echo 'selected'; endif; ?>>غير نشط</option>
          </select>
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
            <th class="hide-mobile">الكود</th>
            <th>الاسم</th>
            <th>الفرع</th>
            <th>العملة</th>
            <th>الحالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $cashboxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($c->id); ?></td>
              <td class="hide-mobile"><code><?php echo e($c->code); ?></code></td>
              <td class="fw-bold"><?php echo e($c->name); ?></td>
              <td><?php echo e($c->branch->name ?? '-'); ?></td>
              <td><span class="badge bg-light text-dark border"><?php echo e($c->currency); ?></span></td>
              <td>
                <span class="badge bg-<?php echo e($c->status == 'active' ? 'success' : 'secondary'); ?>">
                  <?php echo e($c->status == 'active' ? 'نشط' : 'غير نشط'); ?>

                </span>
              </td>
              <td class="text-end">

                <?php if(auth()->user()?->hasPermission('view_cashboxes')): ?>
                  <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('cashboxes.show', $c)); ?>">
                    <i class="bi bi-eye"></i> عرض
                  </a>
                <?php endif; ?>

                <a class="btn btn-sm btn-outline-success" href="<?php echo e(route('cashboxes.transactions.index', $c)); ?>" hidden>
                  <i class="bi bi-arrow-left-right"></i> الحركات
                </a>

                <?php if(auth()->user()?->hasPermission('edit_cashboxes')): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('cashboxes.edit', $c)); ?>">
                    <i class="bi bi-pencil"></i> تعديل
                  </a>
                <?php endif; ?>

                <?php if($c->attachment_path): ?>
                  <a class="btn btn-sm btn-outline-primary" target="_blank"
                    href="<?php echo e(asset('storage/' . $c->attachment_path)); ?>">
                    <i class="bi bi-paperclip"></i> مرفق
                  </a>
                <?php endif; ?>




              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">لا يوجد صناديق</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <?php echo e($cashboxes->links()); ?>

  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/index.blade.php ENDPATH**/ ?>