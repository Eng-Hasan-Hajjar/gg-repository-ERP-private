<?php echo csrf_field(); ?>
<?php if(isset($branch)): ?>
  <?php echo method_field('PUT'); ?>
<?php endif; ?>

<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">اسم الفرع</label>
    <input name="name" class="form-control" required
           value="<?php echo e(old('name', $branch->name ?? '')); ?>"
           placeholder="مثال: فرع ألمانيا">
  </div>

  <div class="col-md-4">
    <label class="form-label">رمز الفرع</label>
    <input name="code" class="form-control" required
           value="<?php echo e(old('code', $branch->code ?? '')); ?>"
           placeholder="مثال: DE">
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
  <a class="btn btn-outline-secondary" href="<?php echo e(route('branches.index')); ?>">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\branches\_form.blade.php ENDPATH**/ ?>