<?php echo csrf_field(); ?>
<?php if(isset($employee)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">

  <?php if(!isset($employee)): ?>

    <input type="hidden" name="type" value="<?php echo e($type ?? 'trainer'); ?>">

    <div class="alert alert-info small ">
      نوع السجل: <strong>
        <?php echo e($type == 'trainer' ? 'مدرب' : 'موظف'); ?>

      </strong>
    </div>

  <?php else: ?>


  <?php endif; ?>


  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" required value="<?php echo e(old('full_name', $employee->full_name ?? '')); ?>">
  </div>




  <div class="col-6 col-md-4">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <option value="active" <?php if(old('status', $employee->status ?? 'active') == 'active'): echo 'selected'; endif; ?>>نشط</option>
      <option value="inactive" <?php if(old('status', $employee->status ?? '') == 'inactive'): echo 'selected'; endif; ?>>غير نشط</option>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="<?php echo e(old('phone', $employee->phone ?? '')); ?>">
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="<?php echo e(old('email', $employee->email ?? '')); ?>">
  </div>



  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">ربط بحساب مستخدم (اختياري)</label>
    <select name="user_id" class="form-select">
      <option value="">— بدون حساب دخول —</option>
      <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($user->id); ?>" <?php if(old('user_id', $employee->user_id ?? '') == $user->id): echo 'selected'; endif; ?>>
          <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <div class="small text-muted mt-1">
      إذا تم الربط سيتمكن هذا الموظف من تسجيل الدخول.
    </div>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id', $employee->branch_id ?? '') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>



  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">فرع ثانوي (اختياري)</label>
    <select name="secondary_branch_id" class="form-select">
      <option value="">— بدون فرع ثانوي —</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('secondary_branch_id', $employee->secondary_branch_id ?? '') == $b->id): echo 'selected'; endif; ?>>
          <?php echo e($b->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <div class="small text-muted mt-1">
      إذا كان الموظف يعمل أيضاً في فرع الأونلاين، اختره هنا.
    </div>
  </div>



  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">المسمى الوظيفي</label>
    <input name="job_title" class="form-control" value="<?php echo e(old('job_title', $employee->job_title ?? '')); ?>">
  </div>

  <div class="col-12 col-md-6" id="diplomas-field">
    <label class="form-label fw-bold">الدبلومات المرتبطة</label>
    <select name="diploma_ids[]" multiple class="form-select" style="min-height:120px">
      <?php ($selected = collect(old('diploma_ids', isset($employee) ? $employee->diplomas->pluck('id')->all() : []))); ?>
      <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($d->id); ?>" <?php if($selected->contains($d->id)): echo 'selected'; endif; ?>>
          <?php echo e($d->name); ?> (<?php echo e($d->code); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <div class="small text-muted mt-1">اضغط Ctrl لاختيار أكثر من دبلومة.</div>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $employee->notes ?? '')); ?></textarea>
  </div>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
















<hr class="my-4">
<h6 class="fw-bold mb-2">🗓 جدول الدوام الأسبوعي</h6>
<p class="text-muted small mb-3">
  حدد وقت بداية ونهاية الدوام لكل يوم، أو فعّل "عطلة" إذا كان اليوم إجازة.
</p>


<div class="row g-3" id="schedule-grid">

  <?php $__currentLoopData = $weekdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div class="col-12 col-md-6 col-xl-4">

      <div class="card border rounded-3 shadow-sm h-100
        <?php echo e($scheduleMap[$wd]['is_off'] ? 'border-secondary bg-light' : 'border-primary'); ?>" id="card_day_<?php echo e($wd); ?>">

        <div class="card-body p-3">

          <div class="d-flex justify-content-between align-items-center mb-3">

            <span class="fw-bold fs-6"><?php echo e($label); ?></span>

            <div class="form-check form-switch mb-0">

              <input class="form-check-input day-off-toggle" type="checkbox" name="schedule[<?php echo e($wd); ?>][is_off]"
                id="off_<?php echo e($wd); ?>" value="1" data-wd="<?php echo e($wd); ?>" <?php echo e($scheduleMap[$wd]['is_off'] ? 'checked' : ''); ?>>

              <label class="form-check-label small fw-semibold text-muted">
                عطلة
              </label>

            </div>

          </div>

          <div id="time_fields_<?php echo e($wd); ?>" style="<?php echo e($scheduleMap[$wd]['is_off'] ? 'display:none' : ''); ?>">

            <div class="row g-2">

              <div class="col-6">

                <label class="form-label small text-muted mb-1">
                  <i class="bi bi-sunrise"></i> بداية الدوام
                </label>

                <input type="time" name="schedule[<?php echo e($wd); ?>][start]" class="form-control form-control-sm"
                  value="<?php echo e($scheduleMap[$wd]['start']); ?>">

              </div>

              <div class="col-6">

                <label class="form-label small text-muted mb-1">
                  <i class="bi bi-sunset"></i> نهاية الدوام
                </label>

                <input type="time" name="schedule[<?php echo e($wd); ?>][end]" class="form-control form-control-sm"
                  value="<?php echo e($scheduleMap[$wd]['end']); ?>">

              </div>

            </div>

          </div>

          <div id="off_label_<?php echo e($wd); ?>"
            class="text-center text-muted py-2 <?php echo e($scheduleMap[$wd]['is_off'] ? '' : 'd-none'); ?>">

            <i class="bi bi-moon-stars fs-4 d-block mb-1"></i>
            <span class="small fw-semibold">يوم عطلة</span>

          </div>

        </div>
      </div>

    </div>

  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>



<script>
  document.querySelectorAll('.day-off-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
      const wd = this.dataset.wd;
      const timeFields = document.getElementById('time_fields_' + wd);
      const offLabel = document.getElementById('off_label_' + wd);
      const card = document.getElementById('card_day_' + wd);

      if (this.checked) {
        timeFields.style.display = 'none';
        offLabel.classList.remove('d-none');
        card.classList.remove('border-primary');
        card.classList.add('border-secondary', 'bg-light');
        timeFields.querySelectorAll('input[type="time"]').forEach(i => i.value = '');
      } else {
        timeFields.style.display = 'block';
        offLabel.classList.add('d-none');
        card.classList.add('border-primary');
        card.classList.remove('border-secondary', 'bg-light');
      }
    });
  });





  function toggleDiplomas() {

    const type = document.querySelector('[name="type"]').value
    const field = document.getElementById('diplomas-field')

    if (type === 'trainer') {
      field.style.display = 'block'
    } else {
      field.style.display = 'none'
    }

  }

  document.querySelector('[name="type"]').addEventListener('change', toggleDiplomas)

  window.addEventListener('load', toggleDiplomas)






</script><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/employees/_form.blade.php ENDPATH**/ ?>