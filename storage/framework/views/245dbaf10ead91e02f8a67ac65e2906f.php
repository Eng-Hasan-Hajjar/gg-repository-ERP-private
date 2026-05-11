
<?php $__env->startSection('title','طلاب الامتحان'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">طلاب الامتحان — <?php echo e($exam->title); ?></h4>
    <div class="text-muted small">
      فرع: <?php echo e($exam->branch->name); ?> — دبلومة: <?php echo e($exam->diploma->name); ?>

    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="<?php echo e(route('exams.show',$exam)); ?>">
    رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: اسم/رقم جامعي">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-namaa fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="<?php echo e(route('exams.students.update',$exam)); ?>">
  <?php echo csrf_field(); ?>
  <?php echo method_field('PUT'); ?>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">اختيار</th>
            <th>الطالب</th>
            <th>رقم جامعي</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_0 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <tr>
              <td>
                <input type="checkbox" name="student_ids[]"
                       value="<?php echo e($s->id); ?>"
                       <?php if(in_array($s->id, $selectedIds)): echo 'checked'; endif; ?>>
              </td>
              <td class="fw-semibold"><?php echo e($s->full_name); ?></td>
              <td><code><?php echo e($s->university_id); ?></code></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr><td colspan="3" class="text-center text-muted py-4">لا يوجد طلاب ضمن هذا الفرع/الدبلومة</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="p-3">
      <button class="btn btn-primary fw-bold px-4">
        حفظ الطلاب المنتسبين
      </button>
    </div>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\exams\students.blade.php ENDPATH**/ ?>