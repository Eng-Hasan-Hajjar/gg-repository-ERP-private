
<?php ($activeModule='exams'); ?>
<?php $__env->startSection('title','الامتحانات'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">الامتحانات</h4>
    <div class="text-muted small">فلترة حسب الفرع/الدبلومة/المدرب/الفترة</div>
  </div>

  <?php if(auth()->user()?->hasPermission('create_exams')): ?>
  <a class="btn btn-primary rounded-pill fw-bold px-4" href="<?php echo e(route('exams.create')); ?>">
    <i class="bi bi-plus-circle"></i> امتحان جديد
  </a>
  <?php endif; ?>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('exams.index')); ?>">
  <div class="row g-2">
    <div class="col-12 col-md-3">
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: اسم/كود">
    </div>

    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id')==$b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="diploma_id" class="form-select">
        <option value="">كل الدبلومات</option>
        <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($d->id); ?>" <?php if(request('diploma_id')==$d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="trainer_id" class="form-select">
        <option value="">المدرب (الكل)</option>
        <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($t->id); ?>" <?php if(request('trainer_id')==$t->id): echo 'selected'; endif; ?>><?php echo e($t->full_name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-1">
      <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control">
    </div>
    <div class="col-6 col-md-1">
      <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control">
    </div>

    <div class="col-12 col-md-1 d-grid">
      <button class="btn btn-namaa fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>التاريخ</th>
          <th>الامتحان</th>
          <th>الدبلومة</th>
          <th>الفرع</th>
          <th class="hide-mobile">المدرب</th>
          <th class="hide-mobile">الحد الأعلى</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="fw-bold"><?php echo e($e->exam_date?->format('Y-m-d') ?? '-'); ?></td>
            <td>
              <div class="fw-bold"><?php echo e($e->title); ?></div>
              <div class="small text-muted"><?php echo e($e->code ?? '-'); ?> — <?php echo e($e->type); ?></div>
            </td>
            <td><?php echo e($e->diploma->name ?? '-'); ?>  <?php echo e($e->diploma->code ?? '-'); ?></td>
            <td><?php echo e($e->branch->name ?? '-'); ?></td>
            <td class="hide-mobile"><?php echo e($e->trainer->full_name ?? '-'); ?></td>
            <td class="hide-mobile"><?php echo e($e->max_score); ?></td>
            <td class="text-end">
              <?php ($studentId = request('student_id')); ?>


                  <?php if(auth()->user()?->hasPermission('view_exams')): ?>

                      <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('exams.show',$e)); ?>">
                        <i class="bi bi-eye"></i> عرض
                      </a>

                  <?php endif; ?>

                  <?php if(auth()->user()?->hasPermission('edit_exams')): ?>

                      <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('exams.edit',$e)); ?>">
                        <i class="bi bi-pencil"></i> تعديل
                      </a>

                  <?php endif; ?>



                  <form action="<?php echo e(route('exams.destroy',$e)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>

                        <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('هل أنت متأكد من حذف الامتحان؟')">
                            حذف
                        </button>
                    </form>

                <?php if(auth()->user()?->hasPermission('enter_grades')): ?>

                  <a class="btn btn-sm btn-namaa" href="<?php echo e(route('exams.results.edit',$e)); ?>">
                      إدخال العلامات
                  </a>

                <?php endif; ?>

            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد امتحانات</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($exams->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/exams/index.blade.php ENDPATH**/ ?>