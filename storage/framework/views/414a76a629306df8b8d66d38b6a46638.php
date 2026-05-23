
<?php $__env->startSection('title','درجات الامتحان'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">إدخال الدرجات — <?php echo e($exam->title); ?></h4>
    <div class="text-muted small">
     الدبلومة: <?php echo e($exam->diploma->name); ?> <?php echo e($exam->diploma->code); ?> — الحد الأعلى: <?php echo e($exam->max_score); ?>

    </div>
  </div>
  <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="<?php echo e(route('exams.show',$exam)); ?>">
    <i class="bi bi-arrow-return-right"></i> رجوع
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

<form method="POST" action="<?php echo e(route('exams.results.update',$exam)); ?>">
  <?php echo csrf_field(); ?>
  <?php echo method_field('PUT'); ?>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الطالب</th>
            <th>رقم جامعي</th>
            <th style="width:140px">الدرجة</th>
            <th style="width:160px">الحالة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
       
          


<?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>

    <td class="fw-semibold"><?php echo e($s->full_name); ?></td>
    <td><code><?php echo e($s->university_id); ?></code></td>

    <td>
        <input type="hidden"
               name="rows[<?php echo e($i); ?>][student_id]"
               value="<?php echo e($s->id); ?>">

        <input type="number"
               name="rows[<?php echo e($i); ?>][score]"
               value="<?php echo e(old('rows.'.$i.'.score', optional($existing[$s->id] ?? null)->score)); ?>"
               class="form-control score-input"
               data-row="<?php echo e($i); ?>"
               min="0"
               max="<?php echo e($exam->max_score); ?>">
    </td>

    <td>
        <select name="rows[<?php echo e($i); ?>][status]"
                class="form-select status-select"
                data-row="<?php echo e($i); ?>"
                required>

            <option value="">-- اختر الحالة --</option>

            <option value="passed"
                <?php echo e(old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'passed' ? 'selected' : ''); ?>>
                ناجح
            </option>

            <option value="failed"
                <?php echo e(old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'failed' ? 'selected' : ''); ?>>
                راسب
            </option>

            <option value="absent"
                <?php echo e(old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'absent' ? 'selected' : ''); ?>>
                غائب
            </option>

            <option value="excused"
                <?php echo e(old('rows.'.$i.'.status', optional($existing[$s->id] ?? null)->status) == 'excused' ? 'selected' : ''); ?>>
                معذور
            </option>

        </select>
    </td>

    <td>
        <input name="rows[<?php echo e($i); ?>][notes]"
               value="<?php echo e(old('rows.'.$i.'.notes', optional($existing[$s->id] ?? null)->notes)); ?>"
               class="form-control"
               placeholder="اختياري">
    </td>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="5" class="text-center text-muted py-4">
        لا يوجد طلاب مطابقون
    </td>
</tr>
<?php endif; ?>






        </tbody>
      </table>
    </div>

    <div class="p-3">
      <?php if($errors->any()): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>

      <button class="btn btn-primary fw-bold px-4">
        <i class="bi bi-save"></i> حفظ الدرجات
      </button>
    </div>
  </div>
</form>













<script>
document.querySelectorAll('.status-select').forEach(function(select) {

    select.addEventListener('change', function() {

        let rowIndex = this.dataset.row;
        let scoreInput = document.querySelector('.score-input[data-row="'+rowIndex+'"]');
        let score = parseFloat(scoreInput.value);

        // تعطيل الدرجة في حالة غائب أو معذور
        if (this.value === 'absent' || this.value === 'excused') {
            scoreInput.value = '';
            scoreInput.disabled = true;
            return;
        } else {
            scoreInput.disabled = false;
        }

        // منع اختيار راسب إذا الدرجة >= 50
        if (this.value === 'failed' && score >= 50) {
            alert('لا يمكن وضع راسب لعلامة 50 أو أكثر');
            this.value = '';
        }

        // منع اختيار ناجح إذا الدرجة أقل من 50
        if (this.value === 'passed' && score < 50) {
            alert('لا يمكن وضع ناجح لعلامة أقل من 50');
            this.value = '';
        }

    });

});


// عند تغيير الدرجة يتم تصحيح الحالة تلقائياً
document.querySelectorAll('.score-input').forEach(function(input){

    input.addEventListener('input', function(){

        let rowIndex = this.dataset.row;
        let select = document.querySelector('.status-select[data-row="'+rowIndex+'"]');
        let score = parseFloat(this.value);

        if (isNaN(score)) return;

        if (score >= 50) {
            select.value = 'passed';
        } else {
            select.value = 'failed';
        }

    });

});
</script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/exams/results_edit.blade.php ENDPATH**/ ?>