
<?php $__env->startSection('title','تفاصيل الامتحان'); ?>

<?php $__env->startSection('content'); ?>




<?php
    if ($successRate >= 70) {
        $progressColor = 'bg-success';
        $textColor = 'text-success';
    } elseif ($successRate >= 50) {
        $progressColor = 'bg-warning';
        $textColor = 'text-warning';
    } else {
        $progressColor = 'bg-danger';
        $textColor = 'text-danger';
    }
?>

<div class="row g-4 mb-4">

    
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <div class="text-muted small">نسبة النجاح</div>
                        <h3 class="fw-bold <?php echo e($textColor); ?>">
                            <?php echo e($successRate); ?>%
                        </h3>
                    </div>
                    <div class="text-end">
                        <div class="text-success fw-bold">
                            <?php echo e($passed); ?>

                        </div>
                        <small class="text-muted">ناجح</small>

                        <div class="text-danger fw-bold mt-2">
                            <?php echo e($failed); ?>

                        </div>
                        <small class="text-muted">راسب</small>
                    </div>
                </div>

                <div class="progress" style="height:10px;">
                    <div class="progress-bar <?php echo e($progressColor); ?>"
                         style="width: <?php echo e($successRate); ?>%">
                    </div>
                </div>

            </div>
        </div>

    </div>


    
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo e($exam->title); ?></h4>
                        <div class="text-muted small">
                            <?php echo e($exam->exam_date?->format('Y-m-d') ?? '-'); ?>

                            — <?php echo e($exam->type); ?>

                            — كود: <?php echo e($exam->code ?? '-'); ?>

                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                      
                        <a class="btn btn-namaa fw-bold rounded-pill px-4" href="<?php echo e(route('exams.results.edit',$exam)); ?>">
                          <i class="bi bi-pencil-square"></i> إدخال/تعديل الدرجات
                        </a>
                        <a href="<?php echo e(route('exams.index')); ?>"
                           class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                            رجوع
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <b>الدبلومة:</b>
                        <?php echo e($exam->diploma->name ?? '-'); ?>     <?php echo e($exam->diploma->code ?? '-'); ?>

                    </div>

                    <div class="col-md-6" hidden>
                        <b>الفرع:</b>
                        <?php echo e($exam->branch->name ?? '-'); ?>

                    </div>

                    <div class="col-md-6">
                        <b>المدرب:</b>
                        <?php echo e($exam->trainer->full_name ?? '-'); ?>

                    </div>

                    <div class="col-md-6">
                        <b>الحد الأعلى:</b>
                        <?php echo e($exam->max_score); ?>

                    </div>
                </div>

                <?php if($exam->notes): ?>
                    <hr>
                    <div class="text-muted">
                        <?php echo e($exam->notes); ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

</div>




<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h6 class="fw-bold mb-3">النتائج المدخلة</h6>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th>الدرجة</th>
            <th>الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $exam->results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="fw-semibold">
                <a href="<?php echo e(route('students.show',$r->student)); ?>" class="text-decoration-none">
                  <?php echo e($r->student->full_name); ?>

                </a>
              </td>
              <td><code><?php echo e($r->student->university_id); ?></code></td>
                 
              <td class="fw-bold">
                  <?php echo e($r->score ?? '-'); ?>

              </td>

              
              <td>
                  <span class="badge 
                      <?php if($r->status === 'passed'): ?> bg-success
                      <?php elseif($r->status === 'failed'): ?> bg-danger
                      <?php elseif($r->status === 'absent'): ?> bg-dark
                      <?php elseif($r->status === 'excused'): ?> bg-info
                      <?php else: ?> bg-secondary
                      <?php endif; ?>">
                      <?php echo e($r->status === 'passed' ? 'ناجح' :
                          ($r->status === 'failed' ? 'راسب' :
                          ($r->status === 'absent' ? 'غائب' :
                          ($r->status === 'excused' ? 'معذور' : 'لم تحدد')))); ?>

                  </span>
              </td>
              <td><?php echo e($r->notes ?? '-'); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">لا يوجد نتائج بعد</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/exams/show.blade.php ENDPATH**/ ?>