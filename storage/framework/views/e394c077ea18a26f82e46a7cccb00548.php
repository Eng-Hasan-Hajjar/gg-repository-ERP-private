<?php echo csrf_field(); ?>
<?php if(isset($cashbox)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">
  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">اسم الصندوق</label>
    <input name="name" class="form-control" required value="<?php echo e(old('name', $cashbox->name ?? '')); ?>">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">الكود</label>
    <input name="code" class="form-control" required value="<?php echo e(old('code', $cashbox->code ?? '')); ?>" placeholder="CB-IST-USD">
  </div>

  <div class="col-12 col-md-3">
    <label class="form-label fw-bold">العملة</label>
    <select name="currency" class="form-select" required>
      <?php $__currentLoopData = ['USD','TRY','EUR']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c); ?>" <?php if(old('currency',$cashbox->currency ?? 'USD')==$c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id',$cashbox->branch_id ?? '')==$b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <option value="active" <?php if(old('status',$cashbox->status ?? 'active')=='active'): echo 'selected'; endif; ?>>نشط</option>
      <option value="inactive" <?php if(old('status',$cashbox->status ?? '')=='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
    </select>
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">رصيد افتتاحي</label>
    <input name="opening_balance" class="form-control" value="<?php echo e(old('opening_balance', $cashbox->opening_balance ?? 0)); ?>">
  </div>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
  </div>
<?php endif; ?>

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="<?php echo e(route('cashboxes.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/cashboxes/_form.blade.php ENDPATH**/ ?>