
<?php ($activeModule = 'students'); ?>
<?php $__env->startSection('title', 'الطلاب'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والحالات</div>
  </div>


  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="<?php echo e(route('students.create')); ?>">
    <i class="bi bi-person-plus"></i> طالب جديد
  </a>

</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('students.index')); ?>">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
        placeholder="بحث: الاسم / الرقم الجامعي / الهاتف / رمز الدبلومة">
    </div>

    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-3">
      <select name="status" class="form-select">
        <option value="">كل حالات الطالب</option>
        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </select>
    </div>

    <div class="col-12 col-md-2">
      <select name="registration_status" class="form-select">
        <option value="">حالة التسجيل</option>
        <?php $__currentLoopData = $registrationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('registration_status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </select>
    </div>

    <div class="col-12 col-md-1 d-grid">
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
          <th>الرقم الجامعي</th>
          <th>الاسم</th>

          <th class="hide-mobile">الفرع</th>

          <th class="hide-mobile">الحالة</th>
          <th class="hide-mobile">التسجيل</th>
          <th class="hide-mobile">مثبّت؟</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          
          <td class="hide-mobile"><?php echo e($s->id); ?></td>
          <td><code><?php echo e($s->university_id); ?></code></td>
          <td class="fw-semibold"><?php echo e($s->full_name); ?></td>

          <td class="hide-mobile"><?php echo e($s->branch->name ?? '-'); ?></td>

          <td class="hide-mobile"><span class="badge bg-secondary"><?php echo e($s->status_ar); ?></span></td>
          <td class="hide-mobile">
            <?php ($map = ['pending' => 'warning', 'confirmed' => 'success', 'archived' => 'secondary', 'dismissed' => 'danger', 'frozen' => 'info']); ?>
            <span class="badge bg-<?php echo e($map[$s->registration_ar] ?? 'secondary'); ?>">
              <?php echo e($s->registration_ar); ?>

            </span>
          </td>
          <td class="hide-mobile">
            <?php if($s->is_confirmed): ?>
              <span class="badge bg-success">نعم</span>
            <?php else: ?>
              <span class="badge bg-secondary">لا</span>
            <?php endif; ?>

          </td>
          <td class="text-end">
            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('students.show', $s)); ?>">
                <i class="bi bi-eye"></i> عرض
              </a>
            <?php endif; ?>

            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('students.edit', $s)); ?>">
                <i class="bi bi-pencil"></i> تعديل
              </a>
            <?php endif; ?>
            <?php if(auth()->user()?->hasPermission('delete_students')): ?>
              <form method="POST" action="<?php echo e(route('students.destroy', $s)); ?>" class="d-inline"
                onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> حذف
                </button>
              </form>
            <?php endif; ?>


            <?php if(!$s->is_confirmed): ?>

              <form method="POST" action="<?php echo e(route('students.confirm', $s)); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-success">تثبيت</button>
              </form>

            <?php endif; ?>

          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>


  





  </div>
</div>

<div class="mt-3">
  <?php echo e($students->links()); ?>

</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/index.blade.php ENDPATH**/ ?>