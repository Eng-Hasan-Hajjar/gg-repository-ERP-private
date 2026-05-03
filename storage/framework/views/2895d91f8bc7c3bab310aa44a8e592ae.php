<?php echo csrf_field(); ?>
<?php if(isset($diploma)): ?>
  <?php echo method_field('PUT'); ?>
<?php endif; ?>

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم الدبلومة</label>
    <input name="name" class="form-control" required value="<?php echo e(old('name', $diploma->name ?? '')); ?>"
      placeholder="مثال: دبلوم البرمجة الاحترافية">
  </div>

  <div class="col-md-3">
    <label class="form-label">رمز الدبلومة</label>
    <input name="code" class="form-control" required value="<?php echo e(old('code', $diploma->code ?? '')); ?>"
      placeholder="مثال: PROG-01">
    <div class="form-text">يسمح: حروف/أرقام/شرطة (-) وشرطة سفلية (_).</div>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="is_active" class="form-select">
      <option value="1" <?php if(old('is_active', ($diploma->is_active ?? true)) == true): echo 'selected'; endif; ?>>مفعّلة</option>
      <option value="0" <?php if(old('is_active', ($diploma->is_active ?? true)) == false): echo 'selected'; endif; ?>>غير مفعّلة</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">مجال الدبلومة</label>
    <input name="field" class="form-control" value="<?php echo e(old('field', $diploma->field ?? '')); ?>"
      placeholder="مثال: تقنية معلومات / لغات / إدارة أعمال ...">
  </div>

  <div class="col-md-4">
    <label class="form-label">نوع الدبلومة</label>
    <select name="type" class="form-select" required>
      <option value="">اختر النوع</option>
      <option value="onsite" <?php if(old('type', $diploma->type ?? '') == 'onsite'): echo 'selected'; endif; ?>>حضوري</option>
      <option value="online" <?php if(old('type', $diploma->type ?? '') == 'online'): echo 'selected'; endif; ?>>أونلاين</option>
    </select>
  </div>

  
  <div class="col-md-4">
    <label class="form-label">
      <i class="bi bi-building text-primary"></i> الفرع
    </label>
    <select name="branch_id" class="form-select" required>
      <option value="">اختر الفرع</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($branch->id); ?>"
          <?php if(old('branch_id', $diploma->branch_id ?? '') == $branch->id): echo 'selected'; endif; ?>>
          <?php echo e($branch->name); ?> (<?php echo e($branch->code); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  
  <div class="col-12 mt-3">
    <label class="form-label fw-semibold">
      <i class="bi bi-file-earmark-pdf text-danger"></i>
      ملف تفاصيل الدبلومة (PDF)
    </label>

    <?php if(isset($diploma) && $diploma->details_pdf): ?>
      <div class="alert alert-light border d-flex align-items-center gap-3 mb-2 py-2">
        <i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>
        <div class="flex-grow-1">
          <div class="fw-semibold small">ملف مرفق حالياً</div>
          <a href="<?php echo e($diploma->pdf_url); ?>" target="_blank" class="small text-primary">
            عرض الملف / تحميله
          </a>
        </div>
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" name="remove_pdf" value="1" id="remove_pdf">
          <label class="form-check-label small text-danger" for="remove_pdf">حذف الملف</label>
        </div>
      </div>
    <?php endif; ?>

    <input type="file" name="details_pdf" class="form-control <?php $__errorArgs = ['details_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
      accept="application/pdf">
    <div class="form-text">الصيغة المقبولة: PDF فقط. الحجم الأقصى: 10MB.</div>

    <?php $__errorArgs = ['details_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
  <button class="btn btn-primary">حفظ</button>
  <a class="btn btn-outline-secondary" href="<?php echo e(route('diplomas.index')); ?>">إلغاء</a>
</div><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/diplomas/_form.blade.php ENDPATH**/ ?>