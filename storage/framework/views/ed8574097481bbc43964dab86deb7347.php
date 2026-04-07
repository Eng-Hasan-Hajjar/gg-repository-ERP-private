
<?php $__env->startSection('title', 'CRM - العملاء المحتملين'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-0">قسم الاستشارات والمبيعات (CRM)</h4>
      <div class="text-muted small">العملاء المحتملون + المتابعات + التحويل إلى طلاب</div>
    </div>
    <a class="btn btn-primary rounded-pill px-4 fw-bold" href="<?php echo e(route('leads.create')); ?>">
      <i class="bi bi-person-plus"></i> عميل محتمل جديد
    </a>
  </div>

  <form class="card card-body shadow-sm border-0 mb-3" method="GET" action="<?php echo e(route('leads.index')); ?>">
    <div class="row g-2">
      <div class="col-md-4">
        <input class="form-control" name="search" value="<?php echo e(request('search')); ?>"
          placeholder="بحث: الاسم / الهاتف / واتساب">
      </div>

      <div class="col-md-2">
        <select class="form-select" name="branch_id">
          <option value="">كل الفروع</option>
          <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-md-2">
        <select class="form-select" name="diploma_id">
          <option value="">كل الدبلومات</option>
          <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($d->id); ?>" <?php if(request('diploma_id') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-md-2">
        <select class="form-select" name="stage">
          <option value="">المرحلة</option>
          <?php $__currentLoopData = $stageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('stage') == $key): echo 'selected'; endif; ?>>
              <?php echo e($label); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

      </div>

      <div class="col-md-2">
        <select class="form-select" name="registration_status">
          <option value="">حالة التسجيل</option>
          <?php $__currentLoopData = $registrationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('registration_status') == $key): echo 'selected'; endif; ?>>
              <?php echo e($label); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-12 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </form>

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th>الاسم</th>
            <th class="hide-mobile">هاتف</th>
            <th class="hide-mobile">الفرع</th>
            <th class="hide-mobile">الدبلومات</th>
            <th class="hide-mobile">المرحلة</th>
            <th class="hide-mobile">الحالة</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="hide-mobile"><?php echo e($l->id); ?></td>
              <td class="fw-bold"><?php echo e($l->full_name); ?></td>
              <td class="hide-mobile"><?php echo e($l->phone ?? '-'); ?></td>
              <td class="hide-mobile"><?php echo e($l->branch->name ?? '-'); ?></td>
              <td class="hide-mobile">
                <?php $__currentLoopData = $l->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="badge bg-light text-dark border"><?php echo e($d->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </td>
              <td class="hide-mobile"><span class="badge bg-info"><?php echo e($l->stage_ar); ?></span></td>

              <td class="hide-mobile">
                <span
                  class="badge bg-<?php echo e($l->registration_status === 'pending' ? 'warning' : ($l->registration_status === 'converted' ? 'success' : 'secondary')); ?>">
                  <?php echo e($l->registration_ar); ?>

                </span>
              </td>

              <td class="text-end">
                <?php if(auth()->user()?->hasPermission('view_leads')): ?>
                  <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('leads.show', $l)); ?>">عرض</a>
                <?php endif; ?>

                <?php if(auth()->user()?->hasPermission('edit_leads')): ?>
                  <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('leads.edit', $l)); ?>">تعديل</a>
                <?php endif; ?>

                <?php if(auth()->user()?->hasPermission('delete_leads')): ?>
                  <form method="POST" action="<?php echo e(route('leads.destroy', $l)); ?>" class="d-inline"
                    onsubmit="return confirm('هل أنت متأكد من حذف العميل؟'">

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
              <td colspan="8" class="text-center text-muted py-4">لا يوجد بيانات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3"><?php echo e($leads->links()); ?></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/index.blade.php ENDPATH**/ ?>