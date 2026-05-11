
<?php $__env->startSection('title','امتحانات الطالب'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">سجل الامتحانات — <?php echo e($student->full_name); ?></h4>
    <div class="text-muted small">رقم جامعي: <code><?php echo e($student->university_id); ?></code></div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="<?php echo e(route('students.show',$student)); ?>">
    <i class="bi bi-arrow-return-right"></i> العودة لملف الطالب
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>التاريخ</th>
          <th>الامتحان</th>
          <th>الدبلومة</th>
          <th>الفرع</th>
          <th>المدرب</th>
          <th>الدرجة</th>
          <th>الحالة</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_0 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
          <tr>
            <td><?php echo e($r->exam->exam_date?->format('Y-m-d') ?? '-'); ?></td>
            <td>
              <a class="text-decoration-none fw-bold" href="<?php echo e(route('exams.show',$r->exam)); ?>">
                <?php echo e($r->exam->title); ?>

              </a>
              <div class="small text-muted"><?php echo e($r->exam->type); ?> — <?php echo e($r->exam->code ?? '-'); ?></div>
            </td>
            <td><?php echo e($r->exam->diploma->name ?? '-'); ?></td>
            <td><?php echo e($r->exam->branch->name ?? '-'); ?></td>
            <td><?php echo e($r->exam->trainer->full_name ?? '-'); ?></td>
            <td><?php echo e($r->score ?? '-'); ?> / <?php echo e($r->exam->max_score); ?></td>
            <td><span class="badge bg-secondary"><?php echo e($r->status); ?></span></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد نتائج بعد</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\students\exams.blade.php ENDPATH**/ ?>