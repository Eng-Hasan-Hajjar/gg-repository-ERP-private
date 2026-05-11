
<?php $__env->startSection('title','إدخال درجات المكونات'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">درجات المكونات — <?php echo e($exam->title); ?></h4>
    <div class="text-muted small">
      دبلومة: <?php echo e($exam->diploma->name); ?>

    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="<?php echo e(route('exams.show',$exam)); ?>">
    <i class="bi bi-arrow-return-right"></i> رجوع
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث طالب">
    </div>
    <div class="col-12 col-md-2 d-grid">
      <button class="btn btn-dark fw-bold">بحث</button>
    </div>
  </div>
</form>

<form method="POST" action="<?php echo e(route('exams.marks.update',$exam)); ?>">
  <?php echo csrf_field(); ?>
  <?php echo method_field('PUT'); ?>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <?php $__currentLoopData = $exam->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <th style="min-width:160px">
                <?php echo e($comp->title); ?>

                <div class="small text-muted">Max: <?php echo e($comp->max_score); ?> | W: <?php echo e($comp->weight); ?></div>
              </th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_0 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
            <?php ($stuRes = $existing[$s->id] ?? collect()); ?>
            <tr>
              <td class="fw-semibold"><?php echo e($s->full_name); ?></td>
              <td><code><?php echo e($s->university_id); ?></code></td>

              <input type="hidden" name="rows[<?php echo e($i); ?>][student_id]" value="<?php echo e($s->id); ?>">

              <?php $__currentLoopData = $exam->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j => $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($r = $stuRes->firstWhere('exam_component_id', $comp->id)); ?>
                <td>
                  <input type="hidden" name="rows[<?php echo e($i); ?>][components][<?php echo e($j); ?>][component_id]" value="<?php echo e($comp->id); ?>">

                  <input type="number" step="0.01"
                    name="rows[<?php echo e($i); ?>][components][<?php echo e($j); ?>][score]"
                    value="<?php echo e(old("rows.$i.components.$j.score", $r->score ?? '')); ?>"
                    class="form-control mb-1"
                    placeholder="0..<?php echo e($comp->max_score); ?>">

                  <input
                    name="rows[<?php echo e($i); ?>][components][<?php echo e($j); ?>][notes]"
                    value="<?php echo e(old("rows.$i.components.$j.notes", $r->notes ?? '')); ?>"
                    class="form-control"
                    placeholder="ملاحظة (اختياري)">
                </td>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
            <tr><td colspan="<?php echo e(2 + $exam->components->count()); ?>" class="text-center text-muted py-4">لا يوجد طلاب</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="p-3">
      <?php if($errors->any()): ?>
        <div class="alert alert-danger">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <button class="btn btn-primary fw-bold px-4">
        <i class="bi bi-save"></i> حفظ + حساب المحصلة
      </button>
    </div>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\exams\marks_edit.blade.php ENDPATH**/ ?>